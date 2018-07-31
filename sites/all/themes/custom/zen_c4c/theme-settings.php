<?php
/**
 * @file
 * Contains the theme's settings form.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function zen_c4c_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // Create the form using Forms API: http://api.drupal.org/api/7

  $form['zen_c4c__page_title'] = array(
    '#type' => 'select',
    '#title' => t('Page title'),
    '#options' => array('_none' => t('- Ignore this option -'), 'no_regions' => t('Hide if page has no regions'), 'all' => t('Hide in all pages')),
    '#default_value' => theme_get_setting('zen_c4c__page_title'),
    '#description' => t('This option allow to hide page title to be rendered inside page.tpl.php. Useful, if you have a panels page without regions.'),
  );
  $form['zen_c4c__user_form_override_fields'] = array(
    '#type' => 'checkbox',
    '#title' => t('Override user_register_form file fields'),
    '#default_value' => theme_get_setting('zen_c4c__user_form_override_fields'),
  );
  $form['zen_c4c__user_form_override_file_fields'] = array( 
    '#type' => 'textarea',
    '#title' => t('Fields to override'),
    '#description' => t('Enter a comma-separated list of fields to override. Ex. field_user_picture,field_other_picture.'),
    '#states' => array(
      'invisible' => array(
       ':input[name="zen_c4c__user_form_override_fields"]' => array('checked' => FALSE),
      ),
    ),
    '#default_value' => theme_get_setting('zen_c4c__user_form_override_file_fields'),
  );

  /* -- Delete this line if you want to remove this base theme setting.
  // We don't need breadcrumbs to be configurable on this site.
  unset($form['breadcrumb']);
  // */

  // We are editing the $form in place, so we don't need to return anything.
}
