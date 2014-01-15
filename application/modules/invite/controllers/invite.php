<?php
class Invite extends MX_Controller
{
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @link: /invites
	 */
	public function index($type='email') {
		$template = $this->is_mod_enabled('design_ugc') ? 'invite/invite_ugc' : 'invite/invite';
		return parent::template($template, array(
			'messages' => $this->session->flashdata('validation_msgs'),
			'type' => $type,
		), 'Invite Friends to Fandrop');
	}
	
	public function invite_email() {

		if ($this->input->post('email')) {
			//for validation
			$this->load->library('parser');
			$this->load->library('form_validation');
			$this->lang->load('email/invite',LANGUAGE);
	
			$post = $this->input->post();
			
			// $post['email'] = array_unique($post['email']);
			
			foreach($post['email'] as $k=>$email) {
				if (!$email) {
					unset($post['email'][$k]);
				} else {
					$this->form_validation->set_rules('email['.$k.']', 'Email Address '.$k+1, 'trim|valid_email|callback_email_check');
				}
			}

		   	if ($this->form_validation->run() == FALSE) {

		   		$errors = array();

		   		foreach ($post['email'] as $key => $value) {
		   			# code...
		   			$err = Form_Helper::form_error('email[' . $key . ']');
		   			if ($err) 
		   				$errors[$key] = $err;
		   		}

		   		die( json_encode(array('status'=>false, 'error'=>$errors)) );
			}
			
			foreach($post['email'] as $k=>$email) {
				
				if(! $email) continue;

				// exist user -> skip
				if($this->alpha_user_model->get_userid($email) != false) continue;
				
				if ($this->is_mod_enabled('kissmetrics')) {
					$this->load->library('KISSmetrics/km');
					$this->km->init($this->config->item('km_key'));
					$this->km->identify($this->user->uri_name);
					$this->km->record('invited a friend via email');
				}
				
				$code = Accesskey_helper::generateAccessKey();
				
				$alpha_id = $this->alpha_user_model->insert(array(
								'signup_email'=>$email,
								'alpha_key'=>$code,
								'check'=>'0',
								'used'=>'0',
								'user_id'=>$this->session->userdata('id'),
								'message'=>$post['message']
							));
	
				$subject = $this->lang->line('alpha_user_invite_subject');
				$alpha_link = Url_helper::base_url().'index.php/signup?a='.$alpha_id.'&b='.$code.'&fu='.$this->session->userdata('id');
	
				// http://dev.fantoon.com:8100/browse/FD-4127
				//$invitors = $this->alpha_user_model->get_many_by(array('signup_email' => $email));
				$msg_data['invitor'] = $this->user->full_name;
				
				// http://dev.fantoon.com:8100/browse/FD-4127
				// if( count($invitors) > 1 ) {
				// 	$inv_arr = array();
				// 	foreach($invitors as $invite) {
				// 		if (!$invite->user_id) continue;
				// 		$inv_arr[$invite->user->id] = @$invite->user->full_name;
				// 	}
				// 	$msg_data['invitor'] = implode(', ', $inv_arr);
				// }
	
				$msg_data['message'] = $post['message'] && $post['message'] != 'Add a personal note (optional):' ? nl2br($post['message']) : $this->lang->line('alpha_user_invite_message');
				$msg_data['alpha_link'] = $alpha_link;
				
				$msg = $this->parser->parse('email_templates/invite_friends_template', $msg_data);
	
				Email_helper::SendEmail($email, $subject, $msg);
			}
			
			$return = array("status"=>true);
			
			if ($this->alpha_user_model->count_by( array('user_id' => $this->session->userdata('id')) ) >= 5)	{
				$return['button'] = $this->load->view("includes/drop_button_nav",'', TRUE);
				$return['dialog'] = $this->load->view("includes/info_dialog",'', TRUE);
			}
			
			die(json_encode($return));
		}
		
		$template = $this->is_mod_enabled('design_ugc') ? 'invite/invite_email_ugc' : 'invite/invite_email';
		$this->load->view($template);
	}
	
	public function email_check($str)	{
		
		$user_id = $this->session->userdata('id');
		
		$is_signup_email_exists = $this->alpha_user_model->count_by( array('user_id' => $user_id, 'signup_email'=>$str) );
		$is_email_exists = $this->alpha_user_model->count_by( array('user_id' => $user_id, 'signup_email'=>$str) );
		
		if ($is_signup_email_exists > 0 || $is_email_exists > 0)	{
			$this->form_validation->set_message('email_check', 'You already have sent an invite to that user');
			return FALSE;
		}
		
		return TRUE;	
	}
	
	public function invite_facebook() {
		$friends = $this->input->post('friends', true, array());
		
		$registered = array();
		$not_registered = array();
		
		foreach ($friends as $friend) {
			$user = $this->user_model->get_by(array('fb_id' => $friend['id']));
			if ($user) {
				$registered[] = $user;
			} else {
				$not_registered[] = $friend;
			}
		}
		
		$num_invited = $this->alpha_user_model->count_by(array('user_id' => $this->session->userdata('id')));
		
		$template = $this->is_mod_enabled('design_ugc') ? 'invite/invite_facebook_ugc' : 'invite/invite_facebook';
		$this->load->view($template, array(
			'results' => $not_registered,
			'registered' => $registered,
			'num_invited' => $num_invited
		));
	}
	
	public function invite_gmail() {
		
		$client = $this->load->library('google/apiClient', array());
		
		$client->setApplicationName("Google PHP Starter Application");
		$client->setScopes("http://www.google.com/m8/feeds/");
		$client->setAccessToken($this->session->userdata('access_token_gmail'));
		
		$req = new apiHttpRequest("http://www.google.com/m8/feeds/contacts/default/full?max-results=99999");
		$val = $client->getIo()->authenticatedRequest($req);
		$xml = new SimpleXMLElement($val->getResponseBody());
		$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
		$xml->registerXPathNamespace('default', 'http://www.w3.org/2005/Atom');
		$result = $xml->xpath('//default:entry|//gd:email');
		
		$registered = array();
		$not_registered = array();
		$invited = $this->alpha_user_model->get_user_invited_emails($this->user->id);
		
		foreach ($result as $row) {
			if (!(string) $row->attributes()->address) continue;
			$user = $this->user_model->get_by('email', (string) $row->attributes()->address);
			if ($user) {
				$registered[] = $user;
			} else {
				$not_registered[] = array(
					'name' => (string) ($row->title ? $row->title : $row->attributes()->address),
					'email' => (string) $row->attributes()->address
				);
			}
		}
		
		$template = $this->is_mod_enabled('design_ugc') ? 'invite/invite_gmail_ugc' : 'invite/invite_gmail';
		$this->load->view($template, array(
			'results' => $not_registered,
			'registered' => $registered,
			'invited'	=> $invited,
		));
	
	}
	
	public function invite_yahoo() {
		$this->load->library('yahoo/yahoo');
		
		//this will redirect to login page if needs to login or it will return the contacts directly
		$contacts = $this->yahoo->get_contacts();
		
		if ($contacts == -6)	{ // access token expired
			
			$this->yahoo->removeAccess();
			
			echo "<script type='text/javascript'>";
			echo "php.auth_yahoo = false;";
			echo "$('#inviteLeft .yahooInvite').click()";
			echo '</script>';
			
		}

		$invited = $this->alpha_user_model->get_user_invited_emails($this->user->id);
		
		$registered = array();
		$not_registered = array();
		foreach($contacts as $email=>$name) {
			$user = $this->user_model->get_by('email', $email);
			if ($user) {
				$registered[] = $user;
			} else {
				$not_registered[] = array(
					'name' => $name ? $name : $email,
					'email' => $email
				);
			}
		}
		
		$template = $this->is_mod_enabled('design_ugc') ? 'invite/invite_yahoo_ugc' : 'invite/invite_yahoo';
		$this->load->view($template, array(
			'results' => $not_registered,
			'registered' => $registered,
			'invited'=>$invited
		));
	}
	
	public function invited_users() {
		$users = $this->input->post('users');
		foreach ($users as $user) {
			if (strpos($user['full_name'], ' ') !== false) {
				list($first, $last) = explode(' ', $user['full_name'], 2);
			} else {
				$first = $user['full_name'];
				$last = '';
			}
			
			$is_fb_is_invited = $this->alpha_user_model->count_by( array('fb_id' => $user['fb_id'], 'user_id'=>$this->session->userdata('id') ) );
			
			$user = array(
				'fb_id' => $user['fb_id'],
				'alpha_key' => isset($user['fb_id']) ? 'fb_request' : '',
				'first_name' => $first,
				'last_name' => $last,
				'user_id' => $this->session->userdata('id')
			);
			
			if ($is_fb_is_invited == 0)	{
				$this->alpha_user_model->insert($user);
			}	else {
			}
		}
		
		$return = array("status"=>true);
			
		if ($this->alpha_user_model->count_by( array('user_id' => $this->session->userdata('id')) ) >= 5)	{
			$return['button'] = $this->load->view("includes/drop_button_nav",'', TRUE);
			$return['dialog'] = $this->load->view("includes/info_dialog",'', TRUE);
		}
		
		die(json_encode($return));
	}
	
	/**
	 * Called on info dialog open
	 */
	public function info_dialog_opened() {
		$this->session->unset_userdata('invite_more');
		die(json_encode(array('status'=>true)));
	}
	
}
