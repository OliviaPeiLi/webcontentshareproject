<?php
/*
 * Fandrop extensions to twitter library
 */
require_once "twitter/OAuth.php";
require_once "twitter/TwitterOAuth.php";
class Twitter {
	
	public $twitteroauth;
	public $user_id;
	private $ci;
	
	public function __construct($config = array()) {
		$this->ci = get_instance();
		
		if (isset($config['oauth_token']) && isset($config['oauth_token_secret'])) {
			$this->ci->session->set_userdata('twitter_request', array(
				'oauth_token' => $config['oauth_token'],
				'oauth_token_secret' => $config['oauth_token_secret']
			));
		}
		
		$twitter_request = $this->ci->session->userdata('twitter_request');
		if ($twitter_request && isset($twitter_request['oauth_token']) ) {
			$this->twitteroauth = new TwitterOAuth(
											$this->ci->config->item('twtr_api_key'),
											$this->ci->config->item('twtr_api_secret'),
											$twitter_request['oauth_token'],
											$twitter_request['oauth_token_secret']
										);
			if (isset($_GET['oauth_verifier'])) {
				$twitter_request = $this->twitteroauth->getAccessToken($_GET['oauth_verifier']);
				$this->ci->session->set_userdata('twitter_request', $twitter_request);
			}
		} else {
			if (isset($config['user_id'])) $this->user_id = $config['user_id'];
			$this->twitteroauth = new TwitterOAuth(
											$this->ci->config->item('twtr_api_key'),
											$this->ci->config->item('twtr_api_secret')
										);
		}
	}
	
	/**
	 * @param $url - callback url to which twitter will redirect after login
	 * return twitter url to add it to the link
	 */
	public function set_approve_url($url, $full = true) {
		//$request_token = $this->ci->session->userdata('twitter_request');

		$request_token = null;

		if (!$request_token || !isset($request_token['oauth_token'])) {
			$request_token = $this->twitteroauth->getRequestToken($url, $full ? 'write' : 'read');
			if(isset($request_token['Failed to validate oauth signature and token'])) {
				$request_token = $this->twitteroauth->getRequestToken($url, $full ? 'write' : 'read');
			}
			$this->ci->session->set_userdata('twitter_request', $request_token);
		}
		return $this->twitteroauth->getAuthorizeURL($request_token['oauth_token']);
	}
	
	public function get_user_info() {
		return $this->twitteroauth->get('account/verify_credentials');
	}
	
	function get_wall() {
		$ret = array();
		$params = array(
			'user_id' => $this->user_id,
			'trim_user' => true,
			'include_entities' => true
		);
		$posts = $this->twitteroauth->get("/statuses/user_timeline", $params);
		foreach ($posts as $post) {
			if (!isset($post->created_at)) {
				echo "Error: "; var_dump($post); echo "\n";
				continue;
			}
			if (isset($post->entities->urls[0]->expanded_url)) {
				$ret[] = array(
					'type' => 'content',
					'title' => $post->text,
					'link' => $post->entities->urls[0]->expanded_url,
					'created_at' => date('Y-m-d H:i:s', strtotime($post->created_at))
				);
			} else {
				$ret[] = array(
					'type' => 'text',
					'title' => date('m/d/Y H:i:s', strtotime($post->created_at)),
					'text' => $post->text,
					'created_at' => date('Y-m-d H:i:s', strtotime($post->created_at))
				);
			}
		}
		return $ret;
	}
	
	function post($message) {
		return  $this->twitteroauth->post('statuses/update', array('status' => $message));
	}
}