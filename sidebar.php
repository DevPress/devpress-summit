<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Currents
 */
?>
	<div id="secondary" class="widget-area clearfix <?php echo footer_widgetarea_class(); ?>" role="complementary">
		<?php if ( ! dynamic_sidebar( 'footer' ) ) : ?>
		<?php endif; // end sidebar widget area ?>
	</div><!-- #secondary -->
