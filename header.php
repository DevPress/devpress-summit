<?php
/**
 * The Header for the theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Summit
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">

		<div class="header-image">
			<img src="<?php echo get_template_directory_uri() . '/images/blank.gif' ?>" width="880" height="410" alt="">
			<div class="opacity"></div>
		</div>

		<div class="site-branding">

			<?php if ( get_theme_mod( 'logo', false ) ) {
				$class = 'site-logo';
				$output = '<img src="' . esc_url( get_theme_mod( 'logo' ) ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			} else {
				$class = 'site-title';
				$output = get_bloginfo( 'name' );
			} ?>

			<h1 class="<?php echo $class; ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo $output; ?>
				</a>
			</h1>

			<?php if ( get_theme_mod( 'display-site-tagline', 1 ) ) : ?>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php endif; ?>

			<?php wp_nav_menu( array(
				'theme_location' => 'social',
				'depth' => '1',
				'fallback_cb' => false,
				'container_class' => 'social-menu',
				'link_before'     => '<span class="screen-reader-text">',
				'link_after'      => '</span>'
			) ); ?>
		</div>

	</header><!-- #masthead -->

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
	<nav id="site-navigation" class="main-navigation" role="navigation">
		<h3 class="menu-toggle"><?php _e( 'Menu', 'summit' ); ?></h3>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'summit' ); ?></a>

		<?php wp_nav_menu( array(
			'theme_location' => 'primary',
			'depth' => '2',
			'fallback_cb' => false,
		) ); ?>
	</nav><!-- #site-navigation -->
	<?php else : ?>
		<div class="no-main-navigation"></div>
	<?php endif; ?>

	<div id="content" class="site-content">
