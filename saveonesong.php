<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.frischware.net/wordpress
 * @since             1.0.0
 * @package           Saveonesong
 *
 * @wordpress-plugin
 * Plugin Name:       SaveOneSong
 * Plugin URI:        https://www.frischware.net/wordpress/save_one_song
 * Description:       Extracts current song from 'egoFM' and sends name with artist to configured notification-channels.
 * Version:           1.0.0
 * Author:            Detlef Chen
 * Author URI:        https://www.frischware.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       saveonesong
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SAVEONESONG_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-saveonesong-activator.php
 */
function activate_saveonesong() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saveonesong-activator.php';
	Saveonesong_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-saveonesong-deactivator.php
 */
function deactivate_saveonesong() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-saveonesong-deactivator.php';
	Saveonesong_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_saveonesong' );
register_deactivation_hook( __FILE__, 'deactivate_saveonesong' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-saveonesong.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_saveonesong() {
	$plugin = new Saveonesong();
	$plugin->run();
}
run_saveonesong();
