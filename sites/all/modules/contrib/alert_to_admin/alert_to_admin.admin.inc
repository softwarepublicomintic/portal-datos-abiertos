<?php

/**
 * @file
 * Code that is only expected to run when administrators are viewing the site.
 */

/**
 * Returns the page for configuring the administrator alert settings.
 */
function alert_to_admin_settings_page() {
  return drupal_get_form('alert_to_admin_settings_form');
}

/**
 * Returns a form for configuring the administrator alert settings.
 */
function alert_to_admin_settings_form() {
  $form = array();
  $form['alert_to_admin_message_text'] = array(
    '#type' => 'textarea',
    '#title' => t('Text of the alert message'),
    '#description' => t('Enter the text that site administrators will see at the top of most forms on the site.'),
    '#default_value' => variable_get('alert_to_admin_message_text'),
  );
  $form['alert_to_admin_excluded_form_ids'] = array(
    '#type' => 'textarea',
    '#title' => t('Form IDs to exclude the alert message from (advanced)'),
    '#description' => t('Enter a list of form IDs for which the site administrator alert will <em>not</em> be shown. Put each form ID on a separate line. The site administrator alert will be shown at the top of all forms except those listed here.'),
    '#default_value' => variable_get('alert_to_admin_excluded_form_ids'),
  );
  return system_settings_form($form);
}

/**
 * Alert shown to administrators at the top of most forms on the site.
 *
 * @param array $message
 *   The text of the alert message.
 *
 * @return string
 *   Themed HTML output.
 *
 * @ingroup themeable
 */
function theme_alert_to_admin_message($message) {
  // Since this CSS will potentially be shown to a few users but on many
  // different pages, it is probably a bad idea to cache it.
  drupal_add_css(drupal_get_path('module', 'alert_to_admin') . '/alert_to_admin.css');
  drupal_add_js(drupal_get_path('module', 'alert_to_admin') . '/alert_to_admin.js');
  return '<div class="admin_alert_message">' . $message['message'] . '</div>';
}

/**
 * Themes the help text on this module's administration page.
 *
 * @param string $help_text
 *   An array containing help text.
 *
 * @return string
 *   Themed HTML output.
 *
 * @ingroup themeable
 */
function theme_alert_to_admin_help($help_text) {
  $output = '';
  foreach ($help_text as $text) {
    $output .= '<p>' . $text . '</p>';
  }
  return $output;
}
