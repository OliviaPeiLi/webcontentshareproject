<?php
class Msg_content_model extends MY_Model
{
	protected $_table = 'msg_content';
	protected $primary_key = 'msg_id';
	public $name_key = 'msg_body';

	//Relations
	protected $has_one = array(
							  'msg_info' => array(
								  'foreign_column' => 'msg_id',
							  )
						  );

	protected $belongs_to = array(
								'msg_thread' => array(
									'foreign_model' => 'msg_thread',
									'foreign_column' => 'thread_id',
								)
							);
							
	protected $validate = array(
								'thread_id' => array(
									'label' => 'Thread',
									'rules' => 'required|check_thread',
								),								
								'msg_body' => array(
									'label' => 'Thread',
									'rules' => 'required',
								),								
						  );
	
	public function sample()	{

		return (object)array(
			"msg_info"=>(object)array(
					"from"=>1,
					"display_status"=>0,
					"user_from"=>(object)array(
						"avatar_42"=>"",
						"url"=>"",
						"full_name"=>""
					),
					"time"=>" 0 seconds ago"
				),
			"msg_id"=>"",
			"msg_body"=>"",
			"thread_id"=>-1
		);

	}

	/* ================= PER ITEM =================== */
				
	public function get_msg_info($msg_content) {
		if (isset($msg_content->from) && isset($msg_content->to)) {
			$msg_content->msg_info = new Model_Item();
			$msg_content->msg_info->_model = $this->msg_info_model;
			foreach (array('id','msg_id','from','to','erase_type','display_status','number_read','time') as $field) {
				$msg_content->msg_info->$field = $msg_content->$field;
			}
		}
		return $msg_content->msg_info;
	}
	
	public function jsonfy($message) {
		$message->msg_info = $message->msg_info; //__get
		$message->msg_info->time_ago = Date_Helper::time_ago($message->msg_info->time);

		$message->msg_info->user_from = (object)array(
			"avatar_42" => $message->msg_info->user_from->avatar_42,
			"url" => $message->msg_info->user_from->url,
			"full_name" => $message->msg_info->user_from->full_name
		);

		unset($message->_model, $message->_model_msg_info, $message->_msg_info, $message->msg_info->_model,
				$message->msg_info->_model_user_from, $message->msg_info->_user_from);
		return $message;
	}

	/* =================== Filters ======================== */
	
	public function filter_msg_id($msg_id)	{
		$this->db->join('msg_info', 'msg_info.msg_id = msg_content.msg_id', 'inner');
		$this->db->group_by('msg_content.msg_id');
		$this->db->where(array('msg_info.msg_id'=>$msg_id));
		$this->db->order_by("msg_info.msg_id","DESC");

	return $this;
	}

	public function filter_thread($thread_id) {
		$this->db->where('msg_content.thread_id', $thread_id);
		return $this;
	} 
	
	public function filter_inbox($user_id) {
		$this->db->join('msg_info', 'msg_info.msg_id = msg_content.msg_id', 'inner');
		$this->db->group_by('msg_content.msg_id');
		$this->db->where("(`to` = $user_id AND erase_type !=1 OR `from` = $user_id AND erase_type != 2)");
		return $this;
	}
}