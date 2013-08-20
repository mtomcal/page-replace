<?php
/*
  Plugin Name: Page Replace
  Plugin URI: http://twoeyetech.com/
  Description: Replace text in pages
  Version: 0.1.0 
  Author: Michael A Tomcal 
  Author URI: http://twoeyetech.com
 */

require 'vendor/autoload.php';

function pagereplace_render_admin() {
  include 'views/settings.php';
}

function pagereplace_admin_page() {
	global $pagereplace_settings;
	$pagereplace_settings = add_submenu_page('tools.php', __('Page Replace', 'pagereplace'), __('Page Replace', 'pagereplace'), 'edit_others_posts', 'pagereplace', 'pagereplace_render_admin');
}
add_action('admin_menu', 'pagereplace_admin_page');


function pagereplace_load_scripts($hook) {
	global $pagereplace_settings;

  $app = array(
    "app" => 'app/scripts/app.js',
    "mainctrl" => 'app/scripts/controllers/main.js',
    "process" => 'app/scripts/services/process.js',
    "replace" => 'app/scripts/services/replace.js',

  );
	
	if( $hook != $pagereplace_settings ) {
		return;
  }

  wp_enqueue_style( 'pagereplace-style', plugin_dir_url(__FILE__) . 'app/styles/pagereplace.css');

	wp_enqueue_script('angular', plugin_dir_url(__FILE__) . 'app/components/angular/angular.js', array('jquery'));
	wp_enqueue_script('angular-resource', plugin_dir_url(__FILE__) . 'app/components/angular-resource/angular-resource.js', array('jquery', 'angular'));

  foreach($app as $handle => $path) {
    wp_enqueue_script('pagereplace-' . $handle, plugin_dir_url(__FILE__) . $path, array('jquery', 'angular', 'angular-resource'));
  }

	wp_localize_script('pagereplace-app', 'pagereplace_vars', array(
			'nonce' => wp_create_nonce('pagereplace_nonce'),
      'url' => plugin_dir_url(__FILE__),
		)
	);
		
}
add_action('admin_enqueue_scripts', 'pagereplace_load_scripts');

function pagereplace_process_ajax() {
  
  $req = new PageReplace\Request();

  if (!$req->checkSecurity()) {
    die('0');
  }

  if ($req->getRequest("q")) {
    $q = $req->getRequest('q');
    $type = $req->getRequest('type');
    $query = new WP_Query("post_type={$type}&s={$q}");
    $results = $query->get_posts();
    $response = array("results" => $results);
    $response = json_encode($response);
  }

  echo $response;
	
	die();
}
add_action('wp_ajax_pagereplace-process', 'pagereplace_process_ajax');

function pagereplace_find_replace($content, $find, $replace) {
  return preg_replace("/$find/", $replace, $content);

}

function pagereplace_replace_ajax() {
  
  $req = new PageReplace\Request();

  if (!$req->checkSecurity()) {
    die('0');
  }

  if ($req->getRequest("id")) {
    $id = $req->getRequest("id");
    $find = $req->getRequest('find');
    $replace = $req->getRequest('replace');
    $type = $req->getRequest('type');

    if ($type == 'post') {
      $q = "p={$id}";
    } else {
      $q = "page_id={$id}";
    }

    $query = new WP_Query($q);

    if (!$query->have_posts()) {
      $response = json_encode(array("result" => false, "reason" => 'No Posts Found'));
      echo $response;
      die();
      return;
    }

    $results = $query->get_posts();
    $post = $results[0];

    $updatedPost = array(
      "ID" => $post->ID,
      "post_content" => pagereplace_find_replace($post->post_content, $find, $replace),
    );

    if (wp_update_post($updatedPost) == 0) {
      $response = json_encode(array("result" => false, "reason" => 'Failed to Update Post'));
    } else {
      $response = json_encode(array("result" => true, "title" => $post->post_title));
    }
  }

  echo $response;
	
	die();
}
add_action('wp_ajax_pagereplace-replace', 'pagereplace_replace_ajax');

?>
