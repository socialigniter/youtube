<h2 class="content_title"><img src="<?= $modules_assets ?>module_template_32.png"> Module Template</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home/module_template', 'Recent') ?>
	<?= navigation_list_btn('home/module_template/custom', 'Custom') ?>
	<?php if ($logged_user_level_id <= 2) echo navigation_list_btn('home/module_template/manage', 'Manage', $this->uri->segment(4)) ?>
</ul>