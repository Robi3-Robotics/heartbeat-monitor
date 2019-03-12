<?php

/**
 * Note: THIS PAGE IS NO LONGER IN USE, THIS IS THE OLD INDEX PAGE - judy 20171102
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://wolvensheep.com
 * @since      1.0.0
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

/*
 *  
 *	tab 1 
 * 	- list of current failure
 * 		- client_slug (sortable)
 * 		- failure time time(default) (sortable)
 * 		- failure time till now (hours)
 *  
 * 			- slug filter (?)
 * 
 *  - list of recorded failure
 * 		- client_slug (sortable)
 * 		- status - lost-connection / reconnected / disabled 
 * 		- ordered by failed time(default) (sortable)
 * 		- reconnected / disabled time [or CURRENTLY UNREACHABLE]
 * 		- failed duration (hours)
 
 * 		- slug filter (?)
 * 		
 *	tab 2 - setup / list of clients
 *		- client-slug
 * 		- if client is enabled / disabled
 * 		- client status (active / lost-connection / disabled /deleted)
 * 		- active since [time]
 * 		- setup date
 *		 
 * 	tab(last) - setup
 *		- check-interval time (setup / remove cronjob) (cronjob interval == check interaval)
 * 		- action / hook (eg. email client / run a remote script / enable hooked by functions.php)
 * 		- upon saving, update cronjob also
 * 		- no. of "latest incidents" to show
 * 		- TBD: remove deleted client from DB
 * 
 * [background cronjob]
 * 		- to check if [current time]-[last report time]>[check interval] , alert
 * 		- alert by hook - include time, slug
 * 		- convert to UTC for cosistency
 * 
 * [wp-api]
 * 		- to enter client-slug / last report time (could skip numeric ID?) when api is called
 * 
 * DB:
 * table heartbeatmonitor_setup (or go into option meta?)(
 * 	- check_interval
 * 	- is_send_email
 *  - send sms?? (paid version, etc)
 *  - (able to detect any hook?)
 * 
 * table heartbeatmonitor_incidents
 * 	- incident_id bigint(20) unsigned Auto Increment [PRIMARY INDEX]
 *  - incident_status varchar(20) [lost-connection / reconnected / disabled] * 
 * 	- incident_since datetime [0000-00-00 00:00:00]
 * 	- incident_resumed_since datetime [0000-00-00 00:00:00] (including disabled time) 
 * 	- client_id bigint(20) unsigned [0]	[index]
 * 	- incident_remarks text (action taken (will hook feedback to here?))
 * 
 * table {$wpdb->prefix}heartbeatmonitor_clients
 * 	- id (RO) 						client_id	bigint(20) unsigned Auto Increment [PRIMARY INDEX]
 * 	- slug							client_slug varchar(255) [UNIQUE INDEX]
 *  - random_secret 				client_secret varchar(255) []
 * 	- date_created 					client_created datetime [0000-00-00 00:00:00]
 *  - created_by					created_by_id	bigint(20) unsigned
 * 	- status 						client_status	varchar(20) 
 * 									[active / lost-connection / enabled / disabled / deleted]  
 * 									- 'enabled' is use to mark for the first time before being active 
 * 	- last_active_since (or inactive since) (RO) 	last_status_since datetime [0000-00-00 00:00:00]
 * 	- last_report_time 				last_report_time datetime [0000-00-00 00:00:00]  
 
 */
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	<?php settings_errors(); ?>

	<?var_dump($_REQUEST);?>


	<h2 class="nav-tab-wrapper">
		<a href="?page=<?php echo $this->plugin_name; ?>&tab=incidents" class="nav-tab">Incidents</a>
		<a href="?page=<?php echo $this->plugin_name; ?>&tab=clients" class="nav-tab">Clients</a>
		<a href="?page=<?php echo $this->plugin_name; ?>&tab=settings" class="nav-tab">Settings</a>
	</h2>

	<?php
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'incidents';
 		switch($active_tab) {
			case 'incidents':
				include_once( 'admin-incidents-view.php' );
				break;
			case 'clients':
				include_once( 'admin-clients-view.php' );
				break;
			case 'settings':
				include_once( 'admin-settings-view.php' );
				break;
 		}
 	?>


</div>
