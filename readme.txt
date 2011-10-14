=== rtSocial ===
Contributors: rtcamp, rahul286, rutwick
Donate link: http://rtcamp.com/
Tags: rtcamp, social, sharing, share, social links, twitter, facebook, social share, social sharing
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0

This plugin uses non-blocking JavaScript to display facebook, twitter, etc social media sharing counter on posts/pages. 

== Description ==
Normally social sharing codes provided by facebook, twitter, etc renders iframes at run-time. These iframes increases page-size and slow-down your website (on client-side).

The power of this plugin lies in non-blocking JavaScript. Normally social sharing links use iframes to output their content viz. icon images, share counts etc. The load time of these iframes block the page load, and hence slow down the site. The slowest is on pages with multiple instances of sharing links (e. g. blog indexes). rtSocial uses non blocking JavaScript to get all the data in one shot, and injects them in the mark-up. And it uses a single sprite with all the images required, hence eliminating the need to load the images from the service provider's CDN. Comes with minimal settings, options to display the links automatically above and below the content, choose FB button styles and layouts. A simple function call lets you to display the links anywhere in your theme!

With just 1 image (CSS-sprite), 1 javascript and 1 css file loaded from WordPress-running server; counts are fetched using AJAX request for sites like facebook/twitter after page loading finishes.

= Useful Links =
* [rtSocial Plugin's Homepage](http://rtpanel.com/plugins/rtsocial/)
* [Support Forum](http://rtpanel.com/support/forum/plugins/rtsocial/)

== Installation ==

1. Install the plugin from the 'Plugins' section in your dashboard. Plugins > Add New > Search for rtSocial. Activate it.
2. Alternatively you can download the plugin from the repository. Unzip it and upload it to the plugins folder of your WordPress installation. wp-content > plugins. Activate it through the 'Plugins' section.
3. Adjust the plugin settings from the settings section. Settings > rtSocial Options.

== Screenshots == 

Please check [rtSocial Plugin's Homepage](http://rtpanel.com/plugins/rtsocial/).

== Frequently Asked Questions ==

= Can I use the plugin anywhere in the theme? =

Yes you can. Use the function call 
`<?php 
if ( function_exists( 'rtsocial' ) ) { 
       echo rtsocial(); 
} 
?>`

= Can I modify the images/icons used? =

No. Right now you cannot. 


== Changelog ==

= 1.0 =

Initial release.


