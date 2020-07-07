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

    public static function activate()
    {

        global $jal_db_version;
        global $wpdb;
        $jal_db_version = 1.0;

        $table_name1 = $wpdb->prefix . 'heartbeatmonitor_clients';
        $table_name2 = $wpdb->prefix . 'heartbeatmonitor_incidents';


        $charset_collate = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE $table_name1 (
		client_id bigint(20) NOT NULL AUTO_INCREMENT,
		client_slug varchar(255) NOT NULL COLLATE utf8_unicode_ci, 
		client_secret varchar(255) NOT NULL COLLATE 'utf8_unicode_ci', 
		client_status varchar(20) NOT NULL COLLATE 'utf8_unicode_ci', 
		client_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		created_by_id bigint(20) NOT NULL,
		last_status_since datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_report_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_cronjob_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (client_id)
	) $charset_collate;";

        $sql2 = "CREATE TABLE $table_name2 (
            incident_id bigint(20) NOT NULL AUTO_INCREMENT, 
            incident_status varchar(20) NOT NULL COLLATE 'utf8_unicode_ci', 
            incident_since datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            incident_resumed_since datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            client_id bigint(20) NOT NULL,
            last_cronjob_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (incident_id)  
        ) $charset_collate;";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );


        add_option( 'jal_db_version', $jal_db_version );
    }
}