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

function youtube_get_id_from_url($str)
{
	$protocol	='(http://)|(https://)|(http://www.)|(https://www.)|(www.)';	// Regex for allowed protocol
	$protocol	= str_replace('.', '\.', str_replace('/', '\/', $protocol)); 	// escape those reg exp characters
	$protocol	= ($protocol != '') ? '^(' . $protocol . ')' : $protocol; 		//if empty arg passed, let it it match anything at beginning
	$match_str	= '/' . $protocol . 'youtube\.com\/(.+)(v=.+)/'; 				//build the match string
	preg_match($match_str, $str, $matches); 									// find the matches and put them in $matches variable
	
	// Bad URI
	if (count($matches) < 3)
	{ 
		//No matter what protocol/prefix, we should have at least 3 matches
	  	return FALSE;
	}
	else
	{ 
		// So far so good....
		$qs		= explode('&',$matches[count($matches)-1]); 	//the last match will be the querystring - split them at amperstands
		$vid	= false; 										//default the video ID to false
		
		for ($i=0; $i<count($qs); $i++)
		{ 
			// loop through the params		
			$x = explode('=', $qs[$i]);
			
			 // split at = to find key/value pairs
			if ($x[0] == 'v' && $x[1])
			{ 
				// if the param is 'v', and it has a value associated, we want it
				// set the video id to the val
				$vid = $x[1];
				
				return $vid;
			}
			else
			{
				 //invalid querystring - couldn't find the video ID
				return FALSE;
			}
		}

		return FALSE; //everything went wrong....ouch
	}
}