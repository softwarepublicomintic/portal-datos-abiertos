<?php

/**
 * @file
 */

/**
 * Implements hook_views_plugins().
 */
function better_jump_menu_views_plugins() {
  return array(
    'style' => array(
      'better_jump_menu' => array(
        'title' => t('Better Jump Menu'),
        'help' => t('Displays results in a select box.'),
        'handler' => 'better_jump_menu_plugin_style_better_jump_menu',
        'uses row plugin' => TRUE,
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'uses grouping' => TRUE,
        'type' => 'normal',
        'even empty' => TRUE,
      ),
    ),
  );
}
