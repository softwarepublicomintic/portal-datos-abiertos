<?php

/**
 * @file
 * This contains the form definition.
 */

/**
 * Generate a jump menu form.
 *
 * This can either be used with drupal_get_form() or directly added to a
 * form. The button provides its own submit handler so by default, other
 * submit handlers will not be called.
 *
 * One note: Do not use #tree = TRUE or it will be unable to find the
 * proper value.
 *
 * @code
 * ctools_include('jump-menu');
 * $output = drupal_get_form('ctools_jump_menu', $targets, $options);
 * @endcode
 *
 * @param $select
 *   An array suitable for use as the #options. The keys will be the direct
 *   URLs that will be jumped to, so you absolutely must encode these using
 *   url() in order for them to work reliably.
 *
 * @param $options
 *   $options may be an array with the following options:
 *   - 'title': The text to display for the #title attribute.
 *   - 'description': The text to display for the #description attribute.
 *   - 'default_value': The text to display for the #default_value attribute.
 *   - 'hide': If TRUE the go button will be set to hide via javascript and
 *     will submit on change.
 *   - 'button': The text to display on the button.
 *   - 'image': If set, an image button will be used instead, and the image
 *     set to this.
 *   - 'inline': If set to TRUE (default) the display will be forced inline.
 */
function better_jump_menu($form, &$form_state, $select, $options = array()) {
  ctools_include('jump-menu');

  $options += array(
    'button' => t('Go'),
    'choose' => t('- Choose -'),
    'inline' => TRUE,
    'hide' => TRUE,
    'external' => FALSE,
  );

  $classes = array('better_jump_menu', 'ctools-jump-menu-select');
  $classes[] = isset($options['type']) ? $options['type'] : NULL;

  if (isset($options['type']) && $options['type'] == 'menu' && isset($options['name'])) {
    $form['#contextual_links']['menu'] = array('admin/structure/menu/manage', array($options['name']));
    $classes[] = $options['name'];
  }
  if (isset($options['type']) && $options['type'] == 'view' && isset($options['view'])) {
    $classes[] = $options['view']->name;
    $classes[] = $options['view']->current_display;
  }

  $form['#attached']['js'][] = array(
    'data' => drupal_get_path('module', 'better_jump_menu') . '/js/better-jump-menu.js',
    'type' => 'file',
    'weight' => -10,
  );

  if (!empty($options['choose'])) {
    $select = array('' => $options['choose']) + $select;
  }

  // Find the value of the jump form element.
  $option_value = '';
  $url = parse_url(url($_GET['q'], array('absolute' => TRUE)));
  $path = $url['path'];

  do {
    foreach (array_keys($select) as $option_value) {
      if (!$option_value) {
        continue;
      }
      list($id, $url) = explode('::', $option_value, 2);
      if ($url == url($path, array('absolute' => TRUE))) {
        $path = '';
        break;
      }
      else {
        $option_value = '';
      }
    }
    $path = substr($path, 0, strrpos($path, '/'));

  } while ($path != '');

  $form['jump'] = array(
    '#type' => 'select',
    '#options' => $select,
    '#attributes' => array(
      'class' => $classes,
    ),
    '#value' => $option_value,
  );

  if ($options['external']) {
    $form['jump']['#attributes']['data-external'] = (bool) $options['external'];
  }

  if (!empty($options['title'])) {
    $form['jump']['#title'] = $options['title'];
  }

  if (!empty($options['description'])) {
    $form['jump']['#description'] = $options['description'];
  }

  if (isset($options['image'])) {
    $form['go'] = array(
      '#type' => 'image_button',
      '#src' => $options['image'],
      '#submit' => array('better_jump_menu_submit'),
      '#attributes' => array(
        'class' => array('ctools-jump-menu-button'),
      ),
    );
  }
  else {
    $form['go'] = array(
      '#type' => 'submit',
      '#value' => $options['button'],
      '#submit' => array('better_jump_menu_submit'),
      '#attributes' => array(
        'class' => array('ctools-jump-menu-button'),
      ),
    );
  }

  if ($options['inline']) {
    $form['jump']['#prefix'] = '<div class="container-inline">';
    $form['go']['#suffix'] = '</div>';
  }

  if ($options['hide']) {
    $form['jump']['#attributes']['class'][] = 'ctools-jump-menu-change';
    $form['go']['#attributes']['class'][] = 'ctools-jump-menu-hide';
  }

  return $form;
}

/**
 * Submit handler for the jump menu.
 *
 * This is normally only invoked upon submit without javascript enabled.
 */
function better_jump_menu_submit($form, &$form_state) {
  if ($form_state['input']['jump'] === '') {
    // We have nothing to do when the user has not selected any value.
    return;
  }

  // If the path we are redirecting to contains the string :: then treat the
  // the string after the double colon as the path to redirect to.
  // This allows duplicate paths to be used in jump menus for multiple options.
  $redirect_array = explode("::", $form_state['input']['jump']);

  if (isset($redirect_array[1]) && !empty($redirect_array[1])) {
    $redirect = $redirect_array[1];
  }
  else {
    $redirect = $form_state['input']['jump'];
  }

  // If the path we are redirecting to starts with the base path (for example,
  // "/somepath/node/1"), we need to strip the base path off before passing it
  // to $form_state['redirect'].
  $base_path = base_path();
  if (strpos($redirect, $base_path) === 0) {
    $redirect = substr($redirect, strlen($base_path));
  }

  // Parse the URL so that query strings and fragments are preserved in the
  // redirect.
  $redirect = drupal_parse_url($redirect);
  $redirect['path'] = urldecode($redirect['path']);
  $form_state['redirect'] = array($redirect['path'], $redirect);
}
