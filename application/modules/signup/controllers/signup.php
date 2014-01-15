<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->lang->load('signup/signup', LANGUAGE);
	}
	
	public function index($group='') {
		$groups = array(
			'' => 'header_lean_centered',
			'growthathon' => 'header_lean_centered',
			'stanford' => '',
			'catathon' => '',
			'erlibird' => 'header_lean_centered',
			'superbowl' => '',
			'mashable' => '',
			'valentines' => '',
			'techcrunch' => '',
		);
		
		$user_signup_data = $this->session->userdata('user_signup_data');
		$user_signup_data = $user_signup_data ? $user_signup_data : array();
						
		if ($group && !isset($groups[$group])) {
			$group = '';
		}
		
		if ($group) {
			$user_signup_data['info'] = $group;
		}
		
		if ($this->input->get('ref') && $ref_user = $this->user_model->get_by(array('uri_name'=>$this->input->get('ref')))) {
			$user_signup_data['ref_user_id'] = $ref_user->id;
		}
		
		$this->session->set_userdata('user_signup_data', $user_signup_data);
		
		if ($this->input->get('a') && $this->input->get('b')) {
			Modules::run('signup/request_invite/invite_after', $this->input->get('a'), $this->input->get('b'), $this->input->get('ref_id'));
			//return ;
		}
		
 		if((!$this->is_mod_enabled('open_signup') && !isset($user_signup_data['alpha_user_id']))) {
			$redirect_url = $this->input->get('redirect_url',true);
			if ($redirect_url) $redirect_url = '?redirect_url='.$redirect_url;
			return Url_helper::redirect('/request_invite'.$redirect_url);
		}

		return parent::template($group ? 'team/signup_'.$group : 'signup', array(
			'footer' => 'footer',
			'newsfeeds' => $group ? $this->newsfeed_model->order_by('newsfeed_id','DESC')->filter_user_group($group)->get_all(20) : array(),
		), $this->lang->line('register_title'), $groups[$group]);
 	}
		
 	/**
 	 * @link /signup/form 
 	 */
	public function step1() {
		if(!$this->is_mod_enabled('open_signup') && !$this->session->userdata('user_signup_data')) {
			Url_helper::redirect('/request_invite');
		}

		// Quang: userdata('user_signup_data') contains 'first_name', 'last_name' so that 
		// it makes overlap with $_POST. $_POST array merged should be AFTER 
		// userdata('user_signup_data')
		
		$_POST = array_merge((Array) $this->session->userdata('user_signup_data'), $_POST);
		
		if ($data = $this->user_model->validate($_POST)) {
			
			$model = $this->user_model;
			$model = new User_model();
			// unset($model->behaviors['uploadable']['avatar']);
			
			$id = $model->insert($data);
			
			if (isset($_POST['alpha_user_id']) && $alpha_user = $this->alpha_user_model->get($_POST['alpha_user_id'])) {
				$alpha_user->update(array('user_id'=>$id));
			}

			$user_signup_data = $this->session->userdata('user_signup_data');
			
			//@todo - this may go to alpha_users table
			if(isset($user_signup_data['ref_user_id']) && $user_signup_data['ref_user_id']>0){
				$update_user_stats = $this->user_stats_model->get_by(array('user_id'=>$user_signup_data['ref_user_id']));
				$ref_count = $update_user_stats->ref_count;
				$update_user_stats->update(array('ref_count'=>$ref_count+1));
			}
			
			$user = $this->user_model->get($id);
			$this->user_model->set_login_data($user);
			$this->session->set_userdata(array('just_signup'=>1));
			
			//kissmetrics
			if ($this->is_mod_enabled('kissmetrics')) {
				$this->load->library('KISSmetrics/km');
				$this->km->init($this->config->item('km_key'));
				$this->km->identify($user->uri_name);
				$this->km->record('signup via '.($user->twitter_id ? 'twitter' : ($user->fb_id ? 'facebook' : 'email')));
			}
			
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>true)));
			} else {
				Url_helper::redirect('/');
			}

		} else {

			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
			} else {
				return parent::template('signup/step1_form', array(
					'footer' => 'footer',
				), $this->lang->line('register_title'), 'header_lean_centered');
			}
			
		}	
	}
	
	/**
	 * upload image from the email signup form
	 * @link /signup/form
	 */
	public function edit_picture() {
		$config = $this->user_model->behaviors['uploadable'];
		$config['avatar']['upload_to_s3'] = true;
		$responce = Uploadable_Behavior::do_upload(array(), $config);
		die(json_encode(array(
			'success'=>true,
			'thumb'=>$responce->avatar_73,
			'filename' => substr($responce->avatar, strrpos($responce->avatar, '/')+1)
		)));
	}
	
 	public function signup_error() {
		$this->session->set_flashdata('signup_error', '');
		return parent::template('signup/signup_error', array(), $this->lang->line('register_error_page_title'), 'header_lean_centered');
	}
	
	/**
	 * After user logged in facebook this is the callback url
	 */
	public function facebook_afterlogin() {
		
		if($this->input->get('error',true)) {
			Url_helper::redirect('/');
			return ;
		}
		
		$facebook = $this->load->library('Facebook_driver');
		$user_data = $facebook->get_user_info();
		
		if (!$user_data || !$user_data->id) {
			Url_helper::redirect('/signup_error');
			return ;
		}
		
		//User already exists so login
		if($this->user_model->count_by(array('fb_id'=>$user_data->id)) > 0) {
			Url_helper::redirect('/login/facbook_after?redirect_url='.$this->input->get('redirect_url'));
		}

 		$username = $this->calc_free_username(preg_replace('/[^a-zA-Z0-9]/s', '', $user_data->username));
 		$name = explode(" ",$user_data->name);
 		
 		$this->session->set_userdata('user_signup_data', array_merge(
 			(Array) $this->session->userdata('user_signup_data'), 
 			array(
				'fb_id' => $user_data->id,
				'first_name' => $name[0],
				'last_name' => $name[1],
				'uri_name' => $username,
				'email' => $user_data->email,
				'gender' => $user_data->gender == 'male' ? 'm' : 'f',
				'avatar' => 'https://graph.facebook.com/'.$user_data->username.'/picture?type=large',
				'about' => isset($user_data->bio) ? $user_data->bio : '',
			))
		);
		return $this->step1();
	}
	
	/*
	 * after user logged in twitter retieve data from twitter, include user's basic info, follow list and tweets
	 */
	public function twitter_afterlogin() {

		$twitter = $this->load->library('Twitter');

		$user_info = $twitter->get_user_info();
		if (isset($user_info->errors)) {
			Url_helper::redirect('/signup_error');
			return ;
		}

		if($this->user_model->count_by(array('twitter_id'=>$user_info->id)) > 0) { //User already exists so login
			$user = $this->user_model->get_by(array('twitter_id'=>$user_info->id));
			$this->user_model->set_login_data($user);
			
			$twitter_request = $this->session->userdata('twitter_request');
        	$this->user_model->update($user->id, array('twitter_token'=> $twitter_request['oauth_token']));
        	$this->user_model->set_remember($user->id, $twitter_request['oauth_token']);
        	return Url_helper::redirect($this->input->get('redirect_url', true, '/'));
		}
		
		$first_name = $last_name = '';
		@list($first_name, $last_name) = preg_split('/\s+(?=[^\s]+$)/', $user_info->name, 2);

		if ($this->session->userdata('user_signup_data') == FALSE)	{
			$this->session->set_userdata('user_signup_data',array());
		}

 		$this->session->set_userdata('user_signup_data', array_merge(
 			$this->session->userdata('user_signup_data'), 
 			array(
				'twitter_id' => $user_info->id,
				'first_name' => $first_name,
				'last_name' => $last_name ? $last_name : '',
				'uri_name' => $this->calc_free_username(preg_replace('/[^a-zA-Z0-9\']/','',$user_info->screen_name)),
				'avatar' => str_replace('_normal.', '.', $user_info->profile_image_url),
			)
		)
		);
 		
		return $this->step1();
	}
	
	public function validate_username($username=NULL) {
		$data = array('uri_name' => $this->input->post('uri_name'));
		 
		if ($this->user_model->validate($data)) {
			die(json_encode(array('status'=>true)));
		} else {
			die(json_encode(array('status'=>false,'error'=>strip_tags(Form_Helper::form_error('uri_name')))));
		}
	}
	
	public function validate_contest($username=NULL) {
		$data = array('url' => $this->input->post('url'));
		 
		if ($this->contest_model->validate($data)) {
			die(json_encode(array('status'=>true)));
		} else {
			die(json_encode(array('status'=>false,'error'=>strip_tags(Form_Helper::form_error('url')))));
		}
	}

	public function validate_email() {
		if ($this->user_model->validate(array('email'=>$this->input->post('email')))) {
			die(json_encode(array('status'=>true)));
		} else {
			die(json_encode(array('status'=>false,'error'=>strip_tags(Form_Helper::form_error('email')))));
		}
	}
	
	private function calc_free_username($orig_username) {
		$username = $orig_username; $i=0;		
		while (!$this->user_model->validate(array('uri_name'=>$username))) {
			$username = $orig_username.$i; $i++;
		}
		return $username;
	}

}
