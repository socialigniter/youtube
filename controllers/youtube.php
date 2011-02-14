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
	
}
