/**
 * RTSocial Javascript.
 */

jQuery(document).ready(function(){

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

        jQuery.post( rts_ajax.ajaxurl , rtsocial_gplusdata, function( gplusres ) {

                gplusres = ( gplusres) ? gplusres : '0';
                jQuery(current_list).find('.rts-rt-google-count').html(gplusres);
            });
            
        })
        
    }

    /* If facebook is present then get the count for it. */
    if( jQuery('.rt-fb-share').length > 0 ){

        jQuery('.rtsocial-list').each(function(){
            
            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            var rtsocial_fburl =  'https://api.facebook.com/method/fql.query?callback=?&query=select share_count from link_stat where url in("'+permlink_elem+'")&format=json';

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
            var rtsocial_linkdinurl =  'http://www.linkedin.com/countserv/count/share?callback=?&format=jsonp&url='+permlink_elem;

            jQuery.getJSON( rtsocial_linkdinurl, function(data){

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
            var rtsocial_pintrsturl =  'http://api.pinterest.com/v1/urls/count.json?callback=?&url='+permlink_elem;

            jQuery.getJSON( rtsocial_pintrsturl, function(data){

                share_ct = data.count
                jQuery(current_list).find('.rts-rt-pinterest-count').html(share_ct);
            } );
            
        })

        /*var param_arr = new Array();
        param_arr['callback'] = '?';
        param_arr['url'] = '';
        
        share_count_handler( '.rts-rt-pinterest-count', 'http://api.pinterest.com/v1/urls/count.json', param_arr ); */
        
    }


});/* Document.ready ends here */

/* Function to handle counts of various buttons */
function share_count_handler( counter_div, reqst_url, param_arr ){

    jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            
            if( typeof(param_arr['url']) != 'undefined' ){
                
                param_arr['url'] = permlink_elem;
            }
            
            /* Build query string from array */

            var query_str = ''; // query string
            var flag = true; // for adding parameters
            var len = jQuery(param_arr).length; // total length of array

            jQuery(param_arr).each(function(){
                
                if( !query_str )
                query_str
            })
            
            var rts_share_cnt_url =  reqst_url+'?callback=?&url='+permlink_elem;

            jQuery.getJSON( rts_share_cnt_url, function(data){

                share_ct = data.count
                jQuery(current_list).find('.rts-rt-pinterest-count').html(share_ct);
            } );
            
    });
}

jQuery( window ).on('load', function(){

   /* Show share counts only when window gets loaded completely */
    jQuery('.rtsocial-list .rts-count').each(function(){

       jQuery(this).css( 'visibility' , 'visible' );
    });
});