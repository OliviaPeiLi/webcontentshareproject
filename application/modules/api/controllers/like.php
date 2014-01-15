<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Like extends API {
	
	public function filter($items) {
		$this->db->where('folder_id >',0);
	return parent::filter($items);
	}

	public function item_post() {
		if (!$this->user) return $this->response(array('status'=>false,'error' => 'Not authorized'));
		$post = $this->input->post();
		$post['user_id'] = $this->user->id;
		unset($post['ci_csrf_token']);
		
		if ($this->{$this->model()}->count_by($post)) {
			return $this->response(array('status'=>false,'error' => 'You already liked this item'));
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