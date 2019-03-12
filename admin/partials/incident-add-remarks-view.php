<?php
	// http://rizqy.me/create-modal-box-on-wordpress-dashboard/
    define( 'IFRAME_REQUEST', true );
    iframe_header();	
?>
<div style="padding: 20px">
<h2>Add Remarks</h2>
<form method="POST" action="<? echo site_url();?>/wp-admin/admin.php?action=hbm-incident-add-remarks-action">	<? //TODO: better link? ?>
	  <input type="hidden" name="page" value="heartbeat-monitor">
	  <input type="hidden" name="my_action" value="add-remarks">
	  <input type="hidden" name="incident" value="<?php echo $_REQUEST['incident']?>">  
	  <textarea name='remarks' rows="4" cols="50"></textarea><br>
	  <br>
	  <input type="submit" value="Submit">
</form>
</div>
<?php
    iframe_footer();
    exit;
?>