<?php
/**
 * One-click updater for the Summit Theme
 *
 * @package Summit
 */

// Includes the files needed for the theme updater
include( dirname( __FILE__ ) . '/theme-updater-admin.php' );

// Loads the theme updater admin class
$updater = new DevPress_Theme_Updater_Admin;

// Defines variables to be used by the theme updater
$updater->init(
	array(
		'remote_api_url' => 'https://devpress.com',
		'theme_slug' => 'Summit', // The name of this theme
		'version' => '0.1.0', // The current version of this theme
		'author' => 'Devin Price' // The author of this theme
	)
);
