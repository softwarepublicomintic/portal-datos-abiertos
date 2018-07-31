<?php

/**
 * @file
 * Contains the list style plugin.
 */

/**
 * Style plugin to render each item in a select box.
 *
 * @ingroup views_style_plugins
 */
class better_jump_menu_plugin_style_better_jump_menu extends views_plugin_style {
  /**
   * @inheritdoc
   */
  function option_definition() {
    $options = parent::option_definition();

    $options['hide'] = array('default' => FALSE, 'bool' => TRUE);
    $options['indent'] = array('default' => '-');
    $options['path'] = array('default' => '');
    $options['depth'] = array('default' => '');
    $options['text'] = array('default' => 'Go', 'translatable' => TRUE);
    $options['label'] = array('default' => '', 'translatable' => TRUE);
    $options['choose'] = array('default' => '- Choose -', 'translatable' => TRUE);
    $options['inline'] = array('default' => TRUE, 'bool' => TRUE);

    return $options;
  }

  /**
   * @inheritdoc
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $handlers = $this->display->handler->get_handlers('field');
    if (empty($handlers)) {
      $form['error_markup'] = array(
        '#markup' => t('You need at least one field before you can configure your jump menu settings'),
        '#prefix' => '<div class="error messages">',
        '#suffix' => '</div>',
      );
      return;
    }

    $form['markup'] = array(
      '#markup' => t('To properly configure a jump menu, you must select one field that will represent the path to utilize. You should then set that field to exclude. All other displayed fields will be part of the menu. Please note that all HTML will be stripped from this output as select boxes cannot show HTML.'),
      '#prefix' => '<div class="form-item description">',
      '#suffix' => '</div>',
    );

    foreach ($handlers as $id => $handler) {
      $options[$id] = $handler->ui_name();
    }

    $form['path'] = array(
      '#type' => 'select',
      '#title' => t('Path field'),
      '#options' => $options,
      '#empty_option' => t('- Select a field -'),
      '#default_value' => $this->options['path'],
    );

    $form['depth'] = array(
      '#type' => 'select',
      '#title' => t('Depth field'),
      '#description' => t('Choose here the field that will be used to calculate the depth (or indentation). If you want to display a taxonomy, you might want to use the module !link to have it working properly.', array('!link' => l('Views Term Hierarchy Weight Field', 'https://www.drupal.org/project/views_term_hierarchy_weight_field'))),
      '#options' => $options,
      '#empty_option' => t('- Select a field -'),
      '#default_value' => $this->options['depth'],
    );

    $form['hide'] = array(
      '#type' => 'checkbox',
      '#title' => t('Hide the "Go" button'),
      '#default_value' => !empty($this->options['hide']),
      '#description' => t('If hidden, this button will only be hidden for users with javascript and the page will automatically jump when the select is changed.'),
    );

    $form['indent'] = array(
      '#type' => 'textfield',
      '#title' => t('Indentation character'),
      '#default_value' => $this->options['indent'],
      '#description' => t('The indentation character(s) to prepend to options text.'),
    );

    $form['text'] = array(
      '#type' => 'textfield',
      '#title' => t('Button text'),
      '#default_value' => $this->options['text'],
    );

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => t('Selector label'),
      '#default_value' => $this->options['label'],
      '#description' => t('The text that will appear as the the label of the selector element. If blank no label tag will be used.'),
    );

    $form['choose'] = array(
      '#type' => 'textfield',
      '#title' => t('Choose text'),
      '#default_value' => $this->options['choose'],
      '#description' => t('The text that will appear as the selected option in the jump menu.'),
    );

    $form['inline'] = array(
      '#type' => 'checkbox',
      '#title' => t('Set this field to display inline'),
      '#default_value' => !empty($this->options['inline']),
    );
  }

  /**
   * @inheritdoc
   */
  function render_grouping_sets($sets, $level = 0) {
    $output = '';
    foreach ($sets as $set) {
      $row = reset($set['rows']);
      // Render as a grouping set.
      if (is_array($row) && isset($row['group'])) {
        $output .= theme(views_theme_functions('views_view_grouping', $this->view, $this->display),
          array(
            'view' => $this->view,
            'grouping' => $this->options['grouping'][$level],
            'grouping_level' => $level,
            'rows' => $set['rows'],
            'title' => $set['group'],
          )
        );
      }
      // Render as a record set.
      else {
        if ($this->uses_row_plugin()) {

          foreach ($set['rows'] as $index => $row) {
            $this->view->row_index = $index;
            $depths[$index] = (int) strip_tags(decode_entities($this->get_field($this->view->row_index, $this->options['depth'])));
          }

          $min_depth = (int) min($depths);

          foreach ($set['rows'] as $index => $row) {
            $this->view->row_index = $index;
            // Get the depth.
            $depth = (int) $depths[$index] + 1 - $min_depth;

            // Get the path.
            $path = trim(strip_tags(decode_entities($this->get_field($this->view->row_index, $this->options['path']))));
            // Putting a '/' in front messes up url() so let's take that out
            // so users don't shoot themselves in the foot.
            $base_path = base_path();
            if (strpos($path, $base_path) === 0) {
              $path = drupal_substr($path, drupal_strlen($base_path));
            }

            // Use drupal_parse_url() to preserve query and fragment in case the user
            // wants to do fun tricks.
            $url_options = drupal_parse_url($path);

            $set['rows'][$index] = array(
              '#href' => url($url_options['path'], $url_options),
              '#title' => trim(strip_tags(decode_entities($this->row_plugin->render($row)))),
              '#original_link' => array(
                'depth' => $depth,
              ),
              '#below' => array(),
            );
          }
        }

        $options = array();
        $settings = array(
          'hide' => $this->options['hide'],
          'button' => $this->options['text'],
          'title' => $this->options['label'],
          'choose' => $this->options['choose'],
          'inline' => $this->options['inline'],
          'indent' => $this->options['indent'],
          'type' => 'view',
          'view' => $this->view,
        );
        _better_jump_menu_convert_to_options_array($set['rows'], $options, $settings);

        $output[] = drupal_get_form('better_jump_menu', $options, $settings);
      }
    }
    unset($this->view->row_index);
    return $output;
  }

}
