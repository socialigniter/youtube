<h2 class="content_title"><img src="<?= $modules_assets ?>youtube_32.png"> YouTube</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home/youtube', 'Recent') ?>
	<?= navigation_list_btn('home/youtube/upload', 'Upload') ?>
	<?php if ($logged_user_level_id <= 2) echo navigation_list_btn('home/youtube/manage', 'Manage', $this->uri->segment(4)) ?>
</ul>