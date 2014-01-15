<?php

class Connection extends MX_Controller {
		
	function index() {
		$query = $this->input->post('q') ? $this->input->post('q') : $this->input->get('q');
		$items = $this->connection_model->unique_users()->search($query, $this->user)->dropdown(null, null, true);
		echo json_encode($items);
	}
	
	function create($user_id) {
		if(!$this->user->id || $user_id == $this->user->id) {
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>false, 'error' => 'You can`t follow this user')));
			} else {
				redirect('/'.$this->user_model->get($user_id)->uri_name);
			}
			
		}
		
		$check_data = array('user1_id'=>$this->user->id, 'user2_id'=>$user_id);
		
		if(! $this->connection_model->count_by($check_data)) {
			$connect_id = $this->connection_model->insert($check_data);
		}
		
		if ($this->input->is_ajax_request()) {
			echo json_encode(array('status'=>true));
		} else { //redirect from landing page (non logged in user)
			redirect('/'.$this->user_model->get($user_id)->uri_name);
		}
	}

	function delete($user_id) {
		if($user_id == $this->user->id) {
			echo json_encode(array('status'=>false,'error' => 'You can`t follow or unfollow yourself'));
			return;
		};

		$this->connection_model->delete_by(array('user1_id'=>$this->user->id, 'user2_id'=>$user_id));
		
		echo json_encode(array('status'=>true));
		return;
	}
}
