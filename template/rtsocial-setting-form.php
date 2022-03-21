<?php
/**
 * Admin Settings form for rtSocial.
 *
 * @package rtSocial
 */

?>
<div class="wrap">
	<h2><?php esc_html_e( 'rtSocial Options', 'rtSocial' ); ?></h2>
	<p class="rt_clear"></p>
	<div id="content_block" class="align_left">
		<form action="options.php" method="post">
			<?php
			settings_fields( 'rtsocial_plugin_options' );
			do_settings_sections( __FILE__ );

			$options = get_option( 'rtsocial_plugin_options' );

			$labels = array(
				'tw'  => 'Twitter',
				'fb'  => 'Facebook',
				'lin' => 'LinkedIn',
				'pin' => 'Pinterest',
			);
			?>
			<div class="metabox-holder align_left rtsocial" id="rtsocial">
				<div class="postbox-container">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox">
							<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
							<h3 class="hndle"><?php esc_html_e( 'rtSocial Settings', 'rtSocial' ); ?></h3>
							<div class="inside">
								<table class="form-table">
									<tr id="rtsocial-placement-settings-row">
										<th scope="row"><?php esc_html_e( 'Placement Settings', 'rtSocial' ); ?>:</th>
										<td>
											<fieldset>
												<label>
													<input value="top" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-top-display" type="radio" <?php echo ( 'top' === $options['placement_options_set'] ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
													<span><?php esc_html_e( 'Top', 'rtSocial' ); ?></span>
													<br/>
													<span class="description"><?php esc_html_e( 'Social-media sharing buttons will appear below post-title and above post-content', 'rtSocial' ); ?></span>
												</label>
												<br/>
												<label>
													<input value="bottom" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-bottom-display" type="radio" <?php echo ( 'bottom' === $options['placement_options_set'] ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
													<span><?php esc_html_e( 'Bottom', 'rtSocial' ); ?></span>
													<br/>
													<span class="description"><?php esc_html_e( 'Social-media sharing buttons will appear after (below) post-content', 'rtSocial' ); ?></span>
												</label>
												<br/>
												<label>
													<input value="manual" name='rtsocial_plugin_options[placement_options_set]' id="rtsocial-manual-display" type="radio" <?php echo ( 'manual' === $options['placement_options_set'] ) ? ' checked="checked" ' : ''; ?> style="margin: 7px 0 0 0;" />
													<span><?php esc_html_e( 'Manual', 'rtSocial' ); ?></span>
													<br/>
													<span class="description"><?php esc_html_e( 'For manual placement, please use this function call in your template', 'rtSocial' ); ?>: <br/><strong>&lt;?php if ( function_exists( 'rtsocial' ) ) { echo rtsocial(); } ?&gt;</strong></span>
												</label>
											</fieldset>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Button Style', 'rtSocial' ); ?>:</th>
										<td>
											<table id="rtsocial-button-style-inner">
												<tr>
													<td>
														<input value="vertical" id="display_vertical_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( 'vertical' === $options['display_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													</td>
													<td>
														<div id="rtsocial-display-vertical-sample" class="rtsocial-vertical rtsocial-container-align-none">
															<div class="rtsocial-twitter-vertical">
																<div class="rtsocial-twitter-vertical-button">
																	<a class="rtsocial-twitter-button" href='https://twitter.com/share?via=<?php echo esc_attr( $options['tw_handle'] ) . '&related=' . esc_attr( $options['tw_related_handle'] ) . '&text=' . esc_attr( 'rtSocial... Share Fast!' ) . '&url=https://rtpanel.com/support/forum/plugin/'; ?>' rel="nofollow" target="_blank"></a>
																</div>
															</div>
															<div class="rtsocial-fb-vertical">
																<div class="rtsocial-vertical-count">
																	<span class="rtsocial-fb-count"></span>
																	<div class="rtsocial-vertical-notch"></div>
																</div>
																<div class="rtsocial-fb-vertical-button">
																	<a class="rtsocial-fb-button rtsocial-fb-like-light" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank"><?php esc_html_e( 'Like', 'rtSocial' ); ?></a>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<input value="horizontal" id="display_horizontal_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( 'horizontal' === $options['display_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													</td>
													<td>
														<div id="rtsocial-display-horizontal-sample">
															<div class="rtsocial-fb-horizontal">
																<div class="rtsocial-fb-horizontal-button">
																	<a class="rtsocial-fb-button rtsocial-fb-like-light" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank"><?php esc_html_e( 'Like', 'rtSocial' ); ?></a>
																</div>
																<div class="rtsocial-horizontal-count">
																	<div class="rtsocial-horizontal-notch"></div>
																	<span class="rtsocial-fb-count"></span>
																</div>
															</div>
															<div class="rtsocial-twitter-horizontal">
																<div class="rtsocial-twitter-horizontal-button">
																	<a class="rtsocial-twitter-button" href='https://twitter.com/share?via=<?php echo esc_attr( $options['tw_handle'] ) . '&related=' . esc_attr( $options['tw_related_handle'] ) . '&text=' . esc_attr( 'rtSocial... Share Fast!' ) . '&url=https://rtpanel.com/support/forum/plugin/'; ?>' rel="nofollow" target="_blank"></a>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<input value="icon" id="display_icon_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( 'icon' === $options['display_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													</td>
													<td>
														<div id="rtsocial-display-icon-sample">
															<div class="rtsocial-fb-icon">
																<div class="rtsocial-fb-icon-button">
																	<a class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank"><?php esc_html_e( 'Like', 'rtSocial' ); ?></a>
																</div>
															</div>
															<div class="rtsocial-twitter-icon">
																<div class="rtsocial-twitter-icon-button"><a class="rtsocial-twitter-icon-link" href='https://twitter.com/share?via=<?php echo esc_attr( $options['tw_handle'] ) . '&related=' . esc_attr( $options['tw_related_handle'] ) . '&text=' . esc_attr( 'rtSocial... Share Fast!' ) . '&url=https://rtpanel.com/support/forum/plugin/'; ?>' rel="nofollow" target="_blank"><?php esc_html_e( 'Tweet', 'rtSocial' ); ?></a>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<!--Icons with count-->
												<tr>
													<td>
														<input value="icon-count" id="display_icon_count_input" name='rtsocial_plugin_options[display_options_set]' type="radio" <?php echo ( 'icon-count' === $options['display_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													</td>
													<td>
														<div id="rtsocial-display-icon-count-sample">
															<div class="rtsocial-fb-icon">
																<div class="rtsocial-fb-icon-button">
																	<a class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=https://rtpanel.com/support/forum/plugin/" rel="nofollow" target="_blank"><?php esc_html_e( 'Like', 'rtSocial' ); ?></a>
																</div>
																<div class="rtsocial-horizontal-count">
																	<div class="rtsocial-horizontal-notch"></div>
																	<span class="rtsocial-fb-count">0</span>
																</div>
															</div>
															<div class="rtsocial-twitter-icon">
																<div class="rtsocial-twitter-icon-button"><a class="rtsocial-twitter-icon-link" href='https://twitter.com/share?via=<?php echo esc_attr( $options['tw_handle'] ) . '&related=' . esc_attr( $options['tw_related_handle'] ) . '&text=' . esc_attr( 'rtSocial... Share Fast!' ) . '&url=https://rtpanel.com/support/forum/plugin/'; ?>' rel="nofollow" target="_blank"><?php esc_html_e( 'Tweet', 'rtSocial' ); ?></a>
																</div>
															</div>
														</div>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Alignment Settings', 'rtSocial' ); ?>:</th>
										<td>
											<fieldset>
												<label>
													<input value="left" name='rtsocial_plugin_options[alignment_options_set]' id="align_left_check" type="radio" <?php echo ( 'left' === $options['alignment_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													<span><?php esc_html_e( 'Left', 'rtSocial' ); ?></span>
												</label>
												<br/>
												<label>
													<input value="center" name='rtsocial_plugin_options[alignment_options_set]' id="align_center_check" type="radio" <?php echo ( 'center' === $options['alignment_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													<span><?php esc_html_e( 'Center', 'rtSocial' ); ?></span>
												</label>
												<br/>
												<label>
													<input value="right" name='rtsocial_plugin_options[alignment_options_set]' id="align_right_check" type="radio" <?php echo ( 'right' === $options['alignment_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													<span><?php esc_html_e( 'Right', 'rtSocial' ); ?></span>
												</label>
												<br/>
												<label>
													<input value="none" name='rtsocial_plugin_options[alignment_options_set]' id="align_none_check" type="radio" <?php echo ( 'none' === $options['alignment_options_set'] ) ? ' checked="checked" ' : ''; ?> />
													<span><?php esc_html_e( 'None', 'rtSocial' ); ?></span>
												</label>
											</fieldset>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Active Buttons', 'rtSocial' ); ?> <sup>#</sup>:</th>
										<td>
											<ul id="rtsocial-sorter-active" class="connectedSortable">
												<?php
												if ( ! empty( $options['active'] ) ) {
													foreach ( $options['active'] as $active ) {
														echo '<li id="rtsocial-ord-' . esc_attr( $active ) . '" style="cursor: pointer;"><input id="rtsocial-act-' . esc_attr( $active ) . '" style="display: none;" type="checkbox" name="rtsocial_plugin_options[active][]" value="' . esc_attr( $active ) . '" checked="checked" /><label for="rtsocial-act-' . esc_attr( $active ) . '">' . esc_html( $labels[ $active ] ) . '</label></li>';
													}
												}
												?>
											</ul>
										</td>
									</tr>
									<tr>
										<td colspan="2"><span class="description"># <?php esc_html_e( "Drag buttons around to reorder them OR drop them into 'Inactive' list to disable them.", 'rtSocial' ); ?> <strong><?php esc_html_e( 'All buttons cannot be disabled!', 'rtSocial' ); ?></strong></span></td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Inactive Buttons', 'rtSocial' ); ?> <sup>*</sup>:</th>
										<td>
											<ul id="rtsocial-sorter-inactive" class="connectedSortable">
												<?php
												if ( ! empty( $options['inactive'] ) ) {
													foreach ( $options['inactive'] as $inactive ) {
														if ( ! empty( $labels[ $inactive ] ) ) {
															echo '<li id="rtsocial-ord-' . esc_attr( $inactive ) . '" style="cursor: pointer;"><input id="rtsocial-act-' . esc_attr( $inactive ) . '" style="display: none;" type="checkbox" name="rtsocial_plugin_options[inactive][]" value="' . esc_attr( $inactive ) . '" checked="checked" /><label for="rtsocial-act-' . esc_attr( $inactive ) . '">' . esc_html( $labels[ $inactive ] ) . '</label></li>';
														}
													}
												}
												?>
											</ul>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<span class="description">* <?php esc_html_e( "Drop buttons back to 'Active' list to re-enable them.", 'rtSocial' ); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Hide counts', 'rtSocial' ); ?>:</th>
										<td>
											<fieldset>
												<label>
													<input value="1" name='rtsocial_plugin_options[hide_count]' id="hide_count_check" type="checkbox" <?php echo ( ! empty( $options['hide_count'] ) && ( 1 === (int) $options['hide_count'] ) ) ? ' checked="checked" ' : ''; ?> />
													<span><?php esc_html_e( 'Yes', 'rtSocial' ); ?></span>
												</label>
											</fieldset>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<!--Twitter-->
						<div class="postbox" id="tw_box">
							<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
							<h3 class="hndle"><?php esc_html_e( 'Twitter Button Settings', 'rtSocial' ); ?></h3>
							<div class="inside">
								<table class="form-table">
									<tr>
										<th>
											<span id="rtsocial-twitter"></span>
										</th>
									</tr>
									<tr class="tw_row">
										<th><?php esc_html_e( 'Twitter Handle', 'rtSocial' ); ?>:</th>
										<td>
											<b>@</b> <input type="text" value="<?php echo esc_attr( $options['tw_handle'] ); ?>" id="tw_handle" name="rtsocial_plugin_options[tw_handle]" />
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="tw_row">
										<th><?php esc_html_e( 'Related Twitter Handle', 'rtSocial' ); ?>:</th>
										<td>
											<b>@</b> <input type="text" value="<?php echo esc_attr( $options['tw_related_handle'] ); ?>" id="tw_related_handle" name="rtsocial_plugin_options[tw_related_handle]" />
										</td>
										<td>&nbsp;</td>
									</tr>
								</table>
							</div>
						</div>
						<!--Facebook-->
						<div class="postbox">
							<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
							<h3 class="hndle"> <?php esc_html_e( 'Facebook Button Settings', 'rtSocial' ); ?> </h3>
							<div class="inside">
								<table class="form-table">
									<tr class="fb_title">
										<th>
											<span id="rtsocial-facebook"></span>
										</th>
									</tr>
									<tr class="fb_row">
										<th><?php esc_html_e( 'Facebook Button Style', 'rtSocial' ); ?>:</th>
										<td>
											<input type="radio" name='rtsocial_plugin_options[fb_style]' value="like_light" id="rtsocial-like-light-input" <?php echo ( 'like_light' === $options['fb_style'] ) ? ' checked="checked" ' : ''; ?> />
											<label for="rtsocial-like-light-input">
												<a id="rtsocial-like-light"></a>
											</label>
										</td>
										<td>
											<input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_light" id="rtsocial-recommend-light-input" <?php echo ( 'recommend_light' === $options['fb_style'] ) ? ' checked="checked" ' : ''; ?> />
											<label for="rtsocial-recommend-light-input">
												<a id="rtsocial-recommend-light"></a>
											</label>
										</td>
									</tr>
									<tr class="fb_row">
										<th>&nbsp;</th>
										<td>
											<input type="radio" name='rtsocial_plugin_options[fb_style]' value="like_dark" id="rtsocial-like-dark-input" <?php echo ( 'like_dark' === $options['fb_style'] ) ? ' checked="checked" ' : ''; ?> />
											<label for="rtsocial-like-dark-input">
												<a id="rtsocial-like-dark"></a>
											</label>
										</td>
										<td>
											<input type="radio" name='rtsocial_plugin_options[fb_style]' value="recommend_dark" id="rtsocial-recommend-dark-input" <?php echo ( 'recommend_dark' === $options['fb_style'] ) ? ' checked="checked" ' : ''; ?> />
											<label for="rtsocial-recommend-dark-input">
												<a id="rtsocial-recommend-dark"></a>
											</label>
										</td>
									</tr>
									<tr class="fb_row">
										<th>&nbsp;</th>
										<td>
											<input type="radio" name='rtsocial_plugin_options[fb_style]' value="share" id="rtsocial-share-input" <?php echo ( 'share' === $options['fb_style'] ) ? ' checked="checked" ' : ''; ?> />
											<label for="rtsocial-share-input">
												<a id="rtsocial-share-plain"></a>
											</label>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="fb_row">
										<th><?php esc_html_e( 'Facebook App Access Token', 'rtSocial' ); ?>:</th>
										<td>
										<input type="text" value="<?php echo ( ! empty( $options['fb_access_token'] ) ) ? esc_attr( $options['fb_access_token'] ) : ''; ?>" id="fb_access_token" name="rtsocial_plugin_options[fb_access_token]" />
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<th>&nbsp;</th>
										<td colspan="2">
											<b>Format:</b> App ID|App Secret <br/>
											<b>Example:</b> 3245678987646576|dfghjhg4564768jjgvjvbnnh9876 <br/>
											Follow these <a href="https://github.com/rtCamp/rtsocial/wiki#how-to-get-facebook-access-token" target="_blank">guideline</a> to generate your access token.
										</td>
									</tr>
								</table>
							</div>
						</div>
						<p class="submit">
							<input type="submit" name="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'rtSocial' ); ?>" />
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
					<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
					<h3 class="hndle">
						<span>
							<strong><?php esc_html_e( 'Getting Social is Good', 'rtSocial' ); ?></strong>
						</span>
					</h3>
					<div class="inside rt-social-connect">
						<a href="https://www.facebook.com/rtCamp.solutions" rel="nofollow" target="_blank" title="Become a fan on Facebook" class="rt-sidebar-facebook"><?php esc_html_e( 'Facebook', 'rtSocial' ); ?></a>
						<a href="https://twitter.com/rtcamp" rel="nofollow" target="_blank" title="Follow us on Twitter" class="rt-sidebar-twitter"><?php esc_html_e( 'Twitter', 'rtSocial' ); ?></a>
						<a href="https://feeds.feedburner.com/rtcamp" rel="nofollow" target="_blank" title="Subscribe to our Feeds" class="rt-sidebar-rss"><?php esc_html_e( 'RSS', 'rtSocial' ); ?></a>
					</div>
				</div>
				<div class="postbox" id="donations">
					<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
					<h3 class="hndle">
						<span>
							<strong><?php esc_html_e( 'Promote, Donate, Share...', 'rtSocial' ); ?></strong>
						</span>
					</h3>
					<div class="inside">
						<?php esc_html_e( 'Buy coffee/beer for team behind', 'rtSocial' ); ?> <a href="https://rtcamp.com/rtsocial/" title="<?php esc_attr_e( 'rtSocial Plugin', 'rtSocial' ); ?>">rtSocial</a>.
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
								<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="<?php esc_attr_e( 'PayPal - The safer, easier way to pay online!', 'rtSocial' ); ?>" />
								<img border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="pixel" />
							</form>
						</div>
						<div class="rtsocial-share" style="text-align:center; width: 127px; margin: 2px auto">
							<div class="rt-facebook" style="float:left; margin-right:5px;">
								<a style=" text-align:center;" name="fb_share" type="box_count" title="<?php esc_attr_e( 'rtSocial: Simple, Smarter & Swifter Social Sharing WordPress Plugin', 'rtSocial' ); ?>" share_url="https://rtcamp.com/rtsocial/"></a>
							</div>
							<div class="rt-twitter">
								<a href="https://twitter.com/share" title="<?php esc_attr_e( 'rtSocial: Simple, Smarter & Swifter Social Sharing WordPress Plugin', 'rtSocial' ); ?>" class="twitter-share-button" data-text="<?php esc_attr_e( 'rtSocial: Simple, Smarter & Swifter Social Sharing #WordPress #Plugin', 'rtSocial' ); ?>" data-url="https://rtcamp.com/rtsocial/" data-count="vertical" data-via="rtCamp"><?php esc_html_e( 'Tweet', 'rtSocial' ); ?></a>
							</div>
							<div class="rt_clear"></div>
						</div>
					</div>
					<!-- end of .inside -->
				</div>
				<div class="postbox" id="support">
					<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
					<h3 class="hndle">
						<span>
							<strong><?php esc_html_e( 'Free Support', 'rtSocial' ); ?></strong>
						</span>
					</h3>
					<div class="inside"><?php esc_html_e( 'If you have any problems with this plugin or good ideas for improvements, please talk about them in the', 'rtSocial' ); ?> <a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/rtsocial/' ); ?>" rel="nofollow" target="_blank" title="<?php esc_attr_e( 'free support forums', 'rtSocial' ); ?>"><?php esc_html_e( 'free support forums.', 'rtSocial' ); ?></a></div>
				</div>
				<div class="postbox" id="latest_news">
					<div title="<?php esc_attr_e( 'Click to toggle', 'rtSocial' ); ?>" class="handlediv"><br/></div>
					<h3 class="hndle">
						<span>
							<strong><?php esc_html_e( 'Latest News', 'rtSocial' ); ?></strong>
						</span>
					</h3>
					<div class="inside"><?php rtsocial_get_feeds(); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
