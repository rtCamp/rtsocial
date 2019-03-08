<?php
/**
 * Lightest social sharing plugin developed by rtCamp.
 *
 * @package rtSocial
 * @author  rtCamp
 *
 * Plugin Name: rtSocial
 * Plugin URI:  https://rtcamp.com/rtsocial/
 * Author:      rtCamp
 * Author URI:  https://rtcamp.com/
 * Text Domain: rtSocial
 * Domain Path: /languages
 * Version:     2.2.1
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
 * Tags:        rtcamp, social, sharing, share, social links, twitter, facebook, pin it, pinterest, linkedin, linked in, linked in share, google plus, google plus share, gplus share, g+ button, g+ share, plus one button, social share, social sharing
 */

if ( ! defined( 'RTSOCIAL_PLUGIN_PATH' ) ) {
	define( 'RTSOCIAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}


/*
 * Initial Actions
 */
add_action( 'admin_menu', 'rtsocial_admin' );
add_action( 'wp_ajax_rts_hide_g_plus_notice', 'rts_hide_g_plus_notice' );

register_activation_hook( __FILE__, 'rtsocial_set_defaults' );

/**
 * Commenting delete option code on plugin deactivate because user losting setting on plugin reactivate which is wrong
 */

/**
 * Enableing delete option on plugin delete
 */
register_uninstall_hook( __FILE__, 'rtsocial_reset_defaults' );

/**
 * Settings Page
 */
function rtsocial_admin() {
	// Add settings page.
	$hook = add_options_page( 'rtSocial Options Page', 'rtSocial Options', 'manage_options', 'rtsocial-options', 'rtsocial_admin_fn' );

	// Enqueue CSS and JS for the options page.
	add_action( 'admin_print_scripts-' . $hook, 'rtsocial_assets' );
}

/**
 * Display rtSocial settings form.
 */
function rtsocial_admin_fn() {
	require_once 'template/rtsocial-setting-form.php';
}

/*
 * Add the options variable
 */
add_action( 'admin_init', 'rtsocial_options_init_fn' );

/**
 * Register Setting.
 */
function rtsocial_options_init_fn() {
	register_setting( 'rtsocial_plugin_options', 'rtsocial_plugin_options', 'rtsocial_check' );
}

/**
 * Settings sanitisation
 *
 * @param array $args Settings arguments.
 */
function rtsocial_check( $args ) {
	// Just in case the JavaScript for avoiding deactivation of all services fails, this will fix it! ;).
	if ( empty( $args['active'] ) ) {
		add_settings_error( 'rtsocial_plugin_options', 'all_inactive', 'All options inactive! Resetting all as active.', $type = 'error' );

		$args['active']   = array( 'tw', 'fb', 'lin', 'pin', 'gplus' );
		$args['inactive'] = array();
	}

	return $args;
}

/**
 * Print the sanitisation errors
 */
function rtsocial_get_errors() {
	$errors = get_settings_errors();
	echo esc_html( $errors );
}

/**
 * Inject the widget in the posts
 */
add_filter( 'the_content', 'rtsocial_counter' );
add_filter( 'the_excerpt', 'rtsocial_counter' );

/**
 * Dynamic Content.
 *
 * @param string $content Content.
 */
function rtsocial_dyna( $content ) {
	if ( is_single() ) {
		return rtsocial_counter( $content );
	} else {
		return $content;
	}
}

/**
 * Counter for Facebook, twitter, Linkedin, Pinterest.
 *
 * @param string $content Content.
 */
function rtsocial_counter( $content = '' ) {
	global $post;

	// Working issue on attachment page.
	if ( is_attachment() ) {
		return $content;
	}

	// Check for excluded page.
	$is_visible = get_post_meta( $post->ID, '_rtsocial_visibility', true );

	if ( ! empty( $is_visible ) ) {
		return $content;
	}

	$options = get_option( 'rtsocial_plugin_options' );

	$rtslink = rawurlencode( apply_filters( 'rtsocial_permalink', get_permalink( $post->ID ), $post->ID, $post ) );

	$rtstitle = rt_url_encode( wp_strip_all_tags( get_the_title( $post->ID ) ) );
	$rtatitle = wp_strip_all_tags( get_the_title( $post->ID ) );

	// Ordered buttons array.
	$active_services = array();

	// Twitter.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'tw', $options['active'], true ) ) {
		$tw = array_search( 'tw', $options['active'], true );

		$handle_string  = '';
		$handle_string .= ( ! empty( $options['tw_handle'] ) ) ? '&via=' . $options['tw_handle'] : '';
		$handle_string .= ( ! empty( $options['tw_related_handle'] ) ) ? '&related=' . $options['tw_related_handle'] : '';
		$tw_layout      = '<div class="rtsocial-twitter-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$tw_layout .= '<div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title= "Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$tw_layout .= '<div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$tw_layout .= ' <div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$tw_layout  = '<div class="rtsocial-twitter-icon">';
						$tw_layout .= ' <div class="rtsocial-twitter-icon-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
					}
				}
			}
		}

		$tw_layout .= '</div>';

		$active_services[ $tw ] = $tw_layout;
	}
	// Twitter End.
	// Facebook.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'fb', $options['active'], true ) ) {
		$fb          = array_search( 'fb', $options['active'], true );
		$fb_count    = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
		$class       = '';
		$rt_fb_style = '';
		$path        = plugins_url( 'images/', __FILE__ );

		if ( 'like_dark' === $options['fb_style'] ) {
			$class       = 'rtsocial-fb-like-dark';
			$rt_fb_style = 'fb-dark';
		} else {

			if ( 'recommend_dark' === $options['fb_style'] ) {
				$class       = 'rtsocial-fb-recommend-dark';
				$rt_fb_style = 'fb-dark';
			} else {

				if ( 'recommend_light' === $options['fb_style'] ) {
					$class       = 'rtsocial-fb-recommend-light';
					$rt_fb_style = 'fb-light';
				} else {

					if ( 'share' === $options['fb_style'] ) {
						$class = 'rtsocial-fb-share';
					} else {
						$class       = 'rtsocial-fb-like-light';
						$rt_fb_style = 'fb-light';
					}
				}
			}
		}

		$fb_layout = '<div class="rtsocial-fb-' . $options['display_options_set'] . ' ' . $rt_fb_style . '">';

		$rt_social_text = '';

		if ( 'like_light' === $options['fb_style']
		|| 'like_dark' === $options['fb_style'] ) {
			$rt_social_text = 'Like';
		} else {

			if ( 'recommend_light' === $options['fb_style']
			|| 'recommend_dark' === $options['fb_style'] ) {
				$rt_social_text = 'Recommend';
			} else {
				$rt_social_text = 'Share';
			}
		}

		if ( 'horizontal' === $options['display_options_set'] ) {
			$fb_layout .= '<div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . ( rawurlencode( get_permalink( $post->ID ) ) ) . '" rel="nofollow" target="_blank"></a></div>' . $fb_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$fb_layout .= $fb_count . '<div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . ( rawurlencode( get_permalink( $post->ID ) ) ) . '" rel="nofollow" target="_blank"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$fb_layout .= ' <div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . ( rawurlencode( get_permalink( $post->ID ) ) ) . '" target= "_blank"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$fb_layout  = '<div class="rtsocial-fb-icon" class="' . $rt_fb_style . '">';
						$fb_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
						$fb_layout .= ' <div class="rtsocial-fb-icon-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . ( rawurlencode( get_permalink( $post->ID ) ) ) . '" target= "_blank"></a></div>' . $fb_count;
					}
				}
			}
		}

		$fb_layout .= '</div>';

		$active_services[ $fb ] = $fb_layout;
	}
	// Facebook End.
	// Pinterest.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'pin', $options['active'], true ) ) {
		$pin = array_search( 'pin', $options['active'], true );

		$pin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';

		// Set Pinterest media image.
		if ( has_post_thumbnail( $post->ID ) ) {
			// Use post thumbnail if set.
			$thumb_details = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );

			$thumb_src = $thumb_details[0];
		} else {
			// Else use a default image.
			$thumb_src = plugins_url( 'images/default-pinterest.png', __FILE__ );
		}

		// Set Pinterest description.
		$title = $post->post_title;

		$pin_layout = '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$pin_layout .= '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$pin_layout .= $pin_count . '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$pin_layout .= ' <div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$pin_layout  = '<div class="rtsocial-pinterest-icon">';
						$pin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
						$pin_layout .= ' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
					}
				}
			}
		}

		$pin_layout .= '</div>';

		$active_services[ $pin ] = $pin_layout;
	}
	// Pinterest End.
	// LinkedIn.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'lin', $options['active'], true ) ) {
		$lin = array_search( 'lin', $options['active'], true );

		$lin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';

		$lin_layout = '<div class="rtsocial-linkedin-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$lin_layout .= '<div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( get_permalink( $post->ID ) ) . '&title=' . rawurlencode( $rtatitle ) . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$lin_layout .= $lin_count . ' <div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( get_permalink( $post->ID ) ) . '&title=' . rawurlencode( $rtatitle ) . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$lin_layout .= ' <div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( get_permalink( $post->ID ) ) . '&title=' . rawurlencode( $rtatitle ) . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$lin_layout  = '<div class="rtsocial-linkedin-icon">';
						$lin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
						$lin_layout .= ' <div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( get_permalink( $post->ID ) ) . '&title=' . rawurlencode( $rtatitle ) . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
					}
				}
			}
		}

		$lin_layout .= '</div>';

		$active_services[ $lin ] = $lin_layout;
	}
	// Linked In End.
	// G+ Share Button.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'gplus', $options['active'], true ) ) {
		$gplus = array_search( 'gplus', $options['active'], true );

		$gplus_layout = '<div class="rtsocial-gplus-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$gplus_layout .= '<div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . rawurlencode( get_permalink( $post->ID ) ) . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$gplus_layout .= '<div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . rawurlencode( get_permalink( $post->ID ) ) . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$gplus_layout .= ' <div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . rawurlencode( get_permalink( $post->ID ) ) . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$gplus_layout  = '<div class="rtsocial-gplus-icon">';
						$gplus_layout .= ' <div class="rtsocial-gplus-icon-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . rawurlencode( get_permalink( $post->ID ) ) . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
					}
				}
			}
		}

		$gplus_layout .= '</div>';

		$active_services[ $gplus ] = $gplus_layout;
	}
	// G+ Share Button End.
	// Sort by indexes.
	ksort( $active_services );

	// Form the ordered buttons markup.
	$active_services = implode( '', $active_services );

	// Rest of the stuff.
	$layout = '<div class="rtsocial-container rtsocial-container-align-' . $options['alignment_options_set'] . ' rtsocial-' . $options['display_options_set'] . '">';

	// Append the ordered buttons.
	$layout .= $active_services;

	// Hidden permalink.
	$layout .= '<a rel="nofollow" class="perma-link" href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '"></a><input type="hidden" name="rts_id" class="rts_id" value="' . $post->ID . '" />' . wp_nonce_field( 'rts_media_' . $post->ID, 'rts_media_nonce', true, false ) . '</div>';

	if ( 'top' === $options['placement_options_set'] ) {
		return $layout . $content;
	} else {

		if ( 'bottom' === $options['placement_options_set'] ) {
			return $content . $layout;
		} else {
			return $content;
		}
	}
}

/**
 * Function for manual layout.
 *
 * @param array $args Arguments.
 *
 * Possible options.
 * 'active' = array('tw', 'fb', 'lin', 'pin', 'gplus');
 * 'display_options_set' = 'horizontal', 'vertical', 'icon', 'icon-count'
 * 'alignment_options_set' = 'left', 'right', 'center', 'none'
 * 'tw_handle' = 'whateveryouwant'
 * 'tw_related_handle' = 'whateveryouwant'
 * 'fb_style' = 'like_light', 'like_dark', 'recommend_light', 'recommend_dark', 'share'.
 */
function rtsocial( $args = array() ) {
	// Working issue on attachment page.
	if ( is_attachment() ) {
		return;
	}

	$options = get_option( 'rtsocial_plugin_options' );
	$options = wp_parse_args( $args, $options );

	// If manual mode is selected then avoid this code.
	if ( ! empty( $options )
	&& 'manual' !== $options['placement_options_set'] ) {
		return;
	}

	global $post;

	$post_obj      = apply_filters( 'rtsocial_post_object', $post );
	$rts_permalink = apply_filters( 'rtsocial_permalink', get_permalink( $post_obj->ID ), $post_obj->ID, $post_obj );
	$rtslink       = rawurlencode( $rts_permalink );
	$rtatitle      = apply_filters( 'rtsocial_title', get_the_title( $post_obj->ID ) );
	$rtatitle      = wp_strip_all_tags( $rtatitle );
	$rtstitle      = rt_url_encode( $rtatitle );
	// Ordered buttons.
	$active_services = array();

	// Twitter.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'tw', $options['active'], true ) ) {
		$tw = array_search( 'tw', $options['active'], true );

		$handle_string  = '';
		$handle_string .= ( ! empty( $options['tw_handle'] ) ) ? '&via=' . $options['tw_handle'] : '';
		$handle_string .= ( ! empty( $options['tw_related_handle'] ) ) ? '&related=' . $options['tw_related_handle'] : '';

		$tw_layout = '<div class="rtsocial-twitter-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$tw_layout .= '<div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$tw_layout .= '<div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$tw_layout .= ' <div class="rtsocial-twitter-' . $options['display_options_set'] . '-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$tw_layout  = '<div class="rtsocial-twitter-icon">';
						$tw_layout .= ' <div class="rtsocial-twitter-icon-button"><a title="Tweet: ' . $rtatitle . '" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=' . $rtstitle . $handle_string . '&url=' . $rtslink . '" target= "_blank"></a></div>';
					}
				}
			}
		}

		$tw_layout .= '</div>';

		$active_services[ $tw ] = $tw_layout;
	}
	// Twitter End.
	// Facebook.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'fb', $options['active'], true ) ) {
		$fb          = array_search( 'fb', $options['active'], true );
		$fb_count    = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
		$class       = '';
		$rt_fb_style = '';
		$path        = plugins_url( 'images/', __FILE__ );

		if ( 'like_dark' === $options['fb_style'] ) {
			$class       = 'rtsocial-fb-like-dark';
			$rt_fb_style = 'fb-dark';
		} else {

			if ( 'recommend_dark' === $options['fb_style'] ) {
				$class       = 'rtsocial-fb-recommend-dark';
				$rt_fb_style = 'fb-dark';
			} else {

				if ( 'recommend_light' === $options['fb_style'] ) {
					$class       = 'rtsocial-fb-recommend-light';
					$rt_fb_style = 'fb-light';
				} else {

					if ( 'share' === $options['fb_style'] ) {
						$class = 'rtsocial-fb-share';
					} else {
						$class       = 'rtsocial-fb-like-light';
						$rt_fb_style = 'fb-light';
					}
				}
			}
		}

		$fb_layout = '<div class="rtsocial-fb-' . $options['display_options_set'] . ' ' . $rt_fb_style . '">';

		$rt_social_text = '';

		if ( 'like_light' === $options['fb_style']
		|| 'like_dark' === $options['fb_style'] ) {
			$rt_social_text = 'Like';
		} else {

			if ( 'recommend_light' === $options['fb_style']
			|| 'recommend_dark' === $options['fb_style'] ) {
				$rt_social_text = 'Recommend';
			} else {
				$rt_social_text = 'Share';
			}
		}

		if ( 'horizontal' === $options['display_options_set'] ) {
			$fb_layout .= '<div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>' . $fb_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$fb_layout .= $fb_count . '<div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-button ' . $class . '" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" rel="nofollow" target="_blank"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$fb_layout .= ' <div class="rtsocial-fb-' . $options['display_options_set'] . '-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" target= "_blank"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$fb_layout  = '<div class="rtsocial-fb-icon" class="' . $rt_fb_style . '">';
						$fb_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
						$fb_layout .= ' <div class="rtsocial-fb-icon-button"><a title="' . $rt_social_text . ': ' . $rtatitle . '" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=' . $rtslink . '" target= "_blank"></a></div>' . $fb_count;
					}
				}
			}
		}
		$fb_layout .= '</div>';

		$active_services[ $fb ] = $fb_layout;
	}
	// Facebook End.
	// Pinterest.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'pin', $options['active'], true ) ) {
		$pin = array_search( 'pin', $options['active'], true );

		$pin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';

		// Set Pinterest media image.
		if ( has_post_thumbnail( $post_obj->ID ) ) {
			// Use post thumbnail if set.
			$thumb_details = wp_get_attachment_image_src( get_post_thumbnail_id( $post_obj->ID ), 'thumbnail' );

			$thumb_src = $thumb_details[0];
		} else {
			// Else use a default image.
			$thumb_src = plugins_url( 'images/default-pinterest.png', __FILE__ );
		}

		$thumb_src = apply_filters( 'rtsocial_pinterest_thumb', $thumb_src, $post_obj->ID );
		// Set Pinterest description.
		$title      = $post_obj->post_title;
		$pin_layout = '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$pin_layout .= '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>' . $pin_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$pin_layout .= $pin_count . '<div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" rel="nofollow" target="_blank" title="Pin: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$pin_layout .= ' <div class="rtsocial-pinterest-' . $options['display_options_set'] . '-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$pin_layout  = '<div class="rtsocial-pinterest-icon">';
						$pin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
						$pin_layout .= ' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=' . $rtslink . '&media=' . $thumb_src . '&description=' . $title . '" target= "_blank" title="Pin ' . $rtatitle . '"></a></div>' . $pin_count;
					}
				}
			}
		}

		$pin_layout .= '</div>';

		$active_services[ $pin ] = $pin_layout;
	}
	// Pinterest End.
	// LinkedIn.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'lin', $options['active'], true ) ) {
		$lin = array_search( 'lin', $options['active'], true );

		$lin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-' . $options['display_options_set'] . '-count"><div class="rtsocial-' . $options['display_options_set'] . '-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';

		$lin_layout = '<div class="rtsocial-linkedin-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$lin_layout .= '<div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$lin_layout .= $lin_count . ' <div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" rel="nofollow" target="_blank" title="Share: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$lin_layout .= ' <div class="rtsocial-linkedin-' . $options['display_options_set'] . '-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$lin_layout  = '<div class="rtsocial-linkedin-icon">';
						$lin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
						$lin_layout .= ' <div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=' . $rtslink . '&title=' . $rtstitle . '" target= "_blank" title="Share: ' . $rtatitle . '"></a></div>' . $lin_count;
					}
				}
			}
		}

		$lin_layout .= '</div>';

		$active_services[ $lin ] = $lin_layout;
	}
	// Linked In End.
	// G+ Share Button.
	if ( ! empty( $options )
	&& ! empty( $options['active'] )
	&& in_array( 'gplus', $options['active'], true ) ) {
		$gplus = array_search( 'gplus', $options['active'], true );

		$gplus_layout = '<div class="rtsocial-gplus-' . $options['display_options_set'] . '">';

		if ( 'horizontal' === $options['display_options_set'] ) {
			$gplus_layout .= '<div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . $rtslink . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$gplus_layout .= '<div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-button" href= "https://plus.google.com/share?url=' . $rtslink . '" rel="nofollow" target="_blank" title="+1: ' . $rtatitle . '"></a></div>';
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$gplus_layout .= ' <div class="rtsocial-gplus-' . $options['display_options_set'] . '-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . $rtslink . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$gplus_layout  = '<div class="rtsocial-gplus-icon">';
						$gplus_layout .= ' <div class="rtsocial-gplus-icon-button"><a class="rtsocial-gplus-icon-link" href= "https://plus.google.com/share?url=' . $rtslink . '" target= "_blank" title="+1: ' . $rtatitle . '"></a></div>';
					}
				}
			}
		}

		$gplus_layout .= '</div>';

		$active_services[ $gplus ] = $gplus_layout;
	}
	// G+ Share Button End.
	// Sort by indexes.
	ksort( $active_services );

	// Form the ordered buttons markup.
	$active_services = implode( '', $active_services );

	// Rest of the stuff.
	$layout = '<div class="rtsocial-container rtsocial-container-align-' . $options['alignment_options_set'] . ' rtsocial-' . $options['display_options_set'] . '">';

	// Append the ordered buttons.
	$layout .= $active_services;

	// Hidden permalink.
	$layout .= '<a title="' . esc_attr( $rtatitle ) . '" rel="nofollow" class="perma-link" href="' . $rts_permalink . '"></a><input type="hidden" name="rts_id" class="rts_id" value="' . $post_obj->ID . '" />' . wp_nonce_field( 'rts_media_' . $post_obj->ID, 'rts_media_nonce', true, false ) . '</div>';

	return $layout;
}

/**
 * Function for setting default values
 */
function rtsocial_set_defaults() {
	if ( is_multisite() ) {
		foreach ( get_sites() as $i => $site ) {
			switch_to_blog( $site->blog_id );

			$defaults = array(
				'fb_style'              => 'like_light',
				'tw_handle'             => '',
				'tw_related_handle'     => '',
				'placement_options_set' => 'bottom',
				'display_options_set'   => 'horizontal',
				'alignment_options_set' => 'right',
				'active'                => array( 'tw', 'fb', 'lin', 'pin' ),
				'inactive'              => array( 'gplus' ),
			);

			if ( ! get_option( 'rtsocial_plugin_options' ) ) {
				update_option( 'rtsocial_plugin_options', $defaults );
			}

			restore_current_blog();
		}
	} else {
		$defaults = array(
			'fb_style'              => 'like_light',
			'tw_handle'             => '',
			'tw_related_handle'     => '',
			'placement_options_set' => 'bottom',
			'display_options_set'   => 'horizontal',
			'alignment_options_set' => 'right',
			'active'                => array( 'tw', 'fb', 'lin', 'pin' ),
			'inactive'              => array( 'gplus' ),
		);

		if ( ! get_option( 'rtsocial_plugin_options' ) ) {
			update_option( 'rtsocial_plugin_options', $defaults );
		}
	}
}

/**
 * Delete plugin options
 */
function rtsocial_reset_defaults() {
	if ( is_multisite() && is_plugin_active_for_network( 'rtsocial/source.php' ) ) {
		foreach ( get_sites() as $i => $site ) {
			switch_to_blog( $site->blog_id );

			delete_option( 'rtsocial_plugin_options' );

			restore_current_blog();
		}
	} else {
		delete_option( 'rtsocial_plugin_options' );
	}
}

// The similar action for the admin page is on line no.26 above!
add_action( 'wp_enqueue_scripts', 'rtsocial_assets' );

/**
 * Get file modified time.
 *
 * @param string $file_path File path.
 *
 * @return mixed
 */
function get_file_modified_time( $file_path ) {
	if ( empty( $file_path ) ) {
		return null;
	}
	return filemtime( $file_path );
}

/**
 * Enqueue scripts and styles
 */
function rtsocial_assets() {
	// Get all options for rtsocial add on.
	$options = get_option( 'rtsocial_plugin_options' );

	// Dashboard JS and CSS for admin side only.
	if ( is_admin() ) {
		wp_enqueue_style( 'dashboard' );

		wp_enqueue_script( 'dashboard' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'rt-fb-share', ( 'https://static.ak.fbcdn.net/connect.php/js/FB.Share' ), array(), true, true );
		wp_enqueue_script( 'twitter-widget', ( 'https://platform.twitter.com/widgets.js' ), array(), true, true );
	}

	// Plugin CSS.
	wp_enqueue_style( 'rtsocial-styleSheet', plugins_url( '/styles/style.css', __FILE__ ), array(), get_file_modified_time( RTSOCIAL_PLUGIN_PATH . 'styles/style.css' ) );

	// Plugin JS.
	wp_enqueue_script( 'rtss-main', plugins_url( '/js/rtss-main.js', __FILE__ ), array( 'jquery' ), get_file_modified_time( RTSOCIAL_PLUGIN_PATH . 'js/rtss-main.js' ), true );

	// Localize Script.
	rtsocial_localize_script( 'rtss-main' );
}

/**
 * Localize JS with custom args.
 *
 * @param string $handle The registered script handle you are attaching the data for.
 */
function rtsocial_localize_script( $handle ) {
	// Passing arguments to Plugin JS.
	$options              = get_option( 'rtsocial_plugin_options' );
	$args                 = array();
	$args['button_style'] = $options['display_options_set'];
	$args['hide_count']   = ( ! empty( $options['hide_count'] ) && 1 === (int) $options['hide_count'] ) ? 1 : 0;
	$args['twitter']      = false;
	$args['facebook']     = false;
	$args['pinterest']    = false;
	$args['linkedin']     = false;
	$args['gplus']        = false;

	if ( is_array( $options['active'] ) ) {
		if ( in_array( 'tw', $options['active'], true ) ) {
			$args['twitter'] = true;
		}

		if ( in_array( 'fb', $options['active'], true ) ) {
			$args['facebook'] = true;
		}

		if ( in_array( 'pin', $options['active'], true ) ) {
			$args['pinterest'] = true;
		}

		if ( in_array( 'lin', $options['active'], true ) ) {
			$args['linkedin'] = true;
		}

		if ( in_array( 'gplus', $options['active'], true ) ) {
			$args['gplus'] = true;
		}
	}

	$args['path'] = plugins_url( 'images/', __FILE__ );

	wp_localize_script( $handle, 'args', $args );
}


add_filter( 'plugin_action_links', 'rtsocial_actlinks', 10, 2 );
/**
 * Place in Option List on Settings > Plugins page.
 *
 * @param array  $links Link.
 * @param string $file Plugin basename.
 */
function rtsocial_actlinks( $links, $file ) {
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;

	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}

	if ( $file === $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=rtsocial-options&rtnonce=' . wp_create_nonce( 'rtnonce' ) ) . '">' . __( 'Settings' ) . '</a>';

		array_unshift( $links, $settings_link ); // before other links.
	}

	return $links;
}

register_activation_hook( __FILE__, 'rtsocial_plugin_activate' );
add_action( 'admin_init', 'rtsocial_plugin_redirect' );
/**
 * Activation redirect to rtSocial Options Page.
 */
function rtsocial_plugin_activate() {
	add_option( 'rtsocial_plugin_do_activation_redirect', true );
}

/**
 * Redirect to rtSocial Options Page
 */
function rtsocial_plugin_redirect() {
	if ( get_option( 'rtsocial_plugin_do_activation_redirect', false ) ) {
		delete_option( 'rtsocial_plugin_do_activation_redirect' );

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
			// Makes sure the plugin is defined before trying to use it.
		if ( ! is_plugin_active_for_network( 'rtsocial/source.php' ) ) {
			// Plugin is activated.
			wp_safe_redirect( admin_url( 'options-general.php?page=rtsocial-options&rtnonce=' . wp_create_nonce( 'rtnonce' ) ) );
		}
	}
}


add_action( 'wp_footer', 'rtsocial_ie_fix' );
/**
 * IE Fix
 */
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

/**
 * Function to replace the functionality of PHP's rawurlencode to support titles with special characters in Twitter and Facebook.
 *
 * @param string $string replace the functionality of PHP's rawurlencode to support titles with special characters in Twitter and Facebook.
 */
function rt_url_encode( $string ) {
	$entities     = array( '%26%23038%3B', '%26%238211%3B', '%26%238221%3B', '%26%238216%3B', '%26%238217%3B', '%26%238220%3B' );
	$replacements = array( '%26', '%2D', '%22', '%27', '%27', '%22' );

	return str_replace( $entities, $replacements, rawurlencode( str_replace( array( '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;' ), array( '\'', '\'', '"', '"' ), $string ) ) );
}

add_action( 'wp_head', 'rtsocial_ajaxurl' );
/**
 * Define AJAX URL
 */
function rtsocial_ajaxurl() {
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
	</script>
	<?php
}

/**
 * Google Plus shares count handled via CURL.
 *
 * @param string $feed_url Feed URL.
 */
function rtsocial_get_feeds( $feed_url = 'https://rtcamp.com/blog/category/rtsocial/feed/' ) {
	// Get RSS Feed(s).
	require_once ABSPATH . WPINC . '/feed.php';

	$maxitems = 0;
	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( $feed_url );

	if ( ! is_wp_error( $rss ) ) { // Checks that the object is created correctly
		// Figure out how many total items there are, but limit it to 5.
		$maxitems = $rss->get_item_quantity( 5 );
		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $maxitems );
	}
	?>
	<ul>
		<?php
		if ( 0 === $maxitems ) {
			echo '<li>' . esc_html__( 'No items', 'rtSocial' ) . '.</li>';
		} else {
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ) {
				?>
				<li>
					<a href='<?php echo esc_url( $item->get_permalink() ); ?>' title='<?php echo esc_html_e( 'Posted ', 'rtSocial' ) . esc_attr( $item->get_date( 'j F Y | g:i a' ) ); ?>'><?php echo esc_html( $item->get_title() ); ?></a>
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
	$value = get_post_meta( $post->ID, '_rtsocial_visibility', true );
	?>

	<input type="checkbox" id="rtsocial_visibility" name="rtsocial_visibility" value="1" <?php checked( '1', $value ); ?> />
	<label for="rtsocial_visibility">
	<?php esc_html_e( 'Exclude Social Sharing Icons', 'rtsocial' ); ?>
	</label>
	<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function rtsocial_save_meta_box_data( $post_id ) {

	/**
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['rtsocial_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rtsocial_meta_box_nonce'] ) ), 'rtsocial_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( ! empty( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

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
	if ( empty( $_POST['rtsocial_visibility'] ) ) {
		delete_post_meta( $post_id, '_rtsocial_visibility' );
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( wp_unslash( $_POST['rtsocial_visibility'] ) );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_rtsocial_visibility', $my_data );
}
add_action( 'save_post', 'rtsocial_save_meta_box_data' );
