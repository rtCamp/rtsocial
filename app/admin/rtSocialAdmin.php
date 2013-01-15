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
        public $rtSocial_options;
        

        public function __construct() {

            if (is_admin()) {
                add_action( 'admin_init', array($this,'rtsocial_register_setting') );
                add_action( 'admin_enqueue_scripts', array($this, 'ui') );
                add_action( 'admin_menu', array($this, 'menu'),9 );
            }
            $this->rtSocial_settings = new RTSocialSettings();
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
            add_menu_page(__('RTSocial', RTSOCIAL_TXT_DOMAIN), __('RTSocial', RTSOCIAL_TXT_DOMAIN), 'manage_options', 'rtsocial-revised-options', array($this, 'settings_page') );
        }

        /**
         * Render the BuddyPress Media Settings page
         */
        public function settings_page() {
            $this->render_page('rtsocial-revised-options', true);
        }

        /**
         * Renders the setting page
         */
        public function render_page(){ ?>
            <div class="wrap rtsocial-admin">
                <h2><?php _e( 'RTSocial Options' ); ?></h2>
                <form method="post" action="options.php" name="rtsocial_setting_form" id="rtsocial_setting_form"><?php
                    settings_fields( 'rtsocial_settings' );
                    do_settings_sections( __FILE__ ); 
                    submit_button(); ?>
                </form>
            </div><?php
        }
        
        /**
         * Register Settings
         */
        public function rtsocial_register_setting(){
            
            register_setting( 'rtsocial_plugin_options', 'rtsocial_plugin_options' );
        }

    }
} ?>