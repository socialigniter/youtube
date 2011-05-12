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

		// User Logged In		
		if ($this->social_auth->logged_in()) redirect('connections/youtube/add'.$code);
	
		// Returning From Allow / Deny
		if (isset($_GET['code']))
		{
			// Get Tokens
			$tokens			= $this->google_oauth2->get_tokens('authorization_code', $_GET['code']);
			$tokens 		= json_decode($tokens['output']);						
			
			// Opps
			if (!isset($tokens->access_token)) redirect('connections/youtube', 'refresh');

			// Get YouTube user
			$youtube_user	= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token='.$tokens->access_token);
			$youtube_user	= simplexml_load_string($youtube_user['output']);

			// Make Googles strict data typing proper
			$youtube_username	= (string)$youtube_user->author->name;
			$youtube_email 		= $youtube_username.'@youtube.com';
			$check_connection 	= $this->social_auth->check_connection_user_id($youtube_username, 'youtube');

			// Check Connection
			if ($check_connection)
			{					
				// Login
				if ($this->social_auth->social_login($check_connection->user_id, 'youtube')) 
	        	{ 
		        	$this->session->set_flashdata('message', 'Login with YouTube Success');
		        	redirect(login_redirect(config_item('youtube_connections_redirect')), 'refresh');
		        }
		        else 
		        { 
		        	$this->session->set_flashdata('message', 'Login with YouTube Did Not Work');
		        	redirect('login', 'refresh');
		        }
			}
			else
			{	
				// Check
				if ($user_check = $this->social_auth->get_user('email', $youtube_email))
				{
					// Set
					$user_id = $user_check->user_id;
					
					// Add Connection
			   		$connection_data = array(
			   			'site_id'				=> $this->module_site->site_id,
			   			'user_id'				=> $user_id,
			   			'module'				=> 'youtube',
			   			'type'					=> 'primary',
			   			'connection_user_id'	=> $youtube_username,
			   			'connection_username'	=> $youtube_username,
			   			'auth_one'				=> $tokens->access_token,
			   			'auth_two'				=> $tokens->refresh_token
			   		);
		
					$connection = $this->social_auth->add_connection($connection_data);					
				}
				else
				{
					// Create User
			    	$additional_data = array(
						'name' 		 	=> $youtube_username,
						'image'		 	=> '',
						'language'		=> config_item('languages_default'),
						'time_zone'		=> '',
						'geo_enabled'	=> 0,
						'connection'	=> 'YouTube'
			    	);
			    			       			      				
			    	// Register User
			  		$user_id = $this->social_auth->social_register(strtolower($youtube_username), $youtube_email, $additional_data);
		        	
		        	if ($user_id)
		        	{
		        		$user_meta_data = array();
		        	
						// Add Meta
						if (property_exists($youtube_user, 'link'))
						{							
							$user_meta_data['url'] = 'http://youtube.com/'.$youtube_username;
						}
						
						$this->social_auth->update_user_meta(config_item('site_id'), $user_id, 'users', $user_meta_data);					
						
						// Add Connection
				   		$connection_data = array(
				   			'site_id'				=> $this->module_site->site_id,
				   			'user_id'				=> $user_id,
				   			'module'				=> 'youtube',
				   			'type'					=> 'primary',
		   					'connection_user_id'	=> $youtube_username,
			   				'connection_username'	=> $youtube_username,
				   			'auth_one'				=> $tokens->access_token,
				   			'auth_two'				=> $tokens->refresh_token
				   		);
				   							
						$connection = $this->social_auth->add_connection($connection_data);
		       		}
		       		else
		       		{
		        		$this->session->set_flashdata('message', 'Error creating user & logging in');
		        		redirect('login', 'refresh');
		       		}
		       	}
		       		
				// Login
				if ($this->social_auth->social_login($user_id, 'youtube'))
	        	{
	    			$this->session->set_flashdata('message', 'User created and logged in');
		        	redirect(login_redirect(config_item('youtube_connections_redirect')), 'refresh');
		        }
		        else 
		        {
		        	$this->session->set_flashdata('message', 'Login with YouTube in-correct');
		        	redirect('login', 'refresh');
		        }
			}
		}
		else
		{
			redirect($this->google_oauth2->authorize_url('http://gdata.youtube.com/feeds/'));
		}
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

			// Opps
			if (!isset($tokens->access_token)) redirect('connections/youtube/add', 'refresh');
			
			// Get YouTube user
			$youtube_user		= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/default?oauth_token='.$tokens->access_token);
			$youtube_user		= simplexml_load_string($youtube_user['output']);
			
			// Make Googles strict data typing proper
			$youtube_user_id	= (string)$youtube_user->author->name;
			$youtube_username	= (string)$youtube_user->author->name;
			$check_connection 	= $this->social_auth->check_connection_user_id($youtube_user_id, 'youtube');
			
			if (connection_has_auth($check_connection))
			{			
				$this->session->set_flashdata('message', 'You have already connected this YouTube account');
				redirect(connections_redirect(config_item('youtube_connections_redirect')), 'refresh');							
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
				
					$this->session->set_flashdata('message', 'YouTube account connected');
				 	redirect(connections_redirect(config_item('youtube_connections_redirect')), 'refresh');
				}
				else
				{
				 	$this->session->set_flashdata('message', 'That YouTube account is connected to another user');
				 	redirect(connections_redirect(config_item('youtube_connections_redirect')), 'refresh');
				}
			}
		}
		else
		{
			// Check If User Has Connection
			if ($check_connection = $this->social_auth->check_connection_user($this->session->userdata('user_id'), 'youtube', 'primary'))
			{
				redirect(connections_redirect(config_item('youtube_connections_redirect')), 'refresh');
			}
			else
			{	
				redirect($this->google_oauth2->authorize_url('http://gdata.youtube.com/feeds/'));		
			}
		}
	}
}