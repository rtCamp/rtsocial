<?php
/**
 * Description of RTSocialSettings
 *
 * @author Ankit Gade <ankit.gade@rtcamp.com>
 */
if (!class_exists('RTSocialSettings')) {

    class RTSocialSettings {

        public function __construct() {
            add_action('admin_init', array($this, 'settings') );
        }

        /**
         * Register Settings
         * 
         * @global string RTSOCIAL_TXT_DOMAIN
         */
        public function settings() {

            add_settings_section( 'rts-settings', __('RTSocial placement settings', RTSOCIAL_TXT_DOMAIN), '', 'rtsocial-revised-options' );
            add_settings_field('rts-placement', __('TOP', RTSOCIAL_TXT_DOMAIN), array($this, 'radio'), 'rtsocial-revised-options', 'rts-settings', array('option' => 'button_placement', 'radios' => array( 2 => __('Show social icons at top of post', RTSOCIAL_TXT_DOMAIN), 1 => __('Show social icons at top of post', RTSOCIAL_TXT_DOMAIN)), 'default' => 1 ) );

            register_setting('rtsocial_settings', 'rtsocial_options', array($this, 'sanitize'));
        }

        /**
         * Sanitizes the settings
         */
        public function sanitize($input) {

            add_settings_error('Success', 'rts-update-success', __('Settings saved successfully', RTSOCIAL_TXT_DOMAIN ), 'updated');
            return $input;
        }

        /**
         * Output a checkbox
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function checkbox($args) {
            
        }

        /**
         * Outputs Radio Buttons
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function radio($args) {
            print_r('hello');
            global $rtSocial;
            $options = $rtSocial->options;
            $defaults = array(
                'position' => 'top',
                'radios' => array(),
                'default' => '',
            );
            $args = wp_parse_args($args, $defaults);
            extract($args);
            if (empty($option) || ( 2 > count($radios) )) {
                if (empty($option))
                    trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the follwoing format array( \'option\' => \'option_name\' )', BP_MEDIA_TXT_DOMAIN));
                if ( 2 > count($radios) )
                    trigger_error(__('Need to specify atleast to radios else use a checkbox instead', BP_MEDIA_TXT_DOMAIN));
                return;
            }
            if (empty($options[$option])) {
                $options[$option] = $defaults;
            }
            foreach ($radios as $value => $desc) { ?>
                <label for="<?php echo sanitize_title($desc); ?>"><input<?php checked($options[$option], $value); ?> value='<?php echo $value; ?>' name='rtsocial_plugin_options[<?php echo $option; ?>]' id="<?php echo sanitize_title($desc); ?>" type='radio' /><?php echo $desc; ?></label><br /><?php
            }
        }

        /**
         * Outputs Dropdown
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function dropdown($args) {
            
        }

        /**
         * Outputs a Button
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function button($args) {
            
        }

    }

} ?>