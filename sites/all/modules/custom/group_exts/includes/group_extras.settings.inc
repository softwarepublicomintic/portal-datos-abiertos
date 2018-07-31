<?php
/**
 * @file C4C: Admin settings for Socrata.
 */

/**
 * Admin settings.
 */
function group_extras_settings($form, &$form_state) {
  // Group add form.
  $form['group_add'] = array(
    '#type' => 'fieldset',
    '#title' => t('Group add settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#tree' => FALSE,
  );
  $form['group_add']['gextras_group_add_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use OR in autocomplete texfield'),
    '#default_value' => variable_get('gextras_group_add_enabled', 0),
  );
  $form['group_add']['gextras_group_add_field'] = array(
    '#type' => 'textfield',
    '#title' => t('Field name'),
    '#default_value' => variable_get('gextras_group_add_field', ''),
  );
  
  // Role settings.
  $form['roles'] = array(
    '#type' => 'fieldset',
    '#title' => t('Role settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#tree' => FALSE,
  );
  $form['roles']['gextras_autoroles'] = array(
    '#type' => 'textarea',
    '#title' => t('Add following roles automatically to new users'),
    '#description' => t('Separate multiple values with "," (comma).'),
    '#default_value' => variable_get('gextras_autoroles', ''),
  );
  return system_settings_form($form);
} 
