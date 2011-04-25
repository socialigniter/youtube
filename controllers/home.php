<?php
class Home extends Dashboard_Controller
{
    function __construct()
    {
        parent::__construct();

		$this->data['page_title'] = 'YouTube';

		$this->load->config('youtube');
	}
	
	function custom()
	{
		$this->data['sub_title'] = 'Custom';
	
		$this->load->helper('youtube');
		
		$video_url = 'http://youtube.com/watch?v=_ZSbC09qgLI&feature=rec-HM-rev-rn';
		$video_url = 'https://www.youtube.com/watch?v=eWmVrfjDCyw&feature=feedf';
	
		$u = validate_youtube_url($video_url);
		echo $u[1];
	
		$this->render();
	}
}