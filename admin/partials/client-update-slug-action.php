<?php
// function for client update slug
//var_dump($_REQUEST);


	  switch($_REQUEST['my_action'])
	  {
	  	case 'update-slug':
			
		$slug = sanitize_title($_REQUEST['slug']);
		
		  global $wpdb;

		  $sql = "SELECT COUNT(*)  FROM {$wpdb->prefix}heartbeatmonitor_clients WHERE client_slug = '{$slug}'";
		  if($wpdb->get_var( $sql ))		  {
		  	//non-unique slug	
		  	$site_url= site_url();			
		  	header("Location: {$site_url}/wp-admin/admin.php?action=hbm-client-update-slug&id={$_REQUEST['id']}&slug={$slug}&taken=true"); 
			exit();
		  	
		  }else{
		    
			  $wpdb->update(
			    "{$wpdb->prefix}heartbeatmonitor_clients",	    
			    [ 'client_slug' => $slug ],	      
			    [ 'client_id' => $_REQUEST['id'] ]			    
			  );
			  
			 //close thickbox and refresh parent
			 echo "
				  <script type='text/javascript'>
				        function run_function() {		        	
				            parent.location.reload(1);
				        }
				  	window.onload = run_function();
				  </script>
			  ";
		  }			
	  }	  
		

