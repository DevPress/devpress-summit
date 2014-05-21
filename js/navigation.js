/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
( function( $ ) {
	var $container, $button, $menu;

	$container = $( '#site-navigation' );
	if ( ! $container )
		return;

	$button = $container.find( 'h3' ).eq(0);
	if ( ! ( $button.length > 0 ) )
		return;

	$menu = $container.find( 'ul' ).eq(0);

	// Hide menu toggle button if menu is empty and return early.
	if ( ! ( $menu.length > 0 ) ) {
		$button.css({ 'display':'none' });
		return;
	}

	if ( ! $menu.hasClass('nav-menu') )
		$menu.addClass('nav-menu');

	$button.on( 'click', function() {
		if ( ! $container.hasClass('toggled') ) {
			$menu.slideDown( function() {
				$container.addClass('toggled');
			});
		} else {
			$menu.slideUp( function(){
				$container.removeClass('toggled');
			});
		}
	});

	// Add dropdown toggle that display child menu items.
	$('.main-navigation > div > ul > .page_item_has_children, .main-navigation > div > ul > .menu-item-has-children').append( '<span class="dropdown-toggle" />');

	$('.dropdown-toggle').click( function() {
		var $submenu = $(this).parent().find('.children,.sub-menu'),
			$toggle = $(this);
		if ( ! $(this).hasClass('toggled') ) {
			$submenu.slideDown( function() {
				$toggle.addClass('toggled');
			});
		} else {
			$submenu.slideUp( function(){
				$toggle.removeClass('toggled');
			});
		}
	});

	$(window).on( 'resize', debounce(
    	function(){
	    	$menu.attr('style','');
	    	$menu.find('.children,.sub-menu').each( function(){
		    	$(this).attr('style','');
	    	});
    	}, 200 )
    );

} )( jQuery );