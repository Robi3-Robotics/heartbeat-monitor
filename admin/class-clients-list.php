<?php

 // https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
 // http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/

 // TODO: bulk action is bugged, take a look at it again later.
 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Clients_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Client', 'hbm' ), //singular name of the listed records
			'plural'   => __( 'Clients', 'hbm' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );

	}
	
	/**
	 * Retrieve client’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_clients( $per_page = 5, $page_number = 1 ) {
	
	  global $wpdb;
		  
	  $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_clients  WHERE client_status != 'deleted'";
	
	  if ( ! empty( $_REQUEST['orderby'] ) ) {
	    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
	    $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
	  }
	
	  $sql .= " LIMIT $per_page";	
	  $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;	
	  $result = $wpdb->get_results( $sql, 'ARRAY_A' );
	
	
	 //beautifying item to be displayed
	 	foreach($result as $key => $item)
		{
			switch($item['client_status'] ){
				case 'enabled':
					$result[$key]['last_report_time'] = 'N/A';
					break;
				
				case 'lost-connection':
					$result[$key]['client_status'] = '<span style="color: #FF0000">lost-connection</span>';
					break;
					
				case 'disabled':
					foreach($item as $item_key => $value)					{
						if($item_key != 'client_id' && $item_key != 'client_status')
							$result[$key][$item_key] =  '<span style="color: #CCCCCC">'. $value .'</span>';
					}
					break;
			}
		}
	  return $result;
	}
	
	/**
	 * Delete a client record.
	 *
	 * @param int $id client ID
	 */
	public static function update_client( $id, $status ) {
	  global $wpdb;
	
	  $wpdb->update(
	    "{$wpdb->prefix}heartbeatmonitor_clients",	    
	    [ 'client_status' => $status,
	      'last_status_since' => current_time('mysql') ],	      
	    [ 'client_id' => $id ]
	    
	  );
	}
	
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
	  global $wpdb;
	
	  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}heartbeatmonitor_clients";
	
	  return $wpdb->get_var( $sql );
	}
	
	/** Text displayed when no client data is available */
	public function no_items() {	
		$create_client_url = site_url().'/admin.php?action=hbm-add-client&TB_iframe=true&width=400&height=230';	
	  _e( "You have not create any client machine to be monitored yet. <a href='{$create_client_url}'>Create one</a>.", 'hbm' );
	}
	
	
	/**
	 *	Methods below are for overwriting the parent methods...
	 *  tab 2 - setup / list of clients
	 *		- client-slug
	 * 		- if client is enabled / disabled
	 * 		- client status (active / lost-connection / disabled /deleted)
	 * 		- active since [time]
	 * 		- setup date
	 */
	
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_client_slug( $item ) {
	
	
	  $title = '<strong>' . $item['client_slug'] . '</strong>';

	  $enable_disable = $item['client_status']=='disabled'?'enable':'disable';
  	
  	  // create a nonce - note that due to long URL problem (see below), nounce name would define its action
	  $security_nonce = wp_create_nonce( 'single_action' );	  
	   

	 //Note: the URL can't be too long else the '&' would be automatically converted to '#038'	
	 //.. when i have '_' in URL it's not accepted  	TODO: internationalize
	  $actions = [
	  	'update_slug' => sprintf( '<a href="?action=%s&id=%s&slug=%s&TB_iframe=true&width=400&height=230" class="thickbox">Edit Name</a>',
			 'hbm-client-update-slug', absint( $item['client_id'] ), strip_tags($item['client_slug']) ),	  
		$enable_disable => sprintf( '<a href="?page=%s&action=%s&client=%s&_wpnonce=%s">%s</a>',  
			esc_attr( $_REQUEST['page'] ), $enable_disable, absint( $item['client_id'] ), $security_nonce, ucfirst($enable_disable) ),
		'delete' => sprintf( '<a href="?page=%s&action=%s&client=%s&_wpnonce=%s">Delete</a>', 
			esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['client_id'] ), $security_nonce )
	  ];
	
	  return $title . $this->row_actions( $actions );
	}
	
	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
	  switch ( $column_name ) {
	    case 'client_secret':
	    case 'client_created':
		case 'client_status':
		case 'last_status_since':
		case 'last_report_time':
		case 'last_cronjob_time':
	      return $item[ $column_name ];
	    default:
	      return print_r( $item, true ); //Show the whole array for troubleshooting purposes
	  }
	}	
	
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
	  return sprintf(	  
	    '<input type="checkbox" name="bulk-action[]" value="%s" />', $item['client_id']
	  );
	}	
	
	/**
	 *  Associative array of columns
	 * 	    - client-slug
	 * 		- if client is enabled / disabled
	 * 		- client status (active / lost-connection / disabled /deleted)
	 * 		- active since [time]
	 * 		- setup date
	 *
	 * @return array
	 */
	function get_columns() {
	  $columns = [
	 //   'cb'      => '<input type="checkbox" />',
	    'client_slug'    => __( 'Name', 'hbm' ),
	    'client_secret'    => __( 'Client Secret', 'hbm' ),
	    'client_created'    => __( 'Created', 'hbm' ),	
	    'client_status' => __( 'Status', 'hbm' ),	    
		'last_status_since'    => __( 'Status Since', 'hbm' ),
		'last_report_time'    => __( 'Last Report time', 'hbm' ),
		'last_cronjob_time'    => __( 'System Checked@', 'hbm' ),
	  ];
	
	  return $columns;
	}
	
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
	  $sortable_columns = array(
	    'client_slug' => array( 'client_slug', true ),
	    'client_status' => array( 'client_status', true ),
	    'client_created' => array( 'client_created', false )
	  );
	
	  return $sortable_columns;
	}
	
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
/*	public function get_bulk_actions() {
	  $actions = [
	    'bulk-enable' => 'Enable',
	    'bulk-disable' => 'Disable',
	    'bulk-delete' => 'Delete'
	  ];
	
	  return $actions;
	}*/
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 * Note: the method must include a call to the items parent class properties 
	 * 		 and the store the array of database data saved against it.
	 */
	public function prepare_items() {
	
//	  $this->_column_headers = $this->get_column_info();
	  
	  $columns = $this->get_columns();
  	  $hidden = array();
  	  $sortable = $this->get_sortable_columns();
  	  $this->_column_headers = array($columns, $hidden, $sortable);
	  
	
	  /** Process bulk action */
	  $this->process_bulk_action();
	
	  $per_page     = $this->get_items_per_page( 'clients_per_page', 5 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();
	
	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );
	
	
	  $this->items = self::get_clients( $per_page, $current_page );
	  
	}

	public function process_bulk_action() {
	
	  //Detect when a bulk action is being triggered...	  
	  
	  //Single Actions
	  switch($this->current_action())
	  {
	  	case 'delete':
		case 'enable':
		case 'disable':	
	  
	    $nonce = esc_attr( $_REQUEST['_wpnonce'] );
	
	    if ( ! wp_verify_nonce( $nonce, 'single_action' ) ) {
	      die( 'Go get a life script kiddies' );
	    }
	    else {
	      self::update_client( absint( $_GET['client'] ), $this->current_action().'d' );	//enable to 'enabled'
	
	      wp_redirect( esc_url( add_query_arg() ) );
	      exit;
	    }
	
	  }	
	
	  // If the delete bulk action is triggered !!! the same URL problem occurs!!
	  
	  //$action = isset( $_POST['action'] )?$_POST['action']:$_POST['action2'];
	  $action = $this->current_action();

	  switch($action)
	  {
	  	case 'bulk-delete':	$status='deleted';				 
		case 'bulk-enable':	$status='enabled';			
		case 'bulk-disable':$status='disabled';
	  }	  
	 /* echo '<br>action:'.$_POST['action'];
	  echo '<br>action2:'.$_POST['action2'];
	  echo '<br>action:'.$action;
	  var_dump($_REQUEST);*/
	  switch($action)
	  {
	  	case 'bulk-delete':				 
		case 'bulk-enable':				
		case 'bulk-disable':			
	
		    $client_ids = esc_sql( $_POST['bulk-action'] );
		
		    // loop over the array of record IDs and delete them
		    foreach ( $client_ids as $id ) {
		      self::update_client( $id, $status );	
		    }
		    wp_redirect( esc_url( add_query_arg() ) );
		    exit;
	   } 
 
	}	
}
