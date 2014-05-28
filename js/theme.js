(function($) {

	var summit = {

		/**
		 *
		 */
		cache: {
			$document: $(document),
			$window: $(window),
			$sitebranding: $('.site-branding'),
			$logo: $('.site-branding img'),
			$sitenavigation: $('#site-navigation'),
			$secondary : $('#secondary')
		},

		/**
		 *
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 *
		 */
		bindEvents: function() {

			var self = this;

			this.cache.$document.on( 'ready', function() {

				// If there is an image logo, wait until it loads before positioning
				if ( self.cache.$logo) {
					self.cache.$logo.on( 'load', self.navigationInit() );
				} else {
					self.navigationInit();
				}

				self.brandingInit();
				self.fitVidsInit();
			});

			this.cache.$window.on( 'resize', function() {
				self.cache.$sitebranding.hide();
			});

			this.cache.$window.on( 'resize', self.debounce(
				function() {

					// Reposition Branding
					self.brandingInit();

					// Remove any inline styles that may have been added to menu
					self.cache.$menu.attr('style','');
					self.cache.$menu.find('.children,.sub-menu').each( function(){
		    			$(this).attr('style','');
					});

					self.cache.$menu.find('.dropdown-toggle').each( function(){
						$(this).removeClass('toggled');
					});

				}, 200 )
			);

		},

		/**
		 * Initialize the mobile menu functionality.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		navigationInit: function() {

			var self = this;

			if ( ! this.cache.$sitenavigation ) {
				return;
			}

			this.cache.$menutoggle = this.cache.$sitenavigation.find( '.menu-toggle' ).eq(0);
			this.cache.$menu = this.cache.$sitenavigation.find( 'ul' ).eq(0);

			// Add class to the menu
			if ( ! this.cache.$menu.hasClass('nav-menu') )
				this.cache.$menu.addClass('nav-menu');

			// Add dropdown toggle that display child menu items.
			$('.main-navigation > div > ul > .page_item_has_children, .main-navigation > div > ul > .menu-item-has-children').append( '<span class="dropdown-toggle" />');

			// When mobile menu is tapped/clicked
			this.cache.$menutoggle.on( 'click', function() {
				if ( ! self.cache.$sitenavigation.hasClass('toggled') ) {
					self.cache.$menu.slideDown( function() {
						self.cache.$sitenavigation.addClass('toggled');
					});
				} else {
					self.cache.$menu.slideUp( function(){
						self.cache.$sitenavigation.removeClass('toggled');
					});
				}
			});

			// When mobile submenu is tapped/clicked
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

		},


		/**
		 * Initialize the site branding.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		brandingInit: function() {

			var copyheight = this.cache.$sitebranding.height(),
				mastheadheight = $('#masthead').height(),
				distance = ( ( ( mastheadheight - copyheight ) / 2 ) / mastheadheight ) * 100;

			this.cache.$sitebranding.css({ 'top' : distance + '%' }).fadeIn('200');
		},

		/**
		 * Initialize FitVids.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		fitVidsInit: function() {

			// Make sure lib is loaded.
			if (!$.fn.fitVids) {
				return;
			}

			// Run FitVids
			$('.hentry').fitVids();
		},

		/**
		 * Debounce function.
		 *
		 * @since  1.0.0
		 * @link http://remysharp.com/2010/07/21/throttling-function-calls
		 *
		 * @return void
		 */
		debounce: function(fn, delay) {
			var timer = null;
			return function () {
				var context = this, args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function () {
					fn.apply(context, args);
				}, delay);
			};
		}
	}

	summit.init();

})(jQuery);