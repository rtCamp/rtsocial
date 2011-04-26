<?php
/*
Plugin Name: rtSocial Share
Author: rtCamp, rahul286, rutwick
Author URI: http://rtcamp.com
Version: 1.0
Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
Tags: rtcamp, social, social sharing
*/

add_action('admin_menu', 'rtsocial_admin');
register_activation_hook(__FILE__, 'rtsocial_set_defaults');
register_deactivation_hook(__FILE__, 'rtsocial_reset_defaults');

//add_action('media_buttons_context', 'my_media');

function my_media($context)
{
	$test = '<a href=\'www.google.com\'>Google it</a>';
	
	return $context.$test;
}


function rtsocial_admin()
{
	add_options_page('rtSocial Options Page', 'rtSocial Options', 10, 'rtsocial-options', 'rtsocial_admin_fn');
}

function rtsocial_admin_fn()
{
	?>
	<div class='wrap'>
		<h2><?php _e('rtSocial Options'); ?></h2>
		<p class="clear"></p>
		
       <div id="content_block" class="align_left">
        <form action='options.php' method='post'>
		
		<?php
			settings_fields('rtsocial_plugin_options');
			do_settings_sections(__FILE__);
			$options = get_option('rtsocial_plugin_options');
		?>
		<div class="metabox-holder align_left rtsocial" id='rtsocial'>
        <div class="postbox-container">
		<div class="meta-box-sortables ui-sortable">
		   
		   <div class="postbox">
			<div title="Click to toggle" class="handlediv"><br /></div>
				<h3 class='hndle'> Select position of social-media buttons</h3>
			<div class='inside'>
			<table class='form-table'>
				<tr>
					<td>
						<input value='top' name='rtsocial_plugin_options[placement_options_set]' id='rtsocial-top-display' type='radio' <?php echo ($options['placement_options_set'] == 'top') ? ' checked="checked" ' : ''; ?> />
					</td>
					<th><label for='rtsocial-top-display'>Top</label></th> 
					<td>
						Social-media sharing buttons will appear below post-title and above post-content
					</td>
				</tr>
				
				<tr>
					<td>
						<input value='bottom' name='rtsocial_plugin_options[placement_options_set]' id='rtsocial-bottom-display' type='radio' <?php echo ($options['placement_options_set'] == 'bottom') ? ' checked="checked" ' : ''; ?>/>
					</td>
					<th><label for='rtsocial-bottom-display'> Bottom</label></th>
					<td>
						Social-media sharing buttons will appear after (below) post-content
					</td>
				</tr>
				
				<tr>
					<td>
						<input value='manual' name='rtsocial_plugin_options[placement_options_set]' id='rtsocial-manual-display' type='radio' <?php echo ($options['placement_options_set']== 'manual') ? ' checked="checked" ' : '';?>/>
						
					</td>
					<th id='display_manual_th'><label for='rtsocial-manual-display'>Manual</label></th>
					<td>
						For manual placement, please use this function call in your template: <br /><span><b>&lt;?php if(function_exists('rtsocial')) { echo rtsocial(); } ?&gt;</b></span>
					</td>
				</tr>
				
			</table>
			</div>
			</div>
		   
		   
		   <div class="postbox">
			<div title="Click to toggle" class="handlediv"><br /></div>
				<h3 class='hndle'> Button Style</h3>
			<div class='inside'>
			<table class='form-table rtsocial_options_table'>
				<tr>
					<td>
						<input value='vertical' id='display_vertical_input' name='rtsocial_plugin_options[display_options_set]' type='radio' <?php echo ($options['display_options_set']== "vertical") ? ' checked="checked" ' : '';?>/>
					</td>
					<td id='display_vertical'>
						<label for='display_vertical_input'>
							<span id='rtsocial-display-vertical-twitter-sample' class='vertical-box'>
								<span id='rtsocial-display-vertical-twitter-sample-count'>
								</span>
								
								<span id='rtsocial-display-vertical-twitter-sample-button' class='rtsocial-twitter-button'>
								</span>
							</span>
							
							<span id='rtsocial-display-vertical-facebook-sample' class='vertical-box'>
								<span id='rtsocial-display-vertical-facebook-sample-count'>
								</span>
								
								<span id='rtsocial-display-vertical-facebook-sample-button' class='rtsocial-facebook-button'>
								</span>
							</span>
							
						</label>
					</td>
					
					<td>
					<input value='horizontal' id='display_horizontal_input' name='rtsocial_plugin_options[display_options_set]' type='radio' <?php echo ($options['display_options_set']== "horizontal") ? ' checked="checked" ' : '';?>/>
					</td>
					<td id='display_horizontal'>
						<label for='display_horizontal_input'>
							<span id='rtsocial-display-horizontal-twitter-sample' class='vertical-box'>
								<span id='rtsocial-display-horizontal-twitter-sample-button' class='rtsocial-twitter-button'>
								</span>
								<span id='rtsocial-display-horizontal-twitter-sample-count'>
								</span>
							</span>
							
							<span id='rtsocial-display-horizontal-facebook-sample' class='vertical-box'>
								<span id='rtsocial-display-horizontal-facebook-sample-button' class='rtsocial-facebook-button'>
								</span>
								<span id='rtsocial-display-horizontal-facebook-sample-count'>
								</span>							
							</span>
							
						</label>
					</td>
				</tr>
			</table>
			<table class='form-table'>
				<tr>
				<th>
					Select Alignment:
				</th>
				<td>
					<input value='right' name='rtsocial_plugin_options[alignment_options_set]' id='align_right_check' type='radio' <?php echo ($options['alignment_options_set'] == 'right') ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for='align_right_check'>Right</label>
				</td>
				<td>	
					<input value='left' name='rtsocial_plugin_options[alignment_options_set]' id='align_left_check' type='radio' <?php echo ($options['alignment_options_set'] == 'left') ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for='align_left_check'>Left</label>
				</td>
				<td>	
					<input value='center' name='rtsocial_plugin_options[alignment_options_set]' id='align_center_check' type='radio' <?php echo ($options['alignment_options_set']== 'center') ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for='align_center_check'>Center</label>
				</td>
				<td>	
					<input  value='none' name='rtsocial_plugin_options[alignment_options_set]' id='align_none_check' type='radio' <?php echo ($options['alignment_options_set']== 'none') ? ' checked="checked" ' : ''; ?> />&nbsp;&nbsp;&nbsp;&nbsp;<label for='align_none_check'>None</label>
				</td>
				</tr>
			</table>
			</div>
			</div>
		   
			<div class="postbox" id='tw_box'>
			<div title="Click to toggle" class="handlediv"><br /></div>
				<h3 class='hndle'> Twitter Button Settings </h3>
			<div class='inside'>
			<table class='form-table'>
				<tr>
					<th><span id='rtsocial-twitter'></span></th><td><input id='tw_chk' name='rtsocial_plugin_options[tw_chk]' type='checkbox' <?php echo ($options['tw_chk']) ? ' checked="checked" ' : '' ?> />&nbsp;&nbsp;&nbsp;<label id='tw_display_chk_label' for='tw_chk'>Display Twitter Tweet Button</label></td><td> </td>
				</tr>
				
				<tr class='tw_row'>
					<th>Twitter Handle:</th><td><input type='text' value="<?php echo $options['tw_handle'] ?>" id='tw_handle' name='rtsocial_plugin_options[tw_handle]'/></td>
				</tr>
				
				<tr class='tw_row'>
					<th>Related Twitter Handle:</th><td><input type='text' value="<?php echo $options['tw_related_handle'] ?>" id='tw_related_handle' name='rtsocial_plugin_options[tw_related_handle]'/></td>
				</tr>
			</table>
			</div>
			</div>
			
			<?php //echo "<td><input type='checkbox' id='tw_handle_usermeta'/>&nbsp;&nbsp;&nbsp;<label for='tw_handle_usermeta'>This is the name of usermeta key</label></td>"; ?>
						
			<div class="postbox">
			<div title="Click to toggle" class="handlediv"><br /></div>
				<h3 class='hndle'> Facebook Button Settings </h3>
			<div class='inside'>
			<table class='form-table'>
				<tr class='fb_title'>
					<th><span id='rtsocial-facebook'></span></th><td><input id='fb_chk' name='rtsocial_plugin_options[fb_chk]' type='checkbox' <?php echo ($options['fb_chk']) ? ' checked="checked" ' : '' ?> />&nbsp;&nbsp;&nbsp;<label id='fb_display_chk_label' for='fb_chk'>Display Facebook Sharing Button</label></td>
				</tr>
				
				<tr class='fb_row' >
					<th>Facebook Button Style:</th><td>
												<input type='radio'  name='rtsocial_plugin_options[fb_style]' value='like_light' id='rtsocial-like-light-input' <?php echo ($options['fb_style']== "like_light") ? ' checked="checked" ' : '' ?>></input><label for='rtsocial-like-light-input'><a id='rtsocial-like-light'></a></label>
											</td>
											<td>
												<input type='radio' name='rtsocial_plugin_options[fb_style]' value='recommend_light' id='rtsocial-recommend-light-input' <?php echo ($options['fb_style']== "recommend_light") ? ' checked="checked" ' : '' ?>></input><label for='rtsocial-recommend-light-input'><a id='rtsocial-recommend-light'></a></label>
											</td>
											
				</tr>
				
				<tr class='fb_row'>
					<th></th>				
											<td>
												<input type='radio'  name='rtsocial_plugin_options[fb_style]' value='like_dark' id='rtsocial-like-dark-input' <?php echo ($options['fb_style']== "like_dark") ? ' checked="checked" ' : '' ?>></input><label for='rtsocial-like-dark-input'><a id='rtsocial-like-dark'></a></label>
											</td>
											<td>
												<input type='radio' name='rtsocial_plugin_options[fb_style]' value='recommend_dark' id='rtsocial-recommend-dark-input' <?php echo ($options['fb_style']== "recommend_dark") ? ' checked="checked" ' : '' ?>></input><label for='rtsocial-recommend-dark-input'><a id='rtsocial-recommend-dark'></a></label>
											</td>
				</tr>
				
				<tr class='fb_row'>
					<th></th>				<td>
												<input type='radio' name='rtsocial_plugin_options[fb_style]' value='share' id='rtsocial-share-input' <?php echo ($options['fb_style']== "share") ? ' checked="checked" ' : '' ?>></input><label for='rtsocial-share-input'><a id='rtsocial-share-plain'></a></label>
											</td>
				</tr>
						
			</table>
			</div>
			</div>			
						
			<p class='submit'>
				<input type='submit' name='save' class='button-primary xyz' value='<?php esc_attr_e("Save"); ?>' />
			</p>
			
		</div>
		</div>
		</div>
		</form>
	  </div>	
			<div id="ads_block" class="metabox-holder align_left">
                <div class="postbox-container">
                    <div class="meta-box-sortables ui-sortable">
						
						<div class="postbox" id="social">
                            <div title="Click to toggle" class="handlediv"><br /></div>
                            <h3 class="hndle"><span><strong class="red">Getting Social is Good</strong></span></h3>
                            <div class="inside" style="text-align:center;">
                                <a href="http://www.facebook.com/BloggertoWordpress" target="_blank" title="Become a fan on Facebook"><img src="<?php echo WP_PLUGIN_URL; ?>/rt-social/images/facebook.png" alt="Twitter" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="http://twitter.com/bloggertowp" target="_blank" title="Follow us on Twitter"><img src="<?php echo WP_PLUGIN_URL; ?>/rt-social/images/twitter.png" alt="Facebook" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="http://feeds.feedburner.com/Blogger-to-Wordpress" target="_blank" title="Subscribe to our feeds"><img src="<?php echo WP_PLUGIN_URL; ?>/rt-social/images/rss.png" alt="RSS Feeds" /></a>
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
	                                <input type="hidden" name="item_name" value="Blogger To WordPress Migration" />
	                                <input type="hidden" name="no_note" value="0" />
	                                <input type="hidden" name="currency_code" value="USD" />
	                                <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest" />
	                                <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />
	                                <img border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="pixel" />
	                                </form>                                                                          
                                </div>
                                <div class="rtsocial-share" style="text-align:center; width: 127px; margin: 2px auto">
	    		                	<div class="rt-facebook" style="float:left; margin-right:5px;">	
		                                <a style=" text-align:center;" name="fb_share" type="box_count" share_url="http://bloggertowp.org/blogger-to-wordpress-redirection-plugin/"></a>
	                            	</div>
	    		                	<div class="rt-twitter" style="">	
										<a href="http://twitter.com/share"  class="twitter-share-button" data-text="Blogger to WordPress Redirection Plugin"  data-url="http://bloggertowp.org/blogger-to-wordpress-redirection-plugin/" data-count="vertical" data-via="bloggertowp">Tweet</a> 
										<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
									</div>
									<div class="clear"></div>							
								</div>	
                            </div><!-- end of .inside -->
                        </div>

                        <div class="postbox" id="support">
                            <div title="Click to toggle" class="handlediv"><br /></div>
                            <h3 class="hndle"><span><strong class="red">Free Support</strong></span></h3>
                            <div class="inside">
                            If you have any problems with this plugin or good ideas for improvements, please talk about them in the <a href="http://forum.bloggertowp.org/" target="_blank">Support forums</a>.
                            </div>
                        </div>

                  </div>
                </div>
            </div>
	
	</div>
                      
<?php	
}

add_action('admin_init', 'rtsocial_options_init_fn' );
function rtsocial_options_init_fn()
{
	register_setting('rtsocial_plugin_options', 'rtsocial_plugin_options', 'rtsocial_check');	
}

function rtsocial_check($args)
{
	if(isset($args['tw_chk']) && $args['tw_chk'] != '')
	{
		if($args['tw_handle'] == '' || ctype_space($args['tw_handle']))
		{
			add_settings_error('rtsocial_plugin_options', 'tw_handle_blank', 'Twitter handle blank. Using default = <b>wpveda</b>', $type = 'error');
			$args['tw_handle'] = 'wpveda';
		}
	
		if($args['tw_related_handle'] == '' || ctype_space($args['tw_related_handle']))
		{
			add_settings_error('rtsocial_plugin_options', 'tw_related_handle_blank', 'Related Twitter Handle Blank. Using default = <b>rtCamp</b>', $type = 'error' );
			$args['tw_related_handle'] = 'rtCamp';
		}
	}
	
	if( !isset( $args['tw_chk'] ) && !isset( $args['fb_chk'] ) )
	{
		add_settings_error('rtsocial_plugin_options', 'tw_fb_off', 'No network specified. Using both with default settings.', $type = 'error' );
		$args['tw_chk'] = 'on';
		$args['tw_handle'] = 'wpveda';
		$args['tw_related_handle'] = 'rtCamp';
		
		$args['fb_chk'] = 'on';
		$args['fb_style'] = 'like_light';
	}
	
	return $args;
}

function rtsocial_get_errors()
{
	$errors = get_settings_errors();
	echo $errors;
}
//=====================================================================================================

//Inject the widget in the posts================================================================
add_filter('the_content', 'rtsocial_counter');
function rtsocial_counter($content="")
{
	$options = get_option('rtsocial_plugin_options');
	
	global $post;
	global $id;
	
	$layout = "<div class='rtsocial-container rtsocial-container-align-".$options['alignment_options_set']." rtsocial-".$options['display_options_set']."' >";
		
	if(isset($options['tw_chk']))
	{
		$layout .= "<div id='rtsocial-twitter-".$options['display_options_set']."'><div class='rtsocial-twitter-share'>";
		if($options['display_options_set'] == 'horizontal')
		{	
			$layout .= "<div class='rtsocial-twitter-".$options['display_options_set']."-button'><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=".$options['tw_handle']."&related=".$options['tw_related_handle']."&text=".esc_attr(get_the_title($id))."' target=\"_blank\" ></a></div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-twitter-count'></span> </div>";
		}
		else if($options['display_options_set']== 'vertical')
		{
			$layout .= " <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-twitter-count'></span> </div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-twitter-".$options['display_options_set']."-button'><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=".$options['tw_handle']."&related=".$options['tw_related_handle']."&text=".esc_attr(get_the_title($id))."' target=\"_blank\"></a></div>";
		}
		$layout .= "</div></div>";
	}
	
	if(isset($options['fb_chk']))
	{
			$path = plugins_url('rtsocial/images/');
			if($options['fb_style'] == 'like_dark')
			{
				$class = 'rtsocial-fb-like-dark';
			}
			else if($options['fb_style'] == 'recommend_dark')
			{
				$class = 'rtsocial-fb-recommend-dark';
			}
			else if($options['fb_style'] == 'recommend_light')
			{
				$class = 'rtsocial-fb-recommend-light';
			}
			else if($options['fb_style'] == 'share')
			{
				$class = 'rtsocial-fb-share';
			}
			else
			{
				$class = 'rtsocial-fb-like-light';
			}
		
			$layout .= "<div id='rtsocial-fb-".$options['display_options_set']."'><div class='rtsocial-fb'>";
			if($options['display_options_set'] == 'horizontal')
			{
				$layout .= "<div class='rtsocial-fb-".$options['display_options_set']."-button'><a class='rtsocial-fb-button ".$class."' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\" ></a></div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-fb-count'></span> </div>";
			}
			else if($options['display_options_set'] == 'vertical')
			{
				$layout .= "<div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-fb-count'></span></div> <div class='rtsocial-".$options['display_options_set']."-notch' ></div> <div class='rtsocial-fb-".$options['display_options_set']."-button'><a class='rtsocial-fb-button ".$class."' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\" ></a></div> ";
			}
			$layout .= "</div></div>";
	}
	$layout .= "<a rel='nofollow' class='perma-link' href='".get_permalink($id). "' title='".esc_attr(get_the_title($id))."'></a>";
	$layout .= "</div>";
	
	if($options['placement_options_set'] == 'top')
	{
		return $layout.$content;
	}
	else if($options['placement_options_set'] == 'bottom')
	{
		return $content.$layout;
	}
	else
	{
		return $content;
	}
}

//Function for manual layout============================================
function rtsocial()
{
	$options = get_option('rtsocial_plugin_options');
	
	global $post;
	global $id;
	
	$layout = "<div class='rtsocial-container rtsocial-container-align-".$options['alignment_options_set']." rtsocial-".$options['display_options_set']."' >";
		
	if(isset($options['tw_chk']))
	{
		$layout .= "<div id='rtsocial-twitter-".$options['display_options_set']."'><div class='rtsocial-twitter-share'>";
		if($options['display_options_set'] == 'horizontal')
		{	
			$layout .= "<div class='rtsocial-twitter-".$options['display_options_set']."-button'><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=".$options['tw_handle']."&related=".$options['tw_related_handle']."&text=".esc_attr(get_the_title($id))."' target=\"_blank\" ></a></div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-twitter-count'></span> </div>";
		}
		else if($options['display_options_set']== 'vertical')
		{
			$layout .= " <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-twitter-count'></span> </div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-twitter-".$options['display_options_set']."-button'><a class='rtsocial-twitter-button' href= 'http://twitter.com/share?via=".$options['tw_handle']."&related=".$options['tw_related_handle']."&text=".esc_attr(get_the_title($id))."' target=\"_blank\"></a></div>";
		}
		$layout .= "</div></div>";
	}
	
	if(isset($options['fb_chk']))
	{
			$path = plugins_url('rtsocial/images/');
			if($options['fb_style'] == 'like_dark')
			{
				$class = 'rtsocial-fb-like-dark';
			}
			else if($options['fb_style'] == 'recommend_dark')
			{
				$class = 'rtsocial-fb-recommend-dark';
			}
			else if($options['fb_style'] == 'recommend_light')
			{
				$class = 'rtsocial-fb-recommend-light';
			}
			else if($options['fb_style'] == 'share')
			{
				$class = 'rtsocial-fb-share';
			}
			else
			{
				$class = 'rtsocial-fb-like-light';
			}
		
			$layout .= "<div id='rtsocial-fb-".$options['display_options_set']."'><div class='rtsocial-fb'>";
			if($options['display_options_set'] == 'horizontal')
			{
				$layout .= "<div class='rtsocial-fb-".$options['display_options_set']."-button'><a class='rtsocial-fb-button ".$class."' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\" ></a></div> <div class='rtsocial-".$options['display_options_set']."-notch'></div> <div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-fb-count'></span> </div>";
			}
			else if($options['display_options_set'] == 'vertical')
			{
				$layout .= "<div class='rtsocial-".$options['display_options_set']."-count'><span class='rtsocial-fb-count'></span></div> <div class='rtsocial-".$options['display_options_set']."-notch' ></div> <div class='rtsocial-fb-".$options['display_options_set']."-button'><a class='rtsocial-fb-button ".$class."' href=\"http://www.facebook.com/sharer.php?\" target=\"_blank\" ></a></div> ";
			}
			$layout .= "</div></div>";
	}
	$layout .= "<a rel='nofollow' class='perma-link' href='".get_permalink($id). "' title='".esc_attr(get_the_title($id))."'></a>";
	$layout .= "</div>";
	
	return $layout;
}

//Function for setting default values===================================
function rtsocial_set_defaults()
{
	$defaults = array(
			'tw_chk' => 'on',
			'tw_auto' => 'on',
			'fb_chk' => 'on',
			'fb_style' => 'like_light',
			'tw_handle' => 'wpveda',
			'tw_related_handle' => 'rtCamp',
			'placement_options_set' => 'bottom',
			'display_options_set' => 'horizontal',
			'alignment_options_set' =>'right'
		);
	
	if(!get_option('rtsocial_plugin_options'))
	{
		update_option('rtsocial_plugin_options', $defaults);
	}
}

function rtsocial_reset_defaults()
{
	delete_option('rtsocial_plugin_options');
}

add_action('wp_print_styles', 'rtsocial_stylesheet');
function rtsocial_stylesheet() 
{
    $styleUrl = WP_PLUGIN_URL . '/rt-social/styles/style.css';
    $styleFile = WP_PLUGIN_DIR . '/rt-social/styles/style.css';
    
	if ( file_exists($styleFile) ) 
	{
            wp_register_style('styleSheet', $styleUrl);
            wp_enqueue_style( 'styleSheet');
	}
}

add_action('admin_print_styles', 'rtsocial_admin_stylesheet');
function rtsocial_admin_stylesheet()
{
	$styleUrl = WP_PLUGIN_URL . '/rt-social/styles/style.css';
    $styleFile = WP_PLUGIN_DIR . '/rt-social/styles/style.css';
    
	if ( file_exists($styleFile) ) 
	{
            wp_register_style('styleSheet', $styleUrl);
            wp_enqueue_style( 'styleSheet');
	}
	
	if ($_GET['page'] == 'rtsocial-options')
	{ 
		wp_enqueue_script('dashboard'); 
		wp_enqueue_style('dashboard');
	}
}

	wp_enqueue_script('rtss-main', plugins_url('/js/rtss-main.js', __FILE__),  array('jquery'), '1.0', true);
	$options = get_option('rtsocial_plugin_options');
	$args = array();
	if(isset($options['tw_chk']) && $options['tw_chk']=='on')
	{
		$args['twitter'] = true;
	}
	
	if(isset($options['fb_chk']) && $options['fb_chk']=='on')
	{
		$args['facebook'] = true;
	}
	
	$args['path'] = plugins_url('/rt-social/images/');
	wp_localize_script( 'rtss-main', 'args', $args);
?>
