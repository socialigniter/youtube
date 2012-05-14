<?php
class Home extends Dashboard_Controller
{
    function __construct()
    {
        parent::__construct();

		$this->data['page_title'] = 'YouTube';

		$this->load->config('youtube');
	}
	
	function upload()
	{
		$this->data['categories'] = $this->social_tools->make_categories_dropdown(array('categories.module' => 'youtube'), $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));
		
		$this->render('dashboard_wide');		
	}
	
	function success()
	{
		echo '<h1>Whoopeeeee</h1>';
	}
	
	function file()
	{
	
	
	}
	
}