<form name="settings" method="post" action="<?= base_url() ?>settings/update" enctype="multipart/form-data">	
<div class="content_wrap_inner">

	<div class="content_inner_top_right">
		<h3>Module</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['youtube']['enabled']) ?></p>
	</div>

	<h3>Display</h3>
	
	<p>Date
	<?= form_dropdown('date_style', config_item('date_style_types'), $settings['youtube']['date_style']) ?>
	</p>

</div>

<span class="item_separator"></span>

<div class="content_wrap_inner">
			
	<h3>Comments</h3>	

	<p>Allow
	<?= form_dropdown('module_allow', config_item('yes_or_no'), $settings['youtube']['comments_allow']) ?>
	</p>

	<p>Comments Per-Page
	<?= form_dropdown('module_per_page', config_item('amount_increments_five'), $settings['youtube']['comments_per_page']) ?>
	</p>

	<input type="hidden" name="module" value="blog">

	<p><input type="submit" name="save" value="Save" /></p>

</div>
</form>
