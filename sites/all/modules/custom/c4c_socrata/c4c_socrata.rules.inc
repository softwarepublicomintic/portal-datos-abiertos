<?php

/**
 * @file
 * Integration with Rules.
 */

/**
 * Implements hook_rules_event_info().
 */
function c4c_socrata_rules_event_info() {
  $events = array(
    'c4c_socrata_fetch_data' => array(
      'label' => t('Fetch data'),
      'group' => t('C4C Socrata'),
    ),
  );
  return $events;
}

/**
 * Implements hook_rules_condition_info().
 */
function c4c_socrata_rules_condition_info() {
  return array(
    'c4c_socrata_results_exist' => array(
      'group' => t('C4C Socrata'),
      'label' => t('If results are already stored'),
      'module' => 'c4c_socrata',
    ),
    'c4c_socrata_results_aof' => array(
      'group' => t('C4C Socrata'),
      'label' => t('If results are added on the fly'),
      'module' => 'c4c_socrata',
    ),
  );
}

/**
 * Validate if results are stored.
 */
function c4c_socrata_results_exist() {
  $there_are_results = c4c_socrata_validate_results();
  return $there_are_results;
}

/**
 * Validate if results are added on the fly:
 */
function c4c_socrata_results_aof() {
  $aof = FALSE;
  if (variable_get('c4c_socrata_query_aof', 0)) {
    $aof = TRUE;
  }
  return $aof;
}

/**
 * Implements hook_rules_action_info().
 */
function c4c_socrata_rules_action_info() {
  $actions = array(
    'c4c_socrata_make_query' => array(
      'label' => t('Make a query to Socrata'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'query' => array(
          'type' => 'text',
          'label' => t('Query'),
          'optional' => TRUE,
          'description' => t('Example: <em>Programas especiales</em>. This is used for search queries.'),
        ),
        'where' => array(
          'type' => 'text',
          'label' => t('Where'),
          'description' => t('Example: @where', array('@where' => 'type="table" and public="true"')),
        ),
        'limit' => array(
          'type' => 'integer',
          'label' => t('Limit'),
          'description' => t('An integer'),
        ),
        'select' => array(
          'type' => 'text',
          'label' => t('Select'),
          'optional' => TRUE,
          'description' => t('Fields to query. Leave blank to fetch all. Example: @select', array('@select' => 'name,dataset_link')),
        ),
      ),
    ),
    'c4c_socrata_fetch_available_data' => array(
      'label' => t('Fetch data'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'mode' => array(
          'type' => 'text',
          'label' => t('Mode'),
          'description' => t('Use "@all" to get all results. Otherwise, use "@choosen" to get only choosen results.', array('@all' => 'ALL', '@choosen' => 'CHOOSEN')),
        ),
      ),
      'provides' => array(
        'data' => array('type' => 'list<list>', 'label' => t('Selected data.')),
      ),
    ),
    'c4c_socrata_fetch_record_data' => array(
      'label' => t('Fetch data item'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'record' => array(
          'type' => 'list',
          'label' => t('Item data'),
        ),
        'key' => array(
          'type' => 'text',
          'label' => t('Data key'),
        ),
      ),
      'provides' => array(
        'data_value' => array('type' => 'text', 'label' => t('Fetched value.')),
      ),
    ),
    'c4c_socrata_cleanup_storage' => array(
      'label' => t('Clean up storage'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'clean_mode' => array(
          'type' => 'text',
          'label' => t('Mode'),
          'description' => t('Use "@all" to clean all. "@choosen" to clean only choosen results. Use "@results" to clean results, keeping choosen items.', array('@all' => 'ALL', '@choosen' => 'CHOOSEN', '@results' => 'RESULTS')),
        ),
      ),
      'provides' => array(
        'status' => array('type' => 'text', 'label' => t('Status')),
      ),
    ),
    'c4c_socrata_filter_choosen' => array(
      'label' => t('Filter choosen'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'key' => array(
          'type' => 'text',
          'label' => t('Key'),
          'description' => t('Enter "DATASET" or "CHALLENGE", depending your needs.'),
        ),
        'choosen' => array(
          'type' => 'list',
          'label' => t('Choosen'),
        ),
      ),
    ),
    'c4c_socrata_upsert_data' => array(
      'label' => t('Upsert Socrata Data'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
         'dataset' => array(
           'type' => 'text',
           'label' => t('Dataset UID'),
           'description' => t('Dataset where data is stored'),
         ),
         'node' => array(
           'type' => 'text',
           'label' => t('Node ID'),
           'description' => t('Node to evaluate'),
         ),
       ),
     ),
     'c4c_socrata_delete_data' => array(
       'label' => t('Delete Row Socrata Data'),
       'group' => t('C4C Socrata'),
       'parameter' => array(
          'dataset' => array(
            'type' => 'text',
            'label' => t('Dataset UID'),
            'description' => t('Dataset where data is stored'),
          ),
          'node' => array(
            'type' => 'text',
            'label' => t('Node ID'),
            'description' => t('Node to evaluate'),
          ),
        ),
      ),

    /*
    'c4c_socrata_nice_redirect' => array(
      'label' => t('Nice redirect'),
      'group' => t('C4C Socrata'),
      'parameter' => array(
        'path' => array(
          'type' => 'text',
          'label' => t('Path'),
          'optional' => TRUE,
          'description' => t('A valid path to redirect.'),
        ),
      ),
    ),
    */
  );
  return $actions;
}

/**
 * Perform a new query.
 */
function c4c_socrata_make_query($query, $where, $limit, $select) {
  $params = array(
    "\$where" => $where,
    "\$limit" => $limit,
    "\$select" => $select,
  );
  if ($query) {
    $params["\$q"] = $query;
  }

  $view_uid = variable_get('c4c_socrata_view_uid', '');
  if ($view_uid) {
    $query = c4c_socrata_query($params, $view_uid);
  }
  else {
    $query = array();
  }
  $_SESSION['c4c']['socrata'] = array(
    'params' => $params,
    'data' => $query,
  );
}

/**
 * Get data.
 */
function c4c_socrata_fetch_available_data($mode) {
  $data = array();
  switch ($mode) {
    case 'ALL':
      $data = c4c_socrata_get_all_results();
      break;
    case 'CHOOSEN':
      $data = c4c_socrata_get_all_choosen();
      break;
  }

  return array(
    'data' => $data,
  );
}

/**
 * Once fetched the data, every record is an array. Give array and needed key.
 */
function c4c_socrata_fetch_record_data($record, $key) {
  if (strpos($key, '/')) {
    $keys = explode('/', $key);
    $_SESSION['c4c']['socrata']['tmp'] = array(
      'last_data' => $record,
    );
    foreach($keys as $skey => $lkey) {
      $_SESSION['c4c']['socrata']['tmp']['last_data'] = c4c_socrata_validate_keys($_SESSION['c4c']['socrata']['tmp']['last_data'], $lkey);
    }
    $value = $_SESSION['c4c']['socrata']['tmp']['last_data'];
    unset($_SESSION['c4c']['socrata']['tmp']['last_data']);
  }
  else {
    $value = c4c_socrata_validate_keys($record, $key);
  }

  return array(
    'data_value' => $value,
  );
}

/**
 * Validate if keys exist.
 */
function c4c_socrata_validate_keys($record, $key) {
  return isset($record[$key]) ? $record[$key] : NULL;
}

/**
 * Cleanup storage.
 */
function c4c_socrata_cleanup_storage($clean_mode) {
  $status = 'UNPROCESSED';
  switch ($clean_mode) {
    case 'ALL':
      if (isset($_SESSION['c4c'])) {
        unset($_SESSION['c4c']);
        $status = 'CLEANED - ALL';
      }
      break;
    case 'RESULTS':
      if (isset($_SESSION['c4c']['socrata']['data'])) {
        unset($_SESSION['c4c']['socrata']['data']);
        $status = 'CLEANED - RESULTS';
      }
      break;
    case 'CHOOSEN':
      if (isset($_SESSION['c4c']['socrata']['choosen'])) {
        unset($_SESSION['c4c']['socrata']['choosen']);
        $status = 'CLEANED - CHOOSEN';
      }
      break;
  }
  return array(
    'status' => $status,
  );
}

/**
 * Set choosen.
 */
function c4c_socrata_filter_choosen($key, $choosen) {
  $_SESSION['c4c']['socrata']['current'][$key] = $choosen;
}

/**
 * An action to redirect using jQuery.
 */
/*
function c4c_socrata_nice_redirect($path) {
  $js_str = 'jQuery(document).ready(function () { window.location.href = "' . $path . '"; });';
  drupal_add_js($js_str, 'inline');
}
*/

function c4c_socrata_upsert_data($dataset,$node) {
      // Convierte el contenido del nodo en json

      $US_node = node_load($node);
      $JsonNode = "";
      $tipo = $US_node->type;

      switch ($tipo) {
        case 'project_application':

          // Trae los valores correspondientes en la taxonomia de cada campo
          $TaxTipoApp = $US_node->field_application_type['und'][0]['tid'];
          $Tappi18name = "taxonomy:term:".$US_node->field_application_type['und'][0]['tid'].":name";
          $TaxTA =  taxonomy_term_load($TaxTipoApp);
          $TipoApp = $TaxTA->name;
          $TaxCat = $US_node->field_category['es'][0]['tid'];
          $Cati18name = "taxonomy:term:".$US_node->field_category['es'][0]['tid'].":name";
          $TaxCT =  taxonomy_term_load($TaxCat);
          $Categoria = $TaxCT->name;
          $TaxLic = $US_node->field_license['und'][0]['tid'];
          $Lici18name = "taxonomy:term:".$US_node->field_license['und'][0]['tid'].":name";
          $TaxLC =  taxonomy_term_load($TaxLic);
          $Licencia = $TaxLC->name;

          // Crea el arreglo con la información para convertirla en JSON
          $ArrayNode = array(array('nombre_del_proyecto'=>$US_node->title,
                                   'tipo_de_aplicaci_n'=>asopendata_core_translate($Tappi18name,$TipoApp,"es"),
                                   'descripci_n'=>$US_node->body['und'][0]['value'],
                                   'caegor_a'=>asopendata_core_translate($Cati18name,$Categoria,"es"),
                                   'a_o_del_proyecto'=>$US_node->field_application_year['und'][0]['value'],
                                   'licencia'=>asopendata_core_translate($Lici18name,$Licencia,"es"),
                                   'calificaci_n'=>(!isset($US_node->field_application_year['und'][0]['rating']) ? ' ' : $US_node->field_application_year['und'][0]['rating']),
                                   'url'=>(!isset($US_node->field_application_website['und'][0]['url']) ? ' ':$US_node->field_application_website['und'][0]['url']) ,
                                   'url_c_digo'=>(!isset($US_node->field_project_code['und'][0]['url']) ? ' ':$US_node->field_project_code['und'][0]['url']),
                                   'organizaci_n'=>(!isset($US_node->field_app_organization['und'][0]['value']) ? ' ':$US_node->field_app_organization['und'][0]['value']),
                                   'e_mail_de_contacto'=>(!isset($US_node->field_project_contact_mail['und'][0]['email']) ? ' ':$US_node->field_project_contact_mail['und'][0]['email']),
                                   'caracter_sticas'=>(!isset($US_node->field_app_features['und'][0]['value']) ? ' ':$US_node->field_app_features['und'][0]['value']),
                                   'versi_n'=>(!isset($US_node->field_app_version['und'][0]['value']) ? ' ':$US_node->field_app_version['und'][0]['value']),
                                   'fecha'=>(!isset($US_node->field_app_date_version['und'][0]['value']) ? ' ':$US_node->field_app_date_version['und'][0]['value']),
                                   'url_video'=>(!isset($US_node->field_app_media['und'][0]['video_url']) ? ' ':$US_node->field_app_media['und'][0]['video_url']),
                                   'url_documentaci_n'=>(!isset($US_node->field_project_url_documentation ['und'][0]['url']) ? ' ':$US_node->field_project_url_documentation ['und'][0]['url'])
                                    ));


          // Crea el json y envia la operación a socrata

          $JSONNode = json_encode($ArrayNode);

      //    $US_node->field_app_features['und'][0]['value'] =  "Info->".$JSONNode;
          $response = c4c_socrata_upsert($dataset,$JSONNode);



        //  $US_node->field_app_features['und'][0]['value'] = $US_node->field_app_features['und'][0]['value']." => ".var_export($response,true);

       //node_save($US_node);

          break;

      }


}

function c4c_socrata_delete_data($dataset,$node) {
      // Convierte el contenido del nodo en json

      $US_node = node_load($node);
      $JsonNode = "";
      $tipo = $US_node->type;

      switch ($tipo) {
        case 'project_application':

          // Crea el arreglo con la información para convertirla en JSON
          $ArrayNode = array(array('nombre_del_proyecto'=>$US_node->title,
                                   ':deleted'=>true,
                                    ));

          // Crea el json y envia la operación a socrata
          $JSONNode = json_encode($ArrayNode);
          $response = c4c_socrata_upsert($dataset,$JSONNode);
          break;

      }

}
