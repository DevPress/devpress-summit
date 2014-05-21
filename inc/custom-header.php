<?php
/**
 * Implement the custom header
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Summit
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @since 1.0.0
 */
function summit_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'summit_custom_header_args', array(
		'random-default'         => true,
		'default-text-color'     => 'ffffff',
		'width'                  => 890,
		'height'                 => 410,
		'flex-height'            => false
	) ) );
}
add_action( 'after_setup_theme', 'summit_custom_header_setup' );

/**
 * Remove the header menu item from the appearance menu.
 * This feature will be handled entirely through the customizer.
 *
 * @since 1.0.0
 */
function summit_custom_header_screen() {
	remove_submenu_page( 'themes.php', 'custom-header' );
}
add_action( 'admin_menu', 'summit_custom_header_screen', 20 );

/**
 * Remove the header menu item from the admin bar.
 *
 * @since 1.0.0
 */
function summit_admin_bar_header_menu( $wp_admin_bar ) {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'header' );
}
add_action( 'wp_before_admin_bar_render', 'summit_admin_bar_header_menu' );

/**
 * Remove the header menu item from the admin bar.
 *
 * @since 1.0.0
 */
register_default_headers( array(
	'cloudscape' => array(
		'url'           => '%s/images/cloudscape.jpg',
		'thumbnail_url' => '%s/images/cloudscape.jpg',
		'description'   => __( 'Cloudscape', 'summit' )
	),
	'dune' => array(
		'url'           => '%s/images/dune.jpg',
		'thumbnail_url' => '%s/images/dune.jpg',
		'description'   => __( 'Dune', 'summit' )
	),
	'golden' => array(
		'url'           => '%s/images/golden.jpg',
		'thumbnail_url' => '%s/images/golden.jpg',
		'description'   => __( 'Golden', 'summit' )
	),
	'summit' => array(
		'url'           => '%s/images/summit.jpg',
		'thumbnail_url' => '%s/images/summit.jpg',
		'description'   => __( 'Summit', 'summit' )
	)
) );