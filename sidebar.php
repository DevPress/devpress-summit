<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Summit
 */
?>

<?php if ( is_active_sidebar( 'summit-footer' ) ) : ?>
	<div id="secondary" class="widget-area clearfix <?php echo footer_widgetarea_class(); ?>" role="complementary">
		<?php dynamic_sidebar( 'summit-footer' ); ?>
	</div><!-- #secondary -->
<?php endif; ?>