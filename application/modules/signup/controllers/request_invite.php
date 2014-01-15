<?php
class Request_invite extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('signup/signup', LANGUAGE);
	}
	
	/**
	 * URL: /request_invite
	 */
	public function index() {
		$this->load->library('form_validation');
		$this->load->library('parser');

		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		if($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status' => false, 'error' => 'This email is not valid'));
				return ;
			}
			else {
				$facebook = $this->load->library('Facebook_driver');
				$ri = $this->input->get('redirect_url',true);
				$ri = empty($ri) ? '' : '?redirect_url='.$ri;
				return parent::template('signup/request_invite', array(
					'login_url' => '/signin'.$ri,
				), $this->lang->line('signup_request_invite_title'), 'header_lean_centered');				
			}
		}
		else {
			$email= $this->input->post('email', true);

			// confirm if email is exist or not
			if ( $this->alpha_user_model->get_userid($email) != false ) {
				if ($this->input->is_ajax_request()) {
					echo json_encode(array('status' => false, 'error' => 'This email already exists in our records'));
					return ;
				} else {
					return parent::template('signup/request_invite_confirm', array(
						'email' => $email,
						'exist' => '1'
					), $this->lang->line('signup_request_invite_title'), 'header_lean_centered');
				}
			}
			else {

				$code = uniqid();
				$alpha_id = $this->alpha_user_model->insert(array(
							'signup_email'=>$email,
							'signup_code'=>$code,
							'alpha_key'=>'new_request_0'
						));
				
				if( ! $this->is_mod_enabled('signup_wait_list')) {
					$this->auto_send_email($alpha_id, $email, $code);
				}
				
				if ($this->input->is_ajax_request()) {
					echo json_encode(array('status' => true));
					return ;
				} else {
					return parent::template('signup/request_invite_confirm', array(
						'email' => $email
					), $this->lang->line('signup_request_invite_title'), 'header_lean_centered');
				}
			}
		}
	}
	
	public function fb_index() {
		
		if($this->input->get('error')){
			Url_helper::redirect('/request_invite');
		}
		
		$facebook = $this->load->library('Facebook_driver');
		$facebook->facebook->setAccessToken($this->input->get('access_token'));
		$user_data = $facebook->get_user_info();
		
		if($this->user_model->count_by(array('fb_id'=>$user_data->id)) > 0) { //User already exists so login
			$user = $this->user_model->get_by(array('fb_id'=>$user_data->id));
			$this->user_model->set_login_data($user);
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('login'=>'ok')));
			} else {
				Url_helper::redirect($this->input->get('redirect_url', true, '/'));
			}
		}
		
		$this->alpha_user_model->insert(array(
			'fb_id' => $user_data->id,
			'first_name' => $user_data->first_name,
			'last_name' => $user_data->last_name,
			'signup_email' => $user_data->email,
			'alpha_key' => 'fb_request',
			'check'=>'0',
			'used'=>'0'
		));
		
		return parent::template('signup/request_invite_confirm', array(
			'email' => $user_data->email
		), $this->lang->line('signup_request_invite_title'), 'header_lean_centered');
	}
	
	private function auto_send_email($alpha_id, $email, $code) {
		$key = Accesskey_helper::generateAccessKey();

		$this->lang->load('email/invite','english');

		$msg = $this->parser->parse(
					'email_templates/alpha_invite_template', 
					array(
						'alpha_link' => Url_helper::base_url().'index.php/signup?a='.$alpha_id.'&b='.$key,
						'full_name' => ''
					),
					TRUE
				);
		try {
			if(Email_helper::SendEmail($email, $this->lang->line('alpha_user_invite_subject'), $msg))
			{
				$this->alpha_user_model->update($alpha_id, array('check'=>'1', 'alpha_key'=>$key));
			}
		} catch (Exception $e) { //for localhost - doesnt support mailer function
			if (strpos(BASEPATH, '/fantoon.loc') !== false) { 
				$this->alpha_user_model->update($alpha_id, array('check'=>'1', 'alpha_key'=>$key));
				die(Url_helper::base_url().'index.php/signup?a='.$alpha_id.'&b='.$key);
			}
		}
	}
	
	/**
	 * After the user clicks on the invite link in his mail
	 */
	public function invite_after($beta_id, $key, $ref_id=0) {
		$this->load->model('alpha_user_model');
		$alpha_user = $this->alpha_user_model->get($beta_id);
		if (isset($alpha_user) && !$alpha_user->used && $alpha_user->alpha_key == $key) {
			$alpha_user->update(array('check'=>'1'));
			$this->session->set_userdata('user_signup_data', array(
				'alpha_user_id' => $alpha_user->beta_id,
				'first_name' => $alpha_user->first_name,
				'last_name' => $alpha_user->last_name,
				'email' => $alpha_user->signup_email,
				'ref_user_id' => $alpha_user->user_id,
				'invitee' => $alpha_user->user_id > 0 ? $this->user_model->get($alpha_user->user_id)->info : ''
			));
			//Url_helper::redirect('/signup');
		}elseif(in_array($beta_id, $this->config->item('invitees')) && $key == $this->config->item('invitee_code')){
			$user_signup_data = array('invitee'=>$beta_id);
			if($ref_id > 0) {
				$user_signup_data['ref_user_id'] = $this->user_model->get_by(array('fb_id'=>$ref_id))->id;
				$user_signup_data['invitee'] = $this->user_model->get_by(array('fb_id'=>$ref_id))->info;
			}
			$this->session->set_userdata('user_signup_data', $user_signup_data);
		}else{
			Url_helper::redirect('/');
		}
	}

	/**
	 * validate_invited_email 
	 *     Validate if the email is in invited list already
	 */
	public function validate_invited_email()
	{
		if ($this->input->post('email')) {
			$post = $this->input->post();
			$post['email'] = array_unique($post['email']);

			foreach($post['email'] as $k=>$email) {
				if (!$email) {
					unset($post['email'][$k]);
				} else {
					$is_exist_invitation = $this->alpha_user_model->get_userid($email) == false;
					echo json_encode( array('status' => $is_exist_invitation, 'error' => 'The email was invited already') );
				}
			}
		}
	}
}
