<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Alpha_user extends ADMIN
{

	protected $model = 'alpha_user_model';

	protected $list_fields = array(
								 'beta_id'		=> 'primary_key',
								 'first_name'	=> 'string',
								 'last_name'	=> 'string',
								 'signup_email'	=> 'string',
								 'alpha_key'	=> 'string',
								 'check'		=> 'string',
								 'used'			=> 'string',
								 'user_id'		=> 'string',
								 'uri_name'		=> 'string',
								 'time'			=> 'string'
							 );
							 
	protected $form_fields = array(
								 'first_name'	 => 'string',
								 'last_name'	 => 'string',
								 'signup_email'	 => 'string',
								 'alpha_key'	 => 'string',
								 'check'		 => 'string',
								 'used'			 => 'string',
								 'user_id'		 => 'string',
								 'uri_name'		 => 'string',
								 'time'			 => 'string'
							 );

	protected $filters = array(
								'beta_id'					=> 'primary_key',
								'signup_email'				=> 'string');


	public function index_get()
	{

		if (! $this->model())
		{
			return $this->load->view('layout', array('view'=>'home'));
		}
		$model = $this->model();
		$this->load->model($model);

		$post = $this->input->post();

		//for search
		$waiting = $this->{$this->model}->with('user', 'left');
		$waiting->_set_where(array('check', '0'));
		if(empty($post))
		{
			$waiting = $waiting->paginate($this->input->get('waitingPage'), 20, array('query_string_segment' => 'waitingPage'));
			$data['waiting']['pagination'] = $waiting->pagination->create_links();
		}
		$waiting = $this->filter($waiting);
		$waiting = $this->sort($waiting);
		$data['waiting']['rows'] = $waiting->get_all();
		
		$sent = $this->{$this->model}->with('user', 'left');
		$sent->_set_where(array('check', '1'));
		if(empty($post))
		{
			$sent = $sent->paginate($this->input->get('sentPage'), 20, array('query_string_segment' => 'sentPage'));
			$data['sent']['pagination'] = $sent->pagination->create_links();
		}
		$sent = $this->filter($sent);
		$sent = $this->sort($sent);
		$data['sent']['rows'] = $sent->get_all();
		
		$this->response($data, 'alpha_user');
	}

	private $_item = null;
	public function item()
	{
		if ( ! $this->_item)
		{
			$this->load->model($this->model());
			if ( $this->list_fields )
			{
				foreach($this->list_fields as $k=>$v)
				{
					$fields[] = $k;
					$select_fields = implode(",", $fields);
				}
			}
			else
			{
				$select_fields = $this->primary_key;
			}

			$model_name = $this->model();

			if (strpos($this->model(), '/') > 0)
			{
				$model_info = explode('/', $this->model);
				$model_name = $model_info[count($model_info)-1];
				//die($model_name);
			}

			if ( ! $this->_item = $this-> {$model_name}->select_fields($select_fields)->with('user', 'left')->get($this->item_id))
			{
				$this->response($this->lang->line('common_404'), 404);
			}
		}
		return $this->_item;
	}

	public function index_post()
	{

		$this->load->model('alpha_user_model');
		$this->load->helper('email');
		$this->load->helper('accesskey');

		$post = $this->input->post();
		$post['alpha_key'] = Accesskey_helper::generateAccessKey();
		$post['check'] = $post['used'] = '0';
		$this->alpha_user_model->insert($post);

		Url_helper::redirect('/admin/alpha_user');
	}

	function email_post()
	{
		$this->load->library('parser');
		$this->load->helper('email');
		$this->load->helper('accesskey');
		$this->load->model('alpha_user_model');

		$users = $this->input->post('alpha_users', true);

		foreach($users as $id)
		{
			$key = Accesskey_helper::generateAccessKey();
			//$id = $this->alpha_user_model->get_by(array('signup_email'=>$email))->beta_id;
			$email = $this->alpha_user_model->get($id)->signup_email;
			if($email == null) $email = $this->alpha_user_model->get($id)->email;
			if($id > 0)
			{
				$user_data = $this->alpha_user_model->get($id);
				$subject = 'You are invited';
				$alpha_link = base_url().'index.php/signup?a='.$id.'&b='.$key;

//				if($user_data->user_id == '0')
//			   {
					$msg_data = array(
									'alpha_link' => $alpha_link,
									'full_name' => $user_data->first_name
								);

					$msg = $this->parser->parse('email_templates/alpha_invite_template', $msg_data);
/*
				}
				else
				{
					$invitor = $user_data->user;
					$msg_data['invitor'] = $invitor->first_name;

					if($user_data->message != '')
					{
						$msg_data['message'] = $user_data->message;
					}
					else
					{
						$msg_data['message'] = $this->lang->line('alpha_user_invite_message');
					}
					$msg_data['alpha_link'] = $alpha_link;
					$msg = $this->parser->parse('email_templates/invite_friends_template', $msg_data);
				}
*/
				//$message = base_url().'signup?a='.$id.'&b='.$key;
				if(Email_helper::SendEmail($email, $subject, $msg))
				{
					$this->alpha_user_model->update($id, array('check'=>'1', 'alpha_key'=>$key));
				}
			}
		}

		Url_helper::redirect('/admin/alpha_user');
	}

}