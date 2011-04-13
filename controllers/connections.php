<?php
class Connections extends MY_Controller
{
    function __construct()
    {
        parent::__construct();		   
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
	
	function avail_methods()
	{
/*
		getVideoEntry($videoId, $fullEntry = false)
		getRelatedVideoFeed($videoId)
		getVideoResponseFeed($videoId)
		getVideoCommentFeed($videoId)
		getTopRatedVideoFeed()
		getMostViewedVideoFeed()
		getRecentlyFeaturedVideoFeed()
		getWatchOnMobileVideoFeed()
		getPlaylistListFeed($user = 'default')
		getSubscriptionFeed($user = 'default')
		getContactFeed($user = 'default')
		getUserUploads($user = 'default')
		getUserFavorites($user = 'default')
		getUserProfile($user = 'default')
		getActivityForUser($user = 'default')
		getFriendActivityForCurrentUser()
		getInboxFeedForCurrentUser()
		getFormUploadToken($metadata)
		addComment($videoId, $comment, $commentId = false)
		directUpload($path, $contenttype, $metadata, $user = 'default') 	
*/	
	}	
	
}