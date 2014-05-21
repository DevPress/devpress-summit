<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Summit
 */

if ( ! function_exists( 'summit_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function summit_paging_nav() {

	// Only display on archives
	if ( is_singular() ) {
		return;
	}

	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Page navigation', 'summit' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span><span class="meta-nav">&larr;</span> Older posts</span>', 'summit' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( '<span>Newer posts <span class="meta-nav">&rarr;</span></span>', 'summit' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'summit_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function summit_post_nav() {

	// Only display on single posts and attachments
	if ( ! ( is_single() || is_attachment() ) ) {
		return;
	}

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'summit' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span><span class="meta-nav">&larr;</span> Previous</span>', 'Previous post link', 'summit' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '<span>Next <span class="meta-nav">&rarr;</span></span>', 'Next post link',     'summit' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'summit_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function summit_posted_on() {

	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted %1$s</span><span class="byline"> by %2$s</span>', 'summit' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function summit_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'summit_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'summit_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so summit_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so summit_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in summit_categorized_blog.
 */
function summit_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'summit_categories' );
}
add_action( 'edit_category', 'summit_category_transient_flusher' );
add_action( 'save_post',     'summit_category_transient_flusher' );

/**
 * Wrap the more link in a paragraphy tag
 */
function summit_more_link( $link ){
	return "<p>$link</p>";
}
add_filter( 'the_content_more_link', 'summit_more_link' );
