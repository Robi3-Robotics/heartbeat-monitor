<?php

	  switch($_REQUEST['my_action'])
	  {
	  	case 'add-remarks':	  
		
		  global $wpdb;
		
		  $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_incidents WHERE incident_id = {$_REQUEST['incident']}";	  
		  $row = $wpdb->get_row( $sql, 'ARRAY_A', 0 );	
		
		  $my_remarks = current_time('mysql'). ' by ' . wp_get_current_user()->user_login . 
		  	":\n".stripslashes($_REQUEST['remarks'])."\n\n".$row['incident_remarks'];
		  
		  $wpdb->update(
		    "{$wpdb->prefix}heartbeatmonitor_incidents",	    
		    [ 'incident_remarks' => $my_remarks ],	      
		    [ 'incident_id' => $_REQUEST['incident'] ]
		    
		  );
	  
		  echo "
		  <script type='text/javascript'>
		        function run_function() {		        	
		            parent.location.reload(1);
		        }
		  	window.onload = run_function();
		  </script>
		  ";	
		
	
	   }


	  