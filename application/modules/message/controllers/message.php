<?php

class Message extends MX_Controller
{

	public function __construct() {
		parent::__construct();
		
		$this->session->unset_userdata('view_page');
		$this->lang->load('message/message', LANGUAGE);
	}
	
	/* =============================== LIST ====================================== */
	
	public function index($thread_id) {
		
		$page = $this->input->get('page', 0)+1;

		$messages = $this->msg_content_model
						->filter_thread($thread_id)
						->filter_inbox($this->user->id)
						->get_all();

		
		if ($this->input->is_ajax_request()) {
			
		} else {
		
			$this->load->view('message/msgs', array(
				'messages' => $messages,
				'thread_id'=>$thread_id
			));

		}
	}
	
	/* =============================== GET =========================== */
	
	/* ======================================== CREATE ================================= */
	
	public function create() {
		$post = $this->input->post();
		if ( !$data = $this->msg_content_model->validate($post)) {
			die(json_encode(array('status'=>false, 'error' => $this->form_validation->error_string())));
		}

		$data = $this->form_validation->get_data();
		
		$message = $this->msg_content_model->get(
				$this->msg_thread_model->get($data['thread_id'])->add_message($this->user->id, $data['msg_body'])
			)->jsonfy();

		die(json_encode(array('status'=>true, 'data' => $message)));
	}
	
	/* ===================================== DELETE ================================== */
	
	/**
	 * erase_type=0 - not deleted
	 * erase_type=1 - deleted from inbox
	 * erase_type=2 - deleted from outbox
	 */
	function delete($msg_id) {
		//Delete the message if the other user deleted it too
		$this->msg_info_model->delete_by(array('msg_id'=>$msg_id, 'from'=>$this->user->id,'erase_type'=>1));
		
		//Delete from outbox
		$this->msg_info_model->update_by(array('msg_id'=>$msg_id, 'from'=>$this->user->id), array('erase_type'=>2));
		
		//Delete the message from inbox
		$this->msg_info_model->update_by(array('msg_id'=>$msg_id, 'to'=>$this->user->id), array('erase_type'=>1));
				
		echo json_encode(array('status'=>true));
	}

}
