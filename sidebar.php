<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Currents
 */
?>
	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
	<div id="secondary" class="widget-area clearfix <?php echo footer_widgetarea_class(); ?>" role="complementary">
		<?php dynamic_sidebar( 'footer' ); ?>
	</div><!-- #secondary -->
	<?php endif; ?>
