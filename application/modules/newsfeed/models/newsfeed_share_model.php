<?php

class Newsfeed_share_model extends MY_Model {	
	//Behaviors
	public $behaviors = array(
							'countable' => array(
								array(
									'table' => 'folder',
									'relation' => array('folder_id' => 'folder_id'),
									'fields' => array(
										'fb_share_count'=>'api==fb',
									),
								)
							),
						);
	
	protected function _run_after_create($data) {
		if (isset($data['newsfeed_id'])) {
			$newsfeed = $this->newsfeed_model->get($data['newsfeed_id']);
			if($newsfeed->folder->ends_at=='0000-00-00 00:00:00' || time()-73200 < strtotime($newsfeed->folder->ends_at)){
				$this->db->query("UPDATE newsfeed SET ".$data['api']."_share_count = ".$data['api']."_share_count + 1 WHERE newsfeed_id = ".$data['newsfeed_id']);
			}
		} elseif (isset($data['folder_id']))	{
			$this->db->query("UPDATE folder SET ".$data['api']."_share_count = ".$data['api']."_share_count + 1 WHERE folder_id = ".$data['folder_id']);
		}
		return parent::_run_after_create($data);
	}

}