<?php
	// http://rizqy.me/create-modal-box-on-wordpress-dashboard/
    define( 'IFRAME_REQUEST', true );
    iframe_header();	
?>
<div style="padding: 20px">
<h2>Update Slug</h2>

<?if(isset($_REQUEST['taken']))

	echo '<p style="color: red">'. __('The name is already taken. Please pick another name.') . '</p>';
?>

Please note that this will be the client identifier, it should be unique and will be converted to lower case with dashes.
<form method="POST" action="<? echo site_url();?>/wp-admin/admin.php?action=hbm-client-update-slug-action">
<!--<form method="POST" action="<? echo site_url();?>/wp-admin/admin.php?page=heartbeat-monitor-clients">-->		
	  <input type="hidden" name="page" value="heartbeat-monitor">
	  <input type="hidden" name="my_action" value="update-slug">
	  <input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">  
	  <input type="text" name='slug' value="<?php echo $_REQUEST['slug']?>">
	  <br>
	  <input type="submit" value="Submit">
</form>
</div>
<?php
    iframe_footer();
    exit;
?>