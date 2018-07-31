<?php
/**
 * @file C4C: Admin settings for Socrata.
 */

/**
 * Admin settings.
 */
function c4c_socrata_settings($form, &$form_state) {
  $form['c4c_socrata_view_uid'] = array(
    '#type' => 'textfield',
    '#title' => t('View UID - Datasets'),
    '#default_value' => variable_get('c4c_socrata_view_uid', ''),
  );
  $form['c4c_socrata_view_uid_ext0'] = array(
    '#type' => 'textfield',
    '#title' => t('View UID - Challenges'),
    '#default_value' => variable_get('c4c_socrata_view_uid_ext0', ''),
  );
  $form['c4c_socrata_root_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Root URL'),
    '#default_value' => variable_get('c4c_socrata_root_url', ''),
  );
  $form['c4c_socrata_token'] = array(
    '#type' => 'textfield',
    '#title' => t('Token'),
    '#default_value' => variable_get('c4c_socrata_token', ''),
  );
  $form['c4c_socrata_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#default_value' => variable_get('c4c_socrata_username', ''),
  );
  $form['c4c_socrata_password'] = array(
    '#type' => 'textfield',
    '#title' => t('Password'),
    '#default_value' => variable_get('c4c_socrata_password', ''),
  );
  return system_settings_form($form);
} 
