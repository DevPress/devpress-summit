<?php
/**
 * Currents Theme Customizer
 *
 * @package Currents
 */

/**
 * Currents Options
 *
 * @since  1.0.0
 *
 * @return array $options
 */

function currents_options() {

	// Stores all the controls that will be added
	$options = array();

	// Stores all the sections to be added
	$sections = array();

	// Logo section
	$section = 'logo';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Logo Image', 'currents' ),
		'priority' => '20'
	);

	$options['logo'] = array(
		'id' => 'logo',
		'label'   => __( 'Logo', 'currents' ),
		'section' => $section,
		'type'    => 'upload',
		'default' => '',
	);

	// Title/Tagline Section
	$section = 'title_tagline';

	$options['site-title-text-color'] = array(
		'id' => 'site-title-text-color',
		'label'   => __( 'Site Title Text Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#ffffff',
	);

	$options['site-tagline-text-color'] = array(
		'id' => 'site-tagline-text-color',
		'label'   => __( 'Site Tagline Text Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#ffffff',
	);

	$options['display-site-tagline'] = array(
		'id' => 'display-site-tagline',
		'label'   => __( 'Display Site Tagline', 'currents' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => '1',
	);

	// Header section
	$section = 'header_settings';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Header Settings', 'currents' ),
		'priority' => '70'
	);

	$options['header-background-color'] = array(
		'id' => 'header-background-color',
		'label'   => __( 'Header Background Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#3fc0c3',
	);

	$options['header-overlay-color'] = array(
		'id' => 'header-overlay-color',
		'label'   => __( 'Header Overlay Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#000000',
	);

	$choices = array(
		'0' => 'None',
		'0.05' => '5%',
		'0.10' => '10%',
		'0.15' => '15%',
		'0.20' => '20%',
		'0.25' => '25%',
		'0.30' => '30%',
		'0.35' => '35%',
		'0.40' => '40%',
		'0.45' => '45%',
		'0.50' => '50%',
	);

	$options['header-overlay-opacity'] = array(
		'id' => 'header-overlay-opacity',
		'label'   => __( 'Header Overlay Opacity', 'currents' ),
		'section' => $section,
		'type'    => 'select',
		'choices' => $choices,
		'default' => '0'
	);

	$choices = array(
		'0' => 'None',
		'0.1' => '10%',
		'0.2' => '20%',
		'0.3' => '30%',
		'0.4' => '40%',
		'0.5' => '50%',
		'0.6' => '60%',
		'0.7' => '70%',
		'0.8' => '80%',
		'0.9' => '90%',
	);

	$options['header-text-shadow'] = array(
		'id' => 'header-text-shadow',
		'label'   => __( 'Header Text Shadow', 'currents' ),
		'section' => $section,
		'type'    => 'select',
		'choices' => $choices,
		'default' => '0.4',
	);

	$options['header-text-shadow-color'] = array(
		'id' => 'header-text-shadow-color',
		'label'   => __( 'Header Text Shadow Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#000000',
	);

	// Colors
	$section = 'colors';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Colors', 'currents' ),
		'priority' => '80'
	);

	$options['highlight-color'] = array(
		'id' => 'highlight-color',
		'label'   => __( 'Highlight Color', 'currents' ),
		'section' => $section,
		'type'    => 'color',
		'default' => '#3fc0c3',
	);

	// Typography
	$section = 'typography';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Typography', 'currents' ),
		'priority' => '80'
	);

	// Post Settings
	$section = 'general';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'General', 'currents' ),
		'priority' => '80'
	);

	$options['display-post-dates'] = array(
		'id' => 'display-post-dates',
		'label'   => __( 'Display Post Dates', 'currents' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => '1',
	);

	$options['display-featured-images'] = array(
		'id' => 'display-featured-images',
		'label'   => __( 'Display Featured Images', 'currents' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => '0',
	);

	$options['page-comments'] = array(
		'id' => 'page-comments',
		'label'   => __( 'Display Page Comments', 'currents' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => '0',
	);

	$options['overlay-navigation'] = array(
		'id' => 'overlay-navigation',
		'label'   => __( 'Display Overlay Navigation', 'currents' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => '1',
	);

	// Footer Settings
	$section = 'footer';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Footer', 'currents' ),
		'priority' => '100'
	);

	$choices = array(
		'default' => 'Default',
		'column-1' => '1 Column',
		'column-2' => '2 Columns',
		'column-masonry' => 'Masonry Column'
	);

	$options['footer-widget-columns'] = array(
		'id' => 'footer-widget-columns',
		'label'   => __( 'Footer Widget Columns', 'currents' ),
		'section' => $section,
		'type'    => 'select',
		'choices' => $choices,
		'default' => 'default',
	);

	$options['footer-text'] = array(
		'id' => 'footer-text',
		'label'   => __( 'Footer Text', 'currents' ),
		'section' => $section,
		'type'    => 'textarea',
		'default' => currents_get_default_footer_text(),
	);

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	return $options;

}

/**
 * Alters some of the defaults for the theme customizer
 *
 * @since  1.0.0.
 *
 * @param  object $wp_customize The global customizer object.
 * @return void
 */
function currents_customizer_defaults( $wp_customize ) {

	// Remove default colors section
	$wp_customize->remove_section( 'colors' );

	// Remove header text control
	$wp_customize->remove_control( 'display_header_text' );

	// Remove header text color control
	$wp_customize->remove_control( 'header_textcolor' );

	// Change label for background_image section
	$wp_customize->remove_section( 'background_image' );

	$wp_customize->add_section( 'background_image', array(
		'title'          => __( 'Background' ),
		'priority'       => 80,
	) );

	// Add background color to background_image section
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
		'label'   => __( 'Background Color' ),
		'section' => 'background_image',
	) ) );

}
add_action( 'customize_register', 'currents_customizer_defaults' );