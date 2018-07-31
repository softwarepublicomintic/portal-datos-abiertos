<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function zen_c4c_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  zen_c4c_preprocess_html($variables, $hook);
  zen_c4c_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("html" in this case.)
 */
function zen_c4c_preprocess_html(&$variables, $hook) {
  $arg = arg();
  /*
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  $variables['classes_array'] = array_diff($variables['classes_array'],
    array('class-to-remove')
  );
  */

  // Add CSS classes depending some pages:
  if (current_path() == 'node/add') {
    $variables['classes_array'][] = 'select-content-type';
  }

  $section = isset($_GET['section']) ? 'section-' . $_GET['section'] : 'section-default';
  $variables['classes_array'][] = $section;

  if (in_array('translate', $arg)) {
    $variables['classes_array'][] = 'page-translation';
  }
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function zen_c4c_preprocess_page(&$variables, $hook) {
  $page_title_option = theme_get_setting('zen_c4c__page_title');
  $check_regions = drupal_static('ctools_set_no_blocks', FALSE);

  switch($page_title_option) {
    case 'all':
      $hide = TRUE;
      break;
    case 'no_regions':
      $hide = !$check_regions ? TRUE : FALSE;
      break;
    default:
      $hide = FALSE;
      break;
  }

  $variables['page_title_option'] = $page_title_option;
  $variables['page_title_hide'] = $hide;

  // === Translate some page titles:
  // Menu admin page:
  if (drupal_match_path(current_path(), 'admin/structure/menu/manage/*')) {
    $title = drupal_get_title();
    drupal_set_title(t(filter_xss($title)));
  }
  // ECK add page:
  if (drupal_match_path(current_path(), 'admin/structure/entity-type/*/*/add')) {
    $title = drupal_get_title();
    drupal_set_title(t(filter_xss($title)));
  }

  // Node delete:
  if (drupal_match_path(current_path(), 'node/*/delete')) {
    $node = menu_get_object('node', 1);
    $title = t('Delete @title?', array('@title' => filter_xss($node->title)));
    drupal_set_title($title);
  }
}


/**
 * Implements hook_preprocess_easy_breadcrumb().
 * Workaround for some features.
 */
function zen_c4c_preprocess_easy_breadcrumb(&$variables) {
  // Workaround for last item when using module "menu_position": Set the node title.
  if (module_exists('menu_position')) {
    $node = menu_get_object('node');
    if ($node) {
      end($variables['breadcrumb']);
      $key = key($variables['breadcrumb']);
      $variables['breadcrumb'][$key]['content'] = filter_xss($node->title);
    }
  }

  // Workaround for translations:
  foreach($variables['breadcrumb'] as $item => $options) {
    $content = $variables['breadcrumb'][$item]['content'];
    $variables['breadcrumb'][$item]['content'] = t(filter_xss($content));
  }
}

/**
 * Override or insert variables into the region templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function zen_c4c_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--no-wrapper.tpl.php template for sidebars.
  if (strpos($variables['region'], 'sidebar_') === 0) {
    $variables['theme_hook_suggestions'] = array_diff(
      $variables['theme_hook_suggestions'], array('region__no_wrapper')
    );
  }
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function zen_c4c_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  if ($variables['block_html_id'] == 'block-system-main') {
    $variables['theme_hook_suggestions'] = array_diff(
      $variables['theme_hook_suggestions'], array('block__no_wrapper')
    );
  }
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function zen_c4c_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // zen_c4c_preprocess_node_page() or zen_c4c_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("comment" in this case.).
 */
function zen_c4c_preprocess_comment(&$variables, $hook) {
  // Following code is a workaround to hide some links using comentaccess. Users
  // must have permissions to edit any comment in order to allow them to edit comments
  // on their own posts. Access to edit page is controlled externally to this function. May
  // be using Rules to redirect if user is not allowed to do some tasks.
  $node = $variables['node'];
  $user = $variables['user'];
  $comment_author_uid = $variables['comment']->uid;

  if (module_exists('commentaccess')) {
    $unset_rules = array(
      'commentaccess' => user_access("administer comments on own $node->type") ? TRUE : FALSE,
      'user_is_allowed' => ($user->uid == $node->uid) || user_access('bypass node access') ? TRUE : FALSE,
    );

    if (in_array(FALSE, $unset_rules) && ($user->uid != 1)) {
      if (isset($variables['content']['links']['comment']['#links']['comment-edit']) && $user->uid != $comment_author_uid) {
        unset($variables['content']['links']['comment']['#links']['comment-edit']);
      }
      if (isset($variables['content']['links']['comment']['#links']['comment-delete']) && $user->uid != $comment_author_uid) {
        unset($variables['content']['links']['comment']['#links']['comment-delete']);
      }
    }
  }

}
// */

/**
 * Default theme implementation for lists
 */
function zen_c4c_owlcarousel_list(&$vars) {
  $items = &$vars['items'];
  $output = '';

  if (!empty($items)) {
    $responsivetypes=array(
      'owlcarousel_settings_news-events-responsive',
      'owlcarousel_settings_community-responsive',
    );


    if(!in_array($vars['settings']['instance'], $responsivetypes)) {
      foreach ($items as $i => $item) {
        if ($item['row']) {
          $striping = $i % 2 == 0 ? 'item-odd' : 'item-even';
          $output .= theme('owlcarousel_list_item', array(
            'item' => $item['row'],
            'class' => drupal_html_class('item-' . $i). ' ' . drupal_html_class($striping),
            'group' => $vars['settings']['instance']
          ));
        }
      }
    }
    else {
      $newitems=array();
      foreach($items AS $i=>$item) {
        if($i%2==0) {
          if(!empty($items[$i+1])) {
            $newitems[]['row']='<div class="views-row views-row-odd">' . $items[$i]['row'] . '</div><div class="views-row views-row-even">' . $items[$i+1]['row'] . '</div>';
          }
          else {
            $newitems[]['row']='<div class="views-row views-row-odd">' . $items[$i]['row'] . '</div>';
          }
        }
      }
      foreach ($newitems as $i => $item) {
        if ($item['row']) {
          $striping = $i % 2 == 0 ? 'item-odd' : 'item-even';
          $output .= theme('owlcarousel_list_item', array(
            'item' => $item['row'],
            'class' => drupal_html_class('item-' . $i). ' ' . drupal_html_class($striping),
            'group' => $vars['settings']['instance']
          ));
        }
      }
    }
  }

  return $output;
}

/**
 * Implements hook_form_user_register_form_alter().
 */
function zen_c4c_form_user_register_form_alter(&$form, &$form_state, $form_id) {
  $override_file_fields = theme_get_setting('zen_c4c__user_form_override_fields');
  //drupal_add_js(drupal_get_path('theme', 'zen_c4c') . '/js/user-form--input-file.js');

  if ($override_file_fields) {

    // Prepare new widgets:
    $element_push = array(
      'choose' => array(
        '#markup' => '<a href="#" op="choose" class="button selector trigger">' . t('Choose picture') . '</a>',
      ),
      'upload' => array(
        '#markup' => '<a href="#" op="upload" class="button upload trigger">' . t('Upload') . '</a>',
      ),
      'current' => array(
        '#prefix' => '<div op="choose" class="current selector trigger">',
        '#suffix' => '</div>',
        '#markup' => t('No selected file'),
      ),
    );
    $element_remove = array(
      'delete' => array(
        '#markup' => '<a href="#" op="remove" class="button remove trigger">' . t('Change picture') . '</a>',
      ),
    );

    // Get fields to override:
    $fields = explode(',', str_replace(' ', '', theme_get_setting('zen_c4c__user_form_override_file_fields')));

    foreach($fields as $key => $field) {
      $language = $form[$field]['#language'];
      $form[$field]['#attributes']['class'][] = 'zen-c4c--replace-widget';
      foreach (element_children($form[$field][$language]) as $delta) {
        $field_choose = 'files[' . $field . '_' . $language . '_' . $delta . ']';
        $field_upload = $field . '_' . $language . '_' . $delta . '_upload_button';
        $field_remove = $field . '_' . $language . '_' . $delta . '_remove_button';

        $field_is_empty = !isset($form_state['values'][$field][$language][$delta]) ? TRUE : FALSE;

        $form[$field][$language][$delta]['themed_widget'] = array(
          '#prefix' => '<div class="zen-c4c--file-upload" choose="' . $field_choose . '" upload="' . $field_upload . '" remove="' . $field_remove . '">',
          '#suffix' => '</div>',
          'widget' => $field_is_empty ? $element_push : $element_remove,
        );
      }
    }
    $form['#attached']['js'] = array(
      drupal_get_path('theme', 'zen_c4c') . '/js/user-form--input-file.js',
    );
  }
}

/**
 * Implements hook_preprocess_file_upload_help().
 */
function zen_c4c_preprocess_file_upload_help(&$variables) {
  if (isset($variables['upload_validators']['file_validate_extensions'][0])) {
    $variables['upload_validators']['file_validate_extensions'][0] = str_replace(' ', ', ', $variables['upload_validators']['file_validate_extensions'][0]);
  }
}

/**
 * Implements hook_preprocess_filter_tips().
 */
function zen_c4c_preprocess_filter_tips(&$variables) {
  foreach ($variables['tips'] as $filter_name => $options) {
    $filter_name_i18n = t(filter_xss($filter_name));
    unset($variables['tips'][$filter_name]);
    $variables['tips'][$filter_name_i18n] = $options;
  }
}

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
/*
function zen_c4c_form_node_form_alter(&$form, &$form_state, $form_id) {

  //if (isset($form['body'])) {
    //$form['body']['#after_build'][] = 'zen_c4c_form_node_text_formats';
  //}

  //$form['additional_settings']['#access'] = FALSE;


  // Workaround for Ajax Form Entity: Required fields are not marked
  // with class "error" after submitting a file or making another ajax request:
  if (module_exists('ajax_form_entity')) {
    $form['#validate'][] = 'zen_c4c_form_fields_validate';
  }

  if (!user_access('administer nodes')) {
    $div = '<div class="element-invisible">';
  }
  else {
    drupal_add_css(
      '.node-form-options .form-item-promote,
       .node-form-options .form-item-sticky {
          display: none;
      }',
      array(
        'group' => CSS_THEME,
        'type' => 'inline',
        'preprocess' => FALSE,
        'weight' => '9999',
      )
    );
    $div = '<div class="node-options-vtabs" style="width: 100%; float: left;">';
  }
  $form['additional_settings']['#prefix'] = $div;
  $form['additional_settings']['#suffix'] = '</div>';

  $form['#attached']['js'] = array(
    drupal_get_path('theme', 'zen_c4c') . '/js/node-form--input-file.js',
  );

}
*/
/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function zen_c4c_form_node_form_alter(&$form, &$form_state, $form_id) {
  /*
  if (isset($form['body'])) {
    $form['body']['#after_build'][] = 'zen_c4c_form_node_text_formats';
  }
  */
  //$form['additional_settings']['#access'] = FALSE;


  // Workaround for Ajax Form Entity: Required fields are not marked
  // with class "error" after submitting a file or making another ajax request:
  if (module_exists('ajax_form_entity')) {
    $form['#validate'][] = 'zen_c4c_form_fields_validate';
  }

  if (!user_access('administer nodes')) {
    $div = '<div class="element-invisible">';
  }
  else {
    drupal_add_css(
      '.node-form-options .form-item-promote,
       .node-form-options .form-item-sticky {
          display: none;
      }',
      array(
        'group' => CSS_THEME,
        'type' => 'inline',
        'preprocess' => FALSE,
        'weight' => '9999',
      )
    );
    $div = '<div class="node-options-vtabs" style="width: 100%; float: left;">';
  }
  $form['additional_settings']['#prefix'] = $div;
  $form['additional_settings']['#suffix'] = '</div>';

  /*
  $form['#attached']['js'] = array(
    drupal_get_path('theme', 'zen_c4c') . '/js/node-form.validate.js',
  );
  */
  if (!isset($form['#attached']['js'])) {
    $form['#attached']['js'] = array(
      drupal_get_path('theme', 'zen_c4c') . '/js/node-form.validate.js',
    );
  }
  else {
    $form['#attached']['js'][] = drupal_get_path('theme', 'zen_c4c') . '/js/node-form.validate.js';
  }

}

/*
function zen_c4c_form_fields_validate($form, &$form_state) {
  if ($errors = form_get_errors()) {
      $selectors = array();
      foreach ($errors as $name => $message) {
        $field_name = strtok($name,  '][');

        // Possible fields:
        $parent = '#edit-' . drupal_html_class($field_name);
        $selectors[] = 'input'. $parent;
        $selectors[] = $parent . ' input';
        $selectors[] = $parent . ' textarea';
        //$selectors[] = $parent . ' select';
        $selectors[] = $parent . ' .chosen-container';
      }

      // Last resource: Project form: Sorry, there isn't more time to researh and make a good approach.
      $lang = $form_state['values']['language'];
      $fake_prj_status = isset($form_state['values']['field_project_status']['und'][0]['tid']) ? $form_state['values']['field_project_status']['und'][0]['tid'] : 'NONE';
      $fake_prj_type = isset($form_state['values']['field_project_type'][$lang][0]['tid']) ? $form_state['values']['field_project_type'][$lang][0]['tid'] : 'NONE';
      $fake_prj_category = isset($form_state['values']['field_category'][$lang][0]['tid']) ? $form_state['values']['field_category'][$lang][0]['tid'] : 'NONE';
      $fake_prj_year = isset($form_state['values']['field_project_year']['und'][0]['value']) ? $form_state['values']['field_project_year']['und'][0]['value'] : 'NONE';
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldType' => $fake_prj_type)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldCategory' => $fake_prj_category)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldYear' => $fake_prj_year)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldStatus' => $fake_prj_status)), 'setting');

      // Last resource: Application form: Sorry, there isn't more time to researh and make a good approach.
      $fake_app_type = isset($form_state['values']['field_application_type']['und'][0]['tid']) ? $form_state['values']['field_application_type']['und'][0]['tid'] : 'NONE';
      $fake_app_year = isset($form_state['values']['field_application_year']['und'][0]['value']) ? $form_state['values']['field_application_year']['und'][0]['value'] : 'NONE';
      drupal_add_js(array('ZenC4CNodeFormApplication' => array('ErrorFieldAppType' => $fake_app_type)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormApplication' => array('ErrorFieldAppYear' => $fake_app_year)), 'setting');

      // Add error fields:
      drupal_add_js(array('ZenC4CNodeForm' => array('ErrorFields' => $selectors)), 'setting');
    }
}
*/
function zen_c4c_form_fields_validate($form, &$form_state) {
  if ($errors = form_get_errors()) {
      $selectors = array();
      foreach ($errors as $name => $message) {
        $field_name = strtok($name,  '][');

        // Possible fields:
        $parent = '#edit-' . drupal_html_class($field_name);
        $selectors[] = 'input'. $parent;
        $selectors[] = $parent . ' input';
        $selectors[] = $parent . ' textarea';
        //$selectors[] = $parent . ' select';
        $selectors[] = $parent . ' .chosen-container';
      }

      // Last resource: Project form: Sorry, there isn't more time to researh and make a good approach.
      $lang = $form_state['values']['language'];
      $fake_prj_status = isset($form_state['values']['field_project_status']['und'][0]['tid']) ? $form_state['values']['field_project_status']['und'][0]['tid'] : 'NONE';
      $fake_prj_type = isset($form_state['values']['field_project_type'][$lang][0]['tid']) ? $form_state['values']['field_project_type'][$lang][0]['tid'] : 'NONE';
      $fake_prj_category = isset($form_state['values']['field_category'][$lang][0]['tid']) ? $form_state['values']['field_category'][$lang][0]['tid'] : 'NONE';
      $fake_prj_year = isset($form_state['values']['field_project_year']['und'][0]['value']) ? $form_state['values']['field_project_year']['und'][0]['value'] : 'NONE';
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldType' => $fake_prj_type)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldCategory' => $fake_prj_category)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldYear' => $fake_prj_year)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormProject' => array('ErrorFieldStatus' => $fake_prj_status)), 'setting');

      // Last resource: Application form: Sorry, there isn't more time to researh and make a good approach.
      $fake_app_type = isset($form_state['values']['field_application_type']['und'][0]['tid']) ? $form_state['values']['field_application_type']['und'][0]['tid'] : 'NONE';
      $fake_app_year = isset($form_state['values']['field_application_year']['und'][0]['value']) ? $form_state['values']['field_application_year']['und'][0]['value'] : 'NONE';
      drupal_add_js(array('ZenC4CNodeFormApplication' => array('ErrorFieldAppType' => $fake_app_type)), 'setting');
      drupal_add_js(array('ZenC4CNodeFormApplication' => array('ErrorFieldAppYear' => $fake_app_year)), 'setting');

      // Add error fields:
      drupal_add_js(array('ZenC4CNodeForm' => array('ErrorFields' => $selectors)), 'setting');
    }
}

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function zen_c4c_form_eck__entity__form_alter(&$form, &$form_state, $form_id) {
  if (isset($form['title'])) {
    $label = $form['title']['#title'];
    $form['title']['#title'] = t(filter_xss($label));
  }
}

/**
 * Implements hook_date_popup_process_alter().
 */
function zen_c4c_date_popup_process_alter(&$element, &$form_state, $context) {
  if (strpos($element['#id'], '-value2') === false) {
    $element['#title'] = t('From');
    unset($element['#title_display']);
  }
}

/**
 * Translate text format names.
 */
 /*
function zen_c4c_form_node_text_formats(&$field) {
  $language = $field['#language'];
  foreach (element_children($field[$language]) as $delta => $options) {
    foreach($field[$language][$delta]['format']['format']['#options'] as $key => $format_name) {
      $field[$language][$delta]['format']['format']['#options'][$key] = t(filter_xss($format_name));
    }
  }
  return $field;
}
*/

/**
 * Implements hook_preprocess_image_widget().
 */
/*
function zen_c4c_preprocess_image_widget(&$variables) {
  if ($variables['element']['#entity_type'] == 'node') {
    $button = '<span class="zen-c4c-button">' . t('Choose') . '</span>';
    $filename = '<span class="zen-c4c-filename">' . t('No file chosen.') . '</span>';
    $variables['element']['upload']['#prefix'] = '<span class="zen-c4c--file-upload">' . $button . $filename . '</span>';
  }
}
*/
/**
 * Implements hook_preprocess_image_widget().
 */
function zen_c4c_preprocess_image_widget(&$variables) {
  //if ($variables['element']['#bundle'] != 'user') {
  if (current_path() != '/user/register') {
    $button = '<span class="zen-c4c-button">' . t('Choose') . '</span>';
    $filename = '<span class="zen-c4c-filename">' . t('No file chosen.') . '</span>';
    $variables['element']['upload']['#prefix'] = '<span class="zen-c4c--file-upload">' . $button . $filename . '</span>';
  }
}

/**
 * Implements hook_preprocess_image_widget().
 */
function zen_c4c_preprocess_file_widget(&$variables) {
  if ($variables['element']['#bundle'] != 'user') {
    $button = '<span class="zen-c4c-button">' . t('Choose') . '</span>';
    $filename = '<span class="zen-c4c-filename">' . t('No file chosen.') . '</span>';
    $variables['element']['upload']['#prefix'] = '<span class="zen-c4c--file-upload">' . $button . $filename . '</span>';
  }
}


/**
 * Implements hook_form_FORM_ID_alter().
 */
function zen_c4c_form_user_profile_form_alter(&$form, &$form_state, $form_id) {
  $form['account']['current_pass']['#description'] = t('Enter your current password to change the <em>E-mail</em> or <em>Password</em>.');
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function zen_c4c_form_group_membership_form_alter(&$form, &$form_state, $form_id) {
  $gid = $form_state['group_membership']->gid;
  $form['actions']['submit']['#weight'] = 1;
  $form['actions']['back'] = array(
    '#type' => 'markup',
    '#markup' => l(t('Go back'), 'group/' . $gid . '/member'),
    '#weight' => 2,
  );
}

/**
 * Implements hook_form_alter().
 */
/*
function zen_c4c_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'gadd_add_member_form') {

  }
}
*/
/**
 * Implements hook_form_alter().
 */
function zen_c4c_form_alter(&$form, &$form_state, $form_id) {
  $allowed_forms = array(
    'tools_node_form',
    'news_node_form',
    'event_node_form',
    'project_node_form',
    'project_application_node_form',
    'eck__entity__form_edit_managed_slideshow_standard_slideshow',
    'bean_form',
    'user_profile_form'
  );
  if (in_array($form_id, $allowed_forms)) {
    if (!isset($form['#attached']['js'])) {
      $form['#attached']['js'] = array(
        drupal_get_path('theme', 'zen_c4c') . '/js/input.file.js',
      );
    }
    else {
      $form['#attached']['js'][] = drupal_get_path('theme', 'zen_c4c') . '/js/input.file.js';
    }
  }
}

/*
function zen_c4c_preprocess_menu_overview_form($variables) {

}
*/

/**
 * Implements hook_preprocess_table().
 */
function zen_c4c_preprocess_table(&$variables) {
  if (drupal_match_path(current_path(), 'admin/structure/menu/manage/*')) {
    $label_0 = $variables['header'][0]; // Menu link
    $label_1 = $variables['header'][1]['data']; // Status
    $label_2 = $variables['header'][2]; // Weight
    $label_3 = $variables['header'][3]['data']; // Operations.

    foreach ($variables['rows'] as $row => $row_data) {
      foreach ($variables['rows'][$row]['data'] as $col => $col_data) {
        switch($col) {
          case 0:
            $label = $label_0;
            break;

          case 1:
            $label = $label_1;
            break;

          case 2:
            $label = $label_2;
            break;

          case 3:
          case 4:
          case 5:
          case 6:
          case 7:
          case 8:
          case 9:
          case 10:
            $label = $label_3;
            break;

          default:
            $label = '';
            break;
        }

        if (is_array($col_data)) {
          $variables['rows'][$row]['data'][$col]['data-label'] = $label;
        }
        else {
          $data = $variables['rows'][$row]['data'][$col];
          $class = 'menu--zen-c4c mc4c-' . $col;
          $variables['rows'][$row]['data'][$col] = array('data' => $data, 'class' => $class, 'data-label' => $label);
        }
      }
    }
  }
}
