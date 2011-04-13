<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
<div class="content_wrap_inner">

	<h3>Recent Videos</h3>

	<p>Display
	<?= form_dropdown('recent_videos_display', config_item('numbers_one_five'), $settings['youtube']['recent_videos_display']) ?>
	</p>	

	<p>Click
	<?= form_dropdown('recent_videos_link_actions', config_item('link_actions'), $settings['youtube']['recent_videos_link_actions']) ?>
	</p>	

	<input type="hidden" name="module" value="youtube">

	<p><input type="submit" name="save" value="Save" /></p>

</div>

</form>

<?= $shared_ajax ?>