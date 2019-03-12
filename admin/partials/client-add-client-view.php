<?php
	// http://rizqy.me/create-modal-box-on-wordpress-dashboard/
    define( 'IFRAME_REQUEST', true );
    iframe_header();	
?>
<div style="padding: 20px">
<h2>Create Client Machine</h2>

<?if(isset($_REQUEST['taken']))
	echo '<p style="color: red">'. __('The name is already taken. Please pick another name.') . '</p>';
  if(isset($_REQUEST['empty']))	//TODO: do js check instead
	echo '<p style="color: red">'. __('You did not input any client name.') . '</p>';
?>

<form method="POST" action="<? echo site_url();?>/wp-admin/admin.php?action=hbm-add-client-action">	  
	  <input type="hidden" name="my_action" value="add-client">	
	    
	  Client Name: <input type="text" name='slug' placeholder="my-client-machine" value="<?php echo isset($_REQUEST['slug'])?$_REQUEST['slug']:'' ?>">
	  <br>Please note that this will be the client identifier, it should be unique and will be converted to lower case with dashes.
	  <br>
	  <input type="submit" value="Submit">
</form>
</div>
<?php
    iframe_footer();
    exit;
?>