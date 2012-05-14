<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : YouTube : Widgets
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/youtube
*
* Description: 	Widgets for YouTube for Social Igniter
*/

$config['youtube_widgets'][] = array(
	'regions'	=> array('sidebar', 'leftbar', 'content'),
	'widget'	=> array(
		'module'	=> 'youtube',
		'name'		=> 'Recent Videos',
		'method'	=> 'run',
		'path'		=> 'widgets_recent_videos',
		'multiple'	=> 'FALSE',		
		'order'		=> '1',
		'title'		=> 'Recent Videos',
		'content'	=> ''			
	)
);
