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
	
		$this->render();
	}
}