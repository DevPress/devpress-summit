<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Currents
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function currents_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'currents_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function currents_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'currents_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function currents_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'currents' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'currents_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function currents_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'currents_setup_author' );

/**
 * Replaces definition list elements with their appropriate HTML5 counterparts.
 *
 * @param array $atts The output array of shortcode attributes.
 * @return array HTML5-ified gallery attributes.
 */
function currents_gallery_atts( $atts ) {
    $atts['itemtag']    = 'figure';
    $atts['icontag']    = 'div';
    $atts['captiontag'] = 'figcaption';

    return $atts;
}
add_filter( 'shortcode_atts_gallery', 'currents_gallery_atts' );

/**
 * Removes the default styles for galleries
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Filters avatar size in comments
 */
function currents_custom_avatar_size( $avatar ) {
    global $comment;
    $avatar = get_avatar( $comment, $size = '60' );
    return $avatar;
}
add_filter( 'inline_get_avatar', 'currents_custom_avatar_size' );

/**
 * Counts number of widgets in a sidebar
 */
function currents_count_widgets( $sidebar_id ) {

	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;

	$sidebars_widgets_count = $_wp_sidebars_widgets;

	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		return $widget_count;
	endif;

}

/**
 * Returns a custom class for the
 */
function footer_widgetarea_class() {

	$mod = get_theme_mod( 'footer-widget-columns', 'default' );

	if ( 'default' != $mod ) {
		return $mod;
	}

	$count = currents_count_widgets( 'footer' );

	if ( 0 == $count ) {
		return 'no-widgets';
	}

	if ( 1 == $count ) {
		return 'column-1';
	}

	if ( 2 == $count ) {
		return 'column-2';
	}

	if ( $count >= 3 ) {
		return 'column-masonry';
	}

}

/**
 * Get default footer text
 */
function currents_get_default_footer_text() {
	$text = sprintf(
		__( 'Powered by %s', 'currents' ),
		'<a href="' . esc_url( __( 'http://wordpress.org/', 'currents' ) ) . '">WordPress</a>'
	);
	$text .= '<span class="sep"> | </span>';
	$text .= sprintf(
		__( '%1$s Theme by %2$s.', 'currents' ),
			'Currents',
			'<a href="http://devpress.com/" rel="designer">DevPress</a>'
	);
	return $text;
}