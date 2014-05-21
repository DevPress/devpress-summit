<?php
/**
 * Implements styles set in the theme customizer
 *
 * @package Summit
 * @credit Based on code from "Make" by The Theme Foundary
 */

if ( ! function_exists( 'summit_build_css_rules' ) ) :
/**
 * Process user options to generate CSS needed to implement the choices.
 *
 * This function reads in the options from theme mods and determines whether a CSS rule is needed to implement an
 * option. CSS is only written for choices that are non-default in order to avoid adding unnecessary CSS. All options
 * are also filterable allowing for more precise control via a child theme or plugin.
 *
 * Note that all CSS for options is present in this function except for the CSS for fonts and the logo, which require
 * a lot more code to implement.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function summit_build_css_rules() {

	// Site title color
	$setting = 'site-title-text-color';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );
	if ( $mod !== summit_get_default( $setting ) ) {
		summit_css()->add( array(
			'selectors'    => array( '.site-title a' ),
			'declarations' => array(
				'color' => sanitize_hex_color( $mod )
			)
		) );
	}

	// Site tagline color
	$setting = 'site-tagline-text-color';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );
	if ( $mod !== summit_get_default( $setting ) ) {
		summit_css()->add( array(
			'selectors'    => array( '.site-description' ),
			'declarations' => array(
				'color' => sanitize_hex_color( $mod )
			)
		) );
	}

	// Header Background Color
	$setting = 'header-background-color';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );

	if ( $mod !== summit_get_default( $setting ) ) {

		summit_css()->add( array(
			'selectors'    => array( '#masthead' ),
			'declarations' => array(
				'background' => sanitize_hex_color( $mod )
			)
		) );
	}

	// Header Overlay Opacity
	$setting = 'header-overlay-opacity';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );

	if ( $mod !== summit_get_default( $setting ) ) {

		// Header Overlay Color
		$color = get_theme_mod( 'header-overlay-color', summit_get_default( 'header-overlay-color' ) );
		$color = join( ', ', summit_hex_to_rgb( $color ) );

		summit_css()->add( array(
			'selectors'    => array( '.header-image .opacity' ),
			'declarations' => array(
				'background' => 'rgba(' . $color . ',' . $mod . ')'
			)
		) );
	}

	// Header Text Shadow
	$setting = 'header-text-shadow';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );

	if ( $mod !== summit_get_default( $setting ) ) {

		// Header Overlay Color
		$color = get_theme_mod( 'header-text-shadow-color', summit_get_default( 'header-text-shadow-color' ) );
		$color = join( ', ', summit_hex_to_rgb( $color ) );

		summit_css()->add( array(
			'selectors'    => array( '.site-branding' ),
			'declarations' => array(
				'text-shadow' => '0 1px 1px rgba(' . $color . ',' . $mod . ')'
			)
		) );

	}

	// Highlight Color
	$setting = 'highlight-color';
	$mod = get_theme_mod( $setting, summit_get_default( $setting ) );

	if ( $mod !== summit_get_default( $setting ) ) {

		summit_css()->add( array(
			'selectors' => array( 'a', '.main-navigation .dropdown-toggle:hover', '.entry-title a:hover', '.entry-meta a:hover', '.entry-footer a:hover' ),
			'declarations' => array(
				'color' => sanitize_hex_color( $mod )
			)
		) );

		summit_css()->add( array(
			'selectors' => array( 'button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '#infinite-handle span' ),
			'declarations' => array(
				'background' => sanitize_hex_color( $mod )
			)
		) );

	}


}
endif;

add_action( 'summit_css', 'summit_build_css_rules' );

if ( ! function_exists( 'summit_display_customizations' ) ) :
/**
 * Generates the style tag and CSS needed for the theme options.
 *
 * By using the "summit_css" filter, different components can print CSS in the header. It is organized this way to
 * ensure that there is only one "style" tag and not a proliferation of them.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function summit_display_customizations() {

	do_action( 'summit_css' );

	// Echo the rules
	$css = summit_css()->build();

	if ( ! empty( $css ) ) {
		echo "\n<!-- Begin Currents Custom CSS -->\n<style type=\"text/css\" id=\"summit-custom-css\">\n";
		echo $css;
		echo "\n</style>\n<!-- End Currents Custom CSS -->\n";
	}
}
endif;

add_action( 'wp_head', 'summit_display_customizations', 11 );
