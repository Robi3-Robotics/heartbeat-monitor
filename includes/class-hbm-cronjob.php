<?php
 
 /*
 * 20190106
 * To be run bye hbm-rest-controller as endpoint, to setup and run by system cronjob
 *
 * TODO:
 * Currently, the cronjob is set up for every min
 * Please remember to put this into the system
 */
 //   */5 * * * * wget -qO- "https://robicube.com/wp-json/heartbeat-monitor/v1/run-cronjob" &>/dev/null
 /*    
 * it is now run at 5 min interval by "hardcoding" it to be run at 5min...
 * to change its running at every min and then determine its interval from the setup file..
 */ 


//TODO: settings data - record or something...
    

use Twilio\Rest\Client;    
    
class HBM_Cronjob{
    
    public function run() {
        $time_tolerance = 5;    //TODO: get this from settings
        
        if(!$this->is_runtime())    //supposed cronjob runs every min, and count the min if it's runtime by settings..
            return;
        
        $clients = $this->get_clients();
        foreach($clients as $key => $item)
        {
            $client_id = $clients[$key]['client_id'];
            $client_slug = $clients[$key]['client_slug'];
            
            $time_now = new DateTime(current_time('mysql')); //new DateTime('now') may have timezone problem
            $last_report_time = new DateTime($clients[$key]['last_report_time']);
            $time_diff = $time_now->diff($last_report_time);
            $minute_diff = $time_diff->days * 24 * 60 + $time_diff->h *60 + $time_diff->i;
            
            $is_connection_broken = ($minute_diff > $time_tolerance)?true:false;
            switch($clients[$key]['client_status'])  //deleted, disabled are already filtered out by SQL
            {
                case 'lost-connection':
                    if($is_connection_broken)
                        $this->set_client_cronjob_time($client_id);
                    else
                        $this->status_resume_action($client_id, $client_slug);
                                        
                    break;
                default:    //active / enabled
                    if($is_connection_broken)
                        $this->lost_connection_action($client_id, $client_slug);
                    else
                        $this->set_client_cronjob_time($client_id);
            }
            
            //TODO: constant notification - if opted in settings, lost notification would be keep on
            // sending out every x (settings) min until disabled
            
		}	
    }
    
    //TODO
    public static function is_runtime() {     //supposed cronjob runs every min, and count the min if it's runtime by settings..
      global $wpdb;
        //TODO: mark on SQL and count to run every 5 min, etc...
        
        return true;
    }
    
    
    public static function get_clients() {    
      global $wpdb;
          
      $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_clients  WHERE client_status != 'deleted' AND client_status != 'disabled' ";
      return $wpdb->get_results( $sql, 'ARRAY_A' );
    }

    public static function lost_connection_action($client_id, $client_slug) {        		
		  global $wpdb;
        
          //create incident
          $wpdb->show_errors();
          $wpdb->insert(
                  "{$wpdb->prefix}heartbeatmonitor_incidents",	    
                  [ 'incident_status' => 'lost-connection',
                    'incident_since' => current_time('mysql'),
                    'client_id' => $client_id ]
          );
             
          //set in client table
		  $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_clients",
            
		    [   'client_status' => 'lost-connection',
                'last_status_since' => current_time('mysql'),
                'last_cronjob_time' => current_time('mysql') ],	      
		    [ 'client_id' => $client_id ]
		    
		  );
          
          //notification
          HBM_Cronjob::notify_incident($client_slug, 'lost-connection');
          
    }

    public static function status_resume_action($client_id, $client_slug) {
        
		  global $wpdb;
          $wpdb->show_errors();
        
          //find incident and update
          $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_incidents  WHERE client_id = '{$client_id}' AND incident_resumed_since = 0 ";      
          $row = $wpdb->get_row( $sql, 'ARRAY_A', 0 );  //TODO: possible error if entry found
        
          $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_incidents",	    
		    [ 'incident_resumed_since' => current_time('mysql') ],	      
		    [ 'incident_id' => $row['incident_id'] ]
		    
		  );
          echo $wpdb->last_query;
          
          //set in client table
		  $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_clients",
            [ 'client_status' => 'active' ,
              'last_status_since' => current_time('mysql'),
		      'last_cronjob_time' => current_time('mysql') ],	      
		    [ 'client_id' => $client_id ]
		    
		  );
          
          //notification
          HBM_Cronjob::notify_incident($client_slug, 'resume-connection');
    }

    
    public static function set_client_cronjob_time($client_id) {        		
		  global $wpdb;		
		  $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_clients",	    
		    [ 'last_cronjob_time' => current_time('mysql') ],	      
		    [ 'client_id' => $client_id ]
		    
		  );
    }
    
    //TODO: currently it's hardcoded to make action fast... will need to work on
    public static function notify_incident($client_slug, $client_status) {
        //TODO: get these action from settings
        
        //TODO: send email, if checked
        
        
        
        // Send SMS
        // https://www.twilio.com/blog/2017/08/send-sms-wordpress-php-plugin.html
        // https://www.twilio.com/docs/sms/tutorials/how-to-send-sms-messages-php
        // We did not use composer here, so please check the latest API with https://github.com/twilio/twilio-php/archive/master.zip
        // twilio-php-master is renamed to folder "twilio"
        // If Geographical location doesn't work, check https://www.twilio.com/console/sms/settings/geo-permissions
        
        
         require_once( plugin_dir_path( __FILE__ ) .'/../includes/twilio-php-master/Twilio/autoload.php');
        // use Twilio\Rest\Client; -> moved to top of file
        
        // Find your Account Sid and Auth Token at twilio.com/console
        $sid    = "AC33c573e719a3e22437329d37c044fb64";
        $token  = "8115b73d0eaa3d7c72da3c094dcea697";
        $twilio = new Client($sid, $token);
        
        
        //TODO: for trial receipient number, it must be verified before sending        
        $message = $twilio->messages
                          ->create( "+85363182300", // to
                                   array(
                                       "body" => "Robi3: {$client_slug} appeared to have {$client_status}. Please check.",
                                       "from" => "+15752211955"
                                   )
                          );
        
        
        if($client_slug == "robi3-i001")
        {
            
            $message = $twilio->messages
                              ->create( "+85296288607", // to
                                       array(
                                           "body" => "Robi3: {$client_slug} appeared to have {$client_status}. Please check.",
                                           "from" => "+15752211955"
                                       )
                              );
        }
        
        
        
    }
	
    
}



    
    

?>

You see this coz you call the cronjob manually to see what run. But everything that ran was invisible..
(Fine, Check client-machine to see the "last_cronjob_time" and you'll see it updated.)
