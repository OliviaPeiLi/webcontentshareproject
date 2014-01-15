<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Mention extends API
{

	public function filter($items) {
		$this->db->join('folder', 'mentions.folder_id = folder.folder_id');
		$this->db->where('mentions.newsfeed_id',0);
		$this->db->group_by('folder.folder_id');

	return parent::filter($items);
	}

	public function item_delete() {
		return $this->response('Not authorized', 401);
	}
	
	public function item_post() {
		return $this->response('Not authorized', 401);
	}

}