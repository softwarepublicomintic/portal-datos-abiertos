<?php
/**
 * @file
 * socrata_mintic.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function socrata_mintic_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "socrata" && $api == "socrata") {
    return array("version" => "1");
  }
}
