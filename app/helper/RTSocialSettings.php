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
            add_settings_section( 'rts-settings', __('<h3 class="hndle">RTSocial settings</h3>', RTSOCIAL_TXT_DOMAIN), '', 'rtsocial-revised-options' );
            /* Button placement radio buttons */
            add_settings_field('rts-placement', __('Placement', RTSOCIAL_TXT_DOMAIN), array($this, 'radio'), 'rtsocial-revised-options', 'rts-settings', array('option' => 'button_placement', 'radios' => array( 3 => __('TOP<br/>Social-media sharing buttons will appear below post-title and above post-content', RTSOCIAL_TXT_DOMAIN) ,2 => __('BOTTOM<br/>Social-media sharing buttons will appear after (below) post-content', RTSOCIAL_TXT_DOMAIN), 1 => __('MANUAL<br/>For manual placement, please use this function call in your template', RTSOCIAL_TXT_DOMAIN)), 'default' => 1 ) );
            /* Button style radio buttons */
            add_settings_field('rts-button-style', __('Button Style', RTSOCIAL_TXT_DOMAIN), array($this, 'radio'), 'rtsocial-revised-options', 'rts-settings', array('option' => 'button_style', 'radios' => array( 4 => __('Naked', RTSOCIAL_TXT_DOMAIN),3 => __('Light', RTSOCIAL_TXT_DOMAIN) ,2 => __('Large', RTSOCIAL_TXT_DOMAIN), 1 => __('Icon', RTSOCIAL_TXT_DOMAIN)), 'default' => 3 ) );
            /* Button alignment radio buttons */
            add_settings_field('rts-alignment', __('Alignment Settings', RTSOCIAL_TXT_DOMAIN), array($this, 'radio'), 'rtsocial-revised-options', 'rts-settings', array('option' => 'button_alignment', 'radios' => array( 4 => __('Left', RTSOCIAL_TXT_DOMAIN),3 => __('Right', RTSOCIAL_TXT_DOMAIN) ,2 => __('Center', RTSOCIAL_TXT_DOMAIN), 1 => __('None', RTSOCIAL_TXT_DOMAIN)), 'default' => 3 ) );
            /* Hide count checkbox */
            add_settings_field('rts-hide-count', __('Hide Count', RTSOCIAL_TXT_DOMAIN), array($this, 'checkbox'), 'rtsocial-revised-options', 'rts-settings', array('option' => 'hide_count', 'desc' => __('Hide Count', RTSOCIAL_TXT_DOMAIN)) );
            
            /* Twitter Setting section */
            add_settings_section( 'rts-tw-settings', __('<h3 class="hndle">Twitter Button Settings</h3>', RTSOCIAL_TXT_DOMAIN), '', 'rtsocial-revised-options' );
            /* Twitter handle */
            add_settings_field('rts-tw-handle', __('Twitter Handle', RTSOCIAL_TXT_DOMAIN), array($this, 'text'), 'rtsocial-revised-options', 'rts-tw-settings', array('option' => 'tw_handle', 'input' => '', 'default' => '' ) );
            /* Related Twitter Handle setting field */
            add_settings_field('rts-related-tw-handle', __('Related twitter handle', RTSOCIAL_TXT_DOMAIN), array($this, 'text'), 'rtsocial-revised-options', 'rts-tw-settings', array('option' => 'related_tw_handle', 'input' => '', 'default' => '' ) );
            register_setting( 'rtsocial_settings', 'rtsocial_options', array($this, 'sanitize') );
        }

        /**
         * Sanitizes the settings
         */
        public function sanitize($input) {
            
            return $input;
        }

        public function text($args){

            global $rtSocial;
            $options = $rtSocial->options;
            $defaults = array(
                'option' => '',
                'input'=>'',
                'default' => ''
            );
            $args = wp_parse_args($args, $defaults);
            extract($args);
            if (empty($option)) {
                trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the follwoing format array( \'option\' => \'option_name\' ) ', RTSOCIAL_TXT_DOMAIN));
                return;
            }
            if ( !isset($options[$option]) )
                $options[$option] = ''; ?>
            <label for="<?php echo $option; ?>">
                <input name="rtsocial_options[<?php echo $option; ?>]" id="<?php echo $option; ?>" value="<?php echo $options[$option]; ?>" type="text" />
            </label><?php
        }

        /**
         * Output a checkbox
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function checkbox($args) {

            global $rtSocial;
            $options = $rtSocial->options;
            $defaults = array(
                'option' => '',
                'desc' => '',
            );
            $args = wp_parse_args($args, $defaults);
            extract($args);
            if (empty($option)) {
                trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the follwoing format array( \'option\' => \'option_name\' ) ', RTSOCIAL_TXT_DOMAIN));
                return;
            }
            if (!isset($options[$option]))
                $options[$option] = ''; ?>
            <label for="<?php echo $option; ?>">
                <input<?php checked($options[$option]); ?> name="rtsocial_options[<?php echo $option; ?>]" id="<?php echo $option; ?>" value="1" type="checkbox" />                
            </label><?php
        }

        /**
         * Outputs Radio Buttons
         * 
         * @global type $rtSocial
         * @param array $args
         */
        public function radio($args) {
            global $rtSocial;
            $options = $rtSocial->options;
            $defaults = array(
                'option' => '',
                'radios' => array(),
                'default' => '',
            );
            $args = wp_parse_args($args, $defaults);
            extract($args);
            if (empty($option) || ( 2 > count($radios) )) {
                if (empty($option))
                    trigger_error(__('Please provide "option" value ( required ) in the argument. Pass argument to add_settings_field in the follwoing format array( \'option\' => \'option_name\' )', RTSOCIAL_TXT_DOMAIN));
                if ( 2 > count($radios) )
                    trigger_error(__('Need to specify atleast to radios else use a checkbox instead', BP_MEDIA_TXT_DOMAIN));
                return;
            }
            if (empty($options[$option])) {
                $options[$option] = $default;
            }
            foreach ($radios as $value => $desc) { ?>
                <label for="<?php echo sanitize_title($desc); ?>"><input<?php checked($options[$option], $value); ?> value='<?php echo $value; ?>' name='rtsocial_options[<?php echo $option; ?>]' id="<?php echo sanitize_title($desc); ?>" type='radio' /><?php echo $desc; ?></label><br /><?php
            }
        }

    }

} ?>