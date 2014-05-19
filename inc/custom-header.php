<?php
/**
 * Implement the custom header
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Currents
 */

/**
 * Setup the WordPress core custom header feature.
 */
function currents_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'currents_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/mountain.jpg',
		'default-text-color'     => 'ffffff',
		'width'                  => 880,
		'height'                 => 410,
		'flex-height'            => true
	) ) );
}
add_action( 'after_setup_theme', 'currents_custom_header_setup' );

/**
 * Remove the admin screen for the header.
 * This feature will be handled entirely through the customizer.
 */
function currents_custom_header_screen() {
	remove_submenu_page( 'themes.php', 'custom-header' );
}
add_action( 'admin_menu', 'currents_custom_header_screen', 20 );