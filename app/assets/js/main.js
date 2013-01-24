/**
 * RTSocial Javascript.
 */

jQuery(document).ready(function(){

    /* Get G+ count via ajax */
    if( jQuery('.rt-google').length > 0 && rts_ajax.hide_ct == 0 ){
        
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
    if( jQuery('.rt-fb-share').length > 0 && rts_ajax.hide_ct == 0 ){

        var param_arr = new Array();
        param_arr['id'] = '';        
        share_count_handler( '.rts-rt-fb-share-count', 'http://graph.facebook.com', param_arr, 1 );        
    }

    /* Get twitter counts */
    if( jQuery('.rt-twitter').length > 0 && rts_ajax.hide_ct == 0 ){

        var param_arr = new Array();
        param_arr['callback'] = '?';
        param_arr['url'] = '';
        
        share_count_handler( '.rts-rt-twitter-count', 'http://cdn.api.twitter.com/1/urls/count.json', param_arr, 0 );

    }

    /* Get linkedIn counts */
    if( jQuery('.rt-linked-in').length > 0 && rts_ajax.hide_ct == 0 ){
        
        var param_arr = new Array();
        param_arr['callback'] = '?';
        param_arr['format'] = 'jsonp';
        param_arr['url'] = '';
        
        share_count_handler( '.rts-rt-linked-in-count', 'http://www.linkedin.com/countserv/count/share', param_arr, 0 );

    }

    /* Get Pinterest counts */
    if( jQuery('.rt-pinterest').length > 0 && rts_ajax.hide_ct == 0 ){

        var param_arr = new Array();
        param_arr['callback'] = '?';
        param_arr['url'] = '';
        
        share_count_handler( '.rts-rt-pinterest-count', 'http://api.pinterest.com/v1/urls/count.json', param_arr, 0 );
 
    }


});/* Document.ready ends here */

/* Function to handle counts of various buttons */
function share_count_handler( counter_div, reqst_url, param_arr, is_fb ){

    jQuery('.rtsocial-list').each(function(){

            /* Initialise share count variable to 0 */
            var share_ct = 0;
            var permlink_elem = jQuery(this).find('.perma-link a').attr('href');
            var current_list = jQuery(this);
            
            if( typeof(param_arr['url']) != 'undefined' ){
                
                param_arr['url'] = permlink_elem;
            }else
                if( typeof(param_arr['id']) != 'undefined' && is_fb==1 ){
                param_arr['id'] = permlink_elem;
            }
            
            /* Build query string from array */
            var query_str = ''; // query string
            var flag = true; // for adding parameters
            var len = jQuery(param_arr).length; // total length of array
            var counter = 1;

            for( key in param_arr ){

                if( query_str=='' ){

                    query_str = query_str + '?' + key + '=' + param_arr[key];
                }else{
                    query_str = query_str + '&' + key + '=' + param_arr[key];
                }
                counter++;
            }

            var rts_share_cnt_url =  reqst_url+query_str;
            
            jQuery.getJSON( rts_share_cnt_url, function(data){
                
                if( is_fb==1 )
                    share_ct = data.shares;
                else
                    share_ct = data.count;
                jQuery(current_list).find(counter_div).html(share_ct);
            } );
    });
}

jQuery( window ).on('load', function(){

   /* Show share counts only when window gets loaded completely */
    jQuery('.rtsocial-list .rts-count').each(function(){

       jQuery(this).css( 'visibility' , 'visible' );
    });
});