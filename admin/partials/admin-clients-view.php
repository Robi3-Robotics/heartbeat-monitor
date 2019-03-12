<?php
/*
 * View for the "Clients" tab - 20171031
 */

 //https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
if ( ! class_exists( 'Clients_List' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '../class-clients-list.php';

add_thickbox();	//will be used in add client, update slug..
	
}
?>
	<div class="wrap">
		<h2>Client Machines</h2>
		
		<p><a href="?action=hbm-add-client&TB_iframe=true&width=400&height=230" class="thickbox">Add Client</a></p>
		
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<form method="post">	
												
							<?php							
							$this->clients_obj = new Clients_List();	//TODO: note - this is not singleton. does it matter?
							$this->clients_obj->prepare_items();
							
							//$this->clients_obj->search_box('search', 'search_id'); //TODO
							$this->clients_obj->display(); ?>
						</form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>