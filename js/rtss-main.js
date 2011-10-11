/**
 * Start Of rtSocial Plugin JS
 */

jQuery( document ).ready( function() {
    /**
     * If Twitter is checked, get Twitter Counts
     */
    if ( args.twitter == 1 ) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var key = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_twiturl =  'http://urls.api.twitter.com/1/urls/count.json?url=' + key + '&callback=?';
            var url = jQuery( this ).find( '.rtsocial-twitter-button' ).attr( 'href' );
            jQuery.getJSON( rtsocial_twiturl,function( twitres ) {
                url += '&url=' + twitres['url'];
                jQuery( '.rtsocial-twitter-button', paNode ).attr( 'href', url );
                jQuery( '.rtsocial-twitter-count', paNode ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
            } );
        } );
    }
    /* End of Twitter */
    
    /**
     * If Facebook is checked, get Facebook Shares
     */
    if ( args.facebook == 1 ) {
        var rtsocial_urls = []; /* create an array of urls */
        jQuery( '.rtsocial-container' ).each( function() {
            rtsocial_urls.push( jQuery( 'a.perma-link', this ).attr( 'href' ) );
        } );
        /* End of .rtsocial-container */

        /**
         * Facebook Data
         */
        var rtsocial_fburl =  'https://graph.facebook.com/?ids=' + rtsocial_urls.join() + '&callback=?';
        var rtsocial_fbcounts = new Array();
        jQuery.getJSON( rtsocial_fburl, function( fbres ) {
            jQuery.each( fbres, function( key, value ) { rtsocial_fbcounts[key] = ( value['shares'] ) ? value['shares'] : 0; } );
            rtsocial_update_fbcount( rtsocial_fbcounts );
        } );
        /* End of Callback function in JSON */
    }
    /* End of Facebook */
    
    /*
     * Hide Twitter Section on Load if checkbox is unchecked
     */
    jQuery( '#tw_chk' ).ready( function() {
        if ( jQuery( 'input#tw_chk:checked' ).length === 0 ) {
            jQuery( '.tw_row' ).fadeOut( 'slow' );
            jQuery( '#tw_handle' ).attr( 'value', '' );
            jQuery( '#tw_related_handle' ).attr( 'value', '' );
        }
    } );
    
    /*
     * Hide Facebook Section on Load if checkbox is unchecked
     */
    jQuery( '#fb_chk' ).ready( function() {
        if ( jQuery( 'input#fb_chk:checked' ).length === 0 ) {
            jQuery( '.fb_row' ).fadeOut( 'slow' );
            jQuery( '.fb_row input[type="radio"]' ).attr( 'checked', false );
        }
    } );

    /*
     * Showing the Tweet Count in the Admin Panel
     */
    var twit_url_full = jQuery( '#rtsocial-display-vertical-sample .rtsocial-twitter-button' ).attr( 'href' );
    if ( twit_url_full ) {
        var twit_url_split = twit_url_full.split( '&' );
        var twit_url_arr = twit_url_split[3].split( '=' );
        var twit_url = twit_url_arr[1];
        var twit_data_url = 'http://urls.api.twitter.com/1/urls/count.json?url=' + twit_url + '&callback=?';
        jQuery.getJSON( twit_data_url, function( twitres ) {
            jQuery( '#rtsocial-display-vertical-sample span.rtsocial-twitter-count' ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
            jQuery( '#rtsocial-display-horizontal-sample span.rtsocial-twitter-count' ).text( ( twitres['count'] ) ? ( twitres['count'] ) : '0' );
        } );
    }

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

} ); 
/* End of document.ready */

/* Facebook Count Update */
function rtsocial_update_fbcount( rtsocial_fbcounts ) {
    jQuery( '.rtsocial-container' ).each( function() {
        key = jQuery( this ).find( 'a.perma-link' ).attr( 'href' );
        var url = jQuery( this ).find( '.rtsocial-fb-button' ).attr( 'href' );
        url += 'u=' + key;
        jQuery( this ).find( '.rtsocial-fb-button' ).attr( 'href', url );
        jQuery( this ).find( '.rtsocial-fb-count' ).text( ( ( rtsocial_fbcounts[key] ) ? ( rtsocial_fbcounts[key] ) : '0' ) );
    } ); 
    /* End of "each" */
} 
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