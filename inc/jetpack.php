<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Currents
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function currents_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
		'footer_widgets' => 'footer',
	) );
}
add_action( 'after_setup_theme', 'currents_jetpack_setup' );