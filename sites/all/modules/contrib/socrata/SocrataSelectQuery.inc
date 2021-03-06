<?php
/**
 * @file
 * Decorator class for a Socrata dataset query
 */

class SocrataSelectQuery extends SelectQueryExtender {

  /**
   * Socrata Query Language parameters.
   */
  public $params = array();
  protected $source;

  /**
   * Get the Socrata source object.
   */
  public function setSource($endpoint, $app_token) {
    $source = new stdClass();
    $source->endpoint = $endpoint;
    $source->app_token = $app_token;
    $this->source = $source;
    return $source;
  }

  /**
   * Get the Socrata source object.
   */
  public function getSource() {
    $tables = $this->query->getTables();
    $table_names = array_keys($tables);
    ctools_include('export');
    $source = ctools_export_crud_load('socrata_sources', $table_names[0]);

    return $source;
  }

  /**
   * Return full Socrata URL with endpoint and parameters.
   *
   * @param bool $encode
   *   Determins whether we should URL-encode the returned URL.
   *
   * @return string
   *   Formatted URL
   */
  protected function getCurlUrl($encode = TRUE) {
    $url = '';
    $source = isset($this->source) ? $this->source : $this->getSource();
    $endpoint = $source->endpoint;
    $params = $this->params;

    // We might not want to encode the URL in cases where we just want it to be
    // output for humans to read, most notably in the query displayed in the
    // views preview.
    // In this case, assemble our URL with the query parameters directly,
    // rather than passing them in as query arguments to the URL function, where
    // they'll be URL-encoded.
    if (!$encode) {
      $endpoint_with_params = $endpoint;
      if (!empty($params)) {
        $endpoint_with_params .= '?';
        $params_query = array();
        foreach ($params as $key => $value) {
          $params_query[] = $key . '=' . $value;
        }
      }
      $endpoint_with_params .= implode('&', $params_query);
      $url = url($endpoint_with_params);
    }
    else {
      $url = url($endpoint, array('query' => $params, 'absolute' => TRUE));
    }
    return $url;
  }

  /**
   * Return download Socrata URL.
   *
   * @param $format string
   *
   * @return string
   */
  public function getDownloadUrl($format = 'csv') {
    $source = isset($this->source) ? $this->source : $this->getSource();
    $components = socrata_components_from_endpoint($source->endpoint);

    // Note that this is the old Socrata API style URL.
    return "{$components['scheme']}://{$components['host']}/api/views/{$components['dataset_id']}/rows.{$format}?accessType=DOWNLOAD";
  }

  /**
   * Return Socrata metadata URL.
   *
   * @return string
   *   The download URL
   */
  public function getMetaDataUrl() {
    $source = isset($this->source) ? $this->source : $this->getSource();
    $components = socrata_components_from_endpoint($source->endpoint);

    return "{$components['scheme']}://{$components['host']}/api/views/{$components['dataset_id']}.json";
  }

  /**
   * Execute the query. In our case, this is actually the cURL request.
   *
   * @param $type string
   *   cURL request type, if not the default. The only acceptable value is
   *   currently 'metadata', but we might want others in the future?
   *
   * @return array
   */
  public function execute($type = NULL) {
    $retval = FALSE;

    $source = isset($this->source) ? $this->source : $this->getSource();
    $endpoint = $source->endpoint;
    $app_token = $source->app_token;

    // Make sure we have a valid-looking endpoint.
    if (substr_compare($endpoint, '.json', -5)) {
      $endpoint .= '.json';
    }

    // Prepare authorization headers.
    $params = $this->params;
    if (!empty($app_token)) {
      $params['$$app_token'] = $app_token;
    }

    // Create a new cURL resource.
    $ch = curl_init();
    if ($ch) {
      // Pull and set custom curl options.
      $curlopts = variable_get('socrata_curl_options', array());
      drupal_alter('socrata_curl_options', $curlopts);
      curl_setopt_array($ch, $curlopts);

      // Set required curl options.
      $req_curlopts = array(
        CURLOPT_HEADER => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
      );
      curl_setopt_array($ch, $req_curlopts);

      // Execute the request and cleanup.
      curl_setopt_array($ch, $curlopts);
      do {
        $retval = FALSE;

        // Construct the URL, based on the $type parameter.
        if ($type == 'metadata') {
          // Note that 'metadata' here represents an entirely separate API and
          // endpoint, and is different from the special fields of type
          // 'meta_data' (mentioned below) that are returned from the normal
          // dataset API/endpoint, but when using an old version of the API.
          // @todo Add version number for "old".
          $soda_url = $this->getMetaDataURL();
        }
        else {
          $soda_url = $this->getCurlUrl();
        }
        _socrata_dbg($soda_url);

        curl_setopt($ch, CURLOPT_URL, $soda_url);
        $resp = curl_exec($ch);
        if (FALSE !== $resp) {
          // Pull info from response and see if we had an error.
          $info = curl_getinfo($ch);
          if ($info['http_code'] >= 400) {
            _socrata_log('Server returned error code @errno', array('@errno' => $info['http_code']), WATCHDOG_ERROR, l(t('Socrata Request'), $soda_url, array('absolute' => TRUE)));
            break;
          }
          else {
            // Split out response headers into name => value array.
            list($headers, $data) = explode("\r\n\r\n", $resp, 2);
            $retval['headers'] = _socrata_parse_headers($headers);

            // Test for redirect in the event curl wasn't able to automagically
            // follow due to server config.
            if (!empty($retval['headers']['location'])) {
              $endpoint = $retval['headers']['location'];
            }
            else {
              // Generate an array mapping fields to types, if provided
              // Note that the old API returns some fields of type 'meta_data'
              // (e.g. ':id', ':created_at', ':updated_at').
              // @todo Add version number for "old".
              if (isset($retval['headers']['x-soda2-fields']) && isset($retval['headers']['x-soda2-types'])) {
                foreach ($retval['headers']['x-soda2-fields'] as $idx => $name) {
                  $retval['fields'][$name] = $retval['headers']['x-soda2-types'][$idx];
                }
              }

              // Decode data payload.
              $retval['data'] = json_decode($data, TRUE);
            }
          }
        }
        else {
          _socrata_log('curl_exec failed: @error [@errno]', array('@error' => curl_error($ch), '@errno' => curl_errno($ch)), WATCHDOG_ERROR, l(t('Socrata Request'), $soda_url, array('absolute' => TRUE)));
        }
      } while (FALSE !== $resp && !empty($retval['headers']['location']));

      // Close and cleanup.
      curl_close($ch);
    }
    else {
      _socrata_log('curl_init failed: @error [@errno]', array('@error' => curl_error($ch), '@errno' => curl_errno($ch)), WATCHDOG_ERROR);
    }

    return $retval;
  }

  /**
   * Magic method to return a string for the object.
   *
   * @return string
   *   The Socrata query.
   */
  public function __toString() {
    $query = '';

    $source = $this->getSource();
    if ($source) {
      $soda_url = $this->getCurlUrl(FALSE);
      $this->query->comment('Socrata URL: "' . $soda_url . '" Corresponding SQL query: ');
    }

    return (string) $this->query;
  }
}
