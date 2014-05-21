<?php
/**
 * Currents Theme Customizer
 *
 * @package Summit
 */

if ( ! function_exists( 'summit_customizer' ) ) :
/**
 * Configure settings and controls for the theme customizer
 *
 * @since  1.0.0.
 *
 * @param  object $wp_customize The global customizer object.
 * @return void
 */
function summit_customizer( $wp_customize ) {

	$options = summit_options();
	$sections = $options['sections'];

	if ( isset( $sections ) ) {
		foreach( $sections as $section ) {
			$wp_customize->add_section( $section['id'], array(
				'title' => $section['title'],
				'priority' => $section['priority'],
			) );
		}
	}

	if ( $options ) {

		foreach( $options as $option ) {

			if ( isset( $option['type'] ) ) {

				if ( isset( $option['default'] ) ) {
					$default = array( 'default' => $option['default'] );
				}

			    $wp_customize->add_setting( $option['id'], $default );

				switch ( $option['type'] ) {

					case 'select':

						if ( !isset( $option['sanitize_callback'] ) ) {
							$option['sanitize_callback'] = 'summit_sanitize_choices';
						}

						$wp_customize->add_control( $option['id'], array(
							'type'    => $option['type'],
					        'label'   => $option['label'],
					        'section' => $option['section'],
					        'choices' => $option['choices'],
					        'sanitize_callback' => $option['sanitize_callback']
					    ) );

					    break;

					case 'checkbox':

						if ( !isset( $option['sanitize_callback'] ) ) {
							$option['sanitize_callback'] = 'summit_sanitize_checkbox';
						}

						$wp_customize->add_control( $option['id'], array(
							'type'    => $option['type'],
					        'label'   => $option['label'],
					        'section' => $option['section'],
					        'sanitize_callback' => $option['sanitize_callback']
					    ) );

					    break;

				    case 'color':

						if ( !isset( $option['sanitize_callback'] ) ) {
							$option['sanitize_callback'] = 'sanitize_hex_color';
						}

				    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
				    		$option['id'], array(
								'label'   => $option['label'],
								'section' => $option['section'],
								'sanitize_callback' => $option['sanitize_callback']
						) ) );

						break;

					case 'upload':

						if ( !isset( $option['sanitize_callback'] ) ) {
							$option['sanitize_callback'] = 'summit_sanitize_file_url';
						}

				    	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
				    		$option['id'], array(
								'label'   => $option['label'],
								'section' => $option['section'],
								'sanitize_callback' => $option['sanitize_callback']
						) ) );

						break;

					case 'textarea':

						if ( !isset( $option['sanitize_callback'] ) ) {
							$option['sanitize_callback'] = 'summit_sanitize_text';
						}

				    	$wp_customize->add_control( new Textarea_Custom_Control( $wp_customize,
				    		$option['id'], array(
								'label'   => $option['label'],
								'section' => $option['section'],
								'sanitize_callback' => $option['sanitize_callback']
						) ) );

						break;

			    }

			}
		}
	}
}
endif;

add_action( 'customize_register', 'summit_customizer', 100 );
