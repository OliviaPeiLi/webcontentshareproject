<?php
/**
 * Auth class used for user login and logout
 */
require_once 'api.php';

class Auth extends API
{
	protected $model = 'user_model';

	public function index_get()
	{
		if (!$this->session->userdata('id')) return $this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		return $this->item_get();
	}

	public function item_get()
	{
		$this->load->model($this->model());
		$user = $this-> {$this->model()}->get($this->session->userdata['id']);

		$this->response($this->api_objects->convert_me($user), 200);
	}
	
	public function index_delete() {
		$this->user->logout();
		session_unset();
		$this->response(array('status'=>true));
	}

	public function index_post()
	{
		if ($this->input->post('email',true) && $this->input->post('password',true) && $this-> {$this->model()}->login($this->input->post('email'), $this->input->post('password'), true)) {
			$this->response(array('status'=>true,'id'=>$this->session->userdata('id'), 'session_id'=>session_id()), 200);
		}
		elseif ($this->input->post('fb_id') && $this->input->post('accessToken')) {
			$facebook = $this->load->library('facebook_driver', array('access_token'=>$this->input->post('accessToken',true)));
			$fb_id = $facebook->facebook->getUser();
			if ($fb_id == $this->input->post('fb_id',true) && $user = $this->user_model->get_by(array('fb_id'=>$fb_id))) {
				
				$this->user_model->set_login_data($user);			
				$fb_token = $facebook->getLongAccessToken();
				$user->update(array('fb_token'=> $fb_token));
	        	$this->user_model->set_remember($user->id, $fb_token);
	        	
				$this->response(array('status'=>true,'id'=>$this->session->userdata('id'), 'session_id'=>$this->session->userdata('session_id')), 200);
				
			} else if ($fb_id) {
				$this->response(array('status' => false, 'error' => 'User not found'), 404);
			} else {
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
		}
		elseif ($this->input->post('twt_id') && $this->input->post('accessToken')) {
			list($token, $secret) = explode('|', $this->input->post('accessToken',true));
			$twitter = $this->load->library('twitter', array('oauth_token'=>$token,'oauth_token_secret'=>$secret));
			$user_info = $twitter->twitteroauth->get('account/verify_credentials');
			if ($user_info->id == $this->input->post('twt_id') && $user = $this->user_model->get_by(array('twitter_id'=>$user_info->id))) {
				$this->user_model->set_login_data($user);
	        	$this->user_model->update($user->id, array('twitter_token'=> $token.'&'.$secret));
	        	$this->user_model->set_remember($user->id, $secret.'&'.$secret);

	        	$this->response(array('status'=>true,'id'=>$this->session->userdata('id'), 'session_id'=>$this->session->userdata('session_id')), 200);
			} else if ($user_info->id) {
				$this->response(array('status' => false, 'error' => 'User not found'), 404);
			} else {
				$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
			}
		} 
		else {
			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}
	}

	public function register_post()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]');

		//insert the post
		if ($this->form_validation->run() == FALSE)
		{
			$this->response(array('status'=>false, 'error'=>$this->form_validation->error_array()), 200);
		}
		else
		{
			if ($this->input->post('email',true) && $this->input->post('password',true) && $this-> {$this->model()}->login($this->input->post('email'), $this->input->post('password')))
			{
				$this->response(array('status'=>true,'id'=>$this->session->userdata('id'), 'csrf_token'=>$this->security->get_csrf_token_name(), 'csrf_token_hash'=>$this->security->get_csrf_hash()), 200);
			}
			elseif ($this->input->post('fd_id',true) && $this->input->post('fd_code',true) && $this-> {$this->model()}->keep_login($this->input->post('fd_id'), $this->input->post('fd_code')))
			{
				$this->response(array('status'=>true,'id'=>$this->session->userdata('id'), 'csrf_token'=>$this->security->get_csrf_token_name(), 'csrf_token_hash'=>$this->security->get_csrf_hash()), 200);
			}
		
			$this->load->model('user_model');
			$post = $this->input->post();

			if(!isset($post['username']) || $post['username']=='')
			{
				$post['username'] = $post['first_name'].$post['last_name'];
			}
			$post['username'] = $orig_username = preg_replace('/[^a-zA-Z0-9\']/','',$post['username']);
			if(strlen($post['username'])<5)
			{
				$len = 6 - strlen($post['username']);
				$post['username'] = $orig_username.$this->randomDigits($len);
			}
			$len = 2;
			while($this->user_model->count_by(array('uri_name'=>$post['username'])) > 0)
			{
				$post['username'] = $orig_username.$this->randomDigits($len);
			}
			if(isset($post['gender']) && $post['gender'] == 'male')
			{
				$post['gender'] = 'm';
			}
			elseif(isset($post['gender']) && $post['gender'] == 'female')
			{
				$post['gender'] = 'f';
			}else{
				$post['gender'] = '';
			}

			$user_data = array(
							 'first_name' => $post['first_name'],
							 'last_name' => $post['last_name'],
							 'uri_name' => $post['username'],
							 'email' => $post['email'],
							 'password' => md5($post['password']),
							 'status' => '1',
							 'gender' => $post['gender'],
							 'fb_id' => isset($post['fb_id']) ? $post['fb_id'] : 0
						 );
			$user_id = $this->user_model->insert($user_data);

			$this->session->set_userdata(array(
											 'email' => $post['email'],
											 'id' => $user_id,
											 'uri_name' => $post['username'],
											 'name' => $post['first_name'].' '.$post['last_name'],
											 'first_name' => $post['first_name'],
											 'status' => 1,
											 'is_logged_in' => true
										 ));
			if(isset($post['fb_id']) && $post['fb_id'] > 0){							 
				$src_img = 'https://graph.facebook.com/'.$post['fb_id'].'/picture?type=large';
			}
			
			if (@fopen($src_img,'r'))
			{
				$this->user_model->update($user_id,array('avatar'=>$src_img));
			}
			$this->response(array('status'=>true,'id'=>$user_id, 'csrf_token'=>$this->security->get_csrf_token_name(), 'csrf_token_hash'=>$this->security->get_csrf_hash()), 200);
		}
	}

	function randomDigits($length)
	{
		$digits = null;
		$numbers = range(0,9);
		shuffle($numbers);
		for($i = 0; $i < $length; $i++)
			$digits .= $numbers[$i];
		return $digits;
	}
}