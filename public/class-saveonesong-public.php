<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.frischware.net/wordpress
 * @since      1.0.0
 *
 * @package    Saveonesong
 * @subpackage Saveonesong/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Saveonesong
 * @subpackage Saveonesong/public
 * @author     ted <bauer>
 */
class Saveonesong_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/saveonesong-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/saveonesong-public.js', array( 'jquery' ), $this->version, false );
	}

	// 
	/**
	 * Example URL:  http://sos-127-0-0-1.nip.io/wp-json/sos/v1/find-song
	 */
	public function rest_api_init() {
		register_rest_route( 'sos/v1', '/find-song/stream/(?P<name>\w+)', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'find_song'),
			'args' => array(
				'name' => array(
				  'validate_callback' => function($param, $request, $key) {
					return !empty($param);
				  }
				),
			  ),
  		));
	}

	/**
	 * 
	 */
	function find_song($request) {
		$stream_name = $request['name'];

		$jsonurl = 'https://www.egofm.de/index.php?option=com_playhistory&task=currentall.track&format=json';
		$json = file_get_contents($jsonurl);
		$jsonObj = json_decode($json);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$response = new WP_REST_Response("Decoding JSON failed with error-code: " .json_last_error(), 400);
		}		
		
		// using stream_name to jump to the right node
		$track = $jsonObj->data->tracks->$stream_name->current->track;

		if (empty($track)) {
			$response = new WP_REST_Response("Could not extract track", 404);

		} else {
			// create inital array
			$data = array('track' => $track, 'stream'=> $stream_name, 'timestamp' => time());

			$sos_settings = get_option( 'sos_settings');

			$code = 200;
			if($sos_settings['tg_enabled'] == 'yes') {
				if( ! $this->telegram_send($data, $sos_settings['tg_token'], $sos_settings['tg_chat_id']) ) {
					$code = 400;
				};
			}
			$response = new WP_REST_Response($data, $code);
		}
		return $response;
	}

	/**
	 * 
	 */
	private function telegram_send(&$data, $token, $chat_id) {
		$post_data = array(
				'chat_id' => urlencode($chat_id),
				'text' => $data['track'] . ' ('.$data['stream'].')' 
			);

		$url = 'https://api.telegram.org/bot'.$token.'/sendMessage';

		$response = wp_remote_post( $url, array( 'body' => $post_data ) );

		$ret_code = $response['response']['code'];

		if ( is_wp_error( $response )) {
			$data['error'] = $response->get_error_message();
			return false;

		} else if($ret_code >= 400) {
			$data['error'] = $response['response']['message'];
			return false;

		} else {
			$data['telegram'] = 'send';
			return true;
		}
		
	}
}
