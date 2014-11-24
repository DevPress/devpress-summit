<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Summit
 */
?>

	</div><!-- #content -->

	<?php if ( get_theme_mod( 'overlay-navigation', 1 ) ) : ?>
	<div class="overlay-navigation">
		<?php summit_post_nav(); ?>
	</div>
	<?php endif; ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( get_theme_mod( 'footer-text', customizer_library_get_default( 'footer-text' ) ) != '' ) : ?>
		<div class="site-info">
			<?php echo get_theme_mod( 'footer-text', customizer_library_get_default( 'footer-text' ) ); ?>
		</div><!-- .site-info -->
		<?php endif; ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
