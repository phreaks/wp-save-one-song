<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.frischware.net/wordpress
 * @since      1.0.0
 *
 * @package    Saveonesong
 * @subpackage Saveonesong/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Saveonesong
 * @subpackage Saveonesong/admin
 * @author     ted <bauer>
 */
class Saveonesong_Admin {

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
	 * 
	 */
	private $menu_slug = 'sos_plugin_settings';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$page =  get_current_screen()->id;

		if( $this->menu_slug === substr( $page, -1 * strlen( $this->menu_slug ) ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/saveonesong-admin.css', array(), $this->version, 'all' );
		}	
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$page =  get_current_screen()->id;

		if( $this->menu_slug === substr( $page, -1 * strlen( $this->menu_slug ) ) ) {
			wp_enqueue_script( 'sos-vue', plugin_dir_url( __FILE__ ) . 'js/vue.js', array(), null, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/saveonesong-admin.js', array( 'sos-vue', 'jquery' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'SOS_Data', array(
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'siteUrl' => get_site_url(),
				'options' => get_option( 'sos_settings', [ 'tg_enabled' => 'no', 'tg_token' => '', 'tg_chat_id' => '' ] ),
			) );
		}
	}

	/**
	 * 
	 */
	public function admin_menu() {
		$page_title = 'SoS Settings';
		$menu_title = 'SoS Settings';
		$capability = 'manage_options';
		$callback = 'sos_render_settings_page';
		add_menu_page( $page_title, $menu_title, $capability, $this->menu_slug, array($this, $callback) );
	}

	/**
	 * Callback for rendering the plugin settings page
	 */
	function sos_render_settings_page() {
		require_once __DIR__ . '/partials/saveonesong-admin-display.php';
	}

	/**
	 * 
	 */
	public function rest_api_init() {
		register_rest_route( 'sos/v1', '/save', array(
			'methods' => 'POST',
			'callback' => function() {
				$tg_enabled = sanitize_text_field( $_POST['tg_enabled'] );
				$tg_token = sanitize_text_field( $_POST['tg_token'] );
				$tg_chat_id = sanitize_text_field( $_POST['tg_chat_id'] );

				update_option( 'sos_settings', array(
					'tg_enabled' => $tg_enabled,
					'tg_token' => $tg_token,
					'tg_chat_id' => $tg_chat_id,
				) );
				
				return new WP_REST_Response('ok', 200 );
			},
		) );
	}
}
