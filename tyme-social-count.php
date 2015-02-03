<?php

/**
 *	Plugin Name: Tyme Social Counter
 *	Description: Displays the total number of social shares from Facebook, Twitter, Pinterest, Google+, and LinkedIn
 *	Author: Tyler Bailey
 *	Author URI: http://tylerb.me
 *	Version: 1.0
 */



/**
 *	No Direct Access
 */
defined('ABSPATH') or die("Unauthorized Access Denied.");


/**
 * Define Plugin Constants
 */
if(!defined('TYME_VERSION'))
	define('TYME_VERSION', "1.0");

if(!defined('TYME_DIR'))
	define('TYME_DIR', plugin_dir_path( __FILE__ ));


/**
 *	Require Core Plugin Class
 */
require_once( TYME_DIR . 'tyme.core.php');



/**
 *	Retrieve Share Count Via AJAX
 */
function ajax_shares() {

	// If is AJAX Request
	echo tyme_calculate_all();;
	die();
}
add_action( 'wp_ajax_get_total_shares', 'ajax_shares' );
add_action( 'wp_ajax_nopriv_get_total_shares', 'ajax_shares' );


/*
 *	Put Share Count & Text Together and Return to get_shares() Function for Caching
 */
function tyme_execute() {
	$s_number = intval(tyme_calculate_all());
	return $s_number;
}


/*
 *	Istantiate tymeSocialCount Class and Get Total Social Shares
 */
function tyme_calculate_all() {
	$new_shares = 0;
	$total_shares_count = 0;

	if (defined('DOING_AJAX') && DOING_AJAX) {
		$url = $_GET['url'];
	} else {
		$url = get_permalink();
	}

	if(function_exists('curl_version')) {
		$version = curl_version();
		$bitfields = array(
			'CURL_VERSION_IPV6',
			'CURLOPT_IPRESOLVE'
		);

		foreach($bitfields as $feature) {
			if($version['features'] & constant($feature)) {
				$real_shares = new tymeSocialCount($url);

				$total_shares_count += $real_shares->get_tweets();
				$total_shares_count += $real_shares->get_fb();
				$total_shares_count += $real_shares->get_linkedin();
				$total_shares_count += $real_shares->get_plusones();
				$total_shares_count += $real_shares->get_pinterest();
				break;
			}
		}
	}

	$total_shares = $new_shares + $total_shares_count;

	return $total_shares;
}


/*
 *	Add In Shortcode For Easy Use
 */
function tsc_shortcode( $atts ){
	ajax_shares();
}
add_shortcode( 'tyme_share_count', 'tsc_shortcode' );
