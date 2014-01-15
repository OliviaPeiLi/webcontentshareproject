<?php

class Folder_user_model extends MY_Model {

	protected $_table = 'folder_user';

	protected $belongs_to = array(
							'folder',
							'user'
						);

	protected $polymorphic_has_one = array(
							'ticker' => array(
								'foreign_model' => 'activity',
								'model_column' => 'type',
								'item_column' => 'activity_id',
								'on_delete_cascade' => true
							)
						);
									 
	public $behaviors = array(
							'countable' => array(
								array(
									'table' => 'folder',
									'relation' => array('folder_id' => 'folder_id'),
									'fields' => array(
										'followers_count'
									),
								)
							),
							'active' => array(
								'primary_key' => 'id',
								'user_to_field' => 'user_id',
								'folder_id' => 'folder_id',
								'type' => 'folder_user',
							),
							'notify' => array(
								'type' => 'follow_folder',
								'primary_key' => 'id',
								'user_to_field' => array('folder'=>'user_id'),
							)
						);
						
	 protected $validate = array(
								'id'        => array( 'label' => 'ID',        'rules' => '' ),
								'user_id'   => array( 'label' => 'From User', 'rules' => '' ),
								'folder_id' => array( 'label' => 'Folder',    'rules' => '' ),
						  );
						  
	/* ========================== EVENTS ================================= */
	
	protected function _run_before_create($data) {
		if (!isset($data['user_id'])) {
			$data['user_id'] = get_instance()->user->id;
		}
		
		return parent::_run_before_create($data);
	}
}
