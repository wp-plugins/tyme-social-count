<?php

/**
 * 	Class To Execute cURL API Requests
 *
 * 	@package   Tyme Social Count
 * 	@author    Tyler Bailey <tylerb.media@gmail.com>
 * 	@license   GPL-2.0+
 * 	@link      http://tylerb.me
 */


if (!class_exists('tymeSocialCount')) {

	class tymeSocialCount {

		private $url, $timeout;

		function __construct($url, $timeout=10) {
			$this->url = rawurlencode($url);
			$this->timeout = $timeout;

			$max_requests = 12;
			$curl_options = array(
			    CURLOPT_SSL_VERIFYPEER => FALSE,
			    CURLOPT_SSL_VERIFYHOST => FALSE,
			);
		}

		// Get Number Of Tweets
		public function get_tweets() {
			$json_string = $this->file_get_contents_curl('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);

			if($json_string === false) return 0;

			$json = json_decode($json_string, true);

			return isset($json['count'])?intval($json['count']):0;
		}

		// Get Number of LinkedIn Shares
		public function get_linkedin() {
			$json_string = $this->file_get_contents_curl('http://www.linkedin.com/countserv/count/share?url='.$this->url.'&format=json');

			if($json_string === false) return 0;

			$json = json_decode($json_string, true);

			return isset($json['count'])?intval($json['count']):0;
		}

		// Get Number of Facebook Shares
		public function get_fb() {
			$json_string = $this->file_get_contents_curl('http://graph.facebook.com/?id='.$this->url);

			if($json_string === false) return 0;

			$json = json_decode($json_string, true);

			//return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
			return isset($json['shares'])?intval($json['shares']):0;
		}

		// Get Number of Google+ Shares
		public function get_plusones() {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($this->url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

			$curl_results = curl_exec($curl);

			curl_close($curl);

			if($curl_results === false) return 0;

			$json = json_decode($curl_results, true);
			return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
		}

		// Get Number of Pinterest Shares
		public function get_pinterest() {
			$return_data = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?url='.$this->url);

			if($return_data === false) return 0;

			$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
			$json = json_decode($json_string, true);

			return isset($json['count'])?intval($json['count']):0;
		}

		// cURL Function to Process API Calls
		private function file_get_contents_curl($url) {

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
			if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

			$cont = curl_exec($ch);

			if(curl_errno($ch)) {
				die(curl_error($ch));
			}

			/* Debugging
			$cInfo = curl_getinfo($ch);
			var_dump($cInfo);
			*/

			curl_close($ch);

			return $cont;

		}

	} // End of Class
} // End of class not exists check