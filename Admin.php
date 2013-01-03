<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Admin class, renders Settings UI, to store and modify settings
 * @author Saurabh Shukla <saurabh.shukla@rtcamp.com>
 * @author rtCamp <contact@rtcamp.com>
 */
class Admin {

	/**
	 *
	 * @var array  default options
	 */
	public $rt_social_options	= array(
		//active buttons
		'active'		=> array(
			'twitter',
			'fb-share',		//will add fb-like, once am clear with fb's new inbuilt like
			'linkedin',
			'gplus',
			'pinterest'
		),

		//general options
		'hidecount'		=> false,
		'style'			=> 'light',
		'nofollow'		=> true,

		//twitter specific options
		'tw'			=> array(
			'handle'		=> '',
			'relhandle'		=> '',
			'hashtagon'		=> false
		),

		//git specific options
		'gitusername'	=> ''
	);

	public function __construct() {

	}

	/**
	 * Mixes and matches default options and stored options
	 * Sets $rt_social_options for further use
	 */
	function get_options(){

		$default_options	= $this->rt_social_options;
		$stored_options		= get_option('rt_social_options');
		$this->rt_social_options = wp_parse_args($stored_options, $default_options);

	}

	/**
	 * Updates stored options with new options
	 *
	 * @param array $newoptions
	 */
	function set_options($newoptions){

		$this->get_options();
		$default_options	= $this->rt_social_options;
		$this->rt_social_options	= wp_parse_args($newoptions, $default_options);

		update_option('rt_social_options',$this->rt_social_options);
	}

	/**
	 * Renders the tab for setting active/inactive sharing buttons
	 */
	function active_buttons() {

		echo '<h3>Active Buttons</h3>';

		echo '<ul id="rtsocial-sorter-active" class="buttons-active">';
		if ( isset( $this->rt_social_options[ 'active' ] ) && ! empty( $this->rt_social_options[ 'active' ] ) ) {
			foreach ( $this->rt_social_options[ 'active' ] as $active ) {
				echo '<li id="rtsocial-ord-' . $active . '"><label for="rtsocial-act-' . $active . '">' . $active . '</label></li>';
			}
		}
		echo '</ul>';

		echo '<h3>Inactive Buttons</h3>';

		echo '<ul id="rtsocial-sorter-inactive" class="buttons-active">';

		if ( isset( $this->rt_social_options[ 'inactive' ] ) && ! empty( $this->rt_social_options[ 'inactive' ] ) ) {
			foreach ( $this->rt_social_options[ 'inactive' ] as $inactive ) {
				echo '<li id="rtsocial-ord-' . $inactive . '" style="cursor: pointer;"><input id="rtsocial-act-' . $inactive . '" style="display: none;" type="checkbox" name="rtsocial_plugin_options[inactive][]" value="' . $inactive . '" checked="checked" /><label for="rtsocial-act-' . $inactive . '">' . $labels[ $inactive ] . '</label></li>';
			}
		}

		echo '</ul>';

		echo '<label for="rt-social-git-ribbon">Show GitHub Ribbon<input type="checkbox" id="rt-social-git-ribbon" name="rtsocial_plugin_options[showgithub]"></label>';
	}

	/**
	 * Renders the tab for setting button styles
	 */
	function button_styles() {
		$default_styles=array(
			'naked',
			'glyphicon',
			'large',
			'light',
			'icon'
		);
		echo '<ul>';
		foreach($default_styles as $style){
			echo '<li><label for="rt-social-radio-'.$style.'">'.$style.'<input type="radio" id="rt-social-radio-'.$style.'" value="'.$style.'" name="rtsocial_plugin_options[style]"></label></li>';
		}
		echo '</ul>';

		echo '<label for="rt-social-count">Hide Count<input type="checkbox" id="rt-social-count" name="rtsocial_plugin_options[hidecount]"></label>';

	}

	/**
	 * Renders the tab for setting the layout of the rtSocial button bar/widget
	 */
	function bar_layout() {
		$default_styles=array(
			'left',
			'center',
			'right'
		);
		echo '<ul>';
		foreach($default_styles as $style){
			echo '<li><label for="rt-social-align-'.$style.'"><input type="radio" id="rt-social-align-'.$style.'" value="'.$style.'" name="rtsocial_plugin_options[align]"></label></li>';
		}
		echo '</ul>';
		$this->placement_settings();
	}

	/**
	 * Renders tab for setting placement of rtSocial Widget
	 */
	function placement_settings() {
		?>
		<ul id="rtsocial-placement">
			<li class="dontsort dontmove" id="rttitle">
				<h3>Post Title</h3>
			</li>
			<li id="rtsocialwidget">
				rtSocial Buttons
			</li>
			<li class="dontsort" id="rtmeta">
				<small>Posted by <a href="rtcamp.com">rtcamp</a> on November 9, 2012 in Events</small>
			</li>
			<li class="dontsort" id="rtcontent">
				<p>Post content here. <a href="http://rtcamp.com">rtCamp</a> is a web solutions provider company, prominently working on open-source projects. Our foundation lies in blogging and we run India’s one of the largest blog network with a leading tech blog called <a href="http://devilsworkshop.com">Devils Workshop</a>.</p>
			</li>
			<li class="dontsort dontmove" id="rtmeta">
				<small>Posted in <a href="https://rtcamp.com/store/buddypress-media-kaltura/">BuddyPress Media Kaltura</a>, <a href="https://rtcamp.com/store/buddypress-media-ffmpeg-converter/">BuddyPress Media FFMPEG converter</a>,<a href="https://rtcamp.com/store/activecollab-gitolite/">activeCollab Gitolite Module</a></small>
			</li>
		</ul>
		<input type="hidden" name="rtsocial_plugin_options[placement]" value="">
		<?php
	}

	/**
	 * Renders the tab for twitter specific settings
	 */
	function twitter_settings() {
		echo '<label for="rt-social-tw-handle">Twitter Handle<input id="rt-social-tw-handle" name="rtsocial_plugin_options[tw][handle]"></label>';
		echo '<label for="rt-social-tw-rel-handle">Related Handle<input id="rt-social-tw-rel-handle" name="rtsocial_plugin_options[tw][relhandle]">';
		echo '<label for="rt-social-tw-hashtag">Enable #Hashtags<input type="checkbox" id="rt-social-tw-hashtag" name="rtsocial_plugin_options[tw][hashtagon]">';
	}

	/**
	 * Renders the tab for github specific settings
	 */
	function github_settings() {
		echo '<label for="rt-social-git-user">GitHub Username<input id="rt-social-git-user" name="rtsocial_plugin_options[gitusername]"></label>';

	}

	/**
	 * Will add while implementing fb like and social login
	 */
	function detailed_social_settings() {

	}

	/**
	 * Will add when custom buttons will be added
	 */
	function expert_settings() {

	}

	/**
	 * Will add ui for functionality, so a user can add his own share buttons
	 */
	function custom_share_button() {

	}

	function generate_page(){
		?>
		<div>
			<h2>My custom plugin</h2>
			Options relating to the Custom Plugin.
			<form action="options.php" method="post">
				<?php settings_fields('rt_social_options'); ?>
				<?php do_settings_sections('rtsocial'); ?>
				<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
			</form>
		</div>
		<?php
	}

	/**
	 * Renders the UI
	 */
	function generate_ui() {
		add_menu_page( 'rtsocial', 'rtSocial', 'manage_options', 'rtsocial', array($this,'generate_page'), '', 200 );
		register_setting('rt_social_options', 'rt_social_options');
		add_settings_section('rtsocial_active','Active Buttons',array($this, 'active_buttons'),'rtsocial');
		add_settings_section('rtsocial_button','Button Styles',array($this, 'button_styles'),'rtsocial');
		add_settings_section('rtsocial_layout','Layout',array($this, 'bar_layout'),'rtsocial');
		add_settings_section('rtsocial_twitter','Twitter Settings',array($this, 'twitter_settings'),'rtsocial');
		add_settings_section('rtsocial_github','GitHub Settings',array($this, 'github_settings'),'rtsocial');
	}

	/**
	 * Creates the User Experience (js/css)
	 */
	function generate_ux(){
		
	}


}
?>
