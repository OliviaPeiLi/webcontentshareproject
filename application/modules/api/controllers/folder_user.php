<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Folder_user extends API {

	public function item_post() {
		$post = $this->input->post();
		$post['user_id'] = $this->session->userdata('id');
		unset($post['ci_csrf_token']);
		
		if ($this->{$this->model()}->count_by($post)) {
			return $this->response(array('status'=>false,'error' => 'You already follow this list'));
		}
		return parent::item_post();
	}
	
	public function item_delete() {
		if ($this->item()->user_id != $this->user->id) {
			return false;
		}
		return parent::item_delete();
	}
}