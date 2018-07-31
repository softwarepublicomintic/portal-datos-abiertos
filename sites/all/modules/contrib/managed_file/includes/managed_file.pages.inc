<?php

/**
 * @file
 * Menu callbacks.
 */

/**
 * Menu callback.
 *
 * @internal
 * @see managed_file_menu()
 */
function managed_file_get_fid_by_uri() {
  $params = drupal_get_query_parameters();

  if (isset($params['uri'])) {
    $files = file_load_multiple([], $params);

    if (empty($files)) {
      $file = file_save_data(file_get_contents($params['uri']), $params['uri'], FILE_EXISTS_REPLACE);

      if (FALSE !== $file) {
        exit($file->fid);
      }
    }

    exit(reset($files)->fid);
  }

  exit(0);
}
