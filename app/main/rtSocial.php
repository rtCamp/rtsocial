<?php

/**
 * Description of rtSocial
 *
 * @author rtCamp <rtcamp1@gmail.com>
 * @author Ankit Gade <ankit.gade@rtcamp.com>
 * 
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

    class rtSocial {

	public $text_domain = 'rtsocial';
	public $support_email = 'support@rtcamp.com';
        public $options;
        public $default_query = array(

		'%permalink%' => '',
		'%title%' => 'post_title',
		'%thumb%' => ''
	);

        /**
         * Constructor of class rtSocial.
         */
	public function __construct() {
            /* Define all the required constants */
            $this->constants();
            $this->get_option();

            add_action('the_excerpt', array($this,'render_button') );
            add_action('the_content', array($this,'render_button') );

            add_action( 'wp_enqueue_scripts', array( $this , 'rtsocial_add_scripts' ) );

            /* for handling g+ count via curl */
            add_action( 'wp_ajax_rtsocial_gplus', array($this,'rtsocial_gplus_handler') );
            add_action( 'wp_ajax_nopriv_rtsocial_gplus', array($this,'rtsocial_gplus_handler') );
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

        /**
         * Add scripts and styles.
         */
        public function rtsocial_add_scripts(){

            wp_enqueue_style ( 'rtsocial-main-css', RTSOCIAL_CSS_URL.'/main.css' , array(), false, 'all' );
            wp_enqueue_script ( 'rtsocial-main-js', RTSOCIAL_JS_URL.'/main.js' , array(), false, true );
            wp_localize_script('rtsocial-main-js','rts_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }

        /**
         * Display social buttons on front end.
         * @param type $content
         * @return type
        */
        public function render_button($content) {

            /* Get how to render buttons */
            $button_style = $this->options['button_style'];

            $rtSocial_options = get_option( 'rtsocial_options' );
            if( isset($rtSocial_options['button']) ){
                $markup = '';

                $markup .= '<ul class="rtsocial-list '.$button_style.'">';
                foreach($rtSocial_options['button'] as $button){

                    $markup .= '<li class="rtsocial-button-wrap ' . $button[ 'type' ] . ' ' . sanitize_title($button_style . $button[ 'network' ]) . ' ' .'">'.$this->rtsocial_render_button($button).'</li>';
                }
                $markup .='<li class="perma-link"><a href="'.  get_permalink(get_the_ID()).'">'.  get_the_title(get_the_ID()).'</a></li>';
                $markup .= '</ul>';
            }

            return $content.$markup;
        }

        /**
         * Append valid parameters to the query url.
         * @param type $qappend
         * @param type $post_obj
         * @return type
        */
        function map_query( $qappend, $post_obj ) {

                /* Get setting data into a variable */
                $rtSocial_data = $this->options;

                foreach ( $this->default_query as $word => $replacement ) {
                        if ( $replacement != '' ) {
                                $qappend = str_replace( $word, rawurlencode( $post_obj->{$replacement} ), $qappend );
                        }
                }

                $qappend    = str_replace( '%25permalink%25', rawurlencode( get_permalink( $post_obj->ID ) ), $qappend );
                $qappend    = str_replace( '%25title%25', rawurlencode( $post_obj->post_title ), $qappend );

                /* Check if twitter handle is present */
                if(array_key_exists('tw_handle', $rtSocial_data) && !empty($rtSocial_data['tw_handle']) )
                    $qappend    = str_replace( '%25tweethandle%25', rawurlencode( $rtSocial_data['tw_handle'] ), $qappend );

                /* Check if related twitter accounts are present */
                if(array_key_exists('related_tw_handle', $rtSocial_data) && !empty($rtSocial_data['related_tw_handle']) )
                    $qappend    = str_replace( '%25relatedacc%25', rawurlencode( $rtSocial_data['related_tw_handle'] ), $qappend );
                $qappend    = str_replace( '%25source%25', rawurlencode( get_bloginfo('name') ), $qappend );

                $src_thumb = '';
                if(has_post_thumbnail($post_obj->ID) ){
                    $thumb_id   = get_post_thumbnail_id( $post_obj->ID );
                    $img_thumb = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
                    $img_full  = wp_get_attachment_image_src( $thumb_id, 'full' );
                    $src_thumb  = $img_thumb[0];
                    $src_full   = $img_full[0];
                    $qappend  = str_replace( '%25mediathumb%25', ( $src_full ), $qappend );
                }

                $qappend  = str_replace( '%25thumb%25', ( $src_thumb ), $qappend );
                return $qappend;
        }

        /**
         * Displays the button at front end.
         * @global type $post
         * @param type $button_args buttons array
         * @return string markup for button.
        */
        function rtsocial_render_button( $button_args = false ) {

                    if ( ! is_array( $button_args ) && count( $button_args ) <= 0 ) {
                            return;
                    }

                    $sharable = $button_args[ 'post_obj' ];
                    $button_style = $this->options['button_style'];
                    
                    if ( ! $sharable ) {
                            global $post;
                            $sharable = $post;
                    }
                    
                    /* Check whether to show count */
                    $count_show = empty($this->options['hide_count'])? 'rts-count-show' : '';

                    if ( $button_args[ 'query' ] && is_array( $button_args[ 'query' ] ) ) {

                            /* if post has thumbnail the add %thumb% to query */
                            if(has_post_thumbnail($sharable->ID) ){

                                $button_args[ 'query' ]['picture'] = '%thumb%';
                            }
                            $button_args[ 'query' ]['redirect_uri'] = 'http://abhishek.rtcamp.info';                        
                            $appendquery = http_build_query( $button_args[ 'query' ] );
                            $qappend = $this->map_query( $appendquery, $sharable );
                    }

                    $href =  $button_args[ 'callback' ] . '?' . $qappend;
                    $og_title = $sharable->post_title;
                    $title = esc_attr( $og_title );
                    $excerpt = esc_attr( $sharable->post_excerpt );

                    // why use switch? because we will extend this!
                    switch (trim($button_style) ) {
                            case 'rt-naked' :
                                    $buttoncontent = $button_args[ 'network' ];
                                    break;

                            case 'rt-light' :

                            case 'rt-large' :

                            case 'rt-icon' :

                                    break;
                    }
                    if ( $button_args[ 'suffix' ] == '' ) {
                            $button_args[ 'suffix' ] = 'on ' . ucfirst( $button_args[ 'network' ] );
                    }
                    if ( $button_args[ 'type' ] == '' ) {
                            $button_args[ 'suffix' ] = $button_args[ 'network' ];
                    }

                    $buttoncontent = $button_args[ 'network' ];
                    $button = '<a class="rtsocial-button-link '.$count_show.'" target="_blank" href="'.$href.'" title="' . $button_args[ 'prefix' ] .' '. $title .' '. $button_args[ 'suffix' ] . '">'.$buttoncontent.'</a>';
                    //$button .= '<a class="perma-link" rel="nofollow" href="'.  get_permalink($sharable->ID).'">'.  get_the_title($sharable->ID).'</a>';
                    
                    /* If count has to be shown , then append div for count */
                    if( empty($this->options['hide_count']) ){
                        $button .= '<div class="rts-count rtsocial-'.$button_style.'-count"><div class="rts-notch rts-'.$button_style.'-notch"></div><div class="rts-span-count rts-'.  sanitize_title($button_args['type']).'-count">0</div></div>';
                    }
                    return $button;
        }
        
        /**
         * Function to get G+ count value via curl         * 
         */
        function rtsocial_gplus_handler(){
            echo '23';
            die(1);
            if (isset($_POST['action']) && $_POST['action'] == 'rtsocial_gplus') {
                $url = $_POST['url'];
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                $curl_results = curl_exec ($curl);
                curl_close ($curl);

                $json = json_decode($curl_results, true);

                echo intval( $json[0]['result']['metadata']['globalCounts']['count'] );
                die(1);
            }
        }
}