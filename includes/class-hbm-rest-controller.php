<?php
 //https://www.wpeka.com/make-custom-endpoints-wordpress-rest-api.html
 //https://github.com/Shelob9/docs-v2/blob/pa
 //https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/

class HBM_REST_Controller extends WP_REST_Controller {
 
  //The namespace and version for the REST SERVER
  var $my_namespace = 'heartbeat-monitor/v';
  var $my_version   = '1';
 
  public function register_routes() {
    $namespace = $this->my_namespace . $this->my_version;    
    
    register_rest_route( $namespace, '/checkin', array(  //for reporting heartbeat
    	[   'methods'         => WP_REST_Server::READABLE,
          'callback'        => array( $this, 'report_heartbeat' ) ]  
    )  );
    
    register_rest_route( $namespace, '/run-cronjob', array(     //cronjob
    	[   'methods'         => WP_REST_Server::READABLE,
          'callback'        => array( $this, 'run_hbm_cronjob' ) ]  
    )  );
    
    register_rest_route( $namespace, '/incident-logging', array(  //for reporting machine incidents
    	[   'methods'         => WP_REST_Server::READABLE,
          'callback'        => array( $this, 'incident_logging' ) ]  
    )  );
  }
 
  // Register our REST Server
  public function hook_rest_server(){  	
    add_action( 'rest_api_init', array( $this, 'register_routes' ) );	
  }
  
  public function incident_logging( WP_REST_Request $request ){
      $client_slug = $request->get_param( 'slug' );
      $client_secret = $request->get_param( 'secret' );
      $incident_status = $request->get_param( 'type' );  //operation-error, operation-warning, hardware-error, hardware-warning
      $incident_since = $request->get_param( 'timestamp' );
      $remarks = $request->get_param( 'details' );
      $level = $request->get_param( 'level' );
      
   
      global $wpdb;          
      $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_clients  WHERE client_slug = '{$client_slug}' AND client_secret = '{$client_secret}' ";      
      $row = $wpdb->get_row( $sql, 'ARRAY_A', 0 );
      
      if($row == null)
        return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to view this data.', 'my-text-domain' ), array( 'status' => 401 ) );
      
   	  //$wpdb->show_errors();
      return $wpdb->insert(
              "{$wpdb->prefix}heartbeatmonitor_incidents",	    
              [ 'incident_status' => $incident_status,
                'incident_since' => $incident_since,
                'client_id' => $row['client_id'],
                'incident_remarks' => $remarks,
                ]
      );
    
      //TODO: notify according to level, settings / hardcode 
      
      
      
      //TODO: not proper REST API return value. check with  https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#return-value     
      
  }
  
  public function report_heartbeat( WP_REST_Request $request ){
      $client_slug = $request->get_param( 'slug' );
      $client_secret = $request->get_param( 'secret' );
   
      global $wpdb;          
      $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_clients  WHERE client_slug = '{$client_slug}' AND client_secret = '{$client_secret}' ";      
      $row = $wpdb->get_row( $sql, 'ARRAY_A', 0 );
      
      if($row == null)
        return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to view this data.', 'my-text-domain' ), array( 'status' => 401 ) );
      
   	  return $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_clients",	    
		    [ 'last_report_time' => current_time('mysql') ],	      
		    [ 'client_id' => $row['client_id'] ]
		    
		  );
      //TODO: not proper REST API return value. check with  https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#return-value     
      
  }

  public function run_hbm_cronjob( WP_REST_Request $request ){
    
  require_once( plugin_dir_path( __FILE__ ) .'/class-hbm-cronjob.php');    
    //TODO: there is no security measure as it won't matter if some other external party would like to run the cron-job...
    // There might be possible spammer but it should be blocked by firewall..
     // * To be run by system cronjob directly - currently the file is setup to run as endpoint see hb-rest-controller
     
     
    $this->my_cronjob_obj = new HBM_Cronjob();
    $this->my_cronjob_obj->run();
    
    
    return true;
    //TODO: not proper REST API return value. check with  https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#return-value
    //return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to view this data.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
  
  
}
