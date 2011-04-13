<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
<div class="content_wrap_inner">

	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['youtube']['enabled']) ?></p>
	</div>

	<h3>Application Keys</h3>

	<p>YouTube requires <a href="https://youtube.com" target="_blank">registering your application</a></p>
				
	<p><input type="text" name="consumer_key" value="<?= $settings['youtube']['consumer_key'] ?>"> Consumer Key </p> 
	<p><input type="text" name="consumer_key_secret" value="<?= $settings['youtube']['consumer_key_secret'] ?>"> Consumer Key Secret</p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">

	<h3>Social</h3>

	<p>Sign In
	<?= form_dropdown('social_login', config_item('yes_or_no'), $settings['youtube']['social_login']) ?>
	</p>
	
	<p>Connections 
	<?= form_dropdown('social_connection', config_item('yes_or_no'), $settings['youtube']['social_connection']) ?>
	</p>	

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
			
	<h3>Default Account</h3>

	<p>http://youtube.com/ <input type="text" name="default_account" value="<?= $settings['youtube']['default_account'] ?>"></p> 

	<input type="hidden" name="module" value="youtube">

	<p><input type="submit" name="save" value="Save" /></p>

</div>
</form>

<?= $shared_ajax ?>