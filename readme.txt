=== rtSocial ===
Contributors: rtcamp, rahul286, rutwick
Donate link: http://rtcamp.com/
Tags: rtcamp, social, sharing, share, social links, twitter, facebook, social share, social sharing
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

Extremely fast social sharing plugin, uses non-blocking JavaScript.

== Description ==

The power of this plugin lies in non-blocking JavaScript. Normally social sharing links use iframes to output their content viz. icon images, share counts etc. The load time of these iframes block the page load, and hence slow down the site. The slowest is on pages with multiple instances of sharing links (e. g. blog indexes). rtSocial uses non blocking JavaScript to get all the data in one shot, and injects them in the mark-up. And it uses a single sprite with all the images required, hence eliminating the need to load the images from the service provider's CDN. Comes with minimal settings, options to display the links automatically above and below the content, choose FB button styles and layouts. A simple function call lets you to display the links anywhere in your theme!

== Installation ==

1. Install the plugin from the 'Plugins' section in your dashboard. Plugins > Add New > Search for rtSocial. Activate it.
2. Alternatively you can download the plugin from the repository. Unzip it and upload it to the plugins folder of your WordPress installation. wp-content > plugins. Activate it through the 'Plugins' section.
3. Adjust the plugin settings from the settings section. Settings > rtSocial Options.

== Frequently Asked Questions ==

= Can I use the plugin anywhere in the theme? =

Yes you can. Use the function call __<?php if ( function_exists( 'rtsocial' ) ) { echo rtsocial(); } ?>__

= Can I modify the images/icons used? =

No. Right now you cannot!

== Upgrade Notice ==

First release

== Changelog ==

= 1.0 =

First release.
