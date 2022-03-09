/**
 * Start Of rtSocial Plugin JS.
 *
 * @package rtSocial
 * @author  rtCamp
 */

/**
 * Get Facebook counts.
 *
 * @returns void;
 */
function rtsocial_facebook() {
	if ( '1' === args.facebook && 'icon' !== args.button_style && '1' !== args.hide_count ) {
		var rtsocial_urls = { }; // create an associative array of url as key and counts.
		var sep           = '"'; // URL separatore initial value.
		var tempPostId    = ""; // temp variable for post ID.

		jQuery( '.rtsocial-container' ).each(
			function () {
				var facebookSocial     = this;
				var rtsocial_url_count = 0;
				tempPostId             = jQuery( this ).find( '.rts_id' ).val();
				security               = jQuery( this ).find( '#rts_media_nonce' ).val();
				if ( tempPostId !== '' || tempPostId !== 'undefined' ) {
					jQuery.ajax({
						type: 'GET',
						url: ajaxurl,
						data: {
							"action"  : "rtss_wp_get_shares",
							"post_id" : tempPostId,
							"security": security
						},
						success: function(data){
							jQuery( facebookSocial ).find( '.rtsocial-fb-count' ).text( data );
						}
					});
					return false;
				}
			}
		);
	}
}

/**
 * Returns Pintrest count.
 *
 * @returns void
 */
function rtsocial_pinterest() {
	if ( '1' === args.pinterest && 'icon' !== args.button_style && '1' !== args.hide_count ) {
		jQuery( '.rtsocial-container' ).each(
			function () {
				var paNode                = this;
				var rtsocial_pinurl       = jQuery( this ).find( '.perma-link' ).attr( 'href' );
				var rtsocial_pincount_url = 'https://api.pinterest.com/v1/urls/count.json?callback=?&url=' + rtsocial_pinurl;
				jQuery.getJSON(
					rtsocial_pincount_url,
					function ( pinres ) {
						jQuery( '.rtsocial-pinterest-count', paNode ).text( ( pinres['count'] ) ? ( pinres['count'] ) : '0' );
					}
				);
			}
		);
	}
}


/**
 * Initalize Social Counters.
 *
 * @returns void
 */
function rtsocial_init_counters() {
	/**
	 * If Facebook is checked, get Facebook Shares.
	 */
	rtsocial_facebook();
	/* End of Facebook */

	/* Pinterest. */
	rtsocial_pinterest();

}

jQuery( document ).ready(
	function () {

		rtsocial_init_counters();

		/*
		* Showing the Facebook Share in the Admin Panel.
		*/
		var fb_url_full = jQuery( '#rtsocial-display-vertical-sample .rtsocial-fb-button' ).attr( 'href' );
		if ( fb_url_full ) {
			var fb_url_split = fb_url_full.split( '=' );
			var fb_url       = fb_url_split[1];
			var fb_url_arr   = [ ];
			fb_url_arr.push( fb_url );
			var fb_data_url = 'https://graph.facebook.com/?ids=' + fb_url_arr.join() + '&callback=?';
			jQuery.getJSON(
				fb_data_url,
				function ( fbres ) {
					jQuery.each(
						fbres,
						function ( fb_url, value ) {
							jQuery( '#rtsocial-display-vertical-sample span.rtsocial-fb-count' ).text( ( value['shares'] ) ? ( value['shares'] ) : '0' );
							jQuery( '#rtsocial-display-horizontal-sample span.rtsocial-fb-count' ).text( ( value['shares'] ) ? ( value['shares'] ) : '0' );
						}
					);
				}
			);
		}

		// Sortable Stuff.
		if ( jQuery( '.connectedSortable' ).length > 0 ) {
			jQuery( "#rtsocial-sorter-active, #rtsocial-sorter-inactive" ).sortable(
				{
					connectWith: ".connectedSortable",
					cursor: 'pointer',
					dropOnEmpty: true,
					revert: true,
					update: function ( event, ui ) {
						var ord = jQuery( this ).sortable( 'toArray' );
						// came from active.
						if ( 'rtsocial-sorter-inactive' === ui.item.parent().attr( 'id' ) ) {
							if ( 0 === ord.length ) {
								jQuery( this ).sortable( 'cancel' );
							}
							ui.item.find( 'input' ).attr( 'name', 'rtsocial_plugin_options[inactive][]' );
						} else if ( 'rtsocial-sorter-active' === ui.item.parent().attr( 'id' ) ) {
							// came from inactive.
							ui.item.find( 'input' ).attr( 'name', 'rtsocial_plugin_options[active][]' );
						}
					}
				}
			).disableSelection();
		}

	}
);

/*
 * Removing Twitter Section if Twitter is unchecked.
 */
jQuery( '#tw_chk' ).click(
	function () {
		if ( 0 === jQuery( 'input#tw_chk:checked' ).length ) {
			jQuery( '.tw_row' ).fadeOut( 'slow' );
			jQuery( '#tw_handle' ).attr( 'value', '' );
			jQuery( '#tw_related_handle' ).attr( 'value', '' );
		} else {
			jQuery( '#tw_handle' ).attr( 'value', 'devils_workshop' );
			jQuery( '#tw_related_handle' ).attr( 'value', 'rtCamp' );
			jQuery( '.tw_row' ).fadeIn( 'slow' );
		}
	}
);

/*
 * Removing Facebook Section if Facebook is unchecked.
 */
jQuery( '#fb_chk' ).click(
	function () {
		if ( 0 === jQuery( 'input#fb_chk:checked' ).length ) {
			jQuery( '.fb_row' ).fadeOut( 'slow' );
			jQuery( '.fb_row input[type="radio"]' ).attr( 'checked', false );
		} else {
			jQuery( '.fb_row input[value="like_light"]' ).attr( 'checked', true );
			jQuery( '.fb_row' ).fadeIn( 'slow' );
		}
	}
);

/**
 * Toggle FB Share from Dark to Light.
 */
jQuery( 'body' ).on( 'click', '.fb_color',function() {
	if ( 'light' === jQuery( this ).attr( 'value' ) ) {
		jQuery( '#fb_like' ).attr( 'src', args.path + 'fb_like.gif' );
		jQuery( '#fb_recommend' ).attr( 'src', args.path + 'fb_recommend.gif' );
	} else if ( 'dark' === jQuery( this ).attr( 'value' ) ) {
		jQuery( '#fb_like' ).attr( 'src', args.path + 'fb_like_dark.gif' );
		jQuery( '#fb_recommend' ).attr( 'src', args.path + 'fb_recommend_dark.gif' );
	}
} );
