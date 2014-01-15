<?php
class Msg_thread_model extends MY_Model
{
	protected $_table = 'msg_thread';
	protected $primary_key = 'thread_id';

	protected $after_get = array('_virtual_fields');
	
	protected $has_many = array(
					'msg_infos' => array(
							'foreign_column' => 'thread_id'
						)
				);
				
	public function sample() {

	    $thread = new Model_Item();
		$thread->thread_id = -1;
		$thread->users = array();
		$thread->msg_info = (object)array(
			"display_status"=>TRUE,
			"user_from"=>(object)array(
					"avatar_42"=>"",
					"avatar_73"=>"",
					"avatar_25"=>"",
					"url"=>"/",
					"full_name"=>"Full Name"
				),
			"from"=>-1,
			"message"=>"Message Body"
		);
		$thread->time = "0 seconds ago";
	
		return $thread;
    }
    
    public function jsonfy($limit = NULL) {
    	if (is_object($limit)) {
    		$threads = array($limit);
    	} else {
    		$threads = $this->get_all($limit);
    	}
		
		foreach ($threads as $key => $value) {

				foreach ($value->users as $k => $v) {
					$threads[$key]->users[$k] = (object)array(
						"id"=>$v->id,
						"_avatar_73"=>$v->_avatar_73,
						"avatar_42"=>$v->avatar_42,
						"avatar_25"=>$v->avatar_25,
						"full_name"=>$v->full_name,
						"url"=>$v->url,
					);
					unset($threads[$key]->users[$k]->_model);
				}

				$threads[$key]->msg_info = $threads[$key]->msg_info; 
				
				$threads[$key]->msg_info->user_from = (object)array(
					"full_name"=> $threads[$key]->msg_info->user_from->full_name,
					"avatar_73"=> $threads[$key]->msg_info->user_from->avatar_73
				);

				$threads[$key]->msg_info->message = nl2br(Text_Helper::character_limiter_strict($threads[$key]->msg_info->message->msg_body, 70));
				$threads[$key]->msg_info->time_ago = Date_Helper::time_ago($threads[$key]->msg_info->time);

				unset(
					$threads[$key]->msg_info->_model_user_from, $threads[$key]->msg_info->_user_from, $threads[$key]->_users,
					$threads[$key]->_model_msg_info, $threads[$key]->msg_info->_model, $threads[$key]->msg_info->_model_message,
					$threads[$key]->msg_info->_message, $threads[$key]->_model
				);

		}
		if (is_object($limit)) {
			return $threads[0];
		} else {
			return $threads;
		}
    }

	/* ================= PER ITEM =================== */
				
	public function get_msg_info($thread) {

		/*if (isset($thread->from) && isset($thread->to)) {
			$thread->msg_info = new Model_Item();
			$thread->msg_info->_model = $this->msg_info_model;
			foreach (array('id','msg_id','from','to','erase_type','display_status','number_read','time') as $field) {
				$thread->msg_info->$field = $thread->$field;
			}
		}

		return $thread->msg_info;
		*/
		return $thread->get('msg_infos')->order_by('id','DESC')->get_by(array());
	}
	
	public function add_message($thread, $user_from_id, $msg_body) {

		$msg_id = $this->msg_content_model->insert(array(
													'thread_id' => $thread->thread_id,
													'msg_body' => $msg_body
												));

		foreach ($thread->_users as $user_id) {

			if ($user_id == $user_from_id) continue;

			$this->msg_info_model->insert(array(
				'thread_id' => $thread->thread_id,
				'from' => $user_from_id,
				'to' => $user_id,
				'msg_id' => $msg_id,
				'time' => date("Y-m-d H:i:s"),
			));
		
		}

	return $msg_id;
	}

	/* ================= FILTERS ================== */
	
	public function filter_inbox($user_id) {

		$this->db->join('msg_info', 'msg_info.thread_id = msg_thread.thread_id');
		$this->db->group_by('msg_thread.thread_id');
		$this->db->where(array('to' => $user_id, 'erase_type !=' => 1));
		$this->db->or_where(array('from' => $user_id));
		$this->db->where(array('erase_type !=' => 2));
		// sort by last thread - view first
		$this->db->order_by("msg_thread.thread_id","DESC");


		return $this;
	}
	
	/* ======================== EVENTS ===================== */

	public function _virtual_fields($row=null) {
		if (isset($row->users)) {
			$row->_users = unserialize($row->users);
			$row->users = $this->user_model->filterout(array($this->session->userdata('id')))->get_many($row->_users);
		}
	}

}