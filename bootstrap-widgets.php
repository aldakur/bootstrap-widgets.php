<?php
/**
 * Wordpress Widgets Bootstraped
 *
 * @package   Wordpress Widgets Bootstraped
 * @author    Bryan Willis <bryanwillis1@gmail.com>
 * @co-author Philip Newcomer
 * @license   GPL-2.0+
 * @link      http://wordpress.stackexchange.com/a/211634/43806
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Widgets Bootstraped 
 * Plugin URI:        https://github.com/Wordpress-Development/bootstrap-widgets.php
 * Description:       Add Bootstrap to wordpress widgets. Widget Output Filters plugin included.
 * Version:           1.0.0
 * Author:            Bryan Willis
 * Author URI:        http://profiles.wordpress.org/codecandid
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


/**
 * Widget Output Filters (Philip Newcomer)
 * https://wordpress.org/plugins/widget-output-filters/
 */
if ( ! function_exists('widget_output_filters_dynamic_sidebar_params') ) {
function widget_output_filters_dynamic_sidebar_params( $sidebar_params ) {
	if ( is_admin() ) {
		return $sidebar_params;
	}
	global $wp_registered_widgets;
	$widget_id = $sidebar_params[0]['widget_id'];
	$wp_registered_widgets[ $widget_id ]['original_callback'] = $wp_registered_widgets[ $widget_id ]['callback'];
	$wp_registered_widgets[ $widget_id ]['callback'] = 'widget_output_filters_display_widget';
	return $sidebar_params;
}
  add_filter( 'dynamic_sidebar_params', 'widget_output_filters_dynamic_sidebar_params', 9 );
}
if ( ! function_exists('widget_output_filters_display_widget') ) {
function widget_output_filters_display_widget() {
	global $wp_registered_widgets;
	$original_callback_params = func_get_args();
	$widget_id = $original_callback_params[0]['widget_id'];
	$original_callback = $wp_registered_widgets[ $widget_id ]['original_callback'];
	$wp_registered_widgets[ $widget_id ]['callback'] = $original_callback;
	$widget_id_base = $wp_registered_widgets[ $widget_id ]['callback'][0]->id_base;
	if ( is_callable( $original_callback ) ) {
		ob_start();
		call_user_func_array( $original_callback, $original_callback_params );
		$widget_output = ob_get_clean();
		echo apply_filters( 'widget_output', $widget_output, $widget_id_base, $widget_id );
	}
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
