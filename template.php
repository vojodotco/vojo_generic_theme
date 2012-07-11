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
  
  // If we're in a group context, use group logo and header color provided by
  // fields in the group node.
  $group_node = og_get_group_context();
  if ($group_node) {
    $vars['title_group'] = $group_node->title;
  }
  if (!empty($group_node) && $group_node->field_group_logo[0]['filepath']) {
    $image_path = imagecache_create_url('group_logo_large', $group_node->field_group_logo[0]['filepath']);
    $vars['group_logo'] = '<img src="'. $image_path .'" />';
  }
  if (!empty($group_node)) {
    $vars['home_link'] = $vars['base_path'] . drupal_get_path_alias('node/'. $group_node->nid);
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

function vojo_generic_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  $search = array(' ','.');
  $css_id = strtolower(str_replace($search, '-', strip_tags($link)));

  // Add a CSS ID if the link contains a social website name.
  $social_sites = array('twitter', 'facebook', 'tumblr', 'google plus', 'google', 'rss', 'vimeo');
  foreach ($social_sites as $site) {    
    if (stristr(strtolower(strip_tags($link)), $site)) {
      $css_id = 'social-link-'. str_replace($search, '-', $site);
    }
  }
  return '<li id="' . $css_id . '" class="' . ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf')) .'">'. $link . $children ."</li>\n";
}
