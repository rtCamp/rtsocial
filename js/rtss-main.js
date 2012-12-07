/**
 * Start Of rtSocial Plugin JS
 */

jQuery( document ).ready( function() {
    /**
     * If Twitter is checked, get Twitter Counts
     */
    if ( args.twitter == 1 && args.button_style != 'icon' && args.hide_count != 1) {
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
    if ( args.facebook == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        var rtsocial_urls = []; /* create an array of urls */
        jQuery( '.rtsocial-container' ).each( function() {
            rtsocial_urls.push( jQuery( 'a.perma-link', this ).attr( 'href' ) );
        } );
        /* End of .rtsocial-container */

        /**
         * Facebook Data
         */
        var rtsocial_fburl =  'https://api.facebook.com/method/fql.query?query=select%20%20share_count%20from%20link_stat%20where%20url="'+ rtsocial_urls.join() +'"&format=json';
        var rtsocial_fbcounts = new Array();
        jQuery.getJSON( rtsocial_fburl, function( fbres ) {
            jQuery.each( fbres, function( key, value ) {
                jQuery.each( value, function( sub_key, sub_value ) {
                    rtsocial_fbcounts[rtsocial_urls.join()] = sub_value;
                } );
            rtsocial_update_fbcount( rtsocial_fbcounts );
            } );
        } );
        /* End of Callback function in JSON */
    }
    /* End of Facebook */

    /* Pinterest */
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
    
    /* LinkedIn */
    if ( args.linkedin == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var rtsocial_linurl = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_lincount_url = 'http://www.linkedin.com/countserv/count/share?callback=?&url='+rtsocial_linurl;
            jQuery.getJSON( rtsocial_lincount_url, function( pinres ) {
                jQuery('.rtsocial-linkedin-count', paNode).text( ( pinres['count'] ) ? ( pinres['count'] ) : '0' );
            });
        });
    }
    
    /* G+ Share */
    if ( args.gplus == 1 && args.button_style != 'icon' && args.hide_count != 1) {
        jQuery( '.rtsocial-container' ).each( function() {
            var paNode = this;
            var rtsocial_gplusurl = jQuery( this ).find( '.perma-link' ).attr( 'href' );
            var rtsocial_gplusdata = {
                action: 'rtsocial_gplus',
                url: rtsocial_gplusurl
            };
            
            jQuery.post( ajaxurl, rtsocial_gplusdata, function( gplusres ) {
                jQuery('.rtsocial-gplus-count', paNode).text( ( gplusres ) ? ( gplusres ) : '0' );
            });
        });
    }

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
                    ui.item.find('input').attr('name', 'rtsocial_plugin_options[active][]');
                }
            }
        }).disableSelection();
        /* End of Sortable */
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