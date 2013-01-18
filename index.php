<?php
/*
  Plugin Name: rtSocial Revised
  Plugin URI: http://rtcamp.com/rtsocial
  Description: This plugin is test for rtSocial.
  Version: 2.4
  Author: rtCamp
  Author URI: http://rtcamp.com
  Text domain: rtSocial
 */

/* Define plugin directory path */
if ( ! defined( 'RTSOCIAL_PATH' ) )
	define( 'RTSOCIAL_PATH', plugin_dir_path( __FILE__ ) );

/* Define plugins directory url */
if ( ! defined( 'RTSOCIAL_URL' ) )
	define( 'RTSOCIAL_URL', plugin_dir_url( __FILE__ ) );

/* Define plugins javascript directory path */
if ( ! defined( 'RTSOCIAL_JS_URL' ) )
	define( 'RTSOCIAL_JS_URL', plugin_dir_url( __FILE__ ).'app/assets/js' );

/* Define plugins css directory path */
if ( ! defined( 'RTSOCIAL_CSS_URL' ) )
	define( 'RTSOCIAL_CSS_URL', plugin_dir_url( __FILE__ ).'app/assets/css' );

function rtsocial_autoloader( $class_name ) {
	$rtlibpath = array(
		'app/helper/' . $class_name . '.php',
		'app/admin/' . $class_name . '.php',
		'app/main/' . $class_name . '.php'
	);
        
	foreach ( $rtlibpath as $i => $path ) {
		$path = RTSOCIAL_PATH . $path;
		if ( file_exists( $path ) ) {
			include $path;
			break;
		}
	}
}
spl_autoload_register( 'rtsocial_autoloader' );

global $rtSocial, $rtSocialAdmin;
$rtSocial = new rtSocial();
$rtSocialAdmin = new rtSocialAdmin();