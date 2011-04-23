<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* YouTube Helper
*
* @package		Social Igniter
* @subpackage	YouTube Helper
* @link			http://social-igniter.com/apps/youtube
*
* Description: 	Helper Functions for YouTube 
*/

function youtube_save_flv($video, $save_path)
{		
	$referrer	= 'http://www.youtube.com/v/'.$video.'?fs=0';
	$url		= 'http://www.youtube.com/get_video_info?&video_id='.$video.'&el=embedded&ps=default&eurl=&hl=en_US';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $referrer);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_REFERER, "");
	$o = curl_exec($ch);
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $referrer);
	$o = curl_exec($ch);
	$o = urldecode($o);
	$o = explode("|", $o);
	
	for ($i=0;$i<count($o);$i++)
	{
	   if (preg_match("/^http:\/\//", $o[$i]))
	   {
	      $url = $o[$i];
	      break;
	   }
	}
		
	$video_data = file_get_contents($url);
	
	file_put_contents($save_path.$videos.'.flv', $video_data);
}