<?php
/*
  Plugin Name: rtSocial
  Plugin URI: https://rtcamp.com/rtsocial/
  Author: rtCamp
  Author URI: https://rtcamp.com/
  Version: 2.2.0
  Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
  Tags: rtcamp, social, sharing, share, social links, twitter, facebook, pin it, pinterest, linkedin, linked in, linked in share, google plus, google plus share, gplus share, g+ button, g+ share, plus one button, social share, social sharing
 */

/*
 * Initial Actions
 */
add_action( 'admin_menu', 'rtsocial_admin' );
add_action( 'admin_notices', 'rts_gplus_notice' );
add_action( 'wp_ajax_rts_hide_g_plus_notice', 'rts_hide_g_plus_notice' );

register_activation_hook( __FILE__, 'rtsocial_set_defaults' );
register_deactivation_hook( __FILE__, 'rtsocial_reset_defaults' );

/*
 * Settings Page
 */

function rtsocial_admin() {
    //Add settings page
    $hook = add_options_page( 'rtSocial Options Page', 'rtSocial Options', 'manage_options', 'rtsocial-options', 'rtsocial_admin_fn' );

    //Enqueue CSS and JS for the options page
    add_action( 'admin_print_scripts-' . $hook, 'rtsocial_assets' );
}

/*
 *  Admin notice for Goggle API key
 */

function rts_gplus_notice() {
    if ( !current_user_can( 'manage_options' ) || ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'rtsocial-options' ) ) {
        return;
    }
    
    if ( is_multisite() ) {
        $site_option = get_option( "rts_g_plus_notice" );
    } else {
        $site_option = get_site_option( "rts_g_plus_notice" );
    }
    
    if ( ( !$site_option || $site_option != "hide" ) ) {
        if ( is_multisite() ) {
            update_option( "rts_g_plus_notice", "show" );
        } else {
            update_site_option( "rts_g_plus_notice", "show" );
        }
        ?>
        <div class="error rts_g_plus_notice">
            <p>
                <b>rtSocial:</b> You need to add Google API key under <a href="<?php echo admin_url( 'options-general.php?page=rtsocial-options' ); ?>">rtSocial admin settings</a> to display google+ count.
                <a href="#" onclick="rts_g_plus_override_notice();" style="float:right">Dismiss</a>
            </p>
        </div>
        <script type="text/javascript">
            function rts_g_plus_override_notice() {
                var data = {action: 'rts_hide_g_plus_notice'};

                jQuery.post(ajaxurl, data, function ( response ) {
                    response = response.trim();

                    if (response === "1")
                        jQuery('.rts_g_plus_notice').remove();
                } );
            }
        </script>
        <?php
    }
}

/*
 *  Hide Admin notice for Goggle API key
 */

function rts_hide_g_plus_notice() {
    if ( is_multisite() ) {
        $update_site_option = update_option( "rts_g_plus_notice", "hide" );
    } else {
        $update_site_option = update_site_option( "rts_g_plus_notice", "hide" );
    }

    if ( $update_site_option ) {
        echo "1";
    } else {
        echo "0";
    }
    
    die();
}

function rtsocial_admin_fn() {
    ?>
    <div class="wrap">
        <h2><?php _e( 'rtSocial Options' ); ?></h2>
        <p class="rt_clear"></p>
        <div id="content_block" class="align_left">
            <form action="options.php" method="post">
                <?php
                settings_fields( 'rtsocial_plugin_options' );
                do_settings_sections( __FILE__ );
                
                $options = get_option( 'rtsocial_plugin_options' );
                $labels = array( 'tw' => 'Twitter', 'fb' => 'Facebook', 'lin' => 'LinkedIn', 'pin' => 'Pinterest', 'gplus' => 'Google+' );
                ?>
                <div class="metabox-holder align_left rtsocial" id="rtsocial">
                    <div class="postbox-container">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br/></div>
                                <h3 class="hndle">rtSocial Settings</h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr id="rtsocial-placement-settings-row">
                                            <th scope="row">Placement Settings:</th>
                                            <td>
                                                <fieldset>
                                                    <label>
                                                        <input value="top" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-top-display" type="radio" <?php echo ( $options[ 'placement_options_set' ] == 'top' ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
                                                        <span>Top</span>
                                                        <br/>
                                                        <span class="description">Social-media sharing buttons will appear below post-title and above post-content</span>
                                                    </label>
                                                    <br/>
                                                    <label>
                                                        <input value="bottom" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-bottom-display" type="radio" <?php echo ( $options[ 'placement_options_set' ] == 'bottom' ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
                                                        <span>Bottom</span>
                                                        <br/>
                                                        <span class="description">Social-media sharing buttons will appear after (below) post-content</span>
                                                    </label>
                                                    <br/>
                                                    <label>
                                                        <input value="manual" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-manual-display" type="radio" <?php echo ( $options[ 'placement_options_set' ] == 'manual' ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
                                                        <span>Manual</span>
                                                        <br/>
                                                        <span class="description">For manual placement, please use this function call in your template: <br/><strong>&lt;?php if ( function_exists( 'rtsocial' ) ) { echo rtsocial(); } ?&gt;</strong></span>
                                                    </label>
                                                </fieldset>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Button Style:</th>
                                            <td>
                                                <table id="rtsocial-button-style-inner">
                                                    <tr>
                                                        <td>
                                                            <input value="vertical" id="display_vertical_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( $options[ 'display_options_set' ] == "vertical" ) ? ' checked="checked" ' : ''; ?> />
                                                        </td>
                                                        <td>
                                                            <div id="rtsocial-display-vertical-sample" class="rtsocial-vertical rtsocial-container-align-none">
                                                                <div class="rtsocial-twitter-vertical">
                                                                    <div class="rtsocial-twitter-vertical-button">
                                                                        <a class="rtsocial-twitter-button" href='https://twitter.com/share?via=<?php echo $options[ 'tw_handle' ] . "&related=" . $options[ 'tw_related_handle' ] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=https://rtpanel.com/support/forum/plugin/"; ?>' rel="nofollow" target="_blank"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="rtsocial-fb-vertical">
                                                                    <div class="rtsocial-vertical-count">
                                                                        <span class="rtsocial-fb-count"></span>
                                                                        <div class="rtsocial-vertical-notch"></div>
                                                                    </div>
                                                                    <div class="rtsocial-fb-vertical-button">
                                                                        <a class="rtsocial-fb-button rtsocial-fb-like-light" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank">Like</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input value="horizontal" id="display_horizontal_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( $options[ 'display_options_set' ] == "horizontal" ) ? ' checked="checked" ' : ''; ?> />
                                                        </td>
                                                        <td>
                                                            <div id="rtsocial-display-horizontal-sample">
                                                                <div class="rtsocial-fb-horizontal">
                                                                    <div class="rtsocial-fb-horizontal-button">
                                                                        <a class="rtsocial-fb-button rtsocial-fb-like-light" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank">Like</a>
                                                                    </div>
                                                                    <div class="rtsocial-horizontal-count">
                                                                        <div class="rtsocial-horizontal-notch"></div>
                                                                        <span class="rtsocial-fb-count"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="rtsocial-twitter-horizontal">
                                                                    <div class="rtsocial-twitter-horizontal-button">
                                                                        <a class="rtsocial-twitter-button" href="https://twitter.com/share?via=<?php echo $options[ 'tw_handle' ] . "&related=" . $options[ 'tw_related_handle' ] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=https://rtpanel.com/support/forum/plugin/"; ?>" rel="nofollow" target="_blank"></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input value="icon" id="display_icon_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( $options[ 'display_options_set' ] == "icon" ) ? ' checked="checked" ' : ''; ?> />
                                                        </td>
                                                        <td>
                                                            <div id="rtsocial-display-icon-sample">
                                                                <div class="rtsocial-fb-icon">
                                                                    <div class="rtsocial-fb-icon-button">
                                                                        <a class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank">Like</a>
                                                                    </div>
                                                                </div>
                                                                <div class="rtsocial-twitter-icon">
                                                                    <div class="rtsocial-twitter-icon-button"><a class="rtsocial-twitter-icon-link" href="https://twitter.com/share?via=<?php echo $options[ 'tw_handle' ] . "&related=" . $options[ 'tw_related_handle' ] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=https://rtpanel.com/support/forum/plugin/"; ?>" rel="nofollow" target="_blank">Tweet</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!--Icons with count-->
                                                    <tr>
                                                        <td>
                                                            <input value="icon-count" id="display_icon_count_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( $options[ 'display_options_set' ] == "icon-count" ) ? ' checked="checked" ' : ''; ?> />
                                                        </td>
                                                        <td>
                                                            <div id="rtsocial-display-icon-count-sample">
                                                                <div class="rtsocial-fb-icon">
                                                                    <div class="rtsocial-fb-icon-button">
                                                                        <a class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank">Like</a>
                                                                    </div>
                                                                    <div class="rtsocial-horizontal-count">
                                                                        <div class="rtsocial-horizontal-notch"></div>
                                                                        <span class="rtsocial-fb-count">0</span>
                                                                    </div>
                                                                </div>
                                                                <div class="rtsocial-twitter-icon">
                                                                    <div class="rtsocial-twitter-icon-button"><a class="rtsocial-twitter-icon-link" href="https://twitter.com/share?via=<?php echo $options[ 'tw_handle' ] . "&related=" . $options[ 'tw_related_handle' ] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=https://rtpanel.com/support/forum/plugin/"; ?>" rel="nofollow" target="_blank">Tweet</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Alignment Settings:</th>
                                            <td>
                                                <fieldset>
                                                    <label>
                                                        <input value="left" name='rtsocial_plugin_options[alignment_options_set]' id="align_left_check" type="radio" <?php echo ( $options[ 'alignment_options_set' ] == 'left' ) ? ' checked="checked" ' : ''; ?> />
                                                        <span>Left</span>
                                                    </label>
                                                    <br/>
                                                    <label>
                                                        <input value="center" name='rtsocial_plugin_options[alignment_options_set]' id="align_center_check" type="radio" <?php echo ( $options[ 'alignment_options_set' ] == 'center' ) ? ' checked="checked" ' : ''; ?> />
                                                        <span>Center</span>
                                                    </label>
                                                    <br/>
                                                    <label>
                                                        <input value="right" name='rtsocial_plugin_options[alignment_options_set]' id="align_right_check" type="radio" <?php echo ( $options[ 'alignment_options_set' ] == 'right' ) ? ' checked="checked" ' : ''; ?> />
                                                        <span>Right</span>
                                                    </label>
                                                    <br/>
                                                    <label>
                                                        <input value="none" name='rtsocial_plugin_options[alignment_options_set]' id="align_none_check" type="radio" <?php echo ( $options[ 'alignment_options_set' ] == 'none' ) ? ' checked="checked" ' : ''; ?> />
                                                        <span>None</span>
                                                    </label>
                                                </fieldset>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Your google API key:</th>
                                            <td>
                                                <input type="text" value="<?php if ( isset( $options[ 'google_api_key' ] ) && !empty( $options[ 'google_api_key' ] ) ) { echo $options[ 'google_api_key' ]; } ?>" id="google_api_key" name="rtsocial_plugin_options[google_api_key]" />
                                                <a href="https://developers.google.com/+/api/oauth" target="blank">How to create API key?</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Active Buttons <sup>#</sup>:</th>
                                            <td>
                                                <ul id="rtsocial-sorter-active" class="connectedSortable">
                                                    <?php
                                                    if ( isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) ) {
                                                        foreach ( $options[ 'active' ] as $active ) {
                                                            echo '<li id="rtsocial-ord-' . $active . '" style="cursor: pointer;"><input id="rtsocial-act-' . $active . '" style="display: none;" type="checkbox" name="rtsocial_plugin_options[active][]" value="' . $active . '" checked="checked" /><label for="rtsocial-act-' . $active . '">' . $labels[ $active ] . '</label></li>';
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><span class="description"># Drag buttons around to reorder them OR drop them into 'Inactive' list to disable them. <strong>All buttons cannot be disabled!</strong></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Inactive Buttons <sup>*</sup>:</th>
                                            <td>
                                                <ul id="rtsocial-sorter-inactive" class="connectedSortable">
                                                    <?php
                                                    if ( isset( $options[ 'inactive' ] ) && !empty( $options[ 'inactive' ] ) ) {
                                                        foreach ( $options[ 'inactive' ] as $inactive ) {
                                                            echo '<li id="rtsocial-ord-' . $inactive . '" style="cursor: pointer;"><input id="rtsocial-act-' . $inactive . '" style="display: none;" type="checkbox" name="rtsocial_plugin_options[inactive][]" value="' . $inactive . '" checked="checked" /><label for="rtsocial-act-' . $inactive . '">' . $labels[ $inactive ] . '</label></li>';
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span class="description">* Drop buttons back to 'Active' list to re-enable them.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Hide counts:</th>
                                            <td>
                                                <fieldset>
                                                    <label>
                                                        <input value="1" name='rtsocial_plugin_options[hide_count]' id="hide_count_check" type="checkbox" <?php echo ( isset( $options[ 'hide_count' ] ) && ( $options[ 'hide_count' ] == 1 ) ) ? ' checked="checked" ' : ''; ?> />
                                                        <span>Yes</span>
                                                    </label>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!--Twitter-->
                            <div class="postbox" id="tw_box">
                                <div title="Click to toggle" class="handlediv"><br/></div>
                                <h3 class="hndle">Twitter Button Settings</h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr>
                                            <th>
                                                <span id="rtsocial-twitter"></span>
                                            </th>
                                        </tr>
                                        <tr class="tw_row">
                                            <th>Twitter Handle:</th>
                                            <td>
                                                <input type="text" value="<?php echo $options[ 'tw_handle' ] ?>" id="tw_handle" name="rtsocial_plugin_options[tw_handle]" />
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr class="tw_row">
                                            <th>Related Twitter Handle:</th>
                                            <td>
                                                <input type="text" value="<?php echo $options[ 'tw_related_handle' ] ?>" id="tw_related_handle" name="rtsocial_plugin_options[tw_related_handle]" />
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!--Facebook-->
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br/></div>
                                <h3 class="hndle"> Facebook Button Settings </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr class="fb_title">
                                            <th>
                                                <span id="rtsocial-facebook"></span>
                                            </th>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>Facebook Button Style:</th>
                                            <td>
                                                <input type="radio" name='rtsocial_plugin_options[fb_style]' value="like_light" id="rtsocial-like-light-input" <?php echo ( $options[ 'fb_style' ] == "like_light" ) ? ' checked="checked" ' : '' ?> />
                                                <label for="rtsocial-like-light-input">
                                                    <a id="rtsocial-like-light"></a>
                                                </label>
                                            </td>
                                            <td>
                                                <input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_light" id="rtsocial-recommend-light-input" <?php echo ( $options[ 'fb_style' ] == "recommend_light" ) ? ' checked="checked" ' : '' ?> />
                                                <label for="rtsocial-recommend-light-input">
                                                    <a id="rtsocial-recommend-light"></a>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>&nbsp;</th>
                                            <td>
                                                <input type="radio" name='rtsocial_plugin_options[fb_style]' value="like_dark" id="rtsocial-like-dark-input" <?php echo ( $options[ 'fb_style' ] == "like_dark" ) ? ' checked="checked" ' : '' ?> />
                                                <label for="rtsocial-like-dark-input">
                                                    <a id="rtsocial-like-dark"></a>
                                                </label>
                                            </td>
                                            <td>
                                                <input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_dark" id="rtsocial-recommend-dark-input" <?php echo ( $options[ 'fb_style' ] == "recommend_dark" ) ? ' checked="checked" ' : '' ?> />
                                                <label for="rtsocial-recommend-dark-input">
                                                    <a id="rtsocial-recommend-dark"></a>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>&nbsp;</th>
                                            <td>
                                                <input type="radio" name='rtsocial_plugin_options[fb_style]' value="share" id="rtsocial-share-input" <?php echo ( $options[ 'fb_style' ] == "share" ) ? ' checked="checked" ' : '' ?> />
                                                <label for="rtsocial-share-input">
                                                    <a id="rtsocial-share-plain"></a>
                                                </label>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <p class="submit">
                                <input type="submit" name="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="rtsocial_ads_block" class="metabox-holder align_left">
            <div class="postbox-container">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox" id="social">
                        <div title="Click to toggle" class="handlediv"><br/></div>
                        <h3 class="hndle">
                            <span>
                                <strong>Getting Social is Good</strong>
                            </span>
                        </h3>
                        <div class="inside rt-social-connect">
                            <a href="https://www.facebook.com/rtCamp.solutions" rel="nofollow" target="_blank" title="Become a fan on Facebook" class="rt-sidebar-facebook">Facebook</a>
                            <a href="https://twitter.com/rtcamp" rel="nofollow" target="_blank" title="Follow us on Twitter" class="rt-sidebar-twitter">Twitter</a>
                            <a href="https://feeds.feedburner.com/rtcamp" rel="nofollow" target="_blank" title="Subscribe to our Feeds" class="rt-sidebar-rss">RSS</a>
                        </div>
                    </div>
                    <div class="postbox" id="donations">
                        <div title="Click to toggle" class="handlediv"><br/></div>
                        <h3 class="hndle">
                            <span>
                                <strong>Promote, Donate, Share...</strong>
                            </span>
                        </h3>
                        <div class="inside">
                            Buy coffee/beer for team behind <a href="https://rtcamp.com/rtsocial/" title="rtSocial Plugin">rtSocial</a>.
                            <br/><br/>
                            <div class="rt-paypal" style="text-align:center">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                    <input type="hidden" name="cmd" value="_donations" />
                                    <input type="hidden" name="business" value="paypal@rtcamp.com" />
                                    <input type="hidden" name="lc" value="US" />
                                    <input type="hidden" name="item_name" value="rtSocial" />
                                    <input type="hidden" name="no_note" value="0" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest" />
                                    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
                                    <img border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="pixel" />
                                </form>
                            </div>
                            <div class="rtsocial-share" style="text-align:center; width: 127px; margin: 2px auto">
                                <div class="rt-facebook" style="float:left; margin-right:5px;">
                                    <a style=" text-align:center;" name="fb_share" type="box_count" title="rtSocial: Simple, Smarter & Swifter Social Sharing WordPress Plugin" share_url="https://rtcamp.com/rtsocial/"></a>
                                </div>
                                <div class="rt-twitter" style="">
                                    <a href="https://twitter.com/share" title="rtSocial: Simple, Smarter & Swifter Social Sharing WordPress Plugin" class="twitter-share-button" data-text="rtSocial: Simple, Smarter & Swifter Social Sharing #WordPress #Plugin" data-url="https://rtcamp.com/rtsocial/" data-count="vertical" data-via="rtCamp">Tweet</a>
                                    <script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
                                </div>
                                <div class="rt_clear"></div>
                            </div>
                        </div>
                        <!-- end of .inside -->
                    </div>
                    <div class="postbox" id="support">
                        <div title="Click to toggle" class="handlediv"><br/></div>
                        <h3 class="hndle">
                            <span>
                                <strong>Free Support</strong>
                            </span>
                        </h3>
                        <div class="inside">If you have any problems with this plugin or good ideas for improvements, please talk about them in the <a href="https://rtcamp.com/support/forum/rtsocial/" rel="nofollow" target="_blank" title="free support forums">free support forums</a>.</div>
                    </div>
                    <div class="postbox" id="latest_news">
                        <div title="Click to toggle" class="handlediv"><br/></div>
                        <h3 class="hndle">
                            <span>
                                <strong>Latest News</strong>
                            </span>
                        </h3>
                        <div class="inside"><?php rtsocial_get_feeds(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/*
 * Add the options variable
 */
add_action( 'admin_init', 'rtsocial_options_init_fn' );

function rtsocial_options_init_fn() {
    register_setting( 'rtsocial_plugin_options', 'rtsocial_plugin_options', 'rtsocial_check' );
}

/*
 * Settings sanitisation
 */

function rtsocial_check( $args ) {
    //Just in case the JavaScript for avoiding deactivation of all services fails, this will fix it! ;)
    if ( !isset( $args[ 'active' ] ) || empty( $args[ 'active' ] ) ) {
        add_settings_error( 'rtsocial_plugin_options', 'all_inactive', 'All options inactive! Resetting all as active.', $type = 'error' );
        
        $args[ 'active' ] = array( 'tw', 'fb', 'lin', 'pin', 'gplus' );
        $args[ 'inactive' ] = array();
    }
    
    if ( isset( $args[ 'active' ] ) && in_array( 'gplus', $args[ 'active' ] ) && $args[ 'google_api_key' ] == '' ) {
        if ( is_multisite() ) {
            update_option( "rts_g_plus_notice", "show" );
        } else {
            update_site_option( "rts_g_plus_notice", "show" );
        }
    } else {
        if ( is_multisite() ) {
            update_option( "rts_g_plus_notice", "hide" );
        } else {
            update_site_option( "rts_g_plus_notice", "hide" );
        }
    }

    // put g-plus in inactive state if google api key is not provided
    if ( !isset( $args[ 'google_api_key' ] ) || empty( $args[ 'google_api_key' ] ) ) {
        // google api key is not set and if gplus is in active state put it in inactive state
        if ( ( $key = array_search( 'gplus', $args[ 'active' ] ) ) !== false ) {
            unset( $args[ 'active' ][ $key ] );

            if ( !isset( $args[ 'inactive' ] ) ) {
                $args[ 'inactive' ] = array();
            }
            
            $args[ 'inactive' ][] = 'gplus';
        }

        if ( is_multisite() ) {
            update_option( "rts_g_plus_notice", "show" );
        } else {
            update_site_option( "rts_g_plus_notice", "show" );
        }
    }

    return $args;
}

/*
 * Print the sanitisation errors
 */

function rtsocial_get_errors() {
    $errors = get_settings_errors();
    
    echo $errors;
}

/*
 * Inject the widget in the posts
 */

add_filter( 'the_content', 'rtsocial_counter' );
add_filter( 'the_excerpt', 'rtsocial_counter' );

function rtsocial_dyna( $content ) {
    if ( is_single() ) {
        return rtsocial_counter( $content );
    } else {
        return $content;
    }
}

function rtsocial_counter( $content = '' ) {
	global $post;
	
    //Working issue on attachment page
    if ( is_attachment() ) {
        return $content;
    }
	
	//Check for excluded page
 	$is_visible = get_post_meta( $post->ID, '_rtsocial_visibility', true );
 	if( ! empty( $is_visible ) ) {
 		return $content;
	}

    $options = get_option( 'rtsocial_plugin_options' );
    
    $rtslink = urlencode( apply_filters( "rtsocial_permalink", get_permalink( $post->ID ), $post->ID, $post ) );
    $rtstitle = rt_url_encode( strip_tags( get_the_title( $post->ID ) ) );
    $rtatitle = strip_tags( get_the_title( $post->ID ) );
    //Ordered buttons array
    $active_services = array();

    //Twitter
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'tw', $options[ 'active' ] ) ) {
        $tw = array_search( 'tw', $options[ 'active' ] );
        
        $handle_string = '';
        $handle_string .= ( isset( $options[ 'tw_handle' ] ) && $options[ 'tw_handle' ] != '' ) ? '&via=' . $options[ 'tw_handle' ] : '';
        $handle_string .= ( isset( $options[ 'tw_related_handle' ] ) && $options[ 'tw_related_handle' ] != '' ) ? '&related=' . $options[ 'tw_related_handle' ] : '';
        $tw_layout = '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $tw_layout .= '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title= "Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $tw_layout .= '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $tw_layout .= ' <div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $tw_layout = '<div class="rtsocial-twitter-icon">';
						$tw_layout .= ' <div class="rtsocial-twitter-icon-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
                    }
                }
            }
        }
        
        $tw_layout .= '</div>';
        $active_services[ $tw ] = $tw_layout;
    }
    //Twitter End
    //Facebook
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'fb', $options[ 'active' ] ) ) {
        $fb = array_search( 'fb', $options[ 'active' ] );
        $fb_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
        $path = '';
        $class = '';
        $rt_fb_style = '';
        $path = plugins_url( 'images/', __FILE__ );
        
        if ( $options[ 'fb_style' ] == 'like_dark' ) {
            $class = 'rtsocial-fb-like-dark';
            $rt_fb_style = 'fb-dark';
        } else {
            if ( $options[ 'fb_style' ] == 'recommend_dark' ) {
                $class = 'rtsocial-fb-recommend-dark';
                $rt_fb_style = 'fb-dark';
            } else {
                if ( $options[ 'fb_style' ] == 'recommend_light' ) {
                    $class = 'rtsocial-fb-recommend-light';
                    $rt_fb_style = 'fb-light';
                } else {
                    if ( $options[ 'fb_style' ] == 'share' ) {
                        $class = 'rtsocial-fb-share';
                    } else {
                        $class = 'rtsocial-fb-like-light';
                        $rt_fb_style = 'fb-light';
                    }
                }
            }
        }

        $fb_layout = '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . ' ' . $rt_fb_style . '">';
        $rt_social_text = '';
        
        if ( $options[ 'fb_style' ] == 'like_light' || $options[ 'fb_style' ] == 'like_dark' ) {
            $rt_social_text = 'Like';
        } else {
            if ( $options[ 'fb_style' ] == 'recommend_light' || $options[ 'fb_style' ] == 'recommend_dark' ) {
                $rt_social_text = 'Recommend';
            } else {
                $rt_social_text = 'Share';
            }
        }

        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $fb_layout .= '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . ( urlencode( get_permalink( $post->ID ) ) ) . '" rel="nofollow" target="_blank"></a></div>' . $fb_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $fb_layout .= $fb_count . '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . ( urlencode( get_permalink( $post->ID ) ) ) . '" rel="nofollow" target="_blank"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $fb_layout .= ' <div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . ( urlencode( get_permalink( $post->ID ) ) ) . '" target= "_blank"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $fb_layout = '<div class="rtsocial-fb-icon" class="' . $rt_fb_style . '">';
                        $fb_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
                        $fb_layout .= ' <div class="rtsocial-fb-icon-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . ( urlencode( get_permalink( $post->ID ) ) ) . '" target= "_blank"></a></div>' . $fb_count;
                    }
                }
            }
        }
        
        $fb_layout .= '</div>';
        $active_services[ $fb ] = $fb_layout;
    }
    //Facebook End
    //Pinterest
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'pin', $options[ 'active' ] ) ) {
        $pin = array_search( 'pin', $options[ 'active' ] );
        $pin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';

        //Set Pinterest media image
        if ( has_post_thumbnail( $post->ID ) ) {
            //Use post thumbnail if set
            $thumb_details = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
            $thumb_src = $thumb_details[ 0 ];
        } else {
            //Else use a default image
            $thumb_src = plugins_url( 'images/default-pinterest.png', __FILE__ );
        }

        //Set Pinterest description
        $title = $post->post_title;
        $pin_layout = '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $pin_layout .= '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $pin_layout .= $pin_count . '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $pin_layout .= ' <div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $pin_layout = '<div class="rtsocial-pinterest-icon">';
                        $pin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
                        $pin_layout .= ' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
                    }
                }
            }
        }
        
        $pin_layout .= '</div>';
        $active_services[ $pin ] = $pin_layout;
    }
    //Pinterest End
    //LinkedIn
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'lin', $options[ 'active' ] ) ) {
        $lin = array_search( 'lin', $options[ 'active' ] );
        $lin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
        $lin_layout = '<div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $lin_layout .= '<div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink( $post->ID ) ) . '&title=' . urlencode( $rtatitle ) . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $lin_layout .= $lin_count . ' <div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink( $post->ID ) ) . '&title=' . urlencode( $rtatitle ) . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $lin_layout .= ' <div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink( $post->ID ) ) . '&title=' . urlencode( $rtatitle ) . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $lin_layout = '<div class="rtsocial-linkedin-icon">';
                        $lin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
                        $lin_layout .= ' <div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink( $post->ID ) ) . '&title=' . urlencode( $rtatitle ) . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
                    }
                }
            }
        }
        
        $lin_layout .= '</div>';
        $active_services[ $lin ] = $lin_layout;
    }
    //Linked In End
    //G+ Share Button
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'gplus', $options[ 'active' ] ) && isset( $options[ 'google_api_key' ] ) && $options[ 'google_api_key' ] != '' ) {
        $gplus = array_search( 'gplus', $options[ 'active' ] );
        $gplus_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-gplus-count"></span></div>' : '';

        $gplus_layout = '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $gplus_layout .= '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . urlencode( get_permalink( $post->ID ) ) . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>' . $gplus_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $gplus_layout .= $gplus_count . '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . urlencode( get_permalink( $post->ID ) ) . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $gplus_layout .= ' <div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . urlencode( get_permalink( $post->ID ) ) . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $gplus_layout = '<div class="rtsocial-gplus-icon">';
                        $gplus_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-gplus-count"></span></div>' : '';
                        $gplus_layout .= ' <div class="rtsocial-gplus-icon-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . urlencode( get_permalink( $post->ID ) ) . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>' . $gplus_count;
                    }
                }
            }
        }
        
        $gplus_layout .= '</div>';
        $active_services[ $gplus ] = $gplus_layout;
    }
    //G+ Share Button End
    //Sort by indexes
    ksort( $active_services );

    //Form the ordered buttons markup
    $active_services = implode( '', $active_services );
    //Rest of the stuff
    $layout = '<div class="rtsocial-container rtsocial-container-align-' . $options[ 'alignment_options_set' ] . ' rtsocial-' . $options[ 'display_options_set' ] . '">';
    //Append the ordered buttons
    $layout .= $active_services;
    //Hidden permalink
    $layout .= '<a rel="nofollow" class="perma-link" href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '"></a><input type="hidden" name="rts_id" class="rts_id" value="' . $post->ID . '" />' . wp_nonce_field( 'rts_media_' . $post->ID, 'rts_media_nonce', true, false ) . '</div>';
    
    if ( $options[ 'placement_options_set' ] == 'top' ) {
        return $layout . $content;
    } else {
        if ( $options[ 'placement_options_set' ] == 'bottom' ) {
            return $content . $layout;
        } else {
            return $content;
        }
    }
}

/*
 * Function for manual layout
 *
 * Possible options
 *  'active' = array('tw', 'fb', 'lin', 'pin', 'gplus');
 *  'display_options_set' = 'horizontal', 'vertical', 'icon', 'icon-count'
 *  'alignment_options_set' = 'left', 'right', 'center', 'none'
 *  'tw_handle' = 'whateveryouwant'
 *  'tw_related_handle' = 'whateveryouwant'
 *  'fb_style' = 'like_light', 'like_dark', 'recommend_light', 'recommend_dark', 'share'
 */


function rtsocial( $args = array() ) {
    //Working issue on attachment page
    if ( is_attachment() ) {
        return;
    }

    $options = get_option( 'rtsocial_plugin_options' );
    $options = wp_parse_args( $args, $options );

    //If manual mode is selected then avoid this code
    if ( isset( $options ) && !empty( $options ) && $options[ 'placement_options_set' ] != 'manual' ) {
        return;
    }

    global $post;
    
    $post_obj = apply_filters( "rtsocial_post_object", $post );
    $rts_permalink = apply_filters( "rtsocial_permalink", get_permalink( $post_obj->ID ), $post_obj->ID, $post_obj );
    $rtslink = urlencode( $rts_permalink );
    $rtatitle = apply_filters( "rtsocial_title", get_the_title( $post_obj->ID ) );
	$rtatitle = strip_tags( $rtatitle );
    $rtstitle = rt_url_encode( $rtatitle );
    //Ordered buttons
    $active_services = array();

    //Twitter
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'tw', $options[ 'active' ] ) ) {
        $tw = array_search( 'tw', $options[ 'active' ] );
        
        $handle_string = '';
        $handle_string .= ( isset( $options[ 'tw_handle' ] ) && $options[ 'tw_handle' ] != '' ) ? '&via=' . $options[ 'tw_handle' ] : '';
        $handle_string .= ( isset( $options[ 'tw_related_handle' ] ) && $options[ 'tw_related_handle' ] != '' ) ? '&related=' . $options[ 'tw_related_handle' ] : '';
        $tw_layout = '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $tw_layout .= '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $tw_layout .= '<div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $tw_layout .= ' <div class="rtsocial-twitter-' . $options[ 'display_options_set' ] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $tw_layout = '<div class="rtsocial-twitter-icon">';
                        $tw_layout .= ' <div class="rtsocial-twitter-icon-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
                    }
                }
            }
        }
        
        $tw_layout .= '</div>';
        $active_services[ $tw ] = $tw_layout;
    }
    //Twitter End
    //Facebook
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'fb', $options[ 'active' ] ) ) {
        $fb = array_search( 'fb', $options[ 'active' ] );
        $fb_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
        $path = '';
        $class = '';
        $rt_fb_style = '';
        $path = plugins_url( 'images/', __FILE__ );
        
        if ( $options[ 'fb_style' ] == 'like_dark' ) {
            $class = 'rtsocial-fb-like-dark';
            $rt_fb_style = 'fb-dark';
        } else {
            if ( $options[ 'fb_style' ] == 'recommend_dark' ) {
                $class = 'rtsocial-fb-recommend-dark';
                $rt_fb_style = 'fb-dark';
            } else {
                if ( $options[ 'fb_style' ] == 'recommend_light' ) {
                    $class = 'rtsocial-fb-recommend-light';
                    $rt_fb_style = 'fb-light';
                } else {
                    if ( $options[ 'fb_style' ] == 'share' ) {
                        $class = 'rtsocial-fb-share';
                    } else {
                        $class = 'rtsocial-fb-like-light';
                        $rt_fb_style = 'fb-light';
                    }
                }
            }
        }

        $fb_layout = '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . ' ' . $rt_fb_style . '">';
        $rt_social_text = '';
        
        if ( $options[ 'fb_style' ] == 'like_light' || $options[ 'fb_style' ] == 'like_dark' ) {
            $rt_social_text = 'Like';
        } else {
            if ( $options[ 'fb_style' ] == 'recommend_light' || $options[ 'fb_style' ] == 'recommend_dark' ) {
                $rt_social_text = 'Recommend';
            } else {
                $rt_social_text = 'Share';
            }
        }

        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $fb_layout .= '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>' . $fb_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $fb_layout .= $fb_count . '<div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $fb_layout .= ' <div class="rtsocial-fb-' . $options[ 'display_options_set' ] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" target= "_blank"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $fb_layout = '<div class="rtsocial-fb-icon" class="' . $rt_fb_style . '">';
                        $fb_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
                        $fb_layout .= ' <div class="rtsocial-fb-icon-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" target= "_blank"></a></div>' . $fb_count;
                    }
                }
            }
        }
        $fb_layout .= '</div>';
        $active_services[ $fb ] = $fb_layout;
    }
    //Facebook End
    //Pinterest
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'pin', $options[ 'active' ] ) ) {
        $pin = array_search( 'pin', $options[ 'active' ] );
        $pin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';

        //Set Pinterest media image
        if ( has_post_thumbnail( $post_obj->ID ) ) {
            //Use post thumbnail if set
            $thumb_details = wp_get_attachment_image_src( get_post_thumbnail_id( $post_obj->ID ), 'thumbnail' );
            $thumb_src = $thumb_details[ 0 ];
        } else {
            //Else use a default image
            $thumb_src = plugins_url( 'images/default-pinterest.png', __FILE__ );
        }
        
        $thumb_src = apply_filters( 'rtsocial_pinterest_thumb', $thumb_src, $post_obj->ID );
        //Set Pinterest description
        $title = $post_obj->post_title;
        $pin_layout = '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $pin_layout .= '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $pin_layout .= $pin_count . '<div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $pin_layout .= ' <div class="rtsocial-pinterest-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $pin_layout = '<div class="rtsocial-pinterest-icon">';
                        $pin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
                        $pin_layout .= ' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin ' . $rtatitle . '"></a></div>' . $pin_count;
                    }
                }
            }
        }
        
        $pin_layout .= '</div>';
        $active_services[ $pin ] = $pin_layout;
    }
    //Pinterest End
    //LinkedIn
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'lin', $options[ 'active' ] ) ) {
        $lin = array_search( 'lin', $options[ 'active' ] );
        $lin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
        $lin_layout = '<div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $lin_layout .= '<div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $lin_layout .= $lin_count . ' <div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $lin_layout .= ' <div class="rtsocial-linkedin-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $lin_layout = '<div class="rtsocial-linkedin-icon">';
                        $lin_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
                        $lin_layout .= ' <div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
                    }
                }
            }
        }
        
        $lin_layout .= '</div>';
        $active_services[ $lin ] = $lin_layout;
    }
    //Linked In End
    //G+ Share Button
    if ( isset( $options ) && !empty( $options ) && isset( $options[ 'active' ] ) && !empty( $options[ 'active' ] ) && in_array( 'gplus', $options[ 'active' ] ) && isset( $options[ 'google_api_key' ] ) && $options[ 'google_api_key' ] != '' ) {
        $gplus = array_search( 'gplus', $options[ 'active' ] );
        $gplus_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-' . $options[ 'display_options_set' ] . '-count"><div class="rtsocial-' . $options[ 'display_options_set' ] . '-notch"></div><span class="rtsocial-gplus-count"></span></div>' : '';
        $gplus_layout = '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '">';
        
        if ( $options[ 'display_options_set' ] == 'horizontal' ) {
            $gplus_layout .= '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . $rtslink . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>' . $gplus_count;
        } else {
            if ( $options[ 'display_options_set' ] == 'vertical' ) {
                $gplus_layout .= $gplus_count . '<div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . $rtslink . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
            } else {
                if ( $options[ 'display_options_set' ] == 'icon' ) {
                    $gplus_layout .= ' <div class="rtsocial-gplus-' . $options[ 'display_options_set' ] . '-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . $rtslink . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
                } else {
                    if ( $options[ 'display_options_set' ] == 'icon-count' ) {
                        $gplus_layout = '<div class="rtsocial-gplus-icon">';
                        $gplus_count = (!isset( $options[ 'hide_count' ] ) || $options[ 'hide_count' ] != 1 ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-gplus-count"></span></div>' : '';
                        $gplus_layout .= ' <div class="rtsocial-gplus-icon-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . $rtslink . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>' . $gplus_count;
                    }
                }
            }
        }
        
        $gplus_layout .= '</div>';
        $active_services[ $gplus ] = $gplus_layout;
    }
    //G+ Share Button End
    //Sort by indexes
    ksort( $active_services );

    //Form the ordered buttons markup
    $active_services = implode( '', $active_services );
    //Rest of the stuff
    $layout = '<div class="rtsocial-container rtsocial-container-align-' . $options[ 'alignment_options_set' ] . ' rtsocial-' . $options[ 'display_options_set' ] . '">';
    //Append the ordered buttons
    $layout .= $active_services;
    //Hidden permalink
    $layout .= '<a title="' . esc_attr( $rtatitle ) . '" rel="nofollow" class="perma-link" href="' . $rts_permalink . '"></a><input type="hidden" name="rts_id" class="rts_id" value="' . $post_obj->ID . '" />' . wp_nonce_field( 'rts_media_' . $post_obj->ID, 'rts_media_nonce', true, false ) . '</div>';

    return $layout;
}

/*
 * Function for setting default values
 */

function rtsocial_set_defaults() {
    if( is_multisite() ) {
        foreach( wp_get_sites() as $i => $site ) {
            switch_to_blog( $site[ 'blog_id' ] );
            
            $defaults = array(
                'fb_style' => 'like_light',
                'tw_handle' => '',
                'tw_related_handle' => '',
                'placement_options_set' => 'bottom',
                'display_options_set' => 'horizontal',
                'alignment_options_set' => 'right',
                'active' => array( 'tw', 'fb', 'lin', 'pin' ),
                'inactive' => array( 'gplus' )
            );

            if ( !get_option( 'rtsocial_plugin_options' ) ) {
                update_option( 'rtsocial_plugin_options', $defaults );
            }
            
            restore_current_blog();
        }
    } else {
        $defaults = array(
            'fb_style' => 'like_light',
            'tw_handle' => '',
            'tw_related_handle' => '',
            'placement_options_set' => 'bottom',
            'display_options_set' => 'horizontal',
            'alignment_options_set' => 'right',
            'active' => array( 'tw', 'fb', 'lin', 'pin' ),
            'inactive' => array( 'gplus' )
        );

        if ( !get_option( 'rtsocial_plugin_options' ) ) {
            update_option( 'rtsocial_plugin_options', $defaults );
        }
    }
}

/*
 * Delete plugin options
 */

function rtsocial_reset_defaults() {
    if( is_multisite() && is_plugin_active_for_network( 'rtsocial/source.php' ) ) {
        foreach( wp_get_sites() as $i => $site ) {
            switch_to_blog( $site[ 'blog_id' ] );
            
            delete_option( 'rtsocial_plugin_options' );
            
            restore_current_blog();
        }
    } else {
        delete_option( 'rtsocial_plugin_options' );
    }
}

/*
 * Enqueue scripts and styles
 */
//The similar action for the admin page is on line no.26 above!
add_action( 'wp_enqueue_scripts', 'rtsocial_assets' );

function rtsocial_assets() {
    //Dashboard JS and CSS for admin side only
    if ( is_admin() ) {
        wp_enqueue_script( 'dashboard' );
        wp_enqueue_style( 'dashboard' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'rt-fb-share', ( 'https://static.ak.fbcdn.net/connect.php/js/FB.Share' ), '', '', true );
    }

    //Plugin CSS
    wp_enqueue_style( 'styleSheet', plugins_url( 'styles/style.css', __FILE__ ) );
    //Plugin JS
    wp_enqueue_script( 'rtss-main', plugins_url( '/js/rtss-main.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    //Localize Script
    rtsocial_localize_script( 'rtss-main' );
}

/*
 * Localize JS with custom args
 */

function rtsocial_localize_script( $handle ) {
    //Passing arguments to Plugin JS
    $options = get_option( 'rtsocial_plugin_options' );
    $args = array();
    $args[ 'button_style' ] = $options[ 'display_options_set' ];
    $args[ 'hide_count' ] = ( isset( $options[ 'hide_count' ] ) && $options[ 'hide_count' ] == 1 ) ? 1 : 0;
    $args[ 'twitter' ] = false;
    $args[ 'facebook' ] = false;
    $args[ 'pinterest' ] = false;
    $args[ 'linkedin' ] = false;
    $args[ 'gplus' ] = false;

    if ( is_array( $options[ 'active' ] ) ) {
        if ( in_array( 'tw', $options[ 'active' ] ) ) {
            $args[ 'twitter' ] = true;
        }

        if ( in_array( 'fb', $options[ 'active' ] ) ) {
            $args[ 'facebook' ] = true;
        }

        if ( in_array( 'pin', $options[ 'active' ] ) ) {
            $args[ 'pinterest' ] = true;
        }

        if ( in_array( 'lin', $options[ 'active' ] ) ) {
            $args[ 'linkedin' ] = true;
        }

        if ( in_array( 'gplus', $options[ 'active' ] ) ) {
            $args[ 'gplus' ] = true;
        }
    }

    $args[ 'path' ] = plugins_url( 'images/', __FILE__ );
    
    wp_localize_script( $handle, 'args', $args );
}

/*
 * Place in Option List on Settings > Plugins page
 */
add_filter( 'plugin_action_links', 'rtsocial_actlinks', 10, 2 );

function rtsocial_actlinks( $links, $file ) {
    // Static so we don't call plugin_basename on every plugin row.
    static $this_plugin;
    
    if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
    }
    
    if ( $file == $this_plugin ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=rtsocial-options' ) . '">' . __( 'Settings' ) . '</a>';
        
        array_unshift( $links, $settings_link ); // before other links
    }

    return $links;
}

/*
 * Redirect to rtSocial Options Page
 */
register_activation_hook( __FILE__, 'rtsocial_plugin_activate' );
add_action( 'admin_init', 'rtsocial_plugin_redirect' );

function rtsocial_plugin_activate() {
    add_option( 'rtsocial_plugin_do_activation_redirect', true );
}

function rtsocial_plugin_redirect() {
    if ( get_option( 'rtsocial_plugin_do_activation_redirect', false ) ) {
        delete_option( 'rtsocial_plugin_do_activation_redirect' );
        
        if ( !function_exists( 'is_plugin_active_for_network' ) )
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            // Makes sure the plugin is defined before trying to use it

        if ( !is_plugin_active_for_network( 'rtsocial/source.php' ) ) {
            // Plugin is activated
            wp_redirect( admin_url( 'options-general.php?page=rtsocial-options' ) );
        }
    }
}

/*
 * IE Fix
 */
add_action( 'wp_footer', 'rtsocial_ie_fix' );

function rtsocial_ie_fix() {
    ?>
    <!--[if lte IE 7]>
    <style type="text/css">
            .rtsocial-container-align-center #rtsocial-fb-vertical, .rtsocial-container-align-center #rtsocial-twitter-vertical, .rtsocial-container-align-none #rtsocial-fb-vertical, #btowp_img, #btowp_title, .rtsocial-container-align-center #rtsocial-twitter-horizontal, .rtsocial-container-align-center #rtsocial-fb-horizontal, .rtsocial-fb-like-dark, .rtsocial-fb-like-light, .rtsocial-fb-recommend-light, .rtsocial-fb-recommend-dark, .rt-social-connect a {
                    *display: inline;
            }

            #rtsocial-twitter-vertical {
                    max-width: 58px;
            }

            #rtsocial-fb-vertical {
                    max-width: 96px;
            }
    </style>
    <![endif]-->
    <?php
}

/*
 * Function to replace the functionality of PHP's rawurlencode to support titles with special characters in Twitter and Facebook
 */

function rt_url_encode( $string ) {
    $entities = array( '%26%23038%3B', '%26%238211%3B', '%26%238221%3B', '%26%238216%3B', '%26%238217%3B', '%26%238220%3B' );
    $replacements = array( '%26', '%2D', '%22', '%27', '%27', '%22' );

    return str_replace( $entities, $replacements, rawurlencode( str_replace( array( '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;' ), array( '\'', '\'', '"', '"' ), $string ) ) );
}

/*
 * Define AJAX URL
 */
add_action( 'wp_head', 'rtsocial_ajaxurl' );

function rtsocial_ajaxurl() {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    </script>
    <?php
}

/*
 * Google Plus shares count handled via HTTP API
 */
add_action( 'wp_ajax_rtsocial_gplus', 'rtsocial_gplus_handler' );
add_action( 'wp_ajax_nopriv_rtsocial_gplus', 'rtsocial_gplus_handler' );

function rtsocial_gplus_handler() {
    if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'rtsocial_gplus' ) {
        $url = $_POST[ 'url' ];
        $options = get_option( 'rtsocial_plugin_options' );
        
        if ( isset( $options[ 'google_api_key' ] ) && $options[ 'google_api_key' ] != '' ) {
            $key = $options[ 'google_api_key' ];

            // Check for transient
            if ( false === ( $count = get_transient( 'g_plus_count_' . md5( $url ) ) ) ) {
                $response = wp_remote_request( 'https://clients6.google.com/rpc', array(
                    'method' => 'POST',
                    'body' => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode( $url ) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"' . $key . '","apiVersion":"v1"}]',
                    'headers' => array( 'Content-Type' => 'application/json' )
                    )
                );

                // check the response status code
                if ( 200 == wp_remote_retrieve_response_code( $response ) && !empty( $response ) ) {
                    $result = json_decode( wp_remote_retrieve_body( $response ) );
                    $count = intval( $result[ 0 ]->result->metadata->globalCounts->count );

                    // Set transient to expire every 10 minutes
                    set_transient( 'g_plus_count_' . md5( $url ), absint( $count ), 1 * MINUTE_IN_SECONDS );
                    echo $count;
                } else {
                    echo 0;
                }
            } else {
                echo $count;
            }
        }
        
        die( 1 );
    }
}

/*
 * Google Plus shares count handled via CURL
 */

function rtsocial_get_feeds( $feed_url = 'https://rtcamp.com/blog/category/rtsocial/feed/' ) {
    // Get RSS Feed(s)
    require_once( ABSPATH . WPINC . '/feed.php' );
    
    $maxitems = 0;
    // Get a SimplePie feed object from the specified feed source.
    $rss = fetch_feed( $feed_url );
    
    if ( !is_wp_error( $rss ) ) { // Checks that the object is created correctly
        // Figure out how many total items there are, but limit it to 5.
        $maxitems = $rss->get_item_quantity( 5 );
        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items( 0, $maxitems );
    }
    ?>
    <ul>
        <?php
        if ( $maxitems == 0 ) {
            echo '<li>' . __( 'No items', 'bp-media' ) . '.</li>';
        } else {
            // Loop through each feed item and display each item as a hyperlink.
            foreach ( $rss_items as $item ) {
                ?>
                <li>
                    <a href='<?php echo $item->get_permalink(); ?>' title='<?php echo __( 'Posted ', 'bp-media' ) . $item->get_date( 'j F Y | g:i a' ); ?>'><?php echo $item->get_title(); ?></a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <?php
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function rtsocial_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'rtsocial_sectionid',
			__( 'rtSocial', 'myplugin_textdomain' ),
			'rtsocial_meta_box_callback',
			$screen,
			'side',
			'low'
		);
	}
}
add_action( 'add_meta_boxes', 'rtsocial_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function rtsocial_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'rtsocial_meta_box', 'rtsocial_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_rtsocial_visibility', true ); ?>

	<input type="checkbox" id="rtsocial_visibility" name="rtsocial_visibility" value="1" <?php checked( '1', $value ) ?> />
	<label for="rtsocial_visibility">
	<?php esc_html_e( 'Exclude Social Sharing Icons', 'rtsocial' ) ?>
	</label>
<?php }

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function rtsocial_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */


	// Check if our nonce is set.
	if ( ! isset( $_POST['rtsocial_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['rtsocial_meta_box_nonce'], 'rtsocial_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['rtsocial_visibility'] ) ) {
		delete_post_meta( $post_id, '_rtsocial_visibility' );
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['rtsocial_visibility'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_rtsocial_visibility', $my_data );
}
add_action( 'save_post', 'rtsocial_save_meta_box_data' );
