<?php
class Connections extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('youtube');

		$google_config = array(
			'client_id' 	=> config_item('youtube_client_id'),
			'client_secret'	=> config_item('youtube_client_secret'),
			'base_redirect'	=> base_url().'connections/youtube'
		);

		$this->load->library('google_oauth2', $google_config);

		$this->module_site = $this->social_igniter->get_site_view_row('module', 'youtube');		
	}
	
	function index()
	{	
		// Make Code
		if (isset($_GET['code'])) $code = '?code='.$_GET['code'];
		else $code = '';

		// User Is Logged In		
		if ($this->social_auth->logged_in()) redirect('connections/youtube/add'.$code);
	
		// Is Returning From Allow / Deny
		if (isset($_GET['code']))
		{
			$tokens = $this->google_oauth2->get_tokens('authorization_code', $_GET['code']);
			$tokens = json_decode($tokens['output']);
						
			$youtube_user = $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token='.$tokens->access_token);
			$youtube_user = simplexml_load_string($youtube_user['output']);
/*	
			echo '<h1>Here be yer token</h1>';
			echo '<pre>';
			print_r($token);
			echo '<hr>';
			print_r($youtube_user['output']);
*/
			// Add Connection
	   		$connection_data = array(
	   			'site_id'				=> $this->module_site->site_id,
	   			'user_id'				=> $user_id,
	   			'module'				=> 'youtube',
	   			'type'					=> 'primary',
	   			'connection_user_id'	=> $youtube_user->id,
	   			'connection_username'	=> $youtube_user->author->name,
	   			'auth_one'				=> $tokens->access_token,
	   			'auth_two'				=> $tokens->refresh_token
	   		);

			$connection = $this->social_auth->add_connection($connection_data);			

		}
		else
		{
			redirect($this->google_oauth2->authorize_url('http://gdata.youtube.com/feeds/'));
		}
	}
	
	function test()
	{
		$request	= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token=1/mm7qca40OASbhVUqivCkFvtjukLn8zwGt3FOyXxU6mQ');
		
		$user_info	= simplexml_load_string($request['output']);

		echo $user_info->author->name;
	}
	
	function add()
	{
		// User Is Logged In
		if (!$this->social_auth->logged_in()) redirect('connections/youtube');
				
		// Is Returning From Allow / Deny
		if (isset($_GET['code']))
		{
			// Get Tokens
			$tokens				= $this->google_oauth2->get_tokens('authorization_code', $_GET['code']);
			$tokens 			= json_decode($tokens['output']);
			
			// Get YouTube user
			$youtube_user		= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token='.$tokens->access_token);
			$youtube_user		= simplexml_load_string($youtube_user['output']);
			
			// Make Googles strict data typing proper
			$youtube_user_id	= (string)$youtube_user->author->name;
			$youtube_username	= (string)$youtube_user->author->name;
			$check_connection 	= $this->social_auth->check_connection_user_id($youtube_user_id, 'youtube');
			
			if (connection_has_auth($check_connection))
			{			
				$this->session->set_flashdata('message', "You've already connected this Twitter account");
				redirect('settings/connections', 'refresh');							
			}
			else
			{
				// Add Connection	
		   		$connection_data = array(
		   			'site_id'				=> $this->module_site->site_id,
		   			'user_id'				=> $this->session->userdata('user_id'),
		   			'module'				=> 'youtube',
		   			'type'					=> 'primary',
		   			'connection_user_id'	=> $youtube_user_id,
		   			'connection_username'	=> $youtube_username,
		   			'auth_one'				=> $tokens->access_token,
		   			'auth_two'				=> $tokens->refresh_token
		   		);

	       		// Update / Add Connection	       		
	       		if ($check_connection)
	       		{
	       			$connection = $this->social_auth->update_connection($check_connection->connection_id, $connection_data);
	       		}
	       		else
	       		{
					$connection = $this->social_auth->add_connection($connection_data);
				}

				// Connection Status				
				if ($connection)
				{
					$this->social_auth->set_userdata_connections($this->session->userdata('user_id'));
				
					$this->session->set_flashdata('message', "YouTube account connected");
				 	redirect('settings/connections', 'refresh');
				}
				else
				{
				 	$this->session->set_flashdata('message', "That YouTube account is connected to another user");
				 	redirect('settings/connections', 'refresh');
				}
			}
		}
		else
		{
			redirect($this->google_oauth2->authorize_url('http://gdata.youtube.com/feeds/'));		
		}
	}
	
	function get_profile()
	{
		$request = $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token=1/X1Pir5iy6R5jQON5EDwV-NEit6OAwB32g6qbbbKIZp0');
		
		echo '<h1>Yay some data</h1>';
		echo '<pre>';
				
		// Handles the whole refresh token thing
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
	
}