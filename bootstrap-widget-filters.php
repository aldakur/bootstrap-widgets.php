<?php

/**
 * Wordpress Widget Filters
 *
 * @package    Wordpress Widget Filters
 * @author    Bryan Willis
 * @license   GPL-2.0+
 * @link      http://wordpress.stackexchange.com/a/211634/43806
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Widget Filters
 * Plugin URI:        https://github.com/Wordpress-Development/bootstrap-widgets.php
 * Description:       Add Bootstrap to wordpress widgets. Widget Output Filters plugin included.
 * Version:           1.0.0
 * Author:            Bryan Willis
 * Author URI:        http://profiles.wordpress.org/codecandid
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


defined( 'WPINC' ) or die;

register_activation_hook( __FILE__, 'activate_wop_bootstrap_plugin_script_check' );
function activate_wop_bootstrap_plugin_script_check() {
	if ( ! function_exists('widget_output_filters_dynamic_sidebar_params') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sBootstrap-Widget-Filters unless you have installed the %3$sWidget Output Filters Plugin%4$s. Go back to the %5$sPlugins Page%4$s.' ), '<em>', '</em>', '<a href="https://wordpress.org/plugins/widget-output-filters/" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}
}



/** 
 * Bootstrap support for core wordpress widgets
 */
function brw_bootstrap_widget_output_filters( $widget_output, $widget_type, $widget_id ) {
	if ( 'categories' == $widget_type ) {
      		$widget_output = str_replace('<ul>', '<ul class="list-group">', $widget_output);
      		$widget_output = str_replace('<li class="cat-item cat-item-', '<li class="list-group-item cat-item cat-item-', $widget_output);
      		$widget_output = str_replace('(', '<span class="badge cat-item-count"> ', $widget_output);
      		$widget_output = str_replace(')', ' </span>', $widget_output);
    	}
    	elseif ( 'calendar' == $widget_type ) {
		$widget_output = str_replace('calendar_wrap', 'calendar_wrap table-responsive', $widget_output);
        	$widget_output = str_replace('<table id="wp-calendar', '<table class="table table-condensed" id="wp-calendar', $widget_output);
    	}
    	elseif ( 'tag_cloud' == $widget_type )  {
		$regex = "/(<a[^>]+?)( style='font-size:.+pt;'>)([^<]+?)(<\/a>)/";
		$replace_with = "$1><span class='label label-primary'>$3</span>$4";
		$widget_output = preg_replace( $regex , $replace_with , $widget_output );
	}
  	elseif ( 'archives' == $widget_type ) {
      		$widget_output = str_replace('<ul>', '<ul class="list-group">', $widget_output);
      		$widget_output = str_replace('<li>', '<li class="list-group-item archive-list-group-item">', $widget_output);
		$widget_output = str_replace('(', '<span class="badge cat-item-count"> ', $widget_output);
   		$widget_output = str_replace(')', ' </span>', $widget_output);
   	}
  	elseif ( 'meta' == $widget_type ) {
        	$widget_output = str_replace('<ul>', '<ul class="list-group">', $widget_output);
        	$widget_output = str_replace('<li>', '<li class="list-group-item meta-list-group-item">', $widget_output);
   	}
  	elseif ( 'recent-posts' == $widget_type ) {
        	$widget_output = str_replace('<ul>', '<ul class="list-group">', $widget_output);
        	$widget_output = str_replace('<li>', '<li class="list-group-item recent-posts-list-group-item">', $widget_output);
   	}
  	elseif ( 'recent-comments' == $widget_type ) {
        	$widget_output = str_replace('<ul id="recentcomments">', '<ul id="recentcomments" class="list-group">', $widget_output);
        	$widget_output = str_replace('<li class="recentcomments">', '<li class="recentcomments list-group-item recent-comments-list-group-item">', $widget_output);
   	}
  	elseif ( 'pages' == $widget_type ) {
        	$widget_output = str_replace('<ul>', '<ul class="nav nav-stacked nav-pills">', $widget_output);
   	}
  	elseif ( 'nav_menu' == $widget_type ) {
        	$widget_output = str_replace(' class="menu"', 'class="menu nav nav-stacked nav-pills"', $widget_output);
   	}
      return $widget_output;
}
add_filter( 'widget_output', 'brw_bootstrap_widget_output_filters', 10, 3 );
