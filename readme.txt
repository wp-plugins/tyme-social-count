=== Tyme Social Count ===
Contributors: TyB
Tags: social count, social, shares, facebook, twitter, linkedin, google, pinterest, network, api, social networks, open graph, fb, og
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Calls popular social network APIs and returns a total share/like/+1/tweet/pin count from Facebook, Twitter, LinkedIn, Google Plus and Pinterest.

== Description ==

This plugin will query the popular social network APIs and retrieve the number of shares/likes/plusones from each. All shares are added up and calculated together to give you the total number of shares. All that is returned is a simple number. This number represents the number of shares, likes, tweets, +1's, and pins.

The plugin can be utilized two ways, the first method is the AJAX route which will require you to add a little javascript to your template. This method is the preferred method if you are requesting the share count for multiple posts on one page load. For example on an archive page. Below is a quick example of how that is done:

`
jQuery.get(ajaxurl, { action: "get_total_shares", url: "<?php the_permalink(); ?>" }).done(function(data) {
	var shareNum = parseInt(data); // your total number of shares
});
`

**Note:** You can call this AJAX anyway you wish, but you **must** pass `the_permalink();` as the `url` parameter and `"get_total_shares"` for the `action` parameter.

If you are just displaying the share count for one post at a time, you may use the plugin shortcode to return the number. That shortcode is `[tyme_share_count]`

This plugin also refreshes the Facebook Open Graph scraper each time a post is published. This ensures the proper images and content are being served to Facebook when shared.

== Installation ==

1. Upload the 'tyme-social-count' directory to your '/plugins/'
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can use the plugin by using the shortcode `[tyme_share_count]` or by following the AJAX method mentioned in the README. AJAX is the preferred route to prevent slow page load times

== Screenshots ==
1. Tyme Social Count returns a simple number for you to style and customize as you see fit.
2. Easily customizable and styled to fit your specific theme and/or design.

== Changelog ==
= 1.2 =
* Added cURL request to update Facebook Open Graph scraper on post publish
= 1.1 =
* Bug fixes
= 1.0 =
* First release

== Upgrade Notice ==
= 1.2 =
Added cURL request to update Facebook Open Graph scraper

= 1.1 =
Removed `tyme_activation()` function