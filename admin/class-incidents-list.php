<?php

 //https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
 //http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 //apparently class-clients-list were written first - please refer to it for codes - judy20190105

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Incidents_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Incident', 'hbm' ), //singular name of the listed records
			'plural'   => __( 'Incidents', 'hbm' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );

	}
	
	/**
	 * Retrieve incident’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_incidents( $per_page = 25, $page_number = 1 ) {
	
	  global $wpdb;

		  
	  $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_incidents	 
	  			JOIN {$wpdb->prefix}heartbeatmonitor_clients 
	  			ON {$wpdb->prefix}heartbeatmonitor_incidents.client_id = {$wpdb->prefix}heartbeatmonitor_clients.client_id";
		//TODO: shorten sql search time...
	
	  if ( ! empty( $_REQUEST['orderby'] ) ) {
	    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
	    $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
	  }
	  else
		$sql .= ' ORDER BY `incident_since` DESC';
	
	  $sql .= " LIMIT $per_page";
	
	  $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
	
	
	  $result = $wpdb->get_results( $sql, 'ARRAY_A' );
	  
	  //customizing formatting
	  foreach($result as $key => $item)
	  {	  	
	  	$result[$key]['incident_remarks'] = nl2br($item['incident_remarks']);
		
		//TODO: work for formatting other than lost-connection..depends on error level..?
		if($item['incident_status'] != 'lost-connection'){
		   //TODO: format color?
		   $result[$key]['incident_resumed_since'] = '-';
		   $result[$key]['incident_duration'] = 'N/A';
		   
		} else if($item['incident_resumed_since'] == 0){
			$result[$key]['incident_status'] = '<span style="color: red">lost-connection</span>';
			$result[$key]['incident_resumed_since'] = '-';
			$result[$key]['incident_duration'] = 'Currently lost-connection';
						
		} else {
			$incident_resumed_since = new DateTime($item['incident_resumed_since']);
			$incident_since = new DateTime($item['incident_since']);
			$incident_duration = $incident_resumed_since->diff($incident_since);
			if($incident_duration->d > 0)
				$result[$key]['incident_duration'] = $incident_duration->format("%a day(s) %h hr %i min\n");
			else if	($incident_duration->h > 0)
				$result[$key]['incident_duration'] = $incident_duration->format("%h hours %i min\n");
			else
				$result[$key]['incident_duration'] = $incident_duration->format("%i min\n");
		}	
	  	
	  }
	
	  return $result;
	}
	
	
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
	  global $wpdb;
	
	  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}heartbeatmonitor_incidents";
	
	  return $wpdb->get_var( $sql );
	}
	/* moved to incident-add-remarks-action.php
	public static function add_remarks( $id, $remarks ) {
	  global $wpdb;
	
	  $sql = "SELECT * FROM {$wpdb->prefix}heartbeatmonitor_incidents WHERE incident_id = {$id}";	  
	  $row = $wpdb->get_row( $sql, 'ARRAY_A', 0 );	
	
	  $my_remarks = current_time('mysql'). ' by ' . wp_get_current_user()->user_login . 
	  	":\n".stripslashes($remarks)."\n\n".$row['incident_remarks'];
	  
	  $wpdb->update(
	    "{$wpdb->prefix}heartbeatmonitor_incidents",	    
	    [ 'incident_remarks' => $my_remarks ],	      
	    [ 'incident_id' => $id ]
	    
	  );
	}*/
		
	/**
	 *	Methods below are for overwriting the parent methods...
	 */
	
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */

	 function column_client_slug( $item ) {	  
	 	 return '<strong>' . $item['client_slug'] . '</strong>';
	}
	 
	 function column_incident_remarks( $item ) {
	
	  $actions = [
	    'add_remarks' => sprintf( '<a href="?action=%s&incident=%s&TB_iframe=true&width=400&height=250" class="thickbox">Add Remarks</a>', 
	    	 'hbm-incident-add-remarks', absint( $item['incident_id'] ) )
	  ];
	
	  return $item['incident_remarks'] . $this->row_actions( $actions );
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
	    case 'client_slug':	
	    case 'incident_status':
		case 'incident_since':
		case 'incident_resumed_since':
		case 'incident_duration':	
		case 'incident_remarks':			
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
	    '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
	  );
	}	
	
	/**
	 *  Associative array of columns
	 * 	    - incident-slug
	 * 		- if incident is enabled / disabled
	 * 		- incident status (active / lost-connection / disabled /deleted)
	 * 		- active since [time]
	 * 		- setup date
	 *
	 * @return array
	 *		
	 */
	function get_columns() {
	  $columns = [	 
	    'client_slug'    => __( 'Name', 'hbm' ), //TODO: should be client_slug
	    'incident_status'    => __( 'Status', 'hbm' ),
	    'incident_since'    => __( 'From', 'hbm' ),	
	    'incident_resumed_since' => __( 'To', 'hbm' ),	    
		'incident_duration'    => __( 'Duration', 'hbm' ),
		'incident_remarks'    => __( 'Remarks', 'hbm' ),
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
	    'client_slug' => array( 'client_slug', true ), //'client_slug' => array( 'incident_slug', true ), - too hard TODO
	    'incident_status' => array( 'incident_status', true ),
	    'incident_since' => array( 'incident_since', false ),
	    'incident_remarks' => array( 'incident_remarks', false ),
	    //'incident_duration' => array( 'incident_duration', false ) - this is too hard to sort.. TODO
	  );
	
	  return $sortable_columns;
	}
	
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
	
	  $per_page     = $this->get_items_per_page( 'clients_per_page', 25 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();
	
	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );
	
	
	  $this->items = self::get_incidents( $per_page, $current_page );
	  
	}

	public function process_bulk_action() {
	
	  //Detect when a bulk action is being triggered...	  
	  
	  //Single Actions
	  //moved to incident-add-remarks-action.php
	   /*
	  switch($this->current_action())
	  {
	  	case 'add-remarks':	  
		
	      self::add_remarks( $_REQUEST['incident'],  $_REQUEST['remarks'] );
		  echo "
		  <script type='text/javascript'>
		        function run_function() {		        	
		            parent.location.reload(1);
		        }
		  	window.onload = run_function();
		  </script>
		  ";	
		
			
		 //tb_close();
	     // wp_redirect( esc_url( add_query_arg() ) );
	      //exit;
	    
	
	   }*/	
	 }


}