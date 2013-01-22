/**
 * RTSocial Javascript.
 */

jQuery(document).ready(function(){
    
    /* If facebook is present then get the count for it. */
    if( jQuery('.rt-fb-share').length > 0 ){

        jQuery('.rtsocial-list').each(function(){
            
            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_fburl =  'https://api.facebook.com/method/fql.query?callback=?&query=select url,share_count from link_stat where url in("'+permlink_elem+'")&format=json';

            jQuery.getJSON( rtsocial_fburl, function(data){

                share_ct = data[0].share_count;
                jQuery(current_list).find('.rts-rt-fb-share-count').html(share_ct);
            } );

        })
    }

    /* Get twitter counts */
    if( jQuery('.rt-twitter').length > 0 ){
        
        jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_twurl =  'https://urls.api.twitter.com/1/urls/count.json?callback=?&url='+permlink_elem;

            jQuery.getJSON( 'http://cdn.api.twitter.com/1/urls/count.json?callback=?&url='+permlink_elem, function(data){

                share_ct = data.count
                jQuery(current_list).find('.rts-rt-twitter-count').html(share_ct);
            } );
            
        })
    }

    /* Get linkedIn counts */
    if( jQuery('.rt-linked-in').length > 0 ){
        
        jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_twurl =  'http://www.linkedin.com/countserv/count/share?callback=?&format=jsonp&url='+permlink_elem;

            jQuery.getJSON( rtsocial_twurl, function(data){

                share_ct = data.count
                jQuery(current_list).find('.rts-rt-linked-in-count').html(share_ct);
            } );
            
        })
    }

    /* Get Pinterest counts */
    if( jQuery('.rt-pinterest').length > 0 ){
        
        jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_twurl =  'http://api.pinterest.com/v1/urls/count.json?callback=?&url='+permlink_elem;

            jQuery.getJSON( rtsocial_twurl, function(data){

                share_ct = data.count
                jQuery(current_list).find('.rts-rt-pinterest-count').html(share_ct);
            } );
            
        })
    }
    
    
    /* Get G+ count via ajax */
    if( jQuery('.rt-google').length > 0 ){
        
        jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_gplusdata = {
                action: 'rtsocial_gplus',
                url: permlink_elem
            };

            jQuery.post( rts_ajax , rtsocial_gplusdata, function( gplusres ) {
                //jQuery('.rts-rt-google-count', current_list ).html( ( gplusres ) ? ( gplusres ) : '0' );
                gplusres = ( gplusres) ? gplusres : '0';
                jQuery(current_list).find('.rts-rt-google-count').html(gplusres);
            });
            
        })
    }

});

jQuery( window ).load( function(){

   /* Show share counts only when window gets loaded completely */
    jQuery('.rtsocial-list .rts-count').each(function(){

       jQuery(this).css( 'visibility' , 'visible' );
    });
});