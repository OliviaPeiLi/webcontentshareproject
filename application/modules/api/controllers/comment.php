<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Comment extends API
{

	public function item_delete() {
		if (!$this->item()->can_delete($this->user)) {
			$this->response('Not authorized', 401);
		}
		return parent::item_delete();
	}
	
	public function item_post() {
		if (!@$_POST['comment']) $_POST['comment'] = '';
		return parent::item_post();
	}

}