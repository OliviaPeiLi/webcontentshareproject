<?php

class Like_model extends MY_Model
{
	protected $primary_key = 'like_id';
	
	//Relations
	protected $belongs_to = array(
								'user_from' => array('foreign_column' => 'user_id', 'foreign_model' => 'user'),
								'user_to' => array('foreign_column' => 'user_id_to', 'foreign_model' => 'user'),
								'comment' => array('foreign_column' => 'comment_id', 'foreign_model' => 'comment', 'on_delete_cascade' => false),
								'newsfeed' => array('foreign_column' => 'newsfeed_id', 'foreign_model' => 'newsfeed', 'on_delete_cascade' => false),
								'folder' => array('foreign_column' => 'folder_id', 'foreign_model' => 'folder', 'on_delete_cascade' => false),
							);

	protected $polymorphic_has_one = array(
										 'ticker' => array(
												 'foreign_model' => 'activity',
												 'model_column' => 'type',
												 'item_column' => 'activity_id',
												 'on_delete_cascade' => true
										 )
									 );
									 
	//Behaviors
	public $behaviors = array(
							'countable' => array(
								array(
									'table' => 'user_stats',
									'relation' => array('user_id' => 'user_id'),
									'fields' => array('upvotes_count'),
								),
								array(
									'table' => 'user_stats',
									'relation' => array('user_id_to' => 'user_id'),
									// 'fields' => array('upvotes_got_count'),
									'fields' => array('upvotes_count'=>'comment_id == 0')
								),
								array(
									'table' => 'folder',
									'relation' => array('folder_id' => 'folder_id'),
									'fields' => array('upvotes_count'),
								),
								array(
									'table' => 'newsfeed',
									'relation' => array('newsfeed_id' => 'newsfeed_id'),
									'fields' => array('up_count'),
								),
								array(
									'table' => 'comments',
									'relation' => array('comment_id' => 'comment_id'),
									'fields' => array('up_count'),
								),
							),
							'active' => array(
								'primary_key' => 'like_id',
								'user_from_field' => 'user_id',
								'folder_id' => 'folder_id',
								'type' => 'like'
							),
							'notify' => array(
								'user_from_field' => 'user_id',
								'user_to_field' => 'user_id_to',
								'primary_key' => 'like_id',
								'type' => array(
									'link_like' => 'newsfeed_id > 0',
									'link_comm_like' => 'comment_id > 0',
									'folder_like' => 'folder_id > 0',
								)
							)
						);
	
	protected $validate = array(
								'like_id'	 => array( 'label' => 'ID',		'rules' => '' ),
								'user_id'	 => array( 'label' => 'From User', 'rules' => '' ),
								'user_id_to'  => array( 'label' => 'To User',   'rules' => '' ),
								'comment_id'  => array( 'label' => 'Comment',   'rules' => '' ),
								'newsfeed_id' => array( 'label' => 'Newsfeed',  'rules' => '' ),
								'folder_id'   => array( 'label' => 'Folder',	'rules' => '' ),
						  );

	/* ================================= Filters ============================== */

	
	/* =========================== EVENTS ============================= */
	
	protected function _run_before_create($data) {



		if (!isset($data['user_id'])) $data['user_id'] = get_instance()->user->id;
		// if (!isset($data['user_id_to'])) {
			if (isset($data['comment_id']) && $data['comment_id']) {
				$obj = $this->comment_model->get($data['comment_id']);
				$data['user_id_to'] = $obj->user_id_from;
				$data['folder_id'] = $obj->folder_id;
				$data['newsfeed_id'] = $obj->newsfeed_id;
			}
			elseif (isset($data['newsfeed_id']) && $data['newsfeed_id']) {
				$data['user_id_to'] = $this->newsfeed_model->get($data['newsfeed_id'])->user_id_from;
			}
			elseif (isset($data['folder_id']) && $data['folder_id']) {
				$data['user_id_to'] = $this->folder_model->get($data['folder_id'])->user_id;
			}
		// }

		return parent::_run_before_create($data);
	}
	
	/**
	 * @todo - newsfeed_id, folder_id, comment_id -  This should be polymorphyc relation
	 * @param unknown_type $data
	 */
	protected function _run_before_delete($obj) {
		$this->refresh_cache($obj);
		return parent::_run_before_delete($obj);
	}
	
	protected function _run_after_set($data) {
		$this->refresh_cache($data);
		return parent::_run_after_set($data);
	}
	
	/* ============================ Others ============================== */
	
	private function refresh_cache($row) {
		$arr = array();
		if(!is_array($row))
		{
			//foreach ($row as $key=>$val) $arr[$key] = $val;
			$row = (array)$row;
		} 
		$arr = $row;
		$ci = get_instance();
		if (isset($arr['user_id'])) {
			//remove all user newsfeed likes cache files
			for ($i=1;$i<=5;$i++) {
				//TO-DO - add types to a config file
				$ci->cache->delete("library_newsfeed_{$arr['user_id']}_likes_{$i}_pictures");
				$ci->cache->delete("library_newsfeed_{$arr['user_id']}_likes_{$i}_clips");
				$ci->cache->delete("library_newsfeed_{$arr['user_id']}_likes_{$i}_videos");
				$ci->cache->delete("library_newsfeed_{$arr['user_id']}_likes_{$i}_texts");
			}
		}
	}

}
?>