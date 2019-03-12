<?php
/*
 * View for the "Incidents" tab - 20171031
 */

 //https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
if ( ! class_exists( 'Incidents_List' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '../class-incidents-list.php';	
}

add_thickbox();	//will be used in add_remarks

?>
	<div class="wrap">
		<h2>Incidents</h2>

		TODO： filter by machine-slug
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">						
						<form method="post">														
							
							<?php
							$this->incidents_obj = new Incidents_List();	//TODO: note - this is not singleton. does it matter?
							$this->incidents_obj->prepare_items();
							$this->incidents_obj->display(); ?>
						</form>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
	</div>