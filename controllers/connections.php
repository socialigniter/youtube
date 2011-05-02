<?php
class Connections extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('youtube');

		$google_config = array(
			'client_id' 	=> '175060600288-huob0454npn23b8o13tinka6tgtkmvff.apps.googleusercontent.com',
			'client_secret'	=> 'Rpa-QlOdKizrm_9EHf51bnqt',
			'base_redirect'	=> base_url().'connections/youtube'
		);
	
		$this->load->library('google_oauth2', $google_config);
	}
	
	function index()
	{	
		// User Is Logged In
		if ($this->social_auth->logged_in()) redirect('connections/twitter/add');
	
		if (isset($_GET['code']))
		{
			$token = $this->google_oauth2->get_tokens('authorization_code', $_GET['code']);
			$token = json_decode($token['output']);
	
			echo '<h1>Here be yer token</h1>';
			echo '<pre>';
			print_r($token);
		}
		else
		{
			$auth_scope = 'http://gdata.youtube.com/feeds/api/users/default/uploads/';
			redirect($this->google_oauth2->authorize_url($auth_scope));
		}
	}
	
	function add()
	{
	
	
	}
	
	function get_profile()
	{
		$request = $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/BrennanNovak?oauth_token=1/X1Pir5iy6R5jQON5EDwV-NEit6OAwB32g6qbbbKIZp0');
		
		echo '<h1>Yay some data</h1>';
		echo '<pre>';
		
		$this->load->helper('xml');
		
		// Handles the whole refresh token thing
		//
		// Success
		if ($request['info']['http_code'] == 200)
		{	
			print_r($request['output']);	
		}
		// Awwww auth token expired
		elseif ($request['info']['http_code'] == 401)
		{
			$refresh_token = '1/o-eBlmweYn1GCHh_0xXg_s9ITaQlmDB9wGefanejda0';
			$token = $this->google_oauth2->get_tokens('refresh_token', $refresh_token);
			$token = json_decode($token);
			
			print_r($token);
		}
		else
		{
			print_r($request);
		}
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
			$xml_data = '<?xml version="1.0"?><entry xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:yt="http://gdata.youtube.com/schemas/2007"><media:group><media:title type="plain">Bad Wedding Toast</media:title><media:description type="plain"> I gave a bad toast at my friends wedding.</media:description><media:category scheme="http://gdata.youtube.com/schemas/2007/categories.cat">People</media:category><media:keywords>toast, wedding</media:keywords></media:group></entry>';
		
			$request = $this->google_oauth2->request_oauth_post($oauth_token, $developer_key, $xml_data);

			$this->data['form_type'] 	= 'info';
			$this->data['token']		= '';
			
			echo '<h1>Yay Posted</h1>';
			echo $request['output'];					
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
	
	function test()
	{
		$this->load->library('xml');
		
		$xml = "<?xml version='1.0' encoding='UTF-8'?><response><url>https://uploads.gdata.youtube.com/action/FormDataUpload/AIwbFASwCSFS8Hxp4qSEaMtu0mbd_5veFBfHP6QIJx_WbDdVhJZj9gY-bFtdVhooMueqhWBjZnKyRYqJw4_4Usgvg8PjNo-9th4yN5BW4kDlGaPZ54Zxb06ckXLdqwiLNAL9H7Sx_PopI5zxzpOwTGETAsrYU_0b2UViHcmV6zDfvDKopkNa5DI</url><token>AIwbFASvZCDhFh_Q1JIje9HF0414yaxBhloMt0XuOPlDTwmI-K1kg92iESXQhiVY1N9A7-2bzq8HjCRrmR1eK3bJuYfQAIRIT16DdmmE2s6wLU4fr8kde6WB1VGFsL8UPOSORS1XEZpp_h09ZUEyZc-mZ3yfScFNbAMs-vpV6Wp_ZKP2zojSIcuIacUcnyqUt3sR0qVje2L30lT29Y2u17BO3OZziIYJgiFeasZozkDd9V7-sWXw0IBcVmAIuc9vQpuVqJmy2bgtkOpzHJuflrTVIqRwbMM75FRg_Zn7mAUN0OFjfuqLHfxl89iSQkVLcaCFk-bC5BUo58hlgwIiPXXaow6QAjIikvhflQAFd_wFz9FeJY1oYHraP6lL6p5KrTyAu62HisFaUygNS_IXw0SfJVN3HTZnyAmV_JvBUR4ScSz1n-lBLWg</token></response>";
	
		$test = simplexml_load_string($xml);
	
		echo $test->url;
		
	}
	
	
}