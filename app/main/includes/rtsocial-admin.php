<?php

//add_action('admin_init','rtsocial_on_load_page');

/**
*   Applies WordPress metabox funtionality to metaboxes
*
*
*/
function rtsocial_on_load_page() {

    /* Javascripts loaded to allow drag/drop, expand/collapse and hide/show of boxes. */
    wp_enqueue_script( 'common' );
    wp_enqueue_script( 'wp-lists' );
    wp_enqueue_script( 'postbox' );

    // Check to see which tab we are on
    $tab = isset( $_GET['page'] )  ? $_GET['page'] : "rtsocial-revised-options";

    switch ( $tab ) {
        case 'bp-media-addons' :
            // All metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
            add_meta_box('bp_media_addons_list_metabox',__('BuddyPress Media Addons for Audio/Video Conversion','bp-media'),'bp_media_addons_list','bp-media-settings', 'normal', 'core' );
            break;
        case 'bp-media-support' :
            // All metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
            add_meta_box( 'bp_media_support_metabox', __('BuddyPress Media Support', 'rtPanel'), 'bp_media_support', 'bp-media-settings', 'normal', 'core' );
            add_meta_box( 'bp_media_form_report_metabox', __('Submit a request form', 'rtPanel'), 'bp_media_send_request', 'bp-media-settings', 'normal', 'core' );
            break;
        case $tab :
            // All metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
            add_meta_box( 'rtsocial_settings_metabox', __('RTSocial Settings', 'rtPanel'), 'rtsocial_admin_menu', 'rtsocial-revised-options', 'normal', 'core' );
            //add_meta_box( 'bp_media_options_metabox', __('Spread the word', 'rtPanel'), 'bp_media_settings_options', 'bp-media-settings', 'normal', 'core' );
            //add_meta_box( 'bp_media_other_options_metabox', __('BuddyPress Media Other options', 'rtPanel'), 'bp_media_settings_other_options', 'bp-media-settings', 'normal', 'core' );
            break;
    }
}

function rtsocial_settings_page(){
    
    
}
//add_action( bp_core_admin_hook(), 'rtsocial_settings_page');