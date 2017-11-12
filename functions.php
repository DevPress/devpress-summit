<?php
/**
 * Summit functions and definitions
 *
 * @package Summit
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 698; /* pixels */
}

/**
 * Set constant for version
 */
define( 'SUMMIT_VERSION', '1.3.1' );

if ( ! function_exists( 'summit_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function summit_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'summit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'summit' ),
		'social' => __( 'Social Menu', 'summit' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'gallery',
		'video',
		'quote',
		'link'
	) );

	// Add image sizes
	add_image_size( 'summit-large', '698', 9999, false );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'summit_custom_background_args', array(
		'default-color' => '444444',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // summit_setup
add_action( 'after_setup_theme', 'summit_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function summit_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'summit' ),
		'id'            => 'footer',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'summit_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function summit_scripts() {

	wp_enqueue_style( 'summit-style', get_stylesheet_uri(), array(), SUMMIT_VERSION );

	// Use style-rtl.css for RTL layouts
	wp_style_add_data( 'summit-style', 'rtl', 'replace' );

	wp_enqueue_script(
		'summit-theme',
		get_template_directory_uri() . '/js/combined-min.js',
		array( 'jquery' ),
		SUMMIT_VERSION,
		true
	);

	if ( 'column-masonry' == footer_widgetarea_class() ) {
		wp_enqueue_script( 'summit-masonry', get_template_directory_uri() . '/js/masonry.pkgd.min.js', array( 'jquery' ), SUMMIT_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'summit_scripts' );

/**
 * Replace class on html tag if javascript is supported
 */
function summit_js_class() {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'summit_js_class', 1 );

/**
 * Load placeholder polyfill for IE9 and older
 */
function summit_placeholder_polyfill() {
	echo '<!--[if lte IE 9]><script src="' . get_template_directory_uri() . '/js/jquery-placeholder.js"></script><![endif]-->'. "\n";
}
add_action( 'wp_head', 'summit_placeholder_polyfill' );

/**
 * Enqueue fonts.
 */
function summit_fonts() {

	// Source Sans Pro
	wp_enqueue_style(
		'summit_fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic|Noto+Serif:400,700,400italic',
		array(),
		null,
		'screen'
	);

	// Custom Icon Font
	wp_enqueue_style(
		'summit_icons',
		get_template_directory_uri() . '/fonts/summit-icons/icons.css',
		array(),
		SUMMIT_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'summit_fonts' );

// Implement the Custom Header feature.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Custom functions that act independently of the theme templates.
require get_template_directory() . '/inc/extras.php';

// Helper library for the theme customizer.
require get_template_directory() . '/inc/customizer-library/customizer-library.php';

// Define options for the theme customizer.
require get_template_directory() . '/inc/customizer-options.php';

// Output inline styles based on theme customizer selections.
require get_template_directory() . '/inc/styles.php';

// Load Jetpack compatibility file.
require get_template_directory() . '/inc/jetpack.php';

/**
 * DevPress Theme Updater
 *
 * @since 1.1.0
 */
function devpress_theme_updater() {
	require( get_template_directory() . '/inc/updater/theme-updater.php' );
}
add_action( 'after_setup_theme', 'devpress_theme_updater' );
