<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://wolvensheep.com
 * @since      1.0.0
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/includes
 * @author     Judy Wong <judy@wolvensheep.com>
 */
class Heartbeat_Monitor_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate(){
	    global $wpdb;

        $table_name1 = $wpdb->prefix . 'heartbeatmonitor_clients';
        $table_name2 = $wpdb->prefix . 'heartbeatmonitor_incidents';

        $wpdb->query( "DROP TABLE IF EXISTS $table_name1" );
        $wpdb->query( "DROP TABLE IF EXISTS $table_name2" );
    }

    /*
	public static function js() {

        echo "
        <script type=\"text/JavaScript\">
        window.onload = function(){
            document.querySelector('[data-slug=\"heartbeat-monitor\"] a').addEventListener('click', function(event){
                event.preventDefault()
                    var urlRedirect = document.querySelector('[data-slug=\"heartbeat-monitor\"] a').getAttribute('href');
                    if (confirm('Are you sure you want to delete the database?')) {
                       
                    }
                })
        }
        </script>";
    https://stackoverflow.com/questions/15757750/how-can-i-call-php-functions-by-javascript
    
	}
    */
}



