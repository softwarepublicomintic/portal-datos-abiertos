<?php

/**
 * @file
 * View templates for the Better Jump Menu module.
 */

/**
 * Implements hook_views_templates().
 */
function better_jump_menu_views_templates() {
  $views = array();

  if (!module_exists('entity_menu_links')) {
    return array();
  }

  foreach(menu_get_menus(TRUE) as $menu_machine_name => $menu_title) {

    $view = new view();
    $view->name = 'bjm_menu_' . $menu_machine_name;
    $view->description = 'Better Jump Menu for ' . $menu_title . ' menu.';
    $view->tag = 'default';
    $view->base_table = 'menu_links';
    $view->human_name = 'Better Jump Menu: ' . $menu_title;
    $view->core = 7;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->new_display('default', 'Master', 'default');
    $handler->display->display_options['title'] = $menu_title;
    $handler->display->display_options['use_more_always'] = FALSE;
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['query']['type'] = 'views_query';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'none';
    $handler->display->display_options['pager']['options']['offset'] = '0';
    $handler->display->display_options['style_plugin'] = 'better_jump_menu';
    $handler->display->display_options['style_options']['path'] = 'router_path';
    $handler->display->display_options['style_options']['depth'] = 'depth';
    $handler->display->display_options['row_plugin'] = 'fields';
    /* Field: Menu link: Menu_name */
    $handler->display->display_options['fields']['menu_name']['id'] = 'menu_name';
    $handler->display->display_options['fields']['menu_name']['table'] = 'menu_links';
    $handler->display->display_options['fields']['menu_name']['field'] = 'menu_name';
    $handler->display->display_options['fields']['menu_name']['label'] = '';
    $handler->display->display_options['fields']['menu_name']['exclude'] = TRUE;
    $handler->display->display_options['fields']['menu_name']['element_label_colon'] = FALSE;
    /* Field: Menu link: Depth */
    $handler->display->display_options['fields']['depth']['id'] = 'depth';
    $handler->display->display_options['fields']['depth']['table'] = 'menu_links';
    $handler->display->display_options['fields']['depth']['field'] = 'depth';
    $handler->display->display_options['fields']['depth']['label'] = '';
    $handler->display->display_options['fields']['depth']['exclude'] = TRUE;
    $handler->display->display_options['fields']['depth']['alter']['text'] = '[depth]-1';
    $handler->display->display_options['fields']['depth']['element_label_colon'] = FALSE;
    $handler->display->display_options['fields']['depth']['separator'] = '';
    /* Field: Menu link: Link_path */
    $handler->display->display_options['fields']['link_path']['id'] = 'link_path';
    $handler->display->display_options['fields']['link_path']['table'] = 'menu_links';
    $handler->display->display_options['fields']['link_path']['field'] = 'link_path';
    $handler->display->display_options['fields']['link_path']['label'] = '';
    $handler->display->display_options['fields']['link_path']['exclude'] = TRUE;
    $handler->display->display_options['fields']['link_path']['element_label_colon'] = FALSE;
    /* Field: Menu link: Link_title */
    $handler->display->display_options['fields']['link_title']['id'] = 'link_title';
    $handler->display->display_options['fields']['link_title']['table'] = 'menu_links';
    $handler->display->display_options['fields']['link_title']['field'] = 'link_title';
    $handler->display->display_options['fields']['link_title']['label'] = '';
    $handler->display->display_options['fields']['link_title']['element_label_colon'] = FALSE;
    /* Field: Menu link: Router_path */
    $handler->display->display_options['fields']['router_path']['id'] = 'router_path';
    $handler->display->display_options['fields']['router_path']['table'] = 'menu_links';
    $handler->display->display_options['fields']['router_path']['field'] = 'router_path';
    $handler->display->display_options['fields']['router_path']['label'] = '';
    $handler->display->display_options['fields']['router_path']['exclude'] = TRUE;
    $handler->display->display_options['fields']['router_path']['element_label_colon'] = FALSE;

    if (module_exists('views_term_hierarchy_weight_field')) {
      /* Sort criterion: Menu link: Hweight */
      $handler->display->display_options['sorts']['hweight']['id'] = 'hweight';
      $handler->display->display_options['sorts']['hweight']['table'] = 'menu_links';
      $handler->display->display_options['sorts']['hweight']['field'] = 'hweight';
    }

    /* Filter criterion: Menu link: Menu_name */
    $handler->display->display_options['filters']['menu_name']['id'] = 'menu_name';
    $handler->display->display_options['filters']['menu_name']['table'] = 'menu_links';
    $handler->display->display_options['filters']['menu_name']['field'] = 'menu_name';
    $handler->display->display_options['filters']['menu_name']['value'] = array(
      $menu_machine_name => $menu_machine_name
    );
    /* Filter criterion: Menu link: Router_path */
    $handler->display->display_options['filters']['router_path']['id'] = 'router_path';
    $handler->display->display_options['filters']['router_path']['table'] = 'menu_links';
    $handler->display->display_options['filters']['router_path']['field'] = 'router_path';
    $handler->display->display_options['filters']['router_path']['operator'] = 'not';
    $handler->display->display_options['filters']['router_path']['value'] = '%';
    /* Filter criterion: Menu link: Router_path */
    $handler->display->display_options['filters']['link_path']['id'] = 'link_path';
    $handler->display->display_options['filters']['link_path']['table'] = 'menu_links';
    $handler->display->display_options['filters']['link_path']['field'] = 'link_path';
    $handler->display->display_options['filters']['link_path']['operator'] = 'longerthan';
    $handler->display->display_options['filters']['link_path']['value'] = '0';
    /* Filter criterion: Menu link: Hidden */
    $handler->display->display_options['filters']['hidden']['id'] = 'hidden';
    $handler->display->display_options['filters']['hidden']['table'] = 'menu_links';
    $handler->display->display_options['filters']['hidden']['field'] = 'hidden';
    $handler->display->display_options['filters']['hidden']['value']['value'] = '0';
    /* Filter criterion: Menu link: Link_title */
    $handler->display->display_options['filters']['link_title']['id'] = 'link_title';
    $handler->display->display_options['filters']['link_title']['table'] = 'menu_links';
    $handler->display->display_options['filters']['link_title']['field'] = 'link_title';
    $handler->display->display_options['filters']['link_title']['operator'] = 'longerthan';
    $handler->display->display_options['filters']['link_title']['value'] = '0';

    /* Display: Block */
    $handler = $view->new_display('block', 'Block', 'block_1');
    $handler->display->display_options['block_description'] = 'Better Jump Menu: ' . $menu_title;

    $views[$view->name] = $view;
  }

  return $views;
}
