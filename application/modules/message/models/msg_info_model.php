<?php

class Msg_info_model extends MY_Model
{
	protected $_table = 'msg_info';

	//relations
	protected $belongs_to = array(
								'message' => array(
									'foreign_column' => 'msg_id',
									'foreign_model' => 'msg_content'
								),
								'user_to' => array(
									'foreign_column' => 'to',
									'foreign_model' => 'user'
								),
								'user_from' => array(
									'foreign_column' => 'from',
									'foreign_model' => 'user'
								)
							);
							
	//Behaviors
	public $behaviors = array(
		'notify' => array(
						'user_from_field' => 'from',
						'user_to_field' => 'to',
						'type' => 'message',
						'primary_key' => 'msg_id',
					)
	);
							
	/* =================== PER ITEM ======================= */

	public function mark_read($msg) {
		$msg->update(array('display_status'=>'1'));
	}
	
	/* =================== EVENTS ====================== */
	
	public function _run_after_get($row) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->users)) {
			$users = unserialize($row->users);
			$row->users = $this->user_model->get_many_by(array('id'=>$users));
		}
	}
	
	protected function _run_before_set($data) {
		if (isset($data['erase_type']) && $data['erase_type'] == 1) {
			$obj = $this->get_by(array('msg_id'=>$data['msg_id'],'to'=>$data['to']));
			if ($obj) {
				$this->notification_model->delete_by(array('user_id_from'=>$obj->from, 'user_id_to'=>$obj->to, 'type'=>'message'));
			}
		}
		return parent::_run_before_set($data);
	}

}