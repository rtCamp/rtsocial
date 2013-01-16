<?php

/**
 * Description of rtSocial
 *
 * @author Ankit Gade <ankit.gade@rtcamp.com>
 * 
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

    class rtSocial {
	
	public $text_domain = 'rtsocial';
	public $support_email = 'support@rtcamp.com';
        public $options;
	
	public function __construct() {
            /* Define all the required constants */
            $this->constants();
            $this->get_option();

            add_action('the_excerpt', array($this,'render_button') );
	}
	
	/**
	 * Defines all the constants used in plugin
	 */
	public function constants() {
            /* Text domain */
            if ( ! defined( 'RTSOCIAL_TXT_DOMAIN' ) )
                    define( 'RTSOCIAL_TXT_DOMAIN', $this->text_domain );

            /* If the plugin is installed. */
            if ( ! defined( 'RTSOCIAL_IS_INSTALLED' ) )
                    define( 'RTSOCIAL_IS_INSTALLED', 1 );

            /* Current Version. */
            if ( ! defined( 'RTSOCIAL_VERSION' ) )
                    define( 'RTSOCIAL_VERSION', '3.0' );
    }
    
    public function get_option() {
            $this->options = get_option( 'rtsocial_options' );
    }
    
    public function render_button($content) {
        
        $rtSocial_options = get_option( 'rtsocial_options' );

        if( isset($rtSocial_options['buttons']) ){

            echo '<ul>';
            foreach($rtSocial_options['buttons'] as $index=>$button){
                
                echo '<li>'. $this->rtsocial_render_button($button) .'</li>';
            }
            echo '</ul>';
        }        
    }

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

    /**
     * Displays the button at front end.
     * @global type $post
     * @param type $button_args buttons array
     * @return string markup for button.
     */
    function rtsocial_render_button( $button_args = false ) {
        
                $button_args[ 'style' ] = 'naked';

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
		$button .= ' class="rtsocial-button-link" target="_blank"';
		$button .= ' href="' . $href . '"';
		$button .= ' title="' . $button_args[ 'prefix' ] . $title . $button_args[ 'suffix' ] . '">';
                $button .= $buttoncontent.'</a>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
        }
}