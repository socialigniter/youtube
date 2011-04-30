<?php
class Connections extends MY_Controller
{
    function __construct()
    {
        parent::__construct();		   
	}
	
	
	function index()
	{
		$redirectURI = base_url().'connections/youtube/callback';

		//if (get('google_connect_redirect'))
		//{
		//	$redirectURI .= '?redirect=' . urlencode(get('google_connect_redirect'));
		//}
	
		$google_config = array(
			'clientID' 		=> '175060600288.apps.googleusercontent.com',
			'clientSecret'	=> 'EAxUZv5CKLPAll5FalbfJ3AB',
			'baseRedirect'	=> $redirectURI
		);
	
		$this->load->library('google_oauth2', $google_config);
//		$googleAuth = new GoogleAuth('175060600288.apps.googleusercontent.com', 'EAxUZv5CKLPAll5FalbfJ3AB', $redirectURI);

		// The first visit from the mobile app will include an oauth_token from Geoloqi
		//$this->log_in_from_token();

		if ($this->google_oauth2->isCallback())
		{
			if ($this->google_oauth2->callback())
			{
				$response = $this->api->request('connect/google', array(
					'access_token'  => $this->google_oauth2->accessToken,
					'refresh_token' => $this->google_oauth2->refreshToken
				));
	
				if (property_exists($response, 'result') && $response->result == 'ok')
				{
					//$_SESSION['user_profile']->google_email = $response->email;
				
					print_r($response);
				}
				else
				{
					print_r($response);
					//$this->error(HTTP_SERVER_ERROR, $response->error, k($response, 'error_description'));
				}
			
				/*
				if(get('redirect'))
				{
					redirect(get('redirect'));
				}
				else
				{
					redirect('/settings/connections');
				}
				*/
				echo 'RIGHT HERE';
				
			}
			else
			{
				echo 'HERERERE';
				//$this->error(HTTP_SERVER_ERROR, 'google_auth_error', 'Unable to authenticate with Google.');
			}
		}
		else
		{
			redirect($this->google_oauth2->authorizeURL('http://www.google.com/calendar/feeds/default/allcalendars/full'));
		}	
	}
	
	function callback()
	{
	
		echo $_REQUEST['access_token'];
		
	}
	
	function youtube_request()
	{	
		$token = NULL;
	
	    $params['key'] 				= $this->config->item('google_consumer_key');
	    $params['secret'] 			= $this->config->item('google_consumer_secret');
	    $params['algorithm'] 		= "RSA-SHA1";
	    $params['method']			= 'POST';
		//$params['access_token'] 	= array('oauth_token' => urlencode('asdasd'));
	
	    $this->load->library('google_oauth', $params);
	    $response = $this->google_oauth->get_request_token(site_url("/user/youtube_access"));
	    
	    //$this->_store_somewhere($response['token_secret']);
	    redirect($response['redirect']);	
	}
	
	function youtube_access()
	{
	    $params['key']				= $this->config->item('google_consumer_key');
	    $params['secret']			= $this->config->item('google_consumer_secret');
	    $params['algorithm']		= "HMAC-SHA1";    
	
	    $this->load->library('google_oauth', $params);

	    $token_secret = $this->_get_from_storage('token_secret');
	
	    $oauth = $this->google_oauth->get_access_token(false, $token_secret);
	
	    $this->_store_in_db('youtube_token', $oauth['oauth_token']);
	    $this->_store_in_db('youtube_secret', $oauth['oauth_token_secret']);
	        
	    redirect(site_url());
	}
	
}