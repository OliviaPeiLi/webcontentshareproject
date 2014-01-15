<?php
/**
 * Fandrop facebook driver
 */
require_once 'fb_api/facebook.php';
class Facebook_driver {
	
	public $facebook;
	private $ci;
	
	public function __construct($params = array()) {

		$this->ci = get_instance();
		$this->facebook = new Facebook(array(
							'appId'  => $this->ci->config->item('fb_app_key'),
							'secret' => $this->ci->config->item('fb_app_secret'),
							'cookie' => true
						 ));
		if (isset($params['access_token'])) {
			$this->ci->session->set_userdata('fb_access_token', $params['access_token']);
		}
		if ($this->ci->session->userdata('fb_access_token')) {
			$this->facebook->setAccessToken($this->ci->session->userdata('fb_access_token'));
		}	
	}
	
	public function set_approve_url($url) {
		return $this->facebook->getLoginUrl(array(
			  'scope'=>'user_about_me,email,user_birthday,user_interests,publish_actions,read_stream',
			  'redirect_uri'=>$url
		));
	}
	
	function get_user_info() {				
		// If we get a code, it means that we have re-authed the user 
		//and can get a valid access_token.
		$user_id = $this->facebook->getUser();
		
    	$max_attempts = 5;
    	while(!$user_id && $max_attempts) {
    		$max_attempts--;
	    	$user_id = $this->facebook->getUser();
    	}
	    $this->ci->session->set_userdata('fb_access_token', $this->facebook->getAccessToken());
		$user_profile = (object)$this->facebook->api('/me');
		return $user_profile;
	}
	
	function getLongAccessToken() {
		$params = array(
						'client_id' => $this->facebook->getAppId(),
						'client_secret' => $this->facebook->getAppSecret(),
						'grant_type'=>'fb_exchange_token', 
						'fb_exchange_token'=>$this->facebook->getAccessToken()
					);

		$data = file_get_contents('https://graph.facebook.com/oauth/access_token?'.http_build_query ($params));
		parse_str($data, $output);
		return $output['access_token'];
	}
	
	function get_wall() {
		//returns array of Post objects - https://developers.facebook.com/docs/reference/api/post/
		$res = $this->facebook->api('/me/feed', array('limit'=>300));
		$ret = array();
		foreach ($res['data'] as $post) {
			if ($post['type'] == 'link' || $post['type'] == 'swf') {
				if (!isset($post['link'])) {
					//RR - here we probably need to check for some application request.
					//print_r($post);
					//die('cant be processed');
					continue;
				}
				if (strpos($post['link'], 'facebook.com/') !== false) {
					//RR - this is fb page like - 
					// we probably need to check for application { name: Status/PHoto/Page etc. and process further
					continue;
				}
				$ret[] = array(
					'type' => 'content',
					'link' => $post['link'],
					'title' => isset($post['name']) ? $post['name'] : $post['story'],
					'created_at' => date('Y-m-d H:i:s', strtotime($post['created_time']))
				);
			} elseif ($post['type'] == 'photo' || $post['type'] == 'checkin') {
				if (!isset($post['status_type'])) {
					//We dont save "profile picture change"
					continue;
				}
				if (!isset($post['picture'])) continue;
				$ret[] = array(
					'type' => 'image',
					'image' => str_replace('_s.', '_b.', $post['picture']),
					'link' => $post['link'],
					'title' => isset($post['caption']) ? $post['caption'] : (
									isset($post['description']) ? $post['description'] : (
										isset($post['name']) ? $post['name'] : $post['story'] 
									)
								),
					'created_at' => date('Y-m-d H:i:s', strtotime($post['created_time']))
				);
			} elseif ($post['type'] == 'video') {
				
			} elseif ($post['type'] == 'status') {
				if (isset($post['message'])) {
					$ret[] = array(
						'type' => 'text',
						'text' => $post['message'],
						'title' => isset($post['story']) ? $post['story'] : 'Status',
						'created_at' => date('Y-m-d H:i:s', strtotime($post['created_time']))
					);
				} else {
					//just an activity message like, friends, tag, comment we dont need them
					continue;
				}
			} else {
				print_r($post);
				echo "type not recognized : {$post['type']}\r\n";
			}
			if (count($ret) > 25) break;
		}
		return $ret;
	}
	
	function get_user_friends(){
		$friends = (object)$this->facebook->api('/me/friends');
		return $friends;
	}
	
	public function disconnect() {
		$ch = curl_init("https://graph.facebook.com/" . $this->ci->user->fb_id . "/permissions");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('access_token'=> $this->facebook->getAccessToken()));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
		$this->ci->session->set_userdata('fb_access_token', null);
		return true;
	}
	
}