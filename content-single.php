<?php
/**
 * @package Summit
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( get_theme_mod( 'display-post-dates' ) ) : ?>
		<div class="entry-meta">
			<?php summit_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && get_theme_mod( 'display-featured-images', false ) ) : ?>
	<div class="featured-image">
		<?php the_post_thumbnail( 'summit-large' ); ?>
	</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'summit' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php // Show author information if a user has filled out their description. ?>
	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<div class="author-meta clearfix">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'summit_author_bio_avatar_size', 60 ) ); ?>
			</div>
			<div class="author-description">
				<h3><?php printf( esc_attr__( 'About %s', 'summit' ), get_the_author() ); ?></h3>
				<?php the_author_meta( 'description' ); ?>
			</div>
		</div>
	<?php endif; ?>


	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'summit' ) );
				if ( $categories_list && summit_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php echo $categories_list; ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'summit' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'summit' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'summit' ), __( '1 Comment', 'summit' ), __( '% Comments', 'summit' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'summit' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
