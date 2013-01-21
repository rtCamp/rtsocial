/**
 * RTSocial Javascript.
 */

jQuery(document).ready(function(){
    
    if( jQuery('.rt-fb-share').length > 0 ){
        
        jQuery('.rtsocial-list').each(function(){

            var elem = jQuery(this).find('.perma-link a').attr('href');
            var rtsocial_fburl =  'https://api.facebook.com/method/fql.query?callback=?&query=select url,share_count from link_stat where url in("'+elem+'")&format=xml';
            
            jQuery.getJSON( rtsocial_fburl, function(data){
                
            } );
        })
    }
});

jQuery( window ).load( function(){

   /* Show share counts only when window gets loaded completely */
    jQuery('.rtsocial-list .rts-count').each(function(){

       jQuery(this).css( 'visibility' , 'visible' );
    });
});