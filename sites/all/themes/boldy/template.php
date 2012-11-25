<?php

/**
 * Override of theme_breadcrumb().
 */
function boldy_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' › ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Override or insert variables into the maintenance page template.
 */
function boldy_preprocess_maintenance_page(&$vars) {
  // While markup for normal pages is split into page.tpl.php and html.tpl.php,
  // the markup for the maintenance page is all in the single
  // maintenance-page.tpl.php template. So, to have what's done in
  // garland_preprocess_html() also happen on the maintenance page, it has to be
  // called here.
  boldy_preprocess_html($vars);
}

/**
 * Override or insert variables into the html template.
 */
function boldy_preprocess_html(&$vars) {
  // Add conditional CSS for IE6.
  drupal_add_css(path_to_theme() . '/fix-ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lt IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the html template.
 */
function boldy_process_html(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function boldy_preprocess_page(&$vars) {
  // Move secondary tabs into a separate variable.
  $vars['tabs2'] = array(
    '#theme' => 'menu_local_tasks',
    '#secondary' => $vars['tabs']['#secondary'],
  );
  unset($vars['tabs']['#secondary']);

  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'main-menu'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
        ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_nav'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'secondary-menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
        ));
  }
  else {
    $vars['secondary_nav'] = FALSE;
  }

  // Prepare header
  $site_fields = array();
  if (!empty($vars['site_name'])) {
    $site_fields[] = $vars['site_name'];
  }
  if (!empty($vars['site_slogan'])) {
    $site_fields[] = $vars['site_slogan'];
  }
  $vars['site_title'] = implode(' ', $site_fields);
  if (!empty($site_fields)) {
    $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
  }
  $vars['site_html'] = implode(' ', $site_fields);

  // Set a variable for the site name title and logo alt attributes text.
  $slogan_text = $vars['site_slogan'];
  $site_name_text = $vars['site_name'];
  $vars['site_name_and_slogan'] = $site_name_text . ' ' . $slogan_text;
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function boldy_preprocess_node(&$variables, $hook) {
  // Add $unpublished variable.
  $variables['unpublished'] = (!$variables['status']) ? TRUE : FALSE;

  // Add a class for the view mode.
  if (!$variables['teaser']) {
    $variables['classes_array'][] = 'view-mode-' . $variables['view_mode'];
  }

  // Add a class to show node is authored by current user.
  if ($variables['uid'] && $variables['uid'] == $GLOBALS['user']->uid) {
    $variables['classes_array'][] = 'node-by-viewer';
  }

  $variables['title_attributes_array']['class'][] = 'node-title';

  // Returns the themed submitted-by string for the node.
  $variables['submitted'] = t('!datetime — !username', array(
    '!username' => $variables['name'],
    '!datetime' => $variables['date'],
  ));
}

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
function boldy_preprocess_comment(&$variables, $hook) {
  // If comment subjects are disabled, don't display them.
  if (variable_get('comment_subject_field_' . $variables['node']->type, 1) == 0) {
    $variables['title'] = '';
  }

  // Anonymous class is broken in core. See #1110650
  if ($variables['comment']->uid == 0) {
    $variables['classes_array'][] = 'comment-by-anonymous';
  }
  // Zebra striping.
  if ($variables['id'] == 1) {
    $variables['classes_array'][] = 'first';
  }
  if ($variables['id'] == $variables['node']->comment_count) {
    $variables['classes_array'][] = 'last';
  }
  $variables['classes_array'][] = $variables['zebra'];

  $variables['title_attributes_array']['class'][] = 'comment-title';

  // Returns the themed submitted-by string for the comment.
  $variables['submitted'] = t('!datetime — !username', array(
    '!username' => $variables['name'],
    '!datetime' => $variables['date'],
  ));
}

/**
 * Preprocess variables for region.tpl.php
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
function boldy_preprocess_region(&$variables, $hook) {
  // Sidebar regions get some extra classes and a common template suggestion.
  if (strpos($variables['region'], 'sidebar_') === 0) {
    $variables['classes_array'][] = 'column';
    $variables['classes_array'][] = 'sidebar';
    $variables['theme_hook_suggestions'][] = 'region__sidebar';
    // Allow a region-specific template to override Boldy's region--sidebar.
    $variables['theme_hook_suggestions'][] = 'region__' . $variables['region'];
  }
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function boldy_preprocess_block(&$variables, $hook) {
  // Classes describing the position of the block within the region.
  if ($variables['block_id'] == 1) {
    $variables['classes_array'][] = 'first';
  }
  // The last_in_region property is set in boldy_page_alter().
  if (isset($variables['block']->last_in_region)) {
    $variables['classes_array'][] = 'last';
  }
  $variables['classes_array'][] = $variables['block_zebra'];

  $variables['title_attributes_array']['class'][] = 'block-title';
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function boldy_process_block(&$variables, $hook) {
  // Drupal 7 should use a $title variable instead of $block->subject.
  $variables['title'] = $variables['block']->subject;
}

/**
 * Implements hook_page_alter().
 *
 * Look for the last block in the region. This is impossible to determine from
 * within a preprocess_block function.
 *
 * @param $page
 *   Nested array of renderable elements that make up the page.
 */
function boldy_page_alter(&$page) {
  // Look in each visible region for blocks.
  foreach (system_region_list($GLOBALS['theme'], REGIONS_VISIBLE) as $region => $name) {
    if (!empty($page[$region])) {
      // Find the last block in the region.
      $blocks = array_reverse(element_children($page[$region]));
      while ($blocks && !isset($page[$region][$blocks[0]]['#block'])) {
        array_shift($blocks);
      }
      if ($blocks) {
        $page[$region][$blocks[0]]['#block']->last_in_region = TRUE;
      }
    }
  }
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function boldy_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="' . base_path() . path_to_theme() . '/css/fix-ie.css" />';
  if ($language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "' . base_path() . path_to_theme() . '/css/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}

/**
 * Implementation of hook form alter
 * Override the search box to add the graphic instead of the button.
 */
function boldy_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    // @TODO consider adding this and replace js
//    $form['search_block_form']['#value'] = 'type your search...';
//    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'type your search...';}";
//    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'type your search...') {this.value = '';}";
    $form['actions']['submit']['#value'] = '';
  }
}

/**
 * Implementation of hook_theme.
 */
function boldy_theme() {
  return array(
    'contact_mail_page_1' => array(
      'arguments' => array('form' => NULL),
    ),
  );
}

/**
 * Hide comment links on teaser views
 */
function boldy_links($links, $attributes = array('class' => 'links')) {
  if (count($links) > 0) {
    if (isset($links["node_read_more"])) {
      if (isset($links["comment_add"])) {
        unset($links["comment_add"]);
      }
      if (isset($links["comment_comments"])) {
        unset($links["comment_comments"]);
      }
    }
  }
  return theme_links($links, $attributes);
}

/**
 * Modify the form with the ID "contact_mail_page1"
 */
function boldy_contact_mail_page_1($form) {
  // Remove the "Additional information" from the form
  // dsm($form);
  unset($form['contact_information']);
  $name = drupal_render($form['name']);
  $mail = drupal_render($form['mail']);
  $subject = drupal_render($form['subject']);
  $message = drupal_render($form['message']);
  $copy = drupal_render($form['copy']);
  $submit = drupal_render($form['submit']);
  $everything_else = drupal_render($form);
  return "<div class='leftSide'>" . $name . $mail . $subject . $submit . "</div><div class='rightSide'>" . $message . $copy . "</div>" . $everything_else;
  // return drupal_render($form);
}

/**
 * Converts a string to a suitable html ID attribute.
 *
 * http://www.w3.org/TR/html4/struct/global.html#h-7.5.2 specifies what makes a
 * valid ID attribute in HTML. This function:
 *
 * - Ensure an ID starts with an alpha character by optionally adding an 'id'.
 * - Replaces any character except alphanumeric characters with dashes.
 * - Converts entire string to lowercase.
 *
 * @param $string
 *   The string
 * @return
 *   The converted string
 */
function boldy_id_safe($string) {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  return strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', $string));
}

/*
 * CONTACT FORM
 */

function boldy_hexstr($hexstr) {
  $hexstr = str_replace(' ', '', $hexstr);
  $hexstr = str_replace('\x', '', $hexstr);
  $retstr = pack('H*', $hexstr);
  return $retstr;
}

function boldy_strhex($string) {
  $hexstr = unpack('H*', $string);
  return array_shift($hexstr);
}
