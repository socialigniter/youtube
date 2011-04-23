<style>
#widget_recent_videos { margin: 0; }
img.youtube_sidebar_thumbnail { width: 225px; }	
</style>
<div id="widget_recent_videos">
	<h2>Videos</h2>
	<?php $i=0; foreach ($videos->channel->item as $video): $i++; if ($i <= config_item('youtube_recent_videos_display')): preg_match("/http:\/\/gdata.youtube.com\/feeds\/api\/videos\/(([a-zA-Z0-9]|-|_)*)/", $video->guid, $video_matches);  ?>
	<p>
		<a href="<?= $video->link ?>" target="_blank"><img class="youtube_sidebar_thumbnail" src="http://img.youtube.com/vi/<?= $video_matches[1]; ?>/0.jpg"></a><br>
		<a href="<?= $video->link ?>" target="_blank"><?= $video->title ?></a>
	</p>
	<?php endif; endforeach; ?>
</div>