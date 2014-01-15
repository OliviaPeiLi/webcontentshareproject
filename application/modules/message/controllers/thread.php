<?php
class Thread extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->lang->load('message/message', LANGUAGE);
	}
	
	/* ============================== LIST ========================== */
	
	function index() {

		$per_page = 7;
		$page = $this->input->get('page',0)+1;
		
		$threads = $this->msg_thread_model
						->filter_inbox($this->user->id)
						->paginate($page, $per_page);

		if ($this->input->is_ajax_request()) {
			echo json_encode($threads->jsonfy());
		} else {
			return parent::template('message/threads', array(
				'threads' => $threads->get_all(),
			), 'Fandrop - '. $this->lang->line('message_msg_box_title'));
		}
	}
	
	/* ================================= GET ============================= */
	
	public function get($id) {

		$this->load->helper('typography');
		$page = $this->input->get('page');
		$per_page = 20;
		
		$thread = $this->msg_thread_model->get($id);
		
		parent::template('message/thread', array(
			'thread' => $thread,
		), 'Fandrop - '. $this->lang->line('message_msg_title'));
	
	}
	
	/* =============================== CREATE ========================== */
	
	public function check_users($users) {
		if ($this->user_model->count_by('id IN ('.implode(',', array_keys($users)).')') < count($users)) {
			$this->form_validation->set_message('check_users', $this->lang->line('message_receivers_dont_exist'));
			return false;
		}
		return true;
	}
	
	public function create() {
		//for validation
		$this->load->library('form_validation');
		$this->load->helper('typography');
		$this->form_validation->set_rules('receivers', 'lang:message_form_receiver_lbl', 'required|callback_check_users');
		$this->form_validation->set_rules('msg_body', 'lang:message_form_msg_body_lbl', 'required');
		
		if ( ! $this->form_validation->run()) {
			die(json_encode(array('status'=>false, 'error' => $this->form_validation->error_string())));
		}

		$data = $this->form_validation->get_data();
		$data['receivers'][$this->user->id] = $this->user->full_name;
		ksort($data['receivers']);
		
		$thread = $this->msg_thread_model->get(
			$this->msg_thread_model->insert(array(
				'users' => serialize(array_keys($data['receivers']))
			))
		);
		$last_msg_id = $thread->add_message( $this->user->id, $data['msg_body'] );

		die(json_encode(array('status'=>true ,'data'=>$thread->jsonfy())));
	}
	
	/* ============================== DELETE ========================== */
	function delete($id) {
		echo json_encode(array('status'=>$this->msg_thread_model->delete($id)));
	}
	
}