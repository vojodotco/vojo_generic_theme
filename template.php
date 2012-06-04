<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to vojo_generic_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: vojo_generic_breadcrumb()
 *
 *   where vojo_generic is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/**
 * Implementation of HOOK_theme().
 */
function vojo_generic_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  $hooks['blog_node_form'] = array( 
        'arguments' => array('form' => NULL),
        'template' => 'templates/node-blog-edit'
  );
  $hooks['dropdown_links'] = array( 
    'arguments' => array('links' => NULL, 'attributes' => NULL),
  );
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function vojo_generic_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function vojo_generic_preprocess_page(&$vars, $hook) {
  // Get voip call in number, set at admin/voip/call/settings
  $vars['vojo_callin_number'] = format_phone_number(variable_get('voipcall_cid_number',''));
  
  // If we're in a group context, use group logo and header color provided by
  // fields in the group node.
  $group_node = og_get_group_context();
  if ($group_node) {
    $vars['title_group'] = $group_node->title;
  }
  if (!empty($group_node) && $group_node->field_group_logo[0]['filepath']) {
    $vars['home_link'] = $vars['base_path'] . 'node/'. $group_node->nid;
    $image_path = imagecache_create_url('group_logo_large', $group_node->field_group_logo[0]['filepath']);
    $vars['group_logo'] = '<img src="'. $image_path .'" />';
  }
  // If the colorpicker module is present, the vozmob_og feature will provide a
  // field to the group node to select the background color of the header.
  if (isset($group_node->field_group_header_bg_color)) {
    $vars['header_bg_color'] = $group_node->field_group_header_bg_color[0]['value'];
  }
  if (!$vars['show_blocks']) {
    $vars['sidebar'] = '';
  }
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function vojo_generic_preprocess_node(&$vars, $hook) {

  if (module_exists('uploadterm') && module_exists('taxonomy_image')) {
    // media terms
    $term_image_links = array();
    foreach ($vocabulary[variable_get('uploadterm_vocabulary', 0)] as $term) {
      $term_image_links[] = l(taxonomy_image_display($term['tid']), $term['path'], array('html' => TRUE)); // can add size here
    }
    $vars['mediaterms'] = theme('item_list', $term_image_links, NULL, 'ul', array('class' => 'links inline media'));
  }
  // Keep term links sepearate from other node links. No need to display other links twice.
  $vars['taxonomy'] = taxonomy_link('taxonomy terms', $vars['node']);
  unset($vars['taxonomy']['comment_add']);
  unset($vars['taxonomy']['sms_sendtophone']);
  $vars['terms'] = theme('links', $vars['taxonomy'], array('class' => 'inline links'));
}


/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function vojo_generic_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function vojo_generic_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

function vojo_generic_node_submitted($node) {
  return t('Posted by !username on @datetime', 
    array(
    '!username' => l($node->name, 'blogs/'. $node->name), 
    '@datetime' => format_date($node->created),
  ));
}

function vojo_generic_comment_submitted($comment) {
  return t('Comment by !username on @datetime', 
    array(
    '!username' => l($comment->name, 'blogs/'. $comment->name), 
    '@datetime' => format_date($comment->created),
  ));
}

/** 
 * Provides our own version of theme_links() with markup for a dropdown menu. 
 */
function vojo_generic_dropdown_links($links, $attributes = array('class' => 'links')) {
  global $language;
  $output = '';
  if (count($links) > 0) {
    $output = '<ul'. drupal_attributes($attributes) .'>';

    $num_links = count($links);
    $i = 1;
    foreach ($links as $menu_item) {
      $link = $menu_item['link'];
      // Do not show disabled menu items and submenu
      if ($link['hidden'] != 1) {
        $class = 'menu-' . $menu_item['link']['mlid'];
        // Add first, last and active classes to the list of links to help out themers.
        if ($i == 1) {
          $class .= ' first';
        }
        if ($menu_item['below']) {
          $class .= ' menu-parent';
        }
        if ($i == $num_links) {
          $class .= ' last';
        }

        $class .= ' menu-item-' . $i;

        if ($link['in_active_trail'] == TRUE) {
          $class .= ' active-trail';
        }
        // Add classes based on path name
        $path_alias = drupal_get_path_alias($link['href']); 
        $class_from_path = str_replace(array('/', ' '), '-', $path_alias);
        $class .= ' menu-' . $class_from_path;

        if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
            && (empty($link['language']) || $link['language']->language == $language->language)) {
          $class .= ' active';
        }
        $output .= '<li'. drupal_attributes(array('class' => $class)) .'>';

        if (isset($link['href']) && $link['href'] != 'nolink' && $link['href'] != 'vojo_user_name') {
          // Pass in $link as $options, they share the same keys.
          $output .= l($link['title'], $link['href'], $link);
        }
        else if (!empty($link['title'])) {
          // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
          if (empty($link['html'])) {
            $link['title'] = check_plain($link['title']);
          }
          $span_attributes = '';
          if (isset($link['localized_options']['attributes'])) {
            $span_attributes = drupal_attributes($link['localized_options']['attributes']);
          }
          $output .= '<span'. $span_attributes .'>'. $link['title'] .'</span>';
        }
        $i++;

        // Display expanded submenu items, so we can style it as a dropdown menu
        if (!empty($menu_item['below']) && $menu_item['link']['expanded'] != 0) {
          // reset counter 
          $i_sub = 0;
          $sublinks_count = count($menu_item['below']);

          $output .= '<ul class="submenu' . $submenu_class . '">';

          foreach ($menu_item['below'] as $child_item) {
            $child_link =  $child_item['link'];
            $class = 'menu-' . $child_item['link']['mlid'];

            $options = array();
            if ($child_item['link']['localized_options']['fragment']) { 
              $options = array(
                'fragment' => $child_item['link']['localized_options']['fragment'],
              );
            } 
            // Only show menu items not set to hidden in UI
            if ($child_link['hidden'] != 1) {
              $output .= '<li'. drupal_attributes(array ('class' => $class)) .'>';
              $output .= l($child_link['title'], $child_link['href'], $options);
              $output .= '</li>';
            }
            $i_sub++;
          }
          $output .= '</ul>';
          // End submenu
        }
      }
      $output .= '</li>';
    }

  $output .= '</ul>';
  }
  return $output;
}

