<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wolvensheep.com
 * @since      1.0.0
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Heartbeat_Monitor
 * @subpackage Heartbeat_Monitor/admin
 * @author     Judy Wong <judy@wolvensheep.com>
 */
class Heartbeat_Monitor_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Heartbeat_Monitor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Heartbeat_Monitor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/heartbeat-monitor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Heartbeat_Monitor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Heartbeat_Monitor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/heartbeat-monitor-admin.js', array( 'jquery' ), $this->version, false );

	}

/**
 * Register the administration menu for this plugin into the WordPress Dashboard menu.
 *
 * @since    1.0.0
 */

public function add_plugin_admin_menu() {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     */
    //add_options_page( 'Heartbeat Monitor  Setup', 'Heartbeat Monitor', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')    );
	
	add_menu_page( __('Heartbeat Monitor: Incidents'), __('Heartbeat Monitor'), 'manage_options', $this->plugin_name, array($this, 'display_plugin_incidents_page'), 'dashicons-feedback'    );
	add_submenu_page( $this->plugin_name, __('Heartbeat Monitor: Incidents'), __('Incidents'), 'manage_options', $this->plugin_name , array($this, 'display_plugin_incidents_page'));			
	add_submenu_page( $this->plugin_name, __('Heartbeat Monitor: Client Machines'), __('Client Machines'), 'manage_options', "{$this->plugin_name}-clients" , array($this, 'display_plugin_clients_page'));
	add_submenu_page( $this->plugin_name, __('Heartbeat Monitor: Settings'), __('Settings'), 'manage_options', "{$this->plugin_name}-settings" , array($this, 'display_plugin_setup_page'));
	
	add_action( "admin_action_hbm-incident-add-remarks", array($this, 'display_incident_add_remarks_page') );
	add_action( "admin_action_hbm-incident-add-remarks-action", array($this, 'display_incident_add_remarks_action') );	
	add_action( "admin_action_hbm-add-client", array($this, 'display_add_client_page') );
	add_action( "admin_action_hbm-add-client-action", array($this, 'display_add_client_action') );
	add_action( "admin_action_hbm-client-update-slug", array($this, 'display_client_update_slug_page') );
	add_action( "admin_action_hbm-client-update-slug-action", array($this, 'display_client_update_slug_action') );
	
	
	//add_menu_page('Heartbeat Monitor Settings', 'Heartbeat Monitor', 'manage_options', 'heartbeat-monitor', 'heartbeatmonitor_settings_page', 'dashicons-feedback');
}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	
	public function add_action_links( $links ) {
	    /*
	    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	    */
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );
	
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	
	public function display_plugin_setup_page() {
	    include_once( 'partials/heartbeat-monitor-admin-display.php' );
	    //include_once( 'partials/admin-settings-view.php' );
	}
	
	public function display_plugin_clients_page() {
	    include_once( 'partials/admin-clients-view.php' );
	}
	
	public function display_plugin_incidents_page() {
	    include_once( 'partials/admin-incidents-view.php' );
	}

	public function display_incident_add_remarks_page() {
	    include_once( 'partials/incident-add-remarks-view.php' );
	}

	public function display_incident_add_remarks_action() {
	    include_once( 'partials/incident-add-remarks-action.php' );
	}
		
	public function display_client_update_slug_page() {
	    include_once( 'partials/client-update-slug-view.php' );
	}
	
	public function display_client_update_slug_action() {
	    include_once( 'partials/client-update-slug-action.php' );
	}
	
	public function display_add_client_page() {
	    include_once( 'partials/client-add-client-view.php' );
	}
	
	public function display_add_client_action() {
	    include_once( 'partials/client-add-client-action.php' );
	}
}
