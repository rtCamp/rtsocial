/**
 * Start Of rtSocial Plugin JS
 */

function rtsocial_twitter(){
    if ( args.twitter == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var key = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_twiturl =  ('https:' == document.location.protocol ? 'https://' : 'http://') + 'urls.api.twitter.com/1/urls/count.json?url=' + key + '&callback=?';
            var url = jQuery( this ).find( '.rtsocial-twitter-button' ).attr( 'href' );
            jQuery.getJSON( rtsocial_twiturl,function( twitres ) {
                url += '&url=' + twitres['url'];
                jQuery( '.rtsocial-twitter-button', paNode ).attr( 'href', url );
                jQuery( '.rtsocial-twitter-count', paNode ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
            } );
        } );
    }
}

function rtsocial_facebook(){
    if ( args.facebook == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        var rtsocial_urls = {}; /* create an associative array of url as key and counts*/
        var sep='"'; // URL separatore initial value
        var tempFbUrl=""; // temp variable for url
		
		jQuery( '.rtsocial-container' ).each( function () {
			var facebookSocial = this;
			var rtsocial_url_count = 0;
            tempFbUrl = jQuery( this ).find( 'a.perma-link' ).attr( 'href' );
			if ( tempFbUrl != '' || tempFbUrl != 'undefined' ) {
				//Fetch share count by Facebook Graph API
				var rtsocial_fburl = 'https://graph.facebook.com/?id=' + tempFbUrl;
				/**
				 * Facebook Data
				 */
				jQuery.getJSON( rtsocial_fburl, function ( fbres ) {
					if ( fbres.share && fbres.share.share_count ) {
						rtsocial_url_count = fbres.share.share_count; // Setting value
					}
					jQuery( facebookSocial ).find( '.rtsocial-fb-count' ).text( rtsocial_url_count );
//				rtsocial_update_fbcount( rtsocial_url_count ); // passing count for update
				} );/* End of Callback function in JSON */
			}
        } );       
        /* End of .rtsocial-container */
    }
}

function rtsocial_pinterest(){
    if ( args.pinterest == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var rtsocial_pinurl = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_pincount_url = 'https://api.pinterest.com/v1/urls/count.json?callback=?&url='+rtsocial_pinurl;
            jQuery.getJSON( rtsocial_pincount_url, function( pinres ) {
                jQuery('.rtsocial-pinterest-count', paNode).text( ( pinres['count'] ) ? ( pinres['count'] ) : '0' );
            });
        });
    }
}

function rtsocial_linkedin(){
    if ( args.linkedin == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var rtsocial_linurl = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_lincount_url = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'www.linkedin.com/countserv/count/share?callback=?&url='+rtsocial_linurl;
            jQuery.getJSON( rtsocial_lincount_url, function( pinres ) {
                jQuery('.rtsocial-linkedin-count', paNode).text( ( pinres['count'] ) ? ( pinres['count'] ) : '0' );
            });
        });
    }
}

function rtsocial_gplus(){
    if ( args.gplus == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var rtsocial_gplusurl = jQuery( this ).find( '.perma-link' ).attr( 'href' );
			var rtsocial_gplusid = jQuery( this ).find( '.rts_id' ).val();
            var rtsocial_gplus_nonce = jQuery( this ).find( '#rts_media_nonce' ).val();
			var rtsocial_gplusdata = {
                action: 'rtsocial_gplus',
                url: rtsocial_gplusurl,
                id: rtsocial_gplusid,
				nonce: rtsocial_gplus_nonce
            };

            jQuery.post( ajaxurl, rtsocial_gplusdata, function( gplusres ) {
                jQuery('.rtsocial-gplus-count', paNode).text( ( gplusres ) ? ( gplusres ) : '0' );
            });
        });
    }
}

function rtsocial_init_counters(){
    /**
     * If Twitter is checked, get Twitter Counts
     */
    /* rtsocial_twitter(); */
    /* End of Twitter */

    /**
     * If Facebook is checked, get Facebook Shares
     */
    rtsocial_facebook();
    /* End of Facebook */

    /* Pinterest */
    rtsocial_pinterest();

    /* LinkedIn */
    rtsocial_linkedin();

    /* G+ Share */
    rtsocial_gplus();
}

jQuery( document ).ready( function() {

    rtsocial_init_counters();
    /*
     * Showing the Tweet Count in the Admin Panel
     */
	/*
    var twit_url_full = jQuery( '#rtsocial-display-vertical-sample .rtsocial-twitter-button' ).attr( 'href' );
    if ( twit_url_full ) {
        var twit_url_split = twit_url_full.split( '&' );
        var twit_url_arr = twit_url_split[3].split( '=' );
        var twit_url = twit_url_arr[1];
        var twit_data_url = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'urls.api.twitter.com/1/urls/count.json?url=' + twit_url + '&callback=?';
        jQuery.getJSON( twit_data_url, function( twitres ) {
            jQuery( '#rtsocial-display-vertical-sample span.rtsocial-twitter-count' ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
            jQuery( '#rtsocial-display-horizontal-sample span.rtsocial-twitter-count' ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
        } );
    }
	*/
    /*
     * Showing the Facebook Share in the Admin Panel
     */
    var fb_url_full = jQuery( '#rtsocial-display-vertical-sample .rtsocial-fb-button' ).attr( 'href' );
    if ( fb_url_full ) {
        var fb_url_split = fb_url_full.split( '=' );
        var fb_url = fb_url_split[1];
        var fb_url_arr = [];
        fb_url_arr.push( fb_url );
        var fb_data_url = 'https://graph.facebook.com/?ids=' + fb_url_arr.join() + '&callback=?';
        jQuery.getJSON( fb_data_url, function( fbres ) {
            jQuery.each( fbres, function( fb_url, value ) {
                jQuery( '#rtsocial-display-vertical-sample span.rtsocial-fb-count' ).text( ( value['shares'] ) ? ( value['shares'] ) : '0' );
                jQuery( '#rtsocial-display-horizontal-sample span.rtsocial-fb-count' ).text( ( value['shares'] ) ? ( value['shares'] ) : '0' );
            } );
        } );
    }
    
    /* Sortable Stuff */
    
    if(jQuery('.connectedSortable').length > 0){
        jQuery( "#rtsocial-sorter-active, #rtsocial-sorter-inactive" ).sortable({
            connectWith: ".connectedSortable",
            cursor: 'pointer',
            dropOnEmpty: true,
            revert: true,
            update: function(event, ui){
                var ord = jQuery(this).sortable('toArray');
                //came from active
                if(ui.item.parent().attr('id') == 'rtsocial-sorter-inactive'){
                    if(ord.length == 0){
                        jQuery(this).sortable('cancel');
                    }
                    ui.item.find('input').attr('name', 'rtsocial_plugin_options[inactive][]');
                }
                //came from inactive
                else if(ui.item.parent().attr('id') == 'rtsocial-sorter-active') {
					if(ui.item.context.id == 'rtsocial-ord-gplus'){
						if(jQuery( '#google_api_key' ).val().length == 0) {
							alert('Please enter Google API key to activate g+ share');
							jQuery(this).sortable('cancel');
							die(1);
						}
					}
                    ui.item.find('input').attr('name', 'rtsocial_plugin_options[active][]');
                }
            }
        }).disableSelection();
        /* End of Sortable */
    }
    
} );
/* End of document.ready */

/* End of Function */

/*
 * Removing Twitter Section if Twitter is unchecked
 */
jQuery( '#tw_chk' ).click( function() {
    if ( jQuery( 'input#tw_chk:checked' ).length === 0 ) {
        jQuery( '.tw_row' ).fadeOut( 'slow' );
        jQuery( '#tw_handle' ).attr( 'value', '' );
        jQuery( '#tw_related_handle' ).attr( 'value', '' );
    } else {
        jQuery( '#tw_handle' ).attr( 'value', 'devils_workshop' );
        jQuery( '#tw_related_handle' ).attr( 'value', 'rtCamp' );
        jQuery( '.tw_row' ).fadeIn( 'slow' );
    }
} );

/*
 * Removing Facebook Section if Facebook is unchecked
 */
jQuery( '#fb_chk' ).click( function() {
    if ( jQuery( 'input#fb_chk:checked' ).length === 0 ) {
        jQuery( '.fb_row' ).fadeOut( 'slow' );
        jQuery( '.fb_row input[type="radio"]' ).attr( 'checked', false );
    } else {
        jQuery( '.fb_row input[value="like_light"]' ).attr( 'checked', true );
        jQuery( '.fb_row' ).fadeIn( 'slow' );
    }
} );

jQuery( '.fb_color' ).live( 'click', function() {
    if ( jQuery( this ).attr( 'value' ) == 'light' ) {
        jQuery( '#fb_like' ).attr( 'src', args.path + 'fb_like.gif' );
        jQuery( '#fb_recommend' ).attr( 'src', args.path + 'fb_recommend.gif' );
    } else if ( jQuery( this ).attr( 'value' ) == 'dark' ) {
        jQuery( '#fb_like' ).attr( 'src', args.path + 'fb_like_dark.gif' );
        jQuery( '#fb_recommend' ).attr( 'src', args.path + 'fb_recommend_dark.gif' );
    }
} );
/**
 * End Of rtSocial Plugin JS
 */