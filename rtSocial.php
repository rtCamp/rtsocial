<?php
/*
  Plugin Name: rtSocial
  Plugin URI: http://rtcamp.com/rtsocial/
  Author: rtCamp, rahul286, rutwick, saurabhshukla
  Author URI: http://rtcamp.com/
  Version: 2.1.1
  Description: It is the lightest social sharing plugin, uses non-blocking Javascript and a single sprite to get rid of all the clutter that comes along with the sharing buttons.
  Tags: rtcamp, social, sharing, share, social links, twitter, facebook, pin it, pinterest, linkedin, linked in, linked in share, google plus, google plus share, gplus share, g+ button, g+ share, plus one button, social share, social sharing
 */
/**
 * Main Plugin Class, handles hooks and filters and initiates all functionality
 * @author Saurabh Shukla <saurabh.shukla@rtcamp.com>
 * @author rtCamp <contact@rtcamp.com>
 */
class rtSocial {

	/**
	 *
	 * @var array Describes the buttons in detail, can be extended to add custom buttons
	 */
	var $default_buttons = array(
		array(
			'type' => 'fb-share',
			'callback' => 'facebook.com/sharer.php',
			'post_obj' => null,
			'query' => array(
				'href' => '%permalink%'
			),
			'prefix' => 'share',
			'suffix' => 'on facebook',
			'network' => 'facebook'
		),
		array(
			'type' => 'twitter',
			'callback' => 'twitter.com/share',
			'post_obj' => null,
			'query' => array(
				'url' => '%permalink%',
				'text' => '%title%',
				'via' => '',
				'related' => '',
				'hashtags' => '%rt_s_tw_hashtag%'
			),
			'prefix' => 'Tweet',
			'suffix' => '',
			'network' => 'twitter'
		),
		array(
			'type' => 'linked-in',
			'callback' => 'linkedin.com/shareArticle',
			'post_obj' => null,
			'query' => array(
				'mini' => true,
				'url' => '%permalink%',
				'title' => '%title%',
				'summary' => '%excerpt'
			),
			'prefix' => 'Share',
			'suffix' => 'on Linked In',
			'network' => 'linked in'
		),
		array(
			'type' => 'google',
			'callback' => 'plus.google.com/share',
			'post_obj' => null,
			'query' => array(
				'url' => '%permalink%'
			),
			'prefix' => 'Plus 1',
			'suffix' => 'on Google+',
			'network' => 'google +'
		),
		array(
			'type' => 'pinterest',
			'callback' => 'pinterest.com/pin/create/button/',
			'post_obj' => null,
			'query' => array(
				'url' => '%permalink%',
				'media' => '%thumb%',
				'description' => '%title%'
			),
			'prefix' => 'Pin',
			'suffix' => 'on your Pinterest Board',
			'network' => 'pinterest'
		)
	);

	/**
	 * @var array Matches %template_string% with post object properties. Will be useful for custom buttons.
	 */
	var $default_query = array(
		'%permalink%' => '',
		'%title%' => 'post_title',
		'%thumb%' => '',
		'%excerpt%' => 'post_excerpt',
		'%content%' => 'post_content'
	);

	/**
	 *
	 */
	function __construct(){
		global $rtsocial_admin;
		add_action('admin_menu',array($rtsocial_admin,'generate_ui'));
	}

	/**
	 *
	 * @param type $qappend
	 * @param type $post_obj
	 * @return type
	 */
	function map_query( $qappend, $post_obj ) {

		foreach ( $this->default_query as $word => $replacement ) {
			if ( $replacement != '' ) {
				$qappend . replace( $word, rawurlencode( $post_obj->{$replacement} ) );
			}
		}
		$qappend . replace( '%permalink%', rawurlencode( get_permalink( $post_obj->ID ) ) );
		$thumb_id = get_post_thumbnail_id( $post_obj->ID );
		$qappend . replace( '%thumb%', rawurlencode( wp_get_attachment_image_src( $thumb_id, 'full' ) ) );
		$qappend . replace( '%summary%', htmlentities( $post_obj->post_excerpt ) );
		return $qappend;
	}

	function rtsocial_options( $args ) {
		$defaults = get_option( 'rtsocial_plugin_options' );
		if ( ! $args ) {
			return $defaults;
		}
		$options = wp_parse_args( $args, $defaults );
		return $options;
	}

	function rtsocial_render_github( $github_un = 'rtcamp', $position = 'right', $color = 'gray_6d6d6d', $nofollow = true ) {
		$gitbutton = '<a href="https://github.com/' . $github_un . '">';
		$gitbutton .= '<img src="https://s3.amazonaws.com/github/ribbons/forkme_' . $position . '_' . $color . '.png" alt="Fork ' . $github_un . ' on GitHub"';
		$gitbutton .= ' style="position: absolute; top: 0;border: 0;';
		if ( $position == 'right' ) {
			$gitbutton .= 'right:0;';
		} else {
			$gitbutton .= 'left:0;';
		}
		if ( $nofollow == true ) {
			$gitbutton .= ' rel="nofollow"';
		}
		$gitbutton .= '" target="_blank"></a>';
		return $gitbutton;
	}

	function rtsocial_render_button( $button_args = false ) {
		if ( ! is_array( $button_args ) && count( $button_args ) <= 0 ) {
			return;
		}
		$sharable = $button_args[ 'post_obj' ];
		if ( ! $sharable ) {
			global $post;
			$sharable = $post;
		}

		if ( $button_args[ 'query' ] && is_array( $button_args[ 'query' ] ) ) {
			$appendquery = http_build_query( $button_args[ 'query' ] );
			$qappend = $this->map_query( $appendquery, $sharable );
		}

		$href = $button_args[ 'callback' ] . '?' . $qappend;

		$og_title = $sharable->post_title;
		$title = esc_attr( $og_title );
		$excerpt = esc_attr( $sharable->post_excerpt );

		// why use switch? because we will extend this!
		switch ( $button_args[ 'style' ] ) {
			case 'naked' :
				$buttoncontent = $button_args[ 'network' ];
				break;

			case 'light' :

			case 'large' :

			case 'icon' :

				break;
		}

		if ( $button_args[ 'suffix' ] == '' ) {
			$button_args[ 'suffix' ] = 'on ' . ucfirst( $button_args[ 'network' ] );
		}
		if ( $button_args[ 'type' ] == '' ) {
			$button_args[ 'suffix' ] = $button_args[ 'network' ];
		}

		$buttoncontent = $button_args[ 'network' ];

		$button = '<div class="rtsocial-button-wrap ' . $button_args[ 'type' ] . ' ' . $button_args[ 'style' ] . $button_args[ 'network' ] . ' ' . '">';
		$button .= '<div class="rtsocial-button">';
		$button .= '<a';
		if ( $button_args[ 'nofollow' ] == true ) {
			$button .= ' rel="nofollow"';
		}
		$button .= ' class="rtsocial-button-link" target="_blank"';
		$button .= ' href="' . $href . '"';
		$button .= ' title="' . $button_args[ 'prefix' ] . $title . $button_args[ 'suffix' ] . '"';
		$button .= '</div>';
		if ( $button_args[ 'hidecount' ] == false ) {
			$button .= '<div class="rtsocial-button-count">';
			$button .= '<span class="rtsocial-button-notch">' . $buttoncontent . '</span>';
			$button .= '<span class="rtsocial-button-count">0</span>';
			$button .= '</div>';
		}
		$button .= '</div>';

		return $button;
	}

	function rtsocial_render_widget( $args = null ) {
		$options = $this->rtsocial_options( $args );

		$widget = '<div class="rtsocial-widget">';

		foreach ( $options[ 'buttons' ]as $button ) {
			$widget .= $this->rtsocial_render_button( $button );
		}

		$widget .= '</div>';

		return $widget;

	}

	function rtsocial_gplus_handler() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'rtsocial_gplus' ) {
			$url = $_POST[ 'url' ];
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, "https://clients6.google.com/rpc" );
			curl_setopt( $curl, CURLOPT_POST, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
			$curl_results = curl_exec( $curl );
			curl_close( $curl );

			$json = json_decode( $curl_results, true );

			echo intval( $json[ 0 ][ 'result' ][ 'metadata' ][ 'globalCounts' ][ 'count' ] );
			die( 1 );
		}
	}

	function rtsocial_needs_upgrade() {
		$options = get_option( 'rtsocial_plugin_options' );
		$active_networks = $options[ 'active' ];

		return true;
	}

}

define('RTS_PATH',plugin_dir_path( __FILE__ ) );
define('RTS_URL',plugin_dir_url( __FILE__ ) );

require_once(RTS_PATH.'Admin.php');
require_once(RTS_PATH.'SocialWidget.php');

global $rtsocial, $rtsocial_admin, $rtsocial_widget;
$rtsocial_admin		= new Admin();
$rtsocial_widget	= new SocialWidget();
$rtsocial			= new rtSocial();


