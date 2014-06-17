<?php
/**
 * One-click updater Summit.
 *
 * @package Summit
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'DevPress_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new DevPress_Theme_Updater_Admin(

	array(
		'remote_api_url' => 'https://devpress.com', // Site where EDD is hosted
		'item_name' => 'Summit', // Name of theme
		'theme_slug' => 'summit', // Theme slug
		'version' => '1.1.0', // The current version of this theme
		'author' => 'DevPress' // The author of this theme
	)

);