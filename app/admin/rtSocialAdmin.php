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

                add_action( 'admin_enqueue_scripts', array($this, 'ui') );
                add_action( 'admin_menu', array($this, 'menu') );
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

            add_options_page('RTSocial', 'RTSocial Setting', 'manage_options', 'rtsocial-revised-options', array( $this, 'settings_page' ) );
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
        public function render_page($page){ ?>

            <h2><?php _e( 'RTSocial Options', RTSOCIAL_TXT_DOMAIN ); ?></h2>
            <div class="wrap rtsocial-admin">
                <div title="Click to toggle" class="handlediv"><br></div>                
                <form method="post" action="options.php" name="rtsocial_setting_form" id="rtsocial_setting_form"><?php
                    settings_fields( 'rtsocial_settings' );
                    do_settings_sections( $page );
                    submit_button(); ?>
                </form>
            </div><?php
        }
    }
} ?>