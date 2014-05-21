<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Summit
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function summit_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
		'footer_widgets' => 'footer',
	) );
}
add_action( 'after_setup_theme', 'summit_jetpack_setup' );
