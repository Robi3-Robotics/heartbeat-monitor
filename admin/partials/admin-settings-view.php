<h2>System Heartbeat Monitor Settings</h2>
	<pre>
		Due to time limitation, all settings are currently hardcoded:
		
		limit check is setup to check at every 5 min
		system warning will be issue when the checking is less than 5 min..
		
		
		The cronjob is now hardcoded to perform every 5 min. TODO: put it into settings.. & edit admin/cronjob-action.php
		*/5 * * * * wget -qO- "https://robicube.com/wp-json/heartbeat-monitor/v1/run-cronjob" &>/dev/null
		The "5" min is "set" just according your agreement with the client, and how you set the cronjob. 
		Also, the checking accroding to HBM_cronjob $time_tolerance = 5;    //TODO: get this from settings
		
		Currently it's set to be done at Jason's server every 1 min...
		
		TODO: add to plugin installation / removal:
		create table 
		[prefix]heartbeatmonitor_clients
		[prefix]heartbeatmonitor_incidents
		
		TODO Option: continue to send warning notififation (not only at status change) at an interval of Y minutes
	</pre>
	


	<div class="wrap">
		<h2>System Heartbeat Monitor Settings</h2>
		
	
		
		<p>	New Client Instruction: </p>
			Client machine send to the API at regular interval. 
			send to:
			<div class="alternate">
				<?php echo site_url(); ?>/xxxx/[]
			</div>
			For example, if your client name is "my-client-machine" and secret is "ab36s5d",
			<div class="alternate">
				<?php echo site_url(); ?>/xxxx/slug=my-client-machine&secret=ab36s5d
			</div>
			If it doesn't, an incident will be marked and actions (according to settings below) will be marked.
			
		
		
	</div>
<div style="visibility:  hidden ">	
		<form method="post" name="heartbeat_options" action="options.php">
	
			<!-- remove some meta and generators from the <head> -->
			<fieldset>
				<legend class="screen-reader-text"><span>Clean WordPress head section</span></legend>
				<label for="<?php echo $this->plugin_name; ?>-cleanup">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-cleanup" name="<?php echo $this->plugin_name; ?> [cleanup]" value="1"/>
					<span><?php esc_attr_e('Clean up the head section', $this->plugin_name); ?></span>
				</label>
			</fieldset>
	
			<!-- remove injected CSS from comments widgets -->
			<fieldset>
				<legend class="screen-reader-text"><span>Remove Injected CSS for comment widget</span></legend>
				<label for="<?php echo $this->plugin_name; ?>-comments_css_cleanup">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-comments_css_cleanup" name="<?php echo $this->plugin_name; ?>[comments_css_cleanup]" value="1"/>
					<span><?php esc_attr_e('Remove Injected CSS for comment widget', $this->plugin_name); ?></span>
				</label>
			</fieldset>
	
			<!-- remove injected CSS from gallery -->
			<fieldset>
				<legend class="screen-reader-text"><span>Remove Injected CSS for galleries</span></legend>
				<label for="<?php echo $this->plugin_name; ?>-gallery_css_cleanup">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-gallery_css_cleanup" name="<?php echo $this->plugin_name; ?>[gallery_css_cleanup]" value="1" />
					<span><?php esc_attr_e('Remove Injected CSS for galleries', $this->plugin_name); ?></span>
				</label>
			</fieldset>
	
			<!-- add post,page or product slug class to body class -->
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Add Post, page or product slug to body class', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-body_class_slug">
					<input type="checkbox" id="<?php echo $this->plugin_name;?>-body_class_slug" name="<?php echo $this->plugin_name; ?>[body_class_slug]" value="1" />
					<span><?php esc_attr_e('Add Post slug to body class', $this->plugin_name); ?></span>
				</label>
			</fieldset>
	
			<!-- load jQuery from CDN -->
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Load jQuery from CDN instead of the basic wordpress script', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-jquery_cdn">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-jquery_cdn" name="<?php echo $this->plugin_name; ?>[jquery_cdn]" value="1" />
					<span><?php esc_attr_e('Load jQuery from CDN', $this->plugin_name); ?></span>
				</label>
						<fieldset>
							<p>You can choose your own cdn provider and jQuery version(default will be Google Cdn and version 1.11.1)-Recommended CDN are <a href="https://cdnjs.com/libraries/jquery">CDNjs</a>, <a href="https://code.jquery.com/jquery/">jQuery official CDN</a>, <a href="https://developers.google.com/speed/libraries/#jquery">Google CDN</a> and <a href="http://www.asp.net/ajax/cdn#jQuery_Releases_on_the_CDN_0">Microsoft CDN</a></p>
							<legend class="screen-reader-text"><span><?php _e('Choose your prefered cdn provider', $this->plugin_name); ?></span></legend>
							<input type="url" class="regular-text" id="<?php echo $this->plugin_name; ?>-cdn_provider" name="<?php echo $this->plugin_name; ?>[cdn_provider]" value=""/>
						</fieldset>
			</fieldset>
	
			<?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
	
		</form>
	</div>