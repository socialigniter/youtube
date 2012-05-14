<?php
class YouTube extends Site_Controller
{
    function __construct()
    {
        parent::__construct();       

		$this->load->config('youtube');
	}
	
	function index()
	{
		$this->data['page_title'] = 'YouTube';
		$this->render();	
	}

	function view() 
	{		
		// Basic Content Redirect	
		$this->render();
	}
	
	function widgets_recent_videos($widget_data)
	{
		if (config_item('youtube_default_account'))
		{
			$widget_data['videos'] = simplexml_load_file('http://gdata.youtube.com/feeds/api/users/'.config_item('youtube_default_account').'/uploads?alt=rss');
		}
		else
		{
			$widget_data['videos'] = '';
		}
	
		$this->load->view('widgets/recent_videos', $widget_data);
	}
	
}
