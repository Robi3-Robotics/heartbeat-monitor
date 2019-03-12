<?php
// function for add client 

	  if($_REQUEST['slug'] == '')	{
		  	header("Location: {$site_url}/wp-admin/admin.php?action=hbm-add-client&slug={$slug}&empty=true"); 
			exit();
	  }

	  switch($_REQUEST['my_action'])
	  {
	  	case 'add-client':
			
		$slug = sanitize_title($_REQUEST['slug']);
		
		  global $wpdb;

		  $sql = "SELECT COUNT(*)  FROM {$wpdb->prefix}heartbeatmonitor_clients WHERE client_slug = '{$slug}'"; 
		  if($wpdb->get_var( $sql ))		  {
		  	//non-unique slug	
		  	$site_url= site_url();			
		  	header("Location: {$site_url}/wp-admin/admin.php?action=hbm-add-client&slug={$slug}&taken=true"); 
			exit();
		  	
		  }else{
		    
			  $wpdb->insert(
			    "{$wpdb->prefix}heartbeatmonitor_clients",	    
			    [ 'client_slug' => $slug,
			      'client_secret' => substr( md5(rand()), 0,7),
			      'client_status' => 'enabled',
			      'client_created' => current_time('mysql'),
				  'created_by_id' =>  get_current_user_id(),
				  'last_status_since' => current_time('mysql') ]
			  );
			
			define( 'IFRAME_REQUEST', true );
    		iframe_header(); 
    		
    		
			//TODO: put url
			$usage_url = '#';
			echo "
			<div class='wrap' style='padding: 15px;'>
				Your client is now created. \"Client secret\" is an unchangable random identifcation for your client to report
				to the monitor.			
				<p>
				<a class='button' href='{$usage_url}' target='_self'>Check here for instruction regarding connecting to the monitoring host server.</a>
				<p>
				<a class='button' href='#' onclick='parent.location.reload(1)'>Close & Refresh Client List</a>
			</div>
			";
			iframe_footer();
    		exit;	
			  			  
			  
		  }			
	  }	  
		

