<?php
/**
 * @file
 * socrata_mintic.socrata.inc
 */

/**
 * Implements hook_default_socrata_sources().
 */
function socrata_mintic_default_socrata_sources() {
  $export = array();

  $source = new stdClass();
  $source->disabled = FALSE; /* Edit this to true to make a default source disabled initially */
  $source->api_version = 1;
  $source->name = 'mintic';
  $source->description = 'mintic 2017';
  $source->endpoint = 'https://www.datos.gov.co/resource/gc9e-9mzf.json';
  $source->app_token = '2d2wjq2ZJA8KHivSuqBvgjzX6';
  $export['mintic'] = $source;

  return $export;
}
