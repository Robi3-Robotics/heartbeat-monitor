<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wolvensheep.com
 * @since      1.0.0
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 * @author     Judy Wong <judy@wolvensheep.com>
 */
class Heartbeat_Monitor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'heartbeat-monitor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
