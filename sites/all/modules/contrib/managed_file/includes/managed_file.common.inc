<?php

/**
 * @file
 * Auxiliary functionality.
 */

/**
 * Get list of available file widgets.
 *
 * @return array[]
 *   An array with "path" and "label" keys.
 */
function managed_file_element_widgets() {
  $widgets = [];

  foreach ([
    'imce' => [
      '#label' => t('IMCE'),
      '#module' => 'imce',
      // Function to determine the path of JS library.
      '#function' => ['drupal_get_path', ['module', 'imce']],
      '#subfolder' => 'js',
    ],
    'ckfinder' => [
      '#label' => t('CKFinder'),
      '#module' => 'ckeditor',
      '#function' => ['ckfinder_path', []],
      '#subfolder' => '',
    ],
  ] as $widget => $data) {
    if (module_exists($data['#module'])) {
      list($func, $arguments) = $data['#function'];
      $path = trim(call_user_func_array($func, $arguments), '/');

      // If empty, then module does not exists.
      if (!empty($path)) {
        $widgets[$widget] = [
          'label' => $data['#label'],
          'path' => $path . '/' . $data['#subfolder'],
        ];
      }
    }
  }

  return $widgets;
}

/**
 * Process widget of managed file.
 *
 * @param array $element
 *   Form element to process.
 *
 * @return array
 *   Processed element.
 *
 * @internal
 * @see managed_file_element_info_alter()
 */
function managed_file_element_process(array $element) {
  if (!empty($element['#widget'])) {
    $widget = $element['#widget'];
    $widgets = managed_file_element_widgets();

    if (isset($widgets[$widget])) {
      $scheme_name = file_uri_scheme($element['#upload_location']);
      /* @var \DrupalLocalStreamWrapper $stream_wrapper */
      $stream_wrapper = file_stream_wrapper_get_instance_by_scheme($scheme_name);

      if (FALSE !== $stream_wrapper) {
        global $language;

        $scheme_url = $stream_wrapper->getDirectoryPath();
        $module_path = drupal_get_path('module', 'managed_file');
        $widget_path = $widgets[$widget]['path'];

        $settings = [
          'basePath' => DRUPAL_ROOT . '/' . $widget_path,
          'location' => $element['#upload_location'],
          'language' => $language->language,
          'schemeURL' => $scheme_url,
          'schemeName' => $scheme_name,
        ];

        $settings_key = md5(serialize($settings));

        // Pass only unique settings.
        $element['#attached']['js'] = [
          // Widget library.
          [
            'data' => "$widget_path/$widget.js",
            'weight' => -1002,
          ],
          // File Manager.
          [
            'data' => "$module_path/js/file.js",
            'weight' => -1001,
          ],
          // Implementation of one of file managers.
          [
            'data' => "$module_path/js/file.$widget.js",
            'weight' => -1000,
          ],
          // Widget-related settings.
          [
            'type' => 'setting',
            'data' => [$settings_key => $settings],
          ],
        ];

        // @todo Unable to pass "data-*" or another one attribute.
        $element['#attributes']['class'][$settings_key] = "$widget-file-manager $settings_key";
      }
    }
  }

  if ($element['#preview']) {
    $element['#pre_render'][] = 'managed_file_element_preview';
  }

  if ($element['#styles']) {
    $element['style'] = [
      '#type' => 'select',
      '#title' => t('Image style'),
      '#options' => image_style_options(),
      '#default_value' => isset($element['#default_value']['style']) ? $element['#default_value']['style'] : '',
    ];
  }

  return $element;
}

/**
 * Determine the value of managed file element.
 *
 * @param array $element
 *   Form element.
 * @param array|bool $input
 *   User input data.
 * @param array $form_state
 *   Drupal form state.
 *
 * @return string[]|int
 *   An array with "style" and "fid" keys or file ID.
 *
 * @internal
 * @see managed_file_element_info_alter()
 */
function managed_file_element_value(array &$element, $input, array $form_state) {
  if (!isset($element['#default_value'])) {
    $element['#default_value'] = 0;
  }

  if (!isset($element['#upload_location'])) {
    $element['#upload_location'] = file_default_scheme() . '://';
  }

  $default_value_callback = $element['#default_value_callback'];
  $default_value =& $element['#default_value'];

  if ($element['#styles']) {
    // When this property is TRUE, then $element will be processed as it
    // have the #tree property with a TRUE value. In other words, we will
    // have an array of values instead of file ID.
    //
    // @see file_element_info()
    //
    // @example
    // @code
    // [
    //   'fid' => 1,
    //   'style' => '',
    //   'upload' => NULL,
    // ]
    // @endcode
    $element['#extended'] = TRUE;

    if (!is_array($default_value)) {
      $default_value = ['fid' => $default_value];
    }

    // Ensure that "style" property exists.
    $default_value += ['style' => ''];

    if (FALSE !== $input && isset($input['style'])) {
      $default_value['style'] = $input['style'];
    }
  }

  // When element is extended then the type of default value will be an array.
  // In another case an array could be here from previous form submission or
  // passed directly as default value.
  $default_value_array = $element['#extended'] || (is_array($default_value) && array_key_exists('fid', $default_value));

  // Unable to use ternary operator and assignment by reference.
  if ($default_value_array) {
    $fid =& $default_value['fid'];
  }
  else {
    $fid =& $default_value;
  }

  if (NULL === $fid) {
    $fid = 0;
  }

  // Convert MD5 hash to file ID.
  if (0 !== $fid && function_exists('file_md5_load_file')) {
    $file = file_md5_load_file($fid);

    if (FALSE !== $file) {
      $fid = $file->fid;
    }
  }

  // If user is input nothing then use default value.
  if (FALSE === $input) {
    return $default_value_array ? $default_value : ['fid' => $default_value];
  }

  // Get value from a $form_state to replace MD5 by file ID.
  $value =& drupal_array_get_nested_value($form_state['values'], $element['#parents']);

  if (NULL !== $value && array_key_exists('fid', $input)) {
    if (empty($input['fid'])) {
      $fid = 0;
    }

    if (is_array($value)) {
      $value['fid'] = $fid;
    }
    else {
      $value = $fid;
    }
  }

  return $default_value_callback($element, $input, $form_state);
}

/**
 * Add image preview to managed file.
 *
 * @param array $element
 *   Form element to process.
 *
 * @return array
 *   Processed element.
 *
 * @internal
 */
function managed_file_element_preview(array $element) {
  if (empty($element['#file'])) {
    hide($element['remove_button']);
  }
  else {
    $file = $element['#file'];

    hide($element['upload']);
    hide($element['upload_button']);

    if (!file_validate_is_image($file)) {
      $info = image_get_info($file->uri);
      $variables = [
        'alt' => $file->filename,
        'path' => $file->uri,
        'style_name' => 'thumbnail',
        'attributes' => [
          'class' => ['upload-preview'],
        ],
      ];

      if (is_array($info)) {
        $variables += $info;
      }

      $element['preview'] = [
        '#type' => 'markup',
        '#weight' => -10,
        '#markup' => theme('image_style', $variables),
      ];
    }
  }

  return $element;
}

/**
 * Submit managed file.
 *
 * @param array $element
 *   Form element. Needed to obtain submitted values.
 * @param array $form_state
 *   Drupal form state.
 * @param string[] $file_usage
 *   An array of arguments (module, type, id) for file_usage_add().
 */
function managed_file_element_submit(array $element, array &$form_state, array $file_usage) {
  if (!empty($element['#parents']) && !empty($element['#value']) && count($file_usage) >= 3) {
    // Load file by MD5 hash and return it instead of ID.
    $use_md5 = function_exists('file_md5_load_file');
    $value = $element['#value'];
    // Image style.
    $style = '';
    // File ID or MD5 hash.
    $fid = 0;

    $file_load = function ($argument) use ($use_md5) {
      $id = is_array($argument) ? $argument['fid'] : $argument;

      return empty($id) ? FALSE : ($use_md5 ? file_md5_load_file($id) : file_load($id));
    };

    $file_operation = function ($op, \stdClass $file) use ($file_usage) {
      call_user_func("file_$op", $file);
      array_unshift($file_usage, $file);
      // @see file_usage_add()
      // @see file_usage_delete()
      call_user_func_array('file_usage_' . ('save' === $op ? 'add' : 'delete'), array_slice($file_usage, 0, 5));

      return $file;
    };

    $file = $file_load($value);

    // File is attached and should be saved.
    if (FALSE !== $file) {
      $file->status = FILE_STATUS_PERMANENT;

      $file_operation('save', $file);
      $fid = $use_md5 ? $file->md5 : $file->fid;
    }
    // File is detached and should be removed.
    elseif (!empty($data)) {
      $file = $file_load($data);

      if (FALSE !== $file) {
        $file_operation('delete', $file);
      }
    }

    // An image style should be saved only if file present.
    if (!empty($value['style']) && !empty($fid)) {
      $style = $value['style'];
    }

    $values =& drupal_array_get_nested_value($form_state['values'], $element['#parents']);

    if (empty($element['#extended'])) {
      $values = $fid;
    }
    else {
      $values = [
        'fid' => $fid,
        'style' => $style,
      ];
    }
  }
}
