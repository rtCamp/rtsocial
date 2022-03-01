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
 * Version:     2.2.2
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
 * Tags:        rtcamp, social, sharing, share, social links, twitter, facebook, pin it, pinterest, linkedin, linked in, linked in share, plus one button, social share, social sharing
 */

if ( ! defined( 'RTSOCIAL_PLUGIN_PATH' ) ) {
	define( 'RTSOCIAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Initial Actions
 */
add_action( 'admin_menu', 'rtsocial_admin' );

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
	$hook = add_options_page( esc_html__( 'rtSocial Options Page', 'rtSocial' ), esc_html__( 'rtSocial Options', 'rtSocial' ), 'manage_options', 'rtsocial-options', 'rtsocial_admin_fn' );

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
		add_settings_error( 'rtsocial_plugin_options', 'all_inactive', esc_html__( 'All options inactive! Resetting all as active.', 'rtSocial' ), 'error' );

		$args['active']   = array( 'tw', 'fb', 'lin', 'pin' );
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
		$tw_layout      = sprintf(
			'<div class="rtsocial-twitter-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$tw_layout .= sprintf(
				'<div class="rtsocial-twitter-%1$s-button"><a title= "Tweet: %2$s" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=%3$s%4$s&url=%5$s" rel="nofollow" target="_blank"></a></div>',
				$options['display_options_set'],
				$rtatitle,
				$rtstitle,
				$handle_string,
				$rtslink
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$tw_layout .= sprintf(
					'<div class="rtsocial-twitter-%1$s-button"><a title="Tweet: %2$s" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=%2$s%3$s&url=%4$s" target= "_blank"></a></div>',
					$options['display_options_set'],
					$rtatitle,
					$handle_string,
					$rtslink
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$tw_layout .= sprintf(
						' <div class="rtsocial-twitter-%1$s-button"><a title="Tweet: %2$s" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=%3$s%4$s&url=%5$s" target= "_blank"></a></div>',
						$options['display_options_set'],
						$rtatitle,
						$rtstitle,
						$handle_string,
						$rtslink
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$tw_layout  = '<div class="rtsocial-twitter-icon">';
						$tw_layout .= sprintf(
							' <div class="rtsocial-twitter-icon-button"><a title="Tweet: %1$s" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=%2$s%3$s&url=%4$s" target= "_blank"></a></div>',
							$rtatitle,
							$rtstitle,
							$handle_string,
							$rtslink
						);
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

		$fb_layout = sprintf(
			'<div class="rtsocial-fb-%1$s %2$s">',
			$options['display_options_set'],
			$rt_fb_style
		);

		$rt_social_text = '';

		if ( 'like_light' === $options['fb_style']
		|| 'like_dark' === $options['fb_style'] ) {
			$rt_social_text = esc_html__( 'Like', 'rtSocial' );
		} else {

			if ( 'recommend_light' === $options['fb_style']
			|| 'recommend_dark' === $options['fb_style'] ) {
				$rt_social_text = esc_html__( 'Recommend', 'rtSocial' );
			} else {
				$rt_social_text = esc_html__( 'Share', 'rtSocial' );
			}
		}

		if ( 'horizontal' === $options['display_options_set'] ) {
			$fb_layout .= sprintf(
				'<div class="rtsocial-fb-%1$s-button"><a title="%2$s%3$s" class="rtsocial-fb-button %4$s" href="https://www.facebook.com/sharer.php?u=%5$s" rel="nofollow" target="_blank"></a></div>%6$s',
				$options['display_options_set'],
				$rt_social_text,
				$rtatitle,
				$class,
				( rawurlencode( get_permalink( $post->ID ) ) ),
				$fb_count
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$fb_layout .= sprintf(
					'%1$s<div class="rtsocial-fb-%2$s-button"><a title="%3$s: %4$s" class="rtsocial-fb-button %5$s" href="https://www.facebook.com/sharer.php?u=%6$s" rel="nofollow" target="_blank"></a></div>',
					$fb_count,
					$options['display_options_set'],
					$rt_social_text,
					$rtatitle,
					$class,
					( rawurlencode( get_permalink( $post->ID ) ) )
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$fb_layout .= sprintf(
						' <div class="rtsocial-fb-%1$s-button"><a title="%2$s: %3$s" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=%4$s" target= "_blank"></a></div>',
						$options['display_options_set'],
						$rt_social_text,
						$rtatitle,
						( rawurlencode( get_permalink( $post->ID ) ) )
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$fb_layout  = sprintf(
							'<div class="rtsocial-fb-icon" class="%1$s">',
							$rt_fb_style
						);
						$fb_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
						$fb_layout .= sprintf(
							' <div class="rtsocial-fb-icon-button"><a title="%1$s: %2$s" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=%3$s" target= "_blank"></a></div>%4$s',
							$rt_social_text,
							$rtatitle,
							( rawurlencode( get_permalink( $post->ID ) ) ),
							$fb_count
						);
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

		$pin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? ( sprintf(
			'<div class="rtsocial-%1$s-count"><div class="rtsocial-%1$s-notch"></div><span class="rtsocial-pinterest-count"></span></div>',
			$options['display_options_set']
		) ) : '';

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

		$pin_layout = sprintf(
			'<div class="rtsocial-pinterest-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$pin_layout .= sprintf(
				'<div class="rtsocial-pinterest-%1$s-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=%2$s&media=%3$s&description=%4$s" rel="nofollow" target="_blank" title="Pin: %5$s"></a></div>%6$s',
				$options['display_options_set'],
				get_permalink( $post->ID ),
				$thumb_src,
				$title,
				$rtatitle,
				$pin_count
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$pin_layout .= sprintf(
					'%1$s<div class="rtsocial-pinterest-%2$s-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=%3$s&media=%4$s&description=%5$s" rel="nofollow" target="_blank" title="Pin: %6$s"></a></div>',
					$pin_count,
					$options['display_options_set'],
					get_permalink( $post->ID ),
					$thumb_src,
					$title,
					$rtatitle
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$pin_layout .= sprintf(
						' <div class="rtsocial-pinterest-%1$s-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=%2$s&media=%3$s&description=%4$s" target= "_blank" title="Pin: %5$s"></a></div>',
						$options['display_options_set'],
						get_permalink( $post->ID ),
						$thumb_src,
						$title,
						$rtatitle
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$pin_layout  = '<div class="rtsocial-pinterest-icon">';
						$pin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
						$pin_layout .= sprintf(
							' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=%1$s&media=%2$s&description=%3$s" target= "_blank" title="Pin: %4$s"></a></div>%5$s',
							get_permalink( $post->ID ),
							$thumb_src,
							$title,
							$rtatitle,
							$pin_count
						);
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

		$lin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? ( sprintf(
			'<div class="rtsocial-%1$s-count"><div class="rtsocial-%1$s-notch"></div><span class="rtsocial-linkedin-count"></span></div>',
			$options['display_options_set']
		) ) : '';

		$lin_layout = sprintf(
			'<div class="rtsocial-linkedin-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$lin_layout .= sprintf(
				'<div class="rtsocial-linkedin-%1$s-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%3$s" rel="nofollow" target="_blank" title="Share: %4$s"></a></div>',
				$options['display_options_set'],
				rawurlencode( get_permalink( $post->ID ) ),
				rawurlencode( $rtatitle ),
				$rtatitle
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$lin_layout .= sprintf(
					'<div class="rtsocial-linkedin-%1$s-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%3$s" rel="nofollow" target="_blank" title="Share: %4$s"></a></div>',
					$options['display_options_set'],
					rawurlencode( get_permalink( $post->ID ) ),
					rawurlencode( $rtatitle ),
					$rtatitle
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$lin_layout .= sprintf(
						' <div class="rtsocial-linkedin-%1$s-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%3$s" target= "_blank" title="Share: %4$s"></a></div>',
						$options['display_options_set'],
						rawurlencode( get_permalink( $post->ID ) ),
						rawurlencode( $rtatitle ),
						$rtatitle
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$lin_layout  = '<div class="rtsocial-linkedin-icon">';
						$lin_layout .= sprintf(
							'<div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s" target= "_blank" title="Share: %3$s"></a></div>',
							rawurlencode( get_permalink( $post->ID ) ),
							rawurlencode( $rtatitle ),
							$rtatitle
						);
					}
				}
			}
		}

		$lin_layout .= '</div>';

		$active_services[ $lin ] = $lin_layout;
	}
	// Linked In End.
	// Sort by indexes.
	ksort( $active_services );

	// Form the ordered buttons markup.
	$active_services = implode( '', $active_services );

	// Rest of the stuff.
	$layout = sprintf(
		'<div class="rtsocial-container rtsocial-container-align-%1$s rtsocial-%2$s">',
		$options['alignment_options_set'],
		$options['display_options_set']
	);

	// Append the ordered buttons.
	$layout .= $active_services;

	// Hidden permalink.
	$layout .= sprintf(
		'<a rel="nofollow" class="perma-link" href="%1$s" title="%2$s"></a><input type="hidden" name="rts_id" class="rts_id" value="%3$s" />%4$s</div>',
		get_permalink( $post->ID ),
		esc_attr( get_the_title( $post->ID ) ),
		$post->ID,
		wp_nonce_field( 'rts_media_' . $post->ID, 'rts_media_nonce', true, false )
	);

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
 * 'active' = array('tw', 'fb', 'lin', 'pin');
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

		$tw_layout = sprintf(
			'<div class="rtsocial-twitter-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$tw_layout .= sprintf(
				'<div class="rtsocial-twitter-%1$s-button"><a title="Tweet: %2$s" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=%3$s%4$s&url=%5$s" rel="nofollow" target="_blank"></a></div>',
				$options['display_options_set'],
				$rtatitle,
				$rtstitle,
				$handle_string,
				$rtslink
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$tw_layout .= sprintf(
					'<div class="rtsocial-twitter-%1$s-button"><a title="Tweet: %2$s" class="rtsocial-twitter-button" href= "https://twitter.com/share?text=%3$s%4$s&url=%5$s" target= "_blank"></a></div>',
					$options['display_options_set'],
					$rtatitle,
					$rtstitle,
					$handle_string,
					$rtslink
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$tw_layout .= sprintf(
						' <div class="rtsocial-twitter-%1$s-button"><a title="Tweet: %2$s" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=%3$s%4$s&url=%5$s" target= "_blank"></a></div>',
						$options['display_options_set'],
						$rtatitle,
						$rtstitle,
						$handle_string,
						$rtslink
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$tw_layout  = '<div class="rtsocial-twitter-icon">';
						$tw_layout .= sprintf(
							' <div class="rtsocial-twitter-icon-button"><a title="Tweet: %1$s" class="rtsocial-twitter-icon-link" href= "https://twitter.com/share?text=%2$s%3$s&url=%4$s" target= "_blank"></a></div>',
							$rtatitle,
							$rtstitle,
							$handle_string,
							$rtslink
						);
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
		$fb_count    = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? ( sprintf(
			'<div class="rtsocial-%1$s-count"><div class="rtsocial-%1$s-notch"></div><span class="rtsocial-fb-count"></span></div>',
			$options['display_options_set']
		) ) : '';
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

		$fb_layout = sprintf(
			'<div class="rtsocial-fb-%1$s %2$s">',
			$options['display_options_set'],
			$rt_fb_style
		);

		$rt_social_text = '';

		if ( 'like_light' === $options['fb_style']
		|| 'like_dark' === $options['fb_style'] ) {
			$rt_social_text = esc_html__( 'Like', 'rtSocial' );
		} else {

			if ( 'recommend_light' === $options['fb_style']
			|| 'recommend_dark' === $options['fb_style'] ) {
				$rt_social_text = esc_html__( 'Recommend', 'rtSocial' );
			} else {
				$rt_social_text = esc_html__( 'Share', 'rtSocial' );
			}
		}

		if ( 'horizontal' === $options['display_options_set'] ) {
			$fb_layout .= sprintf(
				'<div class="rtsocial-fb-%1$s-button"><a title="%2$s: %3$s" class="rtsocial-fb-button %4$s" href="https://www.facebook.com/sharer.php?u=%5$s" rel="nofollow" target="_blank"></a></div>%6$s',
				$options['display_options_set'],
				$rt_social_text,
				$rtatitle,
				$class,
				$rtslink,
				$fb_count
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$fb_layout .= sprintf(
					'%1$s<div class="rtsocial-fb-%2$s-button"><a title="%3$s: %4$s" class="rtsocial-fb-button %5$s" href="https://www.facebook.com/sharer.php?u=%6$s" rel="nofollow" target="_blank"></a></div>',
					$fb_count,
					$options['display_options_set'],
					$rt_social_text,
					$rtatitle,
					$class,
					$rtslink
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$fb_layout .= sprintf(
						' <div class="rtsocial-fb-%1$s-button"><a title="%2$s: %3$s" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=%4$s" target= "_blank"></a></div>',
						$options['display_options_set'],
						$rt_social_text,
						$rtatitle,
						$rtslink
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$fb_layout  = sprintf(
							'<div class="rtsocial-fb-icon" class="%1$s">',
							$rt_fb_style
						);
						$fb_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-fb-count"></span></div>' : '';
						$fb_layout .= sprintf(
							' <div class="rtsocial-fb-icon-button"><a title="%1$s: %2$s" class="rtsocial-fb-icon-link" href="https://www.facebook.com/sharer.php?u=%3$s" target= "_blank"></a></div>%4$s',
							$rt_social_text,
							$rtatitle,
							$rtslink,
							$fb_count
						);
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

		$pin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? ( sprintf(
			'<div class="rtsocial-%1$s-count"><div class="rtsocial-%1$s-notch"></div><span class="rtsocial-pinterest-count"></span></div>',
			$options['display_options_set']
		) ) : '';

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
		$pin_layout = sprintf(
			'<div class="rtsocial-pinterest-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$pin_layout .= sprintf(
				'<div class="rtsocial-pinterest-%1$s-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=%2$s&media=%3$s&description=%4$s" rel="nofollow" target="_blank" title="Pin: %5$s"></a></div>%6$s',
				$options['display_options_set'],
				$rtslink,
				$thumb_src,
				$title,
				$rtatitle,
				$pin_count
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$pin_layout .= sprintf(
					'%1$s<div class="rtsocial-pinterest-%2$s-button"><a class="rtsocial-pinterest-button" href= "https://pinterest.com/pin/create/button/?url=%3$s&media=%4$s&description=%5$s" rel="nofollow" target="_blank" title="Pin: %6$s"></a></div>',
					$pin_count,
					$options['display_options_set'],
					$rtslink,
					$thumb_src,
					$title,
					$rtatitle
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$pin_layout .= sprintf(
						' <div class="rtsocial-pinterest-%1$s-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=%2$s&media=%3$s&description=%4$s" target= "_blank" title="Pin: %5$s"></a></div>',
						$options['display_options_set'],
						$rtslink,
						$thumb_src,
						$title,
						$rtatitle
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$pin_layout  = '<div class="rtsocial-pinterest-icon">';
						$pin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-pinterest-count"></span></div>' : '';
						$pin_layout .= sprintf(
							' <div class="rtsocial-pinterest-icon-button"><a class="rtsocial-pinterest-icon-link" href= "https://pinterest.com/pin/create/button/?url=%1$s&media=%2$s&description=%3$s" target= "_blank" title="Pin %4$s"></a></div>%5$s',
							$rtslink,
							$thumb_src,
							$title,
							$rtatitle,
							$pin_count
						);
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

		$lin_count = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? ( sprintf(
			'<div class="rtsocial-%1$s-count"><div class="rtsocial-%1$s-notch"></div><span class="rtsocial-linkedin-count"></span></div>',
			$options['display_options_set']
		) ) : '';

		$lin_layout = sprintf(
			'<div class="rtsocial-linkedin-%1$s">',
			$options['display_options_set']
		);

		if ( 'horizontal' === $options['display_options_set'] ) {
			$lin_layout .= sprintf(
				'<div class="rtsocial-linkedin-%1$s-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%3$s" rel="nofollow" target="_blank" title="Share: %4$s"></a></div>%5$s',
				$options['display_options_set'],
				$rtslink,
				$rtstitle,
				$rtatitle,
				$lin_count
			);
		} else {

			if ( 'vertical' === $options['display_options_set'] ) {
				$lin_layout .= sprintf(
					'%1$s <div class="rtsocial-linkedin-%2$s-button"><a class="rtsocial-linkedin-button" href= "https://www.linkedin.com/shareArticle?mini=true&url=%3$s&title=%4$s" rel="nofollow" target="_blank" title="Share: %5$s"></a></div>',
					$lin_count,
					$options['display_options_set'],
					$rtslink,
					$rtstitle,
					$rtatitle
				);
			} else {

				if ( 'icon' === $options['display_options_set'] ) {
					$lin_layout .= sprintf(
						' <div class="rtsocial-linkedin-%1$s-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=%2$s&title=%3$s" target= "_blank" title="Share: %4$s"></a></div>',
						$options['display_options_set'],
						$rtslink,
						$rtstitle,
						$rtatitle
					);
				} else {

					if ( 'icon-count' === $options['display_options_set'] ) {
						$lin_layout  = '<div class="rtsocial-linkedin-icon">';
						$lin_count   = ( empty( $options['hide_count'] ) || 1 !== (int) $options['hide_count'] ) ? '<div class="rtsocial-horizontal-count"><div class="rtsocial-horizontal-notch"></div><span class="rtsocial-linkedin-count"></span></div>' : '';
						$lin_layout .= sprintf(
							' <div class="rtsocial-linkedin-icon-button"><a class="rtsocial-linkedin-icon-link" href= "https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s" target= "_blank" title="Share: %3$s"></a></div>%4$s',
							$rtslink,
							$rtstitle,
							$rtatitle,
							$lin_count
						);
					}
				}
			}
		}

		$lin_layout .= '</div>';

		$active_services[ $lin ] = $lin_layout;
	}
	// Linked In End.
	// Sort by indexes.
	ksort( $active_services );

	// Form the ordered buttons markup.
	$active_services = implode( '', $active_services );

	// Rest of the stuff.
	$layout = sprintf(
		'<div class="rtsocial-container rtsocial-container-align-%1$s rtsocial-%2$s">',
		$options['alignment_options_set'],
		$options['display_options_set']
	);

	// Append the ordered buttons.
	$layout .= $active_services;

	// Hidden permalink.
	$layout .= sprintf(
		'<a title="%1$s" rel="nofollow" class="perma-link" href="%2$s"></a><input type="hidden" name="rts_id" class="rts_id" value="%3$s" />%4$s</div>',
		esc_attr( $rtatitle ),
		$rts_permalink,
		$post_obj->ID,
		wp_nonce_field( 'rts_media_' . $post_obj->ID, 'rts_media_nonce', true, false )
	);

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
				'inactive'              => array(),
				'fb_access_token'       => '',
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
			'inactive'              => array(),
			'fb_access_token'       => '',
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
		wp_enqueue_script( 'twitter-widget', ( 'https://platform.twitter.com/widgets.js' ), array(), true, true );
	}

	// Plugin CSS.
	wp_enqueue_style( 'rtsocial-styleSheet', plugins_url( '/styles/style.css', __FILE__ ), array(), filemtime( RTSOCIAL_PLUGIN_PATH . 'styles/style.css' ) );

	// Plugin JS.
	wp_enqueue_script( 'rtss-main', plugins_url( '/js/rtss-main.js', __FILE__ ), array( 'jquery' ), filemtime( RTSOCIAL_PLUGIN_PATH . 'js/rtss-main.js' ), true );

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
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			admin_url( 'options-general.php?page=rtsocial-options&rtnonce=' . wp_create_nonce( 'rtnonce' ) ),
			__( 'Settings', 'rtSocial' )
		);

		array_unshift( $links, $settings_link ); // before other links.
	}

	return $links;
}

register_activation_hook( __FILE__, 'rtsocial_plugin_activate' );

/**
 * Activation redirect to rtSocial Options Page.
 */
function rtsocial_plugin_activate() {
	add_option( 'rtsocial_plugin_do_activation_redirect', true );
}

add_action( 'admin_init', 'rtsocial_plugin_redirect' );

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
 * Latest news of rtcamp.
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

add_action( 'add_meta_boxes', 'rtsocial_add_meta_box' );

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function rtsocial_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'rtsocial_sectionid',
			esc_html__( 'rtSocial', 'rtSocial' ),
			'rtsocial_meta_box_callback',
			$screen,
			'side',
			'low'
		);
	}
}

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
	<?php esc_html_e( 'Exclude Social Sharing Icons', 'rtSocial' ); ?>
	</label>
	<?php
}

add_action( 'save_post', 'rtsocial_save_meta_box_data' );

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

/**
 * Display number of shares using WordPress HTTP API
 */
function rtss_wp_get_shares() {
	if ( isset( $_GET['security'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$security = wp_unslash( $_GET['security'] );
	} else {
		$security = '';
	}

	if ( isset( $_GET['post_id'] ) ) {
		$post_id = wp_unslash ( $_GET['post_id'] );
	} else {
		$post_id = '';
	}

	check_ajax_referer( 'rts_media_' . $post_id, 'security' );

	$options      = get_option( 'rtsocial_plugin_options' );
	$cache_key    = 'rtss_fb' . $post_id;
	$access_token = $options['fb_access_token'];
	$count        = get_transient( $cache_key ); // try to get value from WordPress cache.

	if ( ! $access_token ) {
		$count = false;
	}

	// if no value in the cache.
	if ( false === $count || 0 === count ) {
		$response = wp_remote_get(
			add_query_arg(
				array(
					'id'           => urlencode( get_permalink( $post_id ) ),
					'access_token' => $access_token,
					'fields'       => 'engagement',
				),
				'https://graph.facebook.com/v3.0/'
			)
		);
		$body     = json_decode( $response['body'] );
		$count    = intval( $body->engagement->share_count );

		set_transient( $cache_key, $count, 3600 ); // store value in cache for a 1 hour.
	}
	echo esc_html( $count );
	exit;
}

add_action( 'wp_ajax_rtss_wp_get_shares', 'rtss_wp_get_shares' );
add_action( 'wp_ajax_nopriv_rtss_wp_get_shares', 'rtss_wp_get_shares' );
