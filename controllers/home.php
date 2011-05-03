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
	
	
	function upload()
	{
		$oauth_token 	= '1/X1Pir5iy6R5jQON5EDwV-NEit6OAwB32g6qbbbKIZp0';
		$developer_key	= 'AI39si5OF3SZsVtRraN5H7DWu-ltIWGPKlFf8Qfke9R6Dx6B8EmBLJlgJjieUF9N8Vn8ueUK8-GvyxO_eMLzwDoVSmJ7M73zLQ';
	
		if (!$_POST)
		{
			$this->data['form_type']	= 'info';
	
			$this->load->view('upload_info', $this->data);
		}
		else
		{
			$xml_data	= '<?xml version="1.0"?><entry xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:yt="http://gdata.youtube.com/schemas/2007"><media:group><media:title type="plain">Bad Wedding Toast</media:title><media:description type="plain"> I gave a bad toast at my friends wedding.</media:description><media:category scheme="http://gdata.youtube.com/schemas/2007/categories.cat">People</media:category><media:keywords>toast, wedding</media:keywords></media:group></entry>';
		
			$request	= $this->google_oauth2->request_oauth_post($oauth_token, $developer_key, $xml_data);
			$response	= simplexml_load_string($request['output']);

			$this->data['form_type'] 	= 'info';
			$this->data['form_url']		= $response->url;
			$this->data['token']		= $response->token;

			$this->load->view('upload_file', $this->data);					
		}
	}
		
	function upload_file()
	{
		$this->data['url'] = 'https://uploads.gdata.youtube.com/action/FormDataUpload/AIwbFASwCSFS8Hxp4qSEaMtu0mbd_5veFBfHP6QIJx_WbDdVhJZj9gY-bFtdVhooMueqhWBjZnKyRYqJw4_4Usgvg8PjNo-9th4yN5BW4kDlGaPZ54Zxb06ckXLdqwiLNAL9H7Sx_PopI5zxzpOwTGETAsrYU_0b2UViHcmV6zDfvDKopkNa5DI';
		$this->data['token'] = 'AIwbFASvZCDhFh_Q1JIje9HF0414yaxBhloMt0XuOPlDTwmI-K1kg92iESXQhiVY1N9A7-2bzq8HjCRrmR1eK3bJuYfQAIRIT16DdmmE2s6wLU4fr8kde6WB1VGFsL8UPOSORS1XEZpp_h09ZUEyZc-mZ3yfScFNbAMs-vpV6Wp_ZKP2zojSIcuIacUcnyqUt3sR0qVje2L30lT29Y2u17BO3OZziIYJgiFeasZozkDd9V7-sWXw0IBcVmAIuc9vQpuVqJmy2bgtkOpzHJuflrTVIqRwbMM75FRg_Zn7mAUN0OFjfuqLHfxl89iSQkVLcaCFk-bC5BUo58hlgwIiPXXaow6QAjIikvhflQAFd_wFz9FeJY1oYHraP6lL6p5KrTyAu62HisFaUygNS_IXw0SfJVN3HTZnyAmV_JvBUR4ScSz1n-lBLWg';
	
		$this->load->view('upload_file', $this->data);
	}
	
	function success()
	{
		echo '<h1>Yay Boi</h1>';
	}
		
	
}