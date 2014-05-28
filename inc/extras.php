<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package Summit
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array $classes
 */
function summit_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'summit_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function summit_wp_title( $title, $sep ) {
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
		$title .= " $sep " . sprintf( __( 'Page %s', 'summit' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'summit_wp_title', 10, 2 );

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
function summit_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'summit_setup_author' );

/**
 * Replaces definition list elements with their appropriate HTML5 counterparts.
 *
 * @param array $atts The output array of shortcode attributes.
 * @return array HTML5-ified gallery attributes.
 */
function summit_gallery_atts( $atts ) {
    $atts['itemtag']    = 'figure';
    $atts['icontag']    = 'div';
    $atts['captiontag'] = 'figcaption';

    return $atts;
}
add_filter( 'shortcode_atts_gallery', 'summit_gallery_atts' );

/**
 * Removes the default styles for galleries
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Counts number of widgets in a sidebar
 *
 * @param string $sidebar_id
 * @return int $widget_count
 */
function summit_count_widgets( $sidebar_id ) {

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
 * Returns a custom class for the widget area depending
 * on how many widgets are set.
 *
 * @return string
 */
function footer_widgetarea_class() {

	$mod = get_theme_mod( 'footer-widget-columns', 'default' );

	if ( 'default' != $mod ) {
		return $mod;
	}

	$count = summit_count_widgets( 'footer' );

	if ( 0 == $count ) {
		return 'no-widgets';
	}

	if ( 1 == $count ) {
		return 'column-1';
	}

	if ( 2 == $count ) {
		return 'column-2';
	}

}

/**
 * Get default footer text
 *
 * @return string $text
 */
function summit_get_default_footer_text() {
	$text = sprintf(
		__( 'Powered by %s', 'summit' ),
		'<a href="' . esc_url( __( 'http://wordpress.org/', 'summit' ) ) . '">WordPress</a>'
	);
	$text .= '<span class="sep"> | </span>';
	$text .= sprintf(
		__( '%1$s Theme by %2$s.', 'summit' ),
			'Summit',
			'<a href="http://devpress.com/" rel="designer">DevPress</a>'
	);
	return $text;
}

/**
 * Add HTML5 placeholders for each default comment field
 *
 * @param array $fields
 * @return array $fields
 */
function summit_comment_fields( $fields ) {

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] =
        '<p class="comment-form-author">
        	<label for="author">' . __( 'Name', 'summit' ) . '</label>
            <input required minlength="3" maxlength="30" placeholder="' . __( 'Name *', 'summit' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' />
        </p>';

    $fields['email'] =
        '<p class="comment-form-email">
        	<label for="email">' . __( 'Email', 'summit' ) . '</label>
            <input required placeholder="' . __( 'Email *', 'summit' ) . '" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" size="30"' . $aria_req . ' />
        </p>';

    $fields['url'] =
        '<p class="comment-form-url">
        	<label for="url">' . __( 'Website', 'summit' ) . '</label>
            <input placeholder="' . __( 'Website', 'summit' ) . '" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '" size="30" />
        </p>';

    return $fields;
}
add_filter( 'comment_form_default_fields', 'summit_comment_fields' );

/**
 * Add HTML5 placeholder to the comment textarea.
 *
 * @param string $comment_field
 * @return string $comment_field
 */
 function summit_commtent_textarea( $comment_field ) {

    $comment_field =
        '<p class="comment-form-comment">
            <textarea required placeholder="' . __( 'Comment *', 'summit' ) . '" id="comment" name="comment" cols="45" rows="6" aria-required="true"></textarea>
        </p>';

    return $comment_field;
}
add_filter( 'comment_form_field_comment', 'summit_commtent_textarea' );