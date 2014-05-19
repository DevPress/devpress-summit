<?php
/**
 * Customizer Utility Functions
 *
 * @package Currents
 */

/**
 * Helper function to return defaults
 *
 * @since  1.0.0
 *
 * @param string
 * @return mixed $default
 */

function currents_get_default( $setting ) {

	$options = currents_options();

	if ( isset( $options[$setting]['default'] ) ) {
		return $options[$setting]['default'];
	}

}

/**
 * Helper function to return choices
 *
 * @since  1.0.0
 *
 * @param string
 * @return mixed $default
 */

function currents_get_choices( $setting ) {

	$options = currents_options();

	if ( isset( $options[$setting]['choices'] ) ) {
		return $options[$setting]['choices'];
	}

}

/**
 * Converts a hex color to RGB.  Returns the RGB values as an array.
 *
 * @since  1.0.0
 *
 * @access public
 * @param  string  $hex
 * @return array
 */
function currents_hex_to_rgb( $hex ) {

	// Remove "#" if it was added
	$color = trim( $hex, '#' );

	// Return empty array if invalid value was sent
	if ( ! ( 3 === strlen( $color ) ) && ! ( 6 === strlen( $color ) ) ) {
		return array();
	}

	// If the color is three characters, convert it to six.
	if ( 3 === strlen( $color ) ) {
		$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
	}

	// Get the red, green, and blue values
	$red   = hexdec( $color[0] . $color[1] );
	$green = hexdec( $color[2] . $color[3] );
	$blue  = hexdec( $color[4] . $color[5] );

	// Return the RGB colors as an array
	return array( 'r' => $red, 'g' => $green, 'b' => $blue );
}