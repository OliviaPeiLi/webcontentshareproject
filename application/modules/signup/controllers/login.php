<?php

class Login extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->lang->load('signup/signup', LANGUAGE);

		// $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

		// var_dump($ip);

	}

	function index() {
		if ($this->session->userdata('id')) {
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>true)));
			} else {
				Url_helper::redirect('/');
			}
			return ;
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run()) {
			$email = $this->input->post('email', true);
			$password = $this->input->post('password', true);
			$remember = $this->input->post('remember', true);
			if ($this->user_model->login($email, $password, $remember)) {
				if ($this->is_mod_enabled('kissmetrics')) {
					$this->load->library('KISSmetrics/km');
					$this->km->init($this->config->item('km_key'));
					$this->km->identify($user->uri_name);
					$this->km->record('login');
				}
				if ($this->input->is_ajax_request()) {
					die(json_encode(array('status'=>true)));
				} else {
					$this->input->post('redirect_url') ? Url_helper::redirect($this->input->post('redirect_url')) : Url_helper::redirect('/');
				}
				return ;
			} else {
				if ($this->input->is_ajax_request()) {
					die(json_encode(array('status'=>false, 'error'=>$this->lang->line('signup_wrong_credentials_msg'))));
				} else {
					$this->session->set_flashdata('login_error',$this->lang->line('signup_wrong_credentials_msg'));
					$error_msg = $this->lang->line('signup_wrong_credentials_msg');
				}
			}
		}
		if ($this->input->is_ajax_request()) {
			die(json_encode(array('status'=>false, 'error'=>Form_Helper::validation_errors())));
		}
		
		return parent::template('signup/login', array(
			'main_content' => 'signup/login',
			'login_error' => isset($error_msg) ? $error_msg : '',
		), $this->lang->line('signup_login_title'), 'header_lean_centered');
	}

	public function logout() {
		if ($this->user) {
			$this->user->logout();
		}
		Url_helper::redirect(str_replace('https://', 'http://', Url_helper::base_url()));
	}
	
	function facebook_afterlogin() {
		$facebook = $this->load->library('Facebook_driver');
		$fb_id = $facebook->facebook->getUser();		
		$user = $this->user_model->get_by(array('fb_id'=>$fb_id));
		if ($fb_id && isset($user->id)) {
			$this->user_model->set_login_data($user);
			
			$fb_token = $facebook->getLongAccessToken();
			$user->update(array('fb_token'=> $fb_token));
        	$this->user_model->set_remember($user->id, $fb_token);
        	
        	if ($this->input->is_ajax_request()) {
        		die(json_encode(array('status'=>true)));
        	} else {
        		Url_helper::redirect($this->input->get('redirect_url',true,'/'));
        	}
		} else {
            die(json_encode(array('status'=>false,'error'=>'User not connected to fandrop')));
		}
	}
	
	public function set_fb_data($get_access_token = false) {
		
		$auth = $this->input->post('auth');
		$facebook = $this->load->library('facebook_driver', array('access_token'=>$auth['accessToken']));
		
		if ($this->user) {
			
			$long_token = $facebook->getLongAccessToken();

			if ($get_access_token)	{
				die(json_encode(array('status'=>true,'token'=>$long_token)));
			}

			if ($this->user_model->count_by(array('id <>'=>$this->user->id,'fb_id'=>$auth['userID']))) {
				die(json_encode(array('status'=>false,'error'=>'This facebook account is already associated with another fandrop account')));
			}

			$this->user->update(array('fb_id' => $auth['userID'], 'fb_token' => $long_token));

			die(json_encode(array('status'=>true,'token'=>$long_token)));
		}
		die(json_encode(array('status'=>true)));
	}
	
	public function get_csrf_token() {
		echo json_encode(array(
				'status' => true,
				'csrf' => array(
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				)
		));
	}
	
	/**
    * load the twitter api to log in
    */
    public function twitter_login($action='login') {
        $twitter = $this->load->library('twitter');
        if ($action == 'tweet') {
        	$data = array(
        		'original_referer' => $this->input->get('original_referer'),
        		'text' => $this->input->get('text'),
        		'tw_p' => $this->input->get('tw_p'),
        		'url' => $this->input->get('url'),
        	);
        	if ($this->input->get('hashtags')) $data['hashtags'] = $this->input->get('hashtags');
        	if ($this->input->get('via')) $data['via'] = $this->input->get('via');
        	$this->session->set_userdata('tweet_data', $data);
        	header('Location: ' . $twitter->set_approve_url(Url_helper::base_url('tweet'),false));
        } else if ($action == 'login') {
        	header('Location: ' . $twitter->set_approve_url(Url_helper::base_url('login/twitter_after')));
        } else {
        	header('Location: ' . $twitter->set_approve_url(Url_helper::base_url('twitter_connected')));
        }
    	
    }

    /**
     * USer logs in with twitter
     * @link /login/twitter_after
     */
	public function twitter_afterlogin() {

		if($this->input->get('denied')){

			die('<script type="text/javascript">
				setTimeout(function(){
					window.opener.twitterError("closed_window");
					window.close();
				},500);
			</script>');
			// Url_helper::redirect('/signin');
		}

		$twitter = $this->load->library('Twitter');
		$user_info = $twitter->twitteroauth->get('account/verify_credentials');
		$user = $this->user_model->get_by(array('twitter_id'=>$user_info->id));

		if ($user && $user->id) {
			$this->user_model->set_login_data($user);
			
			$twitter_request = $this->session->userdata('twitter_request');
        	$this->user_model->update($user->id, array('twitter_token'=> $twitter_request['oauth_token'].'&'.$twitter_request['oauth_token_secret']));
        	$this->user_model->set_remember($user->id, $twitter_request['oauth_token'].'&'.$twitter_request['oauth_token_secret']);
			echo '<script type="text/javascript">
				setTimeout(function(){
					window.opener.setTwitterID("'.$user_info->id.'");
					window.opener.location="/";
					window.close();
				},500);
			</script>';
			// Url_helper::redirect('/');
		} else {
			echo '<script type="text/javascript">
				setTimeout(function(){
					window.opener.twitterError("'.$this->lang->line('signup_tw_not_assoc').'");
					window.close();
				},500);
			</script>';
		}
	}
	
	/**
	 * User connects to twitter
	 * @link /twitter_connected
	 */
	public function twitter_connected() {
    	$twitter = $this->load->library('Twitter');
    	$user_info = $twitter->get_user_info();
    	if (!@$user_info->id) {
    		echo '<script type="text/javascript">
				window.opener.twitterError('.json_encode($user_info).');
				window.close();
			</script>';
    		return ;
    	} 
    	$twitter_id = $user_info->id;
    	
    	$twitter_request = $this->session->userdata('twitter_request');
        $this->user_model->update($this->session->userdata('id'), array('twitter_id'=>$twitter_id, 'twitter_token'=> $twitter_request['oauth_token']));
        
        echo '<script type="text/javascript">
				window.opener.setTwitterID("'.$twitter_id.'");
				window.close();
			</script>';
    }
    
    /**
     * @link /tweet
     */
    public function tweet() {
    	$twitter = $this->load->library('Twitter');
    	$user_info = $twitter->get_user_info();
    	if (!@$user_info->id) {
    		echo '<script type="text/javascript">
				window.opener.twitterError('.json_encode($user_info).');
				window.close();
			</script>';
    		return ;
    	} 
    	$twitter_id = $user_info->id;
    	$twitter_request = $this->session->userdata('twitter_request');
        $this->user_model->update($this->session->userdata('id'), array('twitter_id'=>$twitter_id, 'twitter_token'=> $twitter_request['oauth_token']));
        $tweet_data = http_build_query($this->session->userdata('tweet_data'));
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        		<html><head>
		        	<script type="text/javascript">
						window.opener.setTwitterID("'.$twitter_id.'");
						window.location.href = "'.str_replace('"', '\"', 'https://twitter.com/intent/tweet?'.$tweet_data).'";
					</script>
				</head><body></body></html>';
    }
}