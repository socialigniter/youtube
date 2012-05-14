<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();

    	$this->form_validation->set_error_delimiters('', '');        
	}
	
    /* Install App */
	function install_authd_get()
	{
		// Load
		$this->load->library('installer');
		$this->load->config('install');        

		// Settings & Create Folders
		$settings = $this->installer->install_settings('youtube', config_item('youtube_settings'));
	
		if ($settings == TRUE)
		{
            $message = array('status' => 'success', 'message' => 'Yay, the YouTube App was installed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Dang the YouTube App could not be uninstalled');
        }		
		
		$this->response($message, 200);
	}	

	function upload_info_authd_post()
	{
		// Validation Rules
	   	$this->form_validation->set_rules('title', 'Title', 'required');

		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {
			$check_connection = $this->social_auth->check_connection_user($this->oauth_user_id, 'youtube', 'primary');
	    
	    	if ($check_connection)
	    	{
	    		// Load Libs
				$google_config = array(
					'client_id' 	=> config_item('youtube_client_id'),
					'client_secret'	=> config_item('youtube_client_secret'),
					'base_redirect'	=> base_url().'connections/youtube'
				);

				$this->load->library('google_oauth2', $google_config);

	    		// Data & XML Template
				$data['title']			= $this->input->post('title');
				$data['description']	= $this->input->post('description');
				$data['category']		= 'People';
				$data['keywords']		= $this->input->post('tags');

				$xml_data	= $this->load->view('partials/upload_atom_data', $data, true);
				$request	= $this->google_oauth2->request_oauth_post($check_connection, config_item('youtube_developer_key'), $xml_data);
						
		    	if ($request['info']['http_code'] == 200)
			    {	
					$response = simplexml_load_string($request['output']);
			    		
					if (isset($response->url) AND isset($response->token))
			    	{
		        		$message = array('status' => 'success', 'message' => 'Awesome. Now Select the file you would like to upload', 'data' => array('url' => (string)$response->url, 'token' => (string)$response->token));		    
		        	}
		        	else
		        	{
		        		$message = array('status' => 'error', 'message' => 'Oops something went awry somewhere');
		        	}	
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops request was not friendly');
		        }
			}
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'No YouTube account connected to this user');
	        }
		}
		else
		{
	        $message = array('status' => 'error', 'message' => validation_errors());
		}

        $this->response($message, 200);		
	}

    function create_authd_post()
    {
		// Load Helper
		$this->load->helper('youtube');
    
		// Does URL have valid ID    	
		if ($video_url = $this->input->post('youtube_url'))
		{	
			$video_id	= youtube_get_id_from_url($video_url);		
			$video		= simplexml_load_file('http://gdata.youtube.com/feeds/api/videos/'.$video_id);			
			$link		= (string)$video->id;
			$title 		= (string)$video->title;
			$published	= (string)$video->published;
			$content	= (string)$video->content;
			$author		= (string)$video->author->name;
			
			$check_content = $this->social_igniter->get_content_title_url('video', $video_id);
			
			if (!$check_content)
			{
				$content_data = array(
					'link'		=> $link,
					'published'	=> $published,
					'content'	=> $content,
					'author'	=> $author
				);
					
				// Values
		    	$content_data = array(
		    		'site_id'			=> config_item('site_id'),
					'parent_id'			=> 0,
					'category_id'		=> 0,
					'module'			=> 'rafachant',
					'type'				=> 'video',
					'source'			=> 'youtube',
					'order'				=> 0,
		    		'user_id'			=> $this->session->userdata('user_id'),
					'title'				=> $title,
					'title_url'			=> $video_id,
					'content'			=> json_encode($content_data),
					'details'			=> '',
					'access'			=> 'E',
					'comments_allow'	=> 'Y',
					'geo_lat'			=> 0,
					'geo_long'			=> 0,
					'viewed'			=> 'N',
					'approval'			=> 'N',
					'status'			=> 'S'
		    	);
		    	
				$activity_data = array(			
					'title'			=> $title,
					'thumb' 		=> 'http://i.ytimg.com/vi/'.$video_id.'/3.jpg',
					'description' 	=> $content,
				);
								     		
				// Insert
				$result = $this->social_igniter->add_content($content_data, $activity_data);	    	
	
		    	if ($result)
			    {
		        	$message = array('status' => 'success', 'message' => 'Awesome we posted your '.$content_data['type'], 'data' => $result['content'], 'activity' => $result['activity']);		    
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops unable to add image to site');
		        }			
			}
			else
			{
				$message = array('status' => 'error', 'message' => 'That video has already been added, but you may record & upload another video');		
			}
		}					
		else 
		{ 
			$message = array('status' => 'error', 'message' => 'That was not a proper YouTube URL', 'data' => $this->input->post('youtube_url'));
		}

        $this->response($message, 200);        
    }  

/*    
    function get_user_uploads()
    {
		log_message('debug', 'RAFA inside video_lookup');

		if ($this->input->post('youtube_account_opt') == 'yes')
		{			
			$connection	= $this->social_auth->check_connection_user($this->oauth_user_id, 'youtube', 'primary');				
			$result		= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/'.$connection->connection_username.'/uploads');
			$videos		= simplexml_load_string($result['output']);
			$video_id	= str_replace('http://gdata.youtube.com/feeds/api/videos/', '',$videos->entry[0]->id);				
			$process	= TRUE;
		}
		else
		{
			$result		= $this->google_oauth2->request_ssl_get('http://gdata.youtube.com/feeds/api/users/'.config_item('youtube_default_account').'/uploads');
			$videos		= simplexml_load_string($result['output']);
			$process	= FALSE;
			$message	= array('status' => 'error', 'message' => 'We could not find your video in our <a href="http://youtube.com/'.config_item('youtube_default_account').'" target="_blank">YouTube account</a>');
			$video_id	= NULL;

			foreach ($videos->entry as $video)
			{	
				if ($video->title == $this->input->post('youtube_video_name'))
				{
					$video_id	= str_replace('http://gdata.youtube.com/feeds/api/videos/', '', $video->id);
					$process	= TRUE;
					break;
				}					
			}
		}        
    }
*/
}