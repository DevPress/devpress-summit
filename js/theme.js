( function( $ ) {

	// Vertically centers the .site-branding
	function summit_logo_position() {
		var $sitebranding = $('.site-branding'),
			copyheight = $sitebranding.height(),
			imageheight = $('.header-image').height(),
			distance = ( ( ( imageheight - copyheight ) / 2 ) / imageheight ) * 100;
		$sitebranding.css({ 'top' : distance + '%' }).fadeIn('200');
	}

	summit_logo_position();

	// Update logo alignment on resize
	$(window).on( 'resize', function() {
		$('.site-branding').hide();
	});

    $(window).on( 'resize', debounce(
    	function(){
	    	summit_logo_position();
    	}, 200 )
    );

} )( jQuery );

jQuery( document ).ready( function ($) {

	var $secondary = $( '#secondary');

	if ( $secondary.hasClass( 'column-masonry' ) ) {
		$secondary.masonry({
			itemSelector: '.widget',
			gutter: 22
		});
	}
});

/*
 * Debounce function
 *
 * @link http://remysharp.com/2010/07/21/throttling-function-calls
 */

function debounce(fn, delay) {
	var timer = null;
		return function () {
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function () {
		  fn.apply(context, args);
		}, delay);
	};
}