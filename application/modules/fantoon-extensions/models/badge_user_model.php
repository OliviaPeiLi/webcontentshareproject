<?php

class Badge_user_model extends MY_Model {
	
	//relations
	protected $belongs_to = array('badge', 'user');
								
	public $behaviors = array(
								'active' => array(
									'primary_key' => 'badge_id',
									'user_from_field' => 'user_id',
									'type' => 'badge'
								),
								'notify' => array(
									'primary_key' => 'id',
									'type' => 'badge'
								),
							);
	
	protected function _run_after_create($data) {
		$this->user_model->update($data['user_id'],array('badge_id'=>$data['badge_id']));
		return parent::_run_after_create($data);
	}

}