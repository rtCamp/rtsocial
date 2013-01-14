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
	
	public function __construct() {

		$this->constants();
		$rtSocialAdmin = new rtSocialAdmin();
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
		if ( ! defined( 'BP_MEDIA_VERSION' ) )
			define( 'BP_MEDIA_VERSION', '3.0' );
	}

}
