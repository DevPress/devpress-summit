<?php
/**
 * Theme updater for Summit
 *
 * @package Summit
 */

if ( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
	// Load our custom theme updater
	include( dirname( __FILE__ ) . '/theme-updater-class.php' );
}

/**
 * Returns settings required by the theme updater.
 *
 * since 1.0.0
 *
 * @param string $setting
 * @returns string $setting data
 */
function summit_updater_settings( $setting ) {

	/* URL of site running EDD */
	$data['remote_api_url'] = 'https://devpress.com';

	/* The name of this theme */
	$data['theme_slug'] = 'summit';

	/* The current theme version we are running */
	$data['version'] = '1.1.0';

	/* The author's name */
	$data['author'] = 'Devin Price';

	if ( isset( $data[$setting] ) ) {
		return $data[$setting];
	}

	return false;
}

/**
 * Creates the updater class.
 *
 * since 1.0.0
 */
new EDD_SL_Theme_Updater( array(
		'remote_api_url' 	=> summit_updater_settings( 'remote_api_url' ),
		'version' 			=> summit_updater_settings( 'version' ),
		'license' 			=> trim( get_option( 'summit_license_key' ) ),
		'item_name' 		=> summit_updater_settings( 'theme_slug' ),
		'author'			=> summit_updater_settings( 'author' )
	)
);

/**
 * Adds a menu item for the theme license under the appearance menu.
 */
function summit_license_menu() {
	add_theme_page(
		__( 'Theme License', 'summit' ),
		__( 'Theme License', 'summit' ),
		'manage_options', 'themename-license',
		'summit_license_page'
	);
}
add_action( 'admin_menu', 'summit_license_menu' );

/**
 * Outputs the markup used on the theme license page.
 *
 * since 1.0.0
 */
function summit_license_page() {

	$license = trim( get_option( 'summit_license_key' ) );
	$status = get_option( 'summit_license_key_status', false );

	// Checks license status to display under license key
	if ( ! $license ) {
		$message    = __( 'Enter your theme license key.', 'summit' );
	} else {
		delete_transient( 'summit_license_message' );
		if ( ! get_transient( 'summit_license_message', false ) ) {
			set_transient( 'summit_license_message', summit_check_license(), ( 60 * 60 * 24 ) );
		}
		$message = get_transient( 'summit_license_message' );
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'Theme License', 'summit' ); ?></h2>
		<?php if ( ! $license ) { ?>
		<h4><?php _e( 'Entering a license key will enable one-click theme updates.', 'summit' ); ?></h4>
		<?php } ?>
		<form method="post" action="options.php">

			<?php settings_fields( 'summit_license' ); ?>

			<table class="form-table">
				<tbody>

					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'License Key', 'summit' ); ?>
						</th>
						<td>
							<input id="summit_license_key" name="summit_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<p class="description">
								<?php echo $message; ?>
							</p>
						</td>
					</tr>

					<?php if ( $license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'License Action', 'summit' ); ?>
						</th>
						<td>
							<?php
							wp_nonce_field( 'summit_sample_nonce', 'summit_sample_nonce' );
							if ( 'valid' == $status ) { ?>
								<input type="submit" class="button-secondary" name="summit_license_deactivate" value="<?php esc_attr_e( 'Deactivate License', 'summit' ); ?>"/>
							<?php } else { ?>
								<input type="submit" class="button-secondary" name="summit_license_activate" value="<?php esc_attr_e( 'Activate License', 'summit' ); ?>"/>
							<?php }
							?>
						</td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	<?php
}

/**
 * Registers the option used to store the license key in the options table.
 *
 * since 1.0.0
 */
function summit_register_option() {
	register_setting( 'summit_license', 'summit_license_key', 'summit_sanitize_license' );
}
add_action( 'admin_init', 'summit_register_option' );

/**
 * Sanitizes the license key.
 *
 * since 1.0.0
 *
 * @param string $new License key that was submitted.
 * @return string $new Sanitized license key.
 */
function summit_sanitize_license( $new ) {

	$old = get_option( 'summit_license_key' );

	if ( $old && $old != $new ) {
		// New license has been entered, so must reactivate
		delete_option( 'summit_license_key_status' );
		delete_transient( 'summit_license_message' );
	}

	return $new;
}

/**
 * Activates the license key.
 *
 * @since 1.0.0
 */
function summit_activate_license() {

	$license = trim( get_option( 'summit_license_key' ) );

	// Data to send in our API request.
	$api_params = array(
		'edd_action' => 'activate_license',
		'license' => $license,
		'item_name' => urlencode( summit_updater_settings( 'theme_slug' ) )
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, summit_updater_settings( 'remote_api_url' ) ), array( 'timeout' => 15, 'sslverify' => false ) );

	// Make sure the response came back okay.
	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "active" or "inactive"
	update_option( 'summit_license_key_status', $license_data->license );
	delete_transient( 'summit_license_message' );

}
add_action( 'update_option_summit_license_key', 'summit_activate_license', 10, 2 );

/**
 * Deactivates the license key.
 *
 * @since 1.0.0
 */
function summit_deactivate_license() {

	// Retrieve the license from the database.
	$license = trim( get_option( 'summit_license_key' ) );


	// Data to send in our API request.
	$api_params = array(
		'edd_action'=> 'deactivate_license',
		'license' 	=> $license,
		'item_name' => urlencode( summit_updater_settings( 'theme_slug' ) )
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, summit_updater_settings( 'remote_api_url' ) ), array( 'timeout' => 15, 'sslverify' => false ) );

	// Make sure the response came back okay
	if ( is_wp_error( $response ) ) {
		return false;
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "deactivated" or "failed"
	if ( $license_data->license == 'deactivated' ) {
		delete_option( 'summit_license_key_status' );
		delete_transient( 'summit_license_message' );
	}
}

/**
 * Checks if a license action was submitted.
 *
 * @since 1.0.0
 */
function summit_license_action() {

	if ( isset( $_POST['summit_license_activate'] ) ) {
		if ( check_admin_referer( 'summit_sample_nonce', 'summit_sample_nonce' ) ) {
			summit_activate_license();
		}
	}

	if ( isset( $_POST['summit_license_deactivate'] ) ) {
		if ( check_admin_referer( 'summit_sample_nonce', 'summit_sample_nonce' ) ) {
			summit_deactivate_license();
		}
	}

}
add_action( 'admin_init', 'summit_license_action' );

/**
 * Checks if license is valid and gets expire date.
 *
 * @since 1.0.0
 *
 * @return string $message License status message.
 */
function summit_check_license() {

	$license = trim( get_option( 'summit_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( summit_updater_settings( 'theme_slug' ) )
	);

	$response = wp_remote_get( add_query_arg( $api_params, summit_updater_settings( 'remote_api_url' ) ), array( 'timeout' => 15, 'sslverify' => false ) );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// If response doesn't include license data, return
	if ( !isset( $license_data->license ) ) {
		$message =  __( 'License status is unknown.', 'summit' );
		return $message;
	}

	// Get expire date
	$expires = false;
	if ( isset( $license_data->expires ) ) {
		$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
		$renew_link = '<a href="' . esc_url( summit_updater_settings( 'remote_api_url' ) ) . '">' . __( 'Renew?', 'summit' ) . '</a>';
	}

	if ( $license_data->license == 'valid' ) {
		$message = __( 'License key is active.', 'summit' );
		if ( $expires ) {
			$message .= sprintf( __( ' Expires %s.', 'summit' ), $expires );
		}
	} else if ( $license_data->license == 'expired' ) {
		if ( $expires ) {
			$message = sprintf( __( 'License key expired %s.', 'summit' ), $expires );
		} else {
			$message = __( 'License key has expired.', 'summit' );
		}
		if ( $renew_link ) {
			$message .= ' ' . $renew_link;
		}
	} else if ( $license_data->license == 'invalid' ) {
		$message =  __( 'License keys do not match.', 'summit' );
	} else if ( $license_data->license == 'inactive' ) {
		$message =  __( 'License is inactive.', 'summit' );
	} else if ( $license_data->license == 'disabled' ) {
		$message =  __( 'License key is disabled.', 'summit' );
	} else if ( $license_data->license == 'site_inactive' ) {
		// Site is inactive
		$message =  __( 'Site is inactive.', 'summit' );
	} else {
		$message =  __( 'License status is unknown.', 'summit' );
	}

	return $message;
}