<?php

/**
 * @file
 * Helpers functions.
 */

/**
 * Convert the result of menu_tree_output to an option array.
 *
 * @param $build
 * @param $options
 * @param $configuration
 *
 * @return mixed
 */
function _better_jump_menu_convert_to_options_array(&$build, &$options, $configuration) {
  foreach (element_children($build) as $key) {
    $value = uniqid() . '::' . url($build[$key]['#href'], array('absolute' => TRUE));
    $indent = str_repeat($configuration['indent'], $build[$key]['#original_link']['depth'] - 1);
    $options[$value] = trim(sprintf('%s %s', $indent, trim($build[$key]['#title'])));
    _better_jump_menu_convert_to_options_array($build[$key]['#below'], $options, $configuration);
  }
}

/**
 * Get the configuration for a better jump menu block.
 *
 * @param $menu_machine_name
 *   The menu machine name.
 *
 * @return array
 */
function _better_jump_menu_get_block_default_configuration($menu_machine_name) {
  $configuration = variable_get('better_jump_menu_block_configuration', array());

  $defaults = array(
    'external' => FALSE,
    'hide' => FALSE,
    'path' => '',
    'indent' => '-',
    'text' => t('Go'),
    'title' => '',
    'choose' => t('- Choose -'),
    'inline' => TRUE,
  );

  return isset($configuration[$menu_machine_name]) ? $configuration[$menu_machine_name] + $defaults : $defaults;
}
