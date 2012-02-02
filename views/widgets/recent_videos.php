<style>
#widget_recent_videos { margin: 0; }
img.youtube_sidebar_thumbnail { width: 225px; }	
</style>
<div class="widget_<?= $widget_region ?> widget_youtube_recent_videos" id="widget_<?= $widget_id ?>">
	<?php if ($widget_title): ?> 
	<div class="widget_youtube_recent_videos_title"><h2><?= $widget_title ?></h2></div>
	<?php endif; ?>
	<?php if ($videos): $i=0; foreach ($videos->channel->item as $video): $i++; if ($i <= config_item('youtube_recent_videos_display')): preg_match("/http:\/\/gdata.youtube.com\/feeds\/api\/videos\/(([a-zA-Z0-9]|-|_)*)/", $video->guid, $video_matches);  ?>
	<p>
		<a href="<?= $video->link ?>" target="_blank"><img class="youtube_sidebar_thumbnail" src="http://img.youtube.com/vi/<?= $video_matches[1]; ?>/0.jpg"></a><br>
		<a href="<?= $video->link ?>" target="_blank"><?= $video->title ?></a>
	</p>
	<?php endif; endforeach; else: ?>
	<p>No YouTube account has been specified</p>
	<?php endif; ?>
</div>