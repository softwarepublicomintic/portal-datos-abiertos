<?php
/**
 * @file
 * Implementation provided by the select_option_sort module.
 */

/**
 * Alter the field settings form.
 */
function _select_option_sort_field_settings_form_alter(&$form, $form_state, $form_id) {
  // Check if field widget is only "Select list"
  if ($form['instance']['widget']['type']['#value'] == 'options_select' && user_access('administer select option sort field settings')) {
    $field_name = key($form_state['field']);
    // Check if field exist in database.
    $results = _select_option_sort_check_field_exist($field_name);

    if (!empty($results)) {
      $apply_sort_option = 1;
      $order_by = $results['order_by'];
      $sort_order = $results['sort_order'];
    }
    else {
      $apply_sort_option = FALSE;
      $order_by = 'text';
      $sort_order = 'asc';
    }

    // Add form elements.
    $form['field']['select_option_sort'] = array(
      '#type' => 'fieldset',
      '#title' => t('Sort options'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $form['field']['select_option_sort']['apply_sort_option'] = array(
      '#type' => 'checkbox',
      '#title' => t('Apply sort option'),
      '#default_value' => $apply_sort_option,
    );

    $form['field']['select_option_sort']['order_by'] = array(
      '#type' => 'select',
      '#title' => t('Order by'),
      '#options' => array(
        'text' => t('Text'),
        'value' => t('Value'),
      ),
      '#default_value' => $order_by,
      '#description' => t('Order by <em>value</em> or <em>text</em>. In example: <em>@options</em>, <em>value</em> = MDA, <em>text</em> = Moldova.',
        array(
          '@options' => '<option value="MDA">Moldova</option>',
        )),
    );

    $form['field']['select_option_sort']['sort_order'] = array(
      '#type' => 'select',
      '#title' => t('Sort'),
      '#options' => array(
        'asc' => t('Ascending'),
        'desc' => t('Descending'),
      ),
      '#default_value' => $sort_order,
      '#description' => t('Select Sort order <em>Ascending</em> or <em>Descending</em>. Order to sort is case insensitive.'),
    );

    // Add a submit handler to process the field permissions settings.
    $form['#submit'][] = 'select_option_sort_field_settings_form_submit';
  }
}

/**
 * Form callback. Submit handler for the Field Settings form.
 */
function select_option_sort_field_settings_form_submit($form, &$form_state) {
  $apply = $form_state['values']['field']['select_option_sort']['apply_sort_option'];
  $order_by = $form_state['values']['field']['select_option_sort']['order_by'];
  $sort_order = $form_state['values']['field']['select_option_sort']['sort_order'];
  $field_name = key($form_state['field']);

  if ($apply == 1) {
    // Save settings to select_option_sort table.
    _select_option_sort_change_db_row('update', $field_name, $order_by, $sort_order);
  }
  else {
    // Delete settings from select_option_sort table.
    _select_option_sort_change_db_row('delete', $field_name, $order_by, $sort_order);
  }
}
