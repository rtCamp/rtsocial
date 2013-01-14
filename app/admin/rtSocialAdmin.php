<?php
/**
 * Description of rtSocialAdmin
 *
 * @author Ankit Gade <ankit.gade@rtcamp.com>
 */
if (!class_exists('rtSocialAdmin')) {
	
	class rtSocialAdmin{
		
		public $rtSocial_upgrade;
        public $rtSocial_settings;

		public function __construct() {

            //$rtSocial_support =  new rtSocialSupport();
            if (is_admin()) {
                add_action( 'admin_enqueue_scripts', array($this, 'ui') );
                add_action( 'admin_menu', array($this, 'menu') );
            }
            //$this->rtSocial_upgrade		= new rtSocialUpgrade();
            //$this->rtSocial_settings	= new rtSocialSettings();
        }
        
		/**
         * Generates the Admin UI
         *
         * @param string $hook
         */
        public function ui($hook) {
            $admin_ajax = admin_url('admin-ajax.php');
            wp_enqueue_script('rtsocial-admin', RTSOCIAL_URL . 'app/assets/js/admin.js');
            wp_localize_script('rtsocial-admin', 'rtsocial_admin_ajax', $admin_ajax);
            wp_enqueue_style('rtsocial-admin', RTSOCIAL_URL . 'app/assets/css/admin.css');
        }
        
        
		/**
         * Admin Menu
         *
         * @global string RTSOCIAL_TXT_DOMAIN
         */
        public function menu() {
            add_options_page( 'rtSocial Options Page', 'rtSocial Options', 'manage_options', 'rtsocial-revised-options', $this->rtSocial_render_page() );
        }
        
		/**
         * Renders the setting page
         */
		public function rtSocial_render_page(){
			
			
		}
        
	}

} ?>
