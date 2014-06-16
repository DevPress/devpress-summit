<?php
/**
 * Theme updater admin page and functions.
 *
 * @package Summit
 */

class DevPress_Theme_Updater_Admin {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	 protected $remote_api_url = null;
	 protected $theme_slug = null;
	 protected $version = null;
	 protected $author = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function __construct( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'remote_api_url' => 'http://easydigitaldownloads.com',
			'theme_slug' => get_template(),
			'item_name' => '',
			'license' => '',
			'version' => '',
			'author' => ''
		) );
		extract( $args );

		// Set args
		$this->remote_api_url = $remote_api_url;
		$this->item_name = $item_name;
		$this->theme_slug = sanitize_key( $theme_slug );
		$this->version = $version;
		$this->author = $author;

		// Populate fallbacks
		if ( '' == $version ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'admin_init', array( $this, 'register_option' ) );
		add_action( 'admin_init', array( $this, 'license_action' ) );
		add_action( 'admin_menu', array( $this, 'license_menu' ) );
		add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );

	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	function updater() {

		/* If there is no valid license key status, don't allow updates. */
		if ( get_option( $this->theme_slug . '_license_key_status', false) != 'valid' ) {
			return;
		}

		if ( !class_exists( 'DevPress_Theme_Updater' ) ) {
			// Load our custom theme updater
			include( dirname( __FILE__ ) . '/theme-updater-class.php' );
		}

		new DevPress_Theme_Updater( array(
				'remote_api_url' 	=> $this->remote_api_url,
				'version' 			=> $this->version,
				'license' 			=> trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_name' 		=> $this->item_name,
				'author'			=> $this->author
			)
		);
	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function license_menu() {
		add_theme_page(
			__( 'Theme License', 'summit' ),
			__( 'Theme License', 'summit' ),
			'manage_options',
			$this->theme_slug . '-license',
			array( $this, 'license_page' )
		);
	}

	/**
	 * Outputs the markup used on the theme license page.
	 *
	 * since 1.0.0
	 */
	function license_page() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status = get_option( $this->theme_slug . '_license_key_status', false );

		// Checks license status to display under license key
		if ( ! $license ) {
			$message    = __( 'Enter your theme license key.', 'summit' );
		} else {
			// delete_transient( $this->theme_slug . '_license_message' );
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}
		?>
		<div class="wrap">
			<h2><?php _e( 'Theme License', 'summit' ); ?></h2>
			<form method="post" action="options.php">

				<?php settings_fields( $this->theme_slug . '-license' ); ?>

				<table class="form-table">
					<tbody>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'License Key', 'summit' ); ?>
							</th>
							<td>
								<input id="<?php echo $this->theme_slug; ?>_license_key" name="<?php echo $this->theme_slug; ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
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
								wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
								if ( 'valid' == $status ) { ?>
									<input type="submit" class="button-secondary" name="<?php echo $this->theme_slug; ?>_license_deactivate" value="<?php esc_attr_e( 'Deactivate License', 'summit' ); ?>"/>
								<?php } else { ?>
									<input type="submit" class="button-secondary" name="<?php echo $this->theme_slug; ?>_license_activate" value="<?php esc_attr_e( 'Activate License', 'summit' ); ?>"/>
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
	function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);
	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 * @return string $new Sanitized license key.
	 */
	function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old != $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		return $new;
	}

	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	 function get_api_response( $api_params ) {

		 // Call the custom API.
		$response = wp_remote_get(
			add_query_arg( $api_params, $this->remote_api_url ),
			array( 'timeout' => 15, 'sslverify' => false )
		);

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		return $response;
	 }

	/**
	 * Activates the license key.
	 *
	 * @since 1.0.0
	 */
	function activate_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name )
		);

		$license_data = $this->get_api_response( $api_params );

		// $response->license will be either "active" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			delete_transient( $this->theme_slug . '_license_message' );
		}

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name )
		);

		$license_data = $this->get_api_response( $api_params );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}
	}

	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[$this->theme_slug . '_license_deactivate'] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}

	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 * @return string $message License status message.
	 */
	function check_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name )
		);

		$license_data = $this->get_api_response( $api_params );

		// If response doesn't include license data, return
		if ( !isset( $license_data->license ) ) {
			$message = __( 'License status is unknown.', 'summit' );
			return $message;
		}

		// Get expire date
		$expires = false;
		if ( isset( $license_data->expires ) ) {
			$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
			$renew_link = '<a href="' . esc_url( $this->remote_api_url) . '">' . __( 'Renew?', 'summit' ) . '</a>';
		}

		// Get site counts
		$site_count = $license_data->site_count;
		$license_limit = $license_data->license_limit;

		// If unlimited
		if ( 0 == $license_limit ) {
			$license_limit = __( 'unlimited', 'summit' );
		}

		if ( $license_data->license == 'valid' ) {
			$message = __( 'License key is active.', 'summit' ) . ' ';
			if ( $expires ) {
				$message .= sprintf( __( 'Expires %s.', 'summit' ), $expires ) . ' ';
			}
			if ( $site_count && $license_limit ) {
				$message .= sprintf( __( 'You have %1$s / %2$s sites activated.', 'summit' ), $site_count, $license_limit );
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
			$message = __( 'License keys do not match.', 'summit' );
		} else if ( $license_data->license == 'inactive' ) {
			$message = __( 'License is inactive.', 'summit' );
		} else if ( $license_data->license == 'disabled' ) {
			$message = __( 'License key is disabled.', 'summit' );
		} else if ( $license_data->license == 'site_inactive' ) {
			// Site is inactive
			$message = __( 'Site is inactive.', 'summit' );
		} else {
			$message = __( 'License status is unknown.', 'summit' );
		}

		return $message;
	}

}