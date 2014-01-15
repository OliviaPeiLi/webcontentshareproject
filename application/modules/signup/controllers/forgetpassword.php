<?php
class Forgetpassword extends MX_Controller
{
	public function __construct() {
		parent::__construct();
		$this->lang->load('signup/forget_password', LANGUAGE);
	}

	public function index() {
		if ($this->input->post('email')) {
			$email = $this->input->post('email');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', $this->lang->line('email'), 'required|min_length[1]	');
			if ( ! $this->form_validation->run()) {
				die(json_encode(array('status' => false, 'error' => $this->lang->line('forget_password_checkPassword_err'))));
			}
	
			$user = $this->user_model->get_by(array('email'=>$email));
			if (!$user) {
				die(json_encode(array('status' => false, 'error' => $this->lang->line('forget_password_no_email_err'))));
			}
						
			$this->load->library('parser');
			$key = Accesskey_helper::generateAccessKey();
			$msg_data['reset_link'] = Url_helper::base_url().'resetpassword/'.$key.'/'.$user->id;
			$msg_data['user_name'] = $user->first_name;
			$msg_data['thumbnail'] = $user->avatar_73;
			$msg = $this->parser->parse('email_templates/resetpassword_template', $msg_data, TRUE);
	
			if(Email_helper::SendEmail($email,  $this->lang->line('forget_password_sendPass_subject'), $msg)) {
				$user->update(array('key'=>$key));
			}
			die(json_encode(array('status'=>true)));
		} else {
			return parent::template('signup/forgetpassword', array(), $this->lang->line('forget_password_forgetpassword_title'), 'header_lean_centered');
		}
	}
	
	public function reset($key, $user_id) {
		$user = $this->user_model->get_by(array('id'=>$user_id,'key'=>$key));
		if ($this->input->post('resetpassword')) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('new_password', 'lang:forget_password_form_pass_field', 'required|matches[new_password_confirm]|md5');//validation for match
			$this->form_validation->set_message('required', $this->lang->line('forget_password_requirepass_err'));
			$this->form_validation->set_message('matches', $this->lang->line('forget_password_passmismatch_err'));
	
			if ($this->form_validation->run()) {
				$this->user_model->update($user->id, array('password' => $this->input->post('new_password'), 'key' => ''), true);
				$user->set_login_data();
				$this->user_model->set_remember($user->id, $user->password);
				
				Url_helper::redirect('/');
			} else {
				$this->session->set_flashdata(array('error_msg' => Form_Helper::validation_errors('<div class="error">', '</div>')));				
			}
		}
		
		if ($this->is_mod_enabled('design_ugc'))	{
			return parent::template('signup/forgetpassword_reset_ugc', array(
				'user' => $user
			), $this->lang->line('forget_password_resetpassword_title'));

		} else {

			return parent::template('signup/forgetpassword_reset', array(
				'user' => $user
			), $this->lang->line('forget_password_resetpassword_title'), 'header_lean_centered');

		}
	}

}
