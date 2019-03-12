<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wolvensheep.com
 * @since             1.0.0
 * @package           Heartbeat_Monitor
 *
 * @wordpress-plugin
 * Plugin Name:       System Heartbeat Monitor Server
 * Plugin URI:        http://wolvensheep.com
 * Description:       This plugin serves as a heartbeat monitor for multiple machines. The plugin expects to receive the heartbeat messages from clients, and if no message is received for over a period of time the plugin will send out alerts. Will set a cronjob on the wordpress server to run properly.
 * Version:           1.0.0
 * Author:            Judy Wong
 * Author URI:        http://wolvensheep.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       heartbeat-monitor
 * Domain Path:       /languages
 */

// tutoial from: https://scotch.io/tutorials/how-to-build-a-wordpress-plugin-part-1 
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-heartbeat-monitor-activator.php
 */
function activate_heartbeat_monitor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-heartbeat-monitor-activator.php';
	Heartbeat_Monitor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-heartbeat-monitor-deactivator.php
 */
function deactivate_heartbeat_monitor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-heartbeat-monitor-deactivator.php';
	Heartbeat_Monitor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_heartbeat_monitor' );
register_deactivation_hook( __FILE__, 'deactivate_heartbeat_monitor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-heartbeat-monitor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_heartbeat_monitor() {

	$plugin = new Heartbeat_Monitor();
	$plugin->run();

}
run_heartbeat_monitor();
