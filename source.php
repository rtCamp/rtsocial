<?php
/*
Plugin Name: rtSocial
Author: rtCamp, rahul286, rutwick
Author URI: http://rtcamp.com
Version: 1.0.2
Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
Tags: rtcamp, social, sharing, share, social links, twitter, facebook, social share, social sharing
*/

add_action( 'admin_menu', 'rtsocial_admin' );
register_activation_hook( __FILE__, 'rtsocial_set_defaults' );
register_deactivation_hook( __FILE__, 'rtsocial_reset_defaults' );

function rtsocial_admin() {
    add_options_page( 'rtSocial Options Page', 'rtSocial Options', 'manage_options', 'rtsocial-options', 'rtsocial_admin_fn' );
}

function rtsocial_admin_fn() { ?>
    <div class="wrap">
        <h2><?php _e( 'rtSocial Options' ); ?></h2>
        <p class="clear"></p>
        <div id="content_block" class="align_left">
            <form action="options.php" method="post"><?php
                settings_fields( 'rtsocial_plugin_options' );
                do_settings_sections( __FILE__ );
                $options = get_option( 'rtsocial_plugin_options' ); ?>
                <div class="metabox-holder align_left rtsocial" id="rtsocial">
                    <div class="postbox-container">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br /></div>
                                <h3 class="hndle">Placement of rtSocial Buttons</h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr>
                                            <td><input value="top" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-top-display" type="radio" <?php echo ( $options['placement_options_set'] == 'top' ) ? ' checked="checked" ' : ''; ?> /></td>
                                            <th><label for="rtsocial-top-display">Top</label></th>
                                            <td>Social-media sharing buttons will appear below post-title and above post-content</td>
                                        </tr>
                                        <tr>
                                            <td><input value="bottom" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-bottom-display" type="radio" <?php echo ( $options['placement_options_set'] == 'bottom' ) ? ' checked="checked" ' : ''; ?> /></td>
                                            <th><label for="rtsocial-bottom-display"> Bottom</label></th>
                                            <td>Social-media sharing buttons will appear after (below) post-content</td>
                                        </tr>
                                        <tr>
                                            <td><input value="manual" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-manual-display" type="radio" <?php echo ( $options['placement_options_set'] == 'manual' ) ? ' checked="checked" ' : ''; ?> /></td>
                                            <th id="display_manual_th"><label for="rtsocial-manual-display">Manual</label></th>
                                            <td>For manual placement, please use this function call in your template: <br /><span class="rtsocial-manual-code"><strong>&lt;?php if ( function_exists( 'rtsocial' ) ) { echo rtsocial(); } ?&gt;</strong></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br /></div>
                                <h3 class="hndle"> Button Style</h3>
                                <div class="inside">
                                    <table class="form-table rtsocial_options_table">
                                        <tr>
                                            <td><input value="vertical" id="display_vertical_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ($options['display_options_set'] == "vertical") ? ' checked="checked" ' : ''; ?> /></td>
                                            <td id="display_vertical">
                                                <div id="rtsocial-display-vertical-sample" class= "rtsocial-vertical rtsocial-container-align-none">
                                                    <div id="rtsocial-twitter-vertical"><div class="rtsocial-vertical-count"><span class="rtsocial-twitter-count"></span></div><div class="rtsocial-twitter-vertical-button"><div class="rtsocial-vertical-notch"></div><a class="rtsocial-twitter-button" href= 'http://twitter.com/share?via=<?php echo $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=http://rtpanel.com/support/forum/plugin/"; ?>' target="_blank"></a></div></div>
                                                    <div id="rtsocial-fb-vertical"><div class="rtsocial-vertical-count"><span class="rtsocial-fb-count"></span></div><div class="rtsocial-fb-vertical-button"><div class="rtsocial-vertical-notch"></div><a class="rtsocial-fb-button rtsocial-fb-like-light" href="http://www.facebook.com/sharer.php?u=http://rtpanel.com/support/forum/plugin/" target="_blank">Like</a></div></div>
                                                </div>
                                            </td>
                                            <td width="130">&nbsp;</td>
                                            <td><input value="horizontal" id="display_horizontal_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ($options['display_options_set'] == "horizontal") ? ' checked="checked" ' : ''; ?> /></td>
                                            <td id="display_horizontal">
                                                <div id="rtsocial-display-horizontal-sample">
                                                    <div id="rtsocial-fb-horizontal">
                                                        <div class="rtsocial-fb-horizontal-button"><a class="rtsocial-fb-button rtsocial-fb-like-light" href="http://www.facebook.com/sharer.php?u=http://rtpanel.com/support/forum/plugin/" target="_blank">Like</a></div><div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>
                                                    </div>
                                                    <div id="rtsocial-twitter-horizontal">
                                                        <div class="rtsocial-twitter-horizontal-button"><a class="rtsocial-twitter-button" href= 'http://twitter.com/share?via=<?php echo $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . esc_attr( "rtSocial... Share Fast!" ) . "&url=http://rtpanel.com/support/forum/plugin/"; ?>' target=\"_blank\" ></a></div><div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-twitter-count"></span></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="form-table">
                                        <tr>
                                            <th>Select Alignment:</th>
                                            <td><input value="left" name='rtsocial_plugin_options[alignment_options_set]' id="align_left_check" type="radio" <?php echo ($options['alignment_options_set'] == 'left' ) ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for="align_left_check">Left</label></td>
                                            <td><input value="center" name='rtsocial_plugin_options[alignment_options_set]' id="align_center_check" type="radio" <?php echo ($options['alignment_options_set'] == 'center' ) ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for="align_center_check">Center</label></td>
                                            <td><input value="right" name='rtsocial_plugin_options[alignment_options_set]' id="align_right_check" type="radio" <?php echo ($options['alignment_options_set'] == 'right' ) ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for="align_right_check">Right</label></td>
                                            <td><input  value="none" name='rtsocial_plugin_options[alignment_options_set]' id="align_none_check" type="radio" <?php echo ($options['alignment_options_set'] == 'none' ) ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for="align_none_check">None</label></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="postbox" id="tw_box">
                                <div title="Click to toggle" class="handlediv"><br /></div>
                                <h3 class="hndle">Twitter Button Settings</h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr>
                                            <th><span id="rtsocial-twitter"></span></th>
                                            <td><input id="tw_chk" name='rtsocial_plugin_options[tw_chk]' type="checkbox" <?php echo ($options['tw_chk']) ? ' checked="checked" ' : '' ?> />&nbsp;&nbsp;&nbsp;<label id="tw_display_chk_label" for="tw_chk">Display Twitter Tweet Button</label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr class="tw_row">
                                            <th>Twitter Handle:</th>
                                            <td><input type="text" value="<?php echo $options['tw_handle'] ?>" id="tw_handle" name="rtsocial_plugin_options[tw_handle]"/></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr class="tw_row">
                                            <th>Related Twitter Handle:</th>
                                            <td><input type="text" value="<?php echo $options['tw_related_handle'] ?>" id="tw_related_handle" name="rtsocial_plugin_options[tw_related_handle]"/></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="postbox">
                                <div title="Click to toggle" class="handlediv"><br /></div>
                                <h3 class="hndle"> Facebook Button Settings </h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <tr class="fb_title">
                                            <th><span id="rtsocial-facebook"></span></th>
                                            <td><input id="fb_chk" name="rtsocial_plugin_options[fb_chk]" type="checkbox" <?php echo ($options['fb_chk']) ? ' checked="checked" ' : '' ?> />&nbsp;&nbsp;&nbsp;<label id="fb_display_chk_label" for="fb_chk">Display Facebook Sharing Button</label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>Facebook Button Style:</th>
                                            <td><input type="radio"  name='rtsocial_plugin_options[fb_style]' value="like_light" id="rtsocial-like-light-input" <?php echo ($options['fb_style'] == "like_light") ? ' checked="checked" ' : '' ?> /><label for="rtsocial-like-light-input"><a id="rtsocial-like-light"></a></label></td>
                                            <td><input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_light" id="rtsocial-recommend-light-input" <?php echo ($options['fb_style'] == "recommend_light") ? ' checked="checked" ' : '' ?> /><label for="rtsocial-recommend-light-input"><a id="rtsocial-recommend-light"></a></label></td>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>&nbsp;</th>
                                            <td><input type="radio"  name='rtsocial_plugin_options[fb_style]' value="like_dark" id="rtsocial-like-dark-input" <?php echo ($options['fb_style'] == "like_dark") ? ' checked="checked" ' : '' ?> /><label for="rtsocial-like-dark-input"><a id="rtsocial-like-dark"></a></label></td>
                                            <td><input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_dark" id="rtsocial-recommend-dark-input" <?php echo ($options['fb_style'] == "recommend_dark") ? ' checked="checked" ' : '' ?> /><label for="rtsocial-recommend-dark-input"><a id="rtsocial-recommend-dark"></a></label></td>
                                        </tr>
                                        <tr class="fb_row">
                                            <th>&nbsp;</th>
                                            <td><input type="radio" name='rtsocial_plugin_options[fb_style]' value="share" id="rtsocial-share-input" <?php echo ($options['fb_style'] == "share") ? ' checked="checked" ' : '' ?> /><label for="rtsocial-share-input"><a id="rtsocial-share-plain"></a></label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <p class="submit"><input type="submit" name="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="rtsocial_ads_block" class="metabox-holder align_left">
            <div class="postbox-container">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox" id="social">
                        <div title="Click to toggle" class="handlediv"><br /></div>
                        <h3 class="hndle"><span><strong class="red">Getting Social is Good</strong></span></h3>
                        <div class="inside rt-social-connect">
                            <a href="http://www.facebook.com/rtPanel" target="_blank" title="Become a fan on Facebook" class="rt-sidebar-facebook">Facebook</a>
                            <a href="http://twitter.com/#!/rtpanel" target="_blank" title="Follow us on Twitter" class="rt-sidebar-twitter">Twitter</a>
                            <a href="http://feeds.feedburner.com/rtpanel" target="_blank" title="Subscribe to our Feeds" class="rt-sidebar-rss">RSS</a>
                        </div>
                    </div>
                    <div class="postbox" id="donations">
                        <div title="Click to toggle" class="handlediv"><br /></div>
                        <h3 class="hndle"><span><strong class="red">Promote, Donate, Share...</strong></span></h3>
                        <div class="inside">
                            A lot of time and effort goes into the development of this plugin. If you find it useful, please consider making a donation, or a review on your blog or sharing this with your friends to help us.<br/><br/>
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
                                    <a style=" text-align:center;" name="fb_share" type="box_count" title="rtPanel WordPress Theme Framework" share_url="http://rtpanel.com/"></a>
                                </div>
                                <div class="rt-twitter" style="">
                                    <a href="http://twitter.com/share" title="rtPanel WordPress Theme Framework" class="twitter-share-button" data-text="rtPanel WordPress Theme Framework"  data-url="http://rtpanel.com/" data-count="vertical" data-via="rtPanel">Tweet</a>
                                    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div><!-- end of .inside -->
                    </div>
                    <div class="postbox" id="support">
                        <div title="Click to toggle" class="handlediv"><br /></div>
                        <h3 class="hndle"><span><strong class="red">Free Support</strong></span></h3>
                        <div class="inside">If you have any problems with this plugin or good ideas for improvements, please talk about them in the <a href="http://rtpanel.com/support/forum/plugin/" target="_blank" title="Support forums">Support forums</a>.</div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
}
add_action( 'admin_init', 'rtsocial_options_init_fn' );

function rtsocial_options_init_fn() {
    register_setting( 'rtsocial_plugin_options', 'rtsocial_plugin_options', 'rtsocial_check' );
}

function rtsocial_check( $args ) {
    if ( isset( $args['tw_chk'] ) && $args['tw_chk'] != '' ) {
        if ( $args['tw_handle'] == '' || ctype_space( $args['tw_handle'] ) ) {
            add_settings_error( 'rtsocial_plugin_options', 'tw_handle_blank', 'Twitter handle blank. Using default = <strong>rtPanel</strong>', $type = 'error' );
            $args['tw_handle'] = 'rtPanel';
        }

        if ( $args['tw_related_handle'] == '' || ctype_space( $args['tw_related_handle'] ) ) {
            add_settings_error( 'rtsocial_plugin_options', 'tw_related_handle_blank', 'Related Twitter Handle Blank. Using default = <strong>rtCamp</strong>', $type = 'error' );
            $args['tw_related_handle'] = 'rtCamp';
        }
    }
    if ( !isset( $args['tw_chk'] ) && !isset( $args['fb_chk'] ) ) {
        add_settings_error( 'rtsocial_plugin_options', 'tw_fb_off', 'No Network Specified. Using both with default settings.', $type = 'error' );
        $args['tw_chk'] = 'on';
        $args['tw_handle'] = 'rtPanel';
        $args['tw_related_handle'] = 'rtCamp';
        $args['fb_chk'] = 'on';
        $args['fb_style'] = 'like_light';
    }
    return $args;
}

function rtsocial_get_errors() {
    $errors = get_settings_errors();
    echo $errors;
}

/*
 * Inject the widget in the posts
 */
add_filter( 'the_content', 'rtsocial_counter' );
add_filter( 'get_the_excerpt', 'rtsocial_counter' );

function rtsocial_counter( $content = '' ) {
    $options = get_option( 'rtsocial_plugin_options' );
    global $post, $id;
    $layout = "<div class='rtsocial-container rtsocial-container-align-" . $options['alignment_options_set'] . " rtsocial-" . $options['display_options_set'] . "' >";

    if ( isset( $options['tw_chk'] ) ) {
        $layout .= "<div id='rtsocial-twitter-" . $options['display_options_set'] . "'>";
        if ( $options['display_options_set'] == 'horizontal' ) {
            $layout .= "<div class='rtsocial-twitter-" . $options['display_options_set'] . "-button'><a title='" . esc_attr( get_the_title( $id ) ) . "' class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=" . $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . rawurlencode( get_the_title( $id ) ) . "' target=\"_blank\" ></a></div><div class='rtsocial-" . $options['display_options_set'] . "-count'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><span class='rtsocial-twitter-count'></span></div>";
        } else if ( $options['display_options_set'] == 'vertical' ) {
            $layout .= " <div class='rtsocial-" . $options['display_options_set'] . "-count'><span class='rtsocial-twitter-count'></span></div><div class='rtsocial-twitter-" . $options['display_options_set'] . "-button'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><a title='" . rawurlencode( get_the_title( $id ) ) . "' class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=" . $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . rawurlencode( get_the_title( $id ) ) . "' target=\"_blank\"></a></div>";
        }
        $layout .= "</div>";
    }

    $path = '';
    $class = '';
    $rt_fb_style = '';
    if ( isset( $options['fb_chk'] ) ) {
        $path = plugins_url( 'images/', __FILE__ );
        if ( $options['fb_style'] == 'like_dark' ) {
            $class = 'rtsocial-fb-like-dark';
            $rt_fb_style = 'fb-dark';
        } else if ($options['fb_style'] == 'recommend_dark' ) {
            $class = 'rtsocial-fb-recommend-dark';
            $rt_fb_style = 'fb-dark';
        } else if ($options['fb_style'] == 'recommend_light' ) {
            $class = 'rtsocial-fb-recommend-light';
            $rt_fb_style = 'fb-light';
        } else if ($options['fb_style'] == 'share' ) {
            $class = 'rtsocial-fb-share';
        } else {
            $class = 'rtsocial-fb-like-light';
            $rt_fb_style = 'fb-light';
        }
        $layout .= "<div id='rtsocial-fb-" . $options['display_options_set'] . "' class='" . $rt_fb_style . "'>";
        $rt_social_text = '';
        if ( $options['fb_style'] == 'like_light' ) {
            $rt_social_text = 'Like';
        } elseif ( $options['fb_style'] == 'like_dark' ) {
            $rt_social_text = 'Like';
        } elseif ( $options['fb_style'] == 'recommend_light' ) {
            $rt_social_text = 'Recommend';
        } else {
            $rt_social_text = 'Recommend';
        }

        if ( $options['display_options_set'] == 'horizontal' ) {
            $layout .= "<div class='rtsocial-fb-" . $options['display_options_set'] . "-button'><a title='" . $rt_social_text . "' class='rtsocial-fb-button " . $class . "' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\">" . $rt_social_text . "</a></div><div class='rtsocial-" . $options['display_options_set'] . "-count'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><span class='rtsocial-fb-count'></span></div>";
        } else if ( $options['display_options_set'] == 'vertical' ) {
            $layout .= "<div class='rtsocial-" . $options['display_options_set'] . "-count'><span class='rtsocial-fb-count'></span></div><div class='rtsocial-fb-" . $options['display_options_set'] . "-button'><div class='rtsocial-" . $options['display_options_set'] . "-notch' ></div><a title='" . $rt_social_text . "' class='rtsocial-fb-button " . $class . "' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\">" . $rt_social_text . "</a></div>";
        }
        $layout .= "</div>";
    }

    $layout .= "<a title='" . esc_attr( get_the_title( $id ) ) . "' rel='nofollow' class='perma-link' href='" . get_permalink( $id ) . "' title='" . esc_attr( get_the_title( $id ) ) . "'></a></div>";
    if ( $options['placement_options_set'] == 'top' ) {
        return $layout . $content;
    } else if ( $options['placement_options_set'] == 'bottom' ) {
        return $content . $layout;
    } else {
        return $content;
    }
}

//Function for manual layout============================================
function rtsocial() {
    $options = get_option( 'rtsocial_plugin_options' );
    global $post, $id;
    $layout = "<div class='rtsocial-container rtsocial-container-align-" . $options['alignment_options_set'] . " rtsocial-" . $options['display_options_set'] . "'>";
    $path = '';
    $class = '';
    $rt_fb_style = '';
    if ( isset( $options['fb_chk'] ) ) {
        $path = plugins_url( 'images/', __FILE__ );
        if ( $options['fb_style'] == 'like_dark' ) {
            $class = 'rtsocial-fb-like-dark';
            $rt_fb_style = 'fb-dark';
        } else if ( $options['fb_style'] == 'recommend_dark' ) {
            $class = 'rtsocial-fb-recommend-dark';
            $rt_fb_style = 'fb-dark';
        } else if ( $options['fb_style'] == 'recommend_light' ) {
            $class = 'rtsocial-fb-recommend-light';
            $rt_fb_style = 'fb-light';
        } else if ( $options['fb_style'] == 'share' ) {
            $class = 'rtsocial-fb-share';
            $rt_fb_style = 'rtsocial-fb-share';
        } else {
            $class = 'rtsocial-fb-like-light';
            $rt_fb_style = 'fb-light';
        }

        $rt_social_text = '';
        if ( $options['fb_style'] == 'like_light' ) {
            $rt_social_text = 'Like';
        } elseif ( $options['fb_style'] == 'like_dark' ) {
            $rt_social_text = 'Like';
        } elseif ( $options['fb_style'] == 'recommend_light' ) {
            $rt_social_text = 'Recommend';
        } else {
            $rt_social_text = 'Recommend';
        }

        $layout .= "<div id='rtsocial-fb-" . $options['display_options_set'] . "' class='" . $rt_fb_style . "'>";
        if ( $options['display_options_set'] == 'horizontal' ) {
            $layout .= "<div class='rtsocial-fb-" . $options['display_options_set'] . "-button'><a class='rtsocial-fb-button " . $class . "' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\">" . $rt_social_text . "</a></div><div class='rtsocial-" . $options['display_options_set'] . "-count'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><span class='rtsocial-fb-count'></span></div>";
        } else if ( $options['display_options_set'] == 'vertical' ) {
            $layout .= "<div class='rtsocial-" . $options['display_options_set'] . "-count'><span class='rtsocial-fb-count'></span></div><div class='rtsocial-fb-" . $options['display_options_set'] . "-button'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><a class='rtsocial-fb-button " . $class . "' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\">" . $rt_social_text . "</a></div>";
        }
        $layout .= "</div>";
    }
    if ( isset( $options['tw_chk'] ) ) {
        $layout .= "<div id='rtsocial-twitter-" . $options['display_options_set'] . "'>";
        if ($options['display_options_set'] == 'horizontal' ) {
            $layout .= "<div class='rtsocial-twitter-" . $options['display_options_set'] . "-button'><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=" . $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . rawurlencode(get_the_title($id)) . "' target=\"_blank\" ></a></div><div class='rtsocial-" . $options['display_options_set'] . "-count'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><span class='rtsocial-twitter-count'></span></div>";
        } else if ( $options['display_options_set'] == 'vertical' ) {
            $layout .= " <div class='rtsocial-" . $options['display_options_set'] . "-count'><span class='rtsocial-twitter-count'></span></div><div class='rtsocial-twitter-" . $options['display_options_set'] . "-button'><div class='rtsocial-" . $options['display_options_set'] . "-notch'></div><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=" . $options['tw_handle'] . "&related=" . $options['tw_related_handle'] . "&text=" . rawurlencode(get_the_title($id)) . "' target=\"_blank\"></a></div>";
        }
        $layout .= "</div>";
    }
    $layout .= "<a rel='nofollow' class='perma-link' href='" . get_permalink($id) . "' title='" . esc_attr(get_the_title($id)) . "'></a>";
    $layout .= "</div>";
    return $layout;
}

//Function for setting default values===================================
function rtsocial_set_defaults() {
    $defaults = array(
        'tw_chk'                => 'on',
        'tw_auto'               => 'on',
        'fb_chk'                => 'on',
        'fb_style'              => 'like_light',
        'tw_handle'             => 'rtPanel',
        'tw_related_handle'     => 'rtCamp',
        'placement_options_set' => 'bottom',
        'display_options_set'   => 'horizontal',
        'alignment_options_set' => 'right'
    );
    if ( !get_option( 'rtsocial_plugin_options' ) ) {
        update_option( 'rtsocial_plugin_options', $defaults);
    }
}
function rtsocial_reset_defaults() {
    delete_option( 'rtsocial_plugin_options' );
}
add_action( 'wp_print_styles', 'rtsocial_stylesheet' );

function rtsocial_stylesheet() {
    $styleUrl = plugins_url('styles/style.css', __FILE__);
    $styleFile = WP_PLUGIN_DIR . "/" . dirname(plugin_basename(__FILE__))."/styles/style.css";
    if ( file_exists( $styleFile ) ) {
        wp_register_style( 'styleSheet', $styleUrl );
        wp_enqueue_style( 'styleSheet' );
    }
}
add_action( 'admin_print_styles', 'rtsocial_admin_stylesheet' );

function rtsocial_admin_stylesheet() {
    $styleUrl = plugins_url('styles/style.css', __FILE__);
    $styleFile = WP_PLUGIN_DIR . "/" . dirname(plugin_basename(__FILE__))."/styles/style.css";
    if ( file_exists( $styleFile ) ) {
        wp_register_style( 'styleSheet', $styleUrl );
        wp_enqueue_style( 'styleSheet' );
    }
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'rtsocial-options' ) {
        wp_enqueue_script( 'dashboard' );
        wp_enqueue_style( 'dashboard' );
    }
}
wp_enqueue_script( 'rtss-main', plugins_url( '/js/rtss-main.js', __FILE__ ), array( 'jquery' ), '1.0', true );
$options = get_option( 'rtsocial_plugin_options' );
$args = array();
if ( isset( $options['tw_chk'] ) && $options['tw_chk'] == 'on' ) {
    $args['twitter'] = true;
}
if ( isset( $options['fb_chk'] ) && $options['fb_chk'] == 'on' ) {
    $args['facebook'] = true;
}
$args['path'] = plugins_url( 'images/', __FILE__ );
wp_localize_script( 'rtss-main', 'args', $args );

/* Place in Option List on Settings > Plugins page */
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
add_filter( 'plugin_action_links', 'rtsocial_actlinks', 10, 2 );

/* Redirect to rtSocial Option Page */
register_activation_hook( __FILE__, 'rtsocial_plugin_activate' );
add_action( 'admin_init', 'rtsocial_plugin_redirect' );
function rtsocial_plugin_activate() {
    add_option( 'rtsocial_plugin_do_activation_redirect', true );
}
function rtsocial_plugin_redirect() {
    if ( get_option( 'rtsocial_plugin_do_activation_redirect', false ) ) {
        delete_option( 'rtsocial_plugin_do_activation_redirect' );
        wp_redirect( admin_url( 'options-general.php?page=rtsocial-options' ) );
    }
}

add_action( 'wp_footer', 'rtsocial_ie_fix' );
function rtsocial_ie_fix() { ?>
<!--[if lte IE 7]>
<style type="text/css">
    .rtsocial-container-align-center #rtsocial-fb-vertical, .rtsocial-container-align-center #rtsocial-twitter-vertical, .rtsocial-container-align-none #rtsocial-fb-vertical, #btowp_img, #btowp_title, .rtsocial-container-align-center #rtsocial-twitter-horizontal, .rtsocial-container-align-center #rtsocial-fb-horizontal, .rtsocial-fb-like-dark, .rtsocial-fb-like-light, .rtsocial-fb-recommend-light, .rtsocial-fb-recommend-dark, .rt-social-connect a { *display: inline; }
    #rtsocial-twitter-vertical { max-width: 58px; }
    #rtsocial-fb-vertical { max-width: 96px; }
</style>
<![endif]-->
<?php }
