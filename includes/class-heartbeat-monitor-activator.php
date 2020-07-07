<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wolvensheep.com
 * @since      1.0.0
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 * @author     Judy Wong <judy@wolvensheep.com>
 */
class Heartbeat_Monitor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		//TODO: somehow the DB table creation is not working. to investigate.
		/*
		//https://wordpress.stackexchange.com/questions/220935/create-a-table-in-custom-plugin-on-the-activating-it
	    global $table_prefix, $wpdb;
	
	    $tblname = 'heartbeatmonitor_clients';
	    $wp_track_table = $table_prefix . "$tblname ";
	
	    #Check to see if the table exists already, if not, then create it
	    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
	    {
	
	        $sql = "CREATE TABLE `". $wp_track_table . "` (
			  `client_id` bigint(20) UNSIGNED NOT NULL,
			  `client_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `client_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `client_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `client_created` datetime NOT NULL,
			  `created_by_id` bigint(20) UNSIGNED NOT NULL,
			  `last_status_since` datetime NOT NULL,
			  `last_report_time` datetime NOT NULL,
			  `last_cronjob_time` datetime NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ";
	        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	        dbDelta($sql);
	    }
		 * 
		 * ALTER TABLE `rbwp_heartbeatmonitor_incidents`
  ADD PRIMARY KEY (`client_id`),
  
				
		$tblname = 'heartbeatmonitor_incidents';
	    $wp_track_table = $table_prefix . "$tblname ";
	
	    #Check to see if the table exists already, if not, then create it
	    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
	    {
	
	        $sql = "CREATE TABLE `". $wp_track_table . "` (
			  `incident_id` bigint(20) UNSIGNED NOT NULL,
			  `incident_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `incident_since` datetime NOT NULL,
			  `incident_resumed_since` datetime NOT NULL,
			  `client_id` bigint(20) UNSIGNED NOT NULL,
			  `incident_remarks` text COLLATE utf8_unicode_ci NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		 * 
		 * ALTER TABLE `rbwp_heartbeatmonitor_incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `client_id` (`client_id`);
		 * 
	        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	        dbDelta($sql);
	    }*/
	
	}

}
