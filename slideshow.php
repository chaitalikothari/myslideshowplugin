<?php 
/*
Plugin Name: My Slideshow Plugin
Plugin URI: http://www.pluginuri.com
Description: Plugin for displaying slideshow on posts and pages.
Author: Chaitali Kothari
Version: 1.0
Author URI: http://www.authorurl.com
*/

/*Add slideshow Menu under settings*/

function slideshow_admin_actions() {
    add_options_page("Slideshow", "Slideshow", 1, "Slideshow", "slideshow_admin");
	
}
add_action('admin_menu', 'slideshow_admin_actions');
add_filter('widget_text','do_shortcode'); //To display shortcode content in text widget

function slideshow_admin() {
    include('slideshow_admin_content.php');
}


add_shortcode('myslideshow','myslideshow_function');
function myslideshow_function() {
	include('slideshow_front_content.php');
}
?>