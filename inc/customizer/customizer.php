<?php
/**
 * Currents Theme Customizer
 *
 * @package Currents
 */

/**
 * Define the path for the customizer files.
 */
$path = get_template_directory() . '/inc/customizer';

/**
 * Custom file to define the customizer options.
 */
require_once $path . '/options.php';

/**
 * Custom file to define inline styles.
 */
require_once $path . '/styles.php';

/**
 * Helper functions to output the customizer controls.
 */
require_once $path . '/extensions/interface.php';

/**
 * Helper functions for customizer sanitization.
 */
require_once $path . '/extensions/interface.php';

/**
 * Helper functions to build the inline CSS.
 */
require_once $path . '/extensions/style-builder.php';

/**
 * Utility functions for the customizer.
 */
require_once $path . '/extensions/utilities.php';

/**
 * Utility functions for the customizer.
 */
require_once $path . '/custom-controls/textarea.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function currents_customize_preview_js() {

	$path = get_template_directory_uri() . '/inc/customizer';

	wp_enqueue_script( 'currents_customizer', $path . '/js/customizer.js', array( 'customize-preview' ), CURRENTS_VERSION, true );

}
add_action( 'customize_preview_init', 'currents_customize_preview_js' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function currents_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'currents_customize_register' );