<?php

class Comment_model extends MY_Model
{
	protected $primary_key = 'comment_id';

	//Relations
	protected $belongs_to = array(
								'user_from' => array('foreign_column' => 'user_id_from', 'foreign_model' => 'user'),
								'user_to' => array('foreign_column' => 'user_id_to', 'foreign_model' => 'user'),
								'newsfeed',
								'folder',
							);

	protected $has_many = array(
							  'likes',
						  );

	protected $polymorphic_has_one = array(
										 'ticker' => array(
												 'foreign_model' => 'activity',
												 'model_column' => 'type',
												 'item_column' => 'activity_id',
												 'on_delete_cascade' => true
										 )
									 );
	
	protected $validate = array(
								'newsfeed_id' => array('label' => 'Newsfeed','rules' => 'required'),
								'folder_id' => array('label' => 'Folder','rules' => 'required'),
								'user_id_from' => array('label' => 'From User','rules' => 'required'),
								'user_id_to' => array('label' => 'To User','rules' => ''),
								'comment' => array('label' => 'Comment','rules' => 'trim|required|max_length[250]|validate_comment'),
						  );
	
	//Behaviors
	public $behaviors = array(
							'countable' => array(
								array(
									'table' => 'user_stats',
									'relation' => array('user_id_from' => 'user_id'),
									'fields' => array('comments_count'),
								),
								array(
									'table' => 'user_stats',
									'relation' => array('user_id_to' => 'user_id'),
									'fields' => array('comments_got_count'),
								),
								array(
									'table' => 'newsfeed',
									'relation' => array('newsfeed_id' => 'newsfeed_id'),
									'fields' => array('comment_count'),
								),
								array(
									'table' => 'folder',
									'relation' => array('folder_id' => 'folder_id'),
									'fields' => array('comments_count'),
								),
							),
							'mentionable' => array(
								'comment' => array(
									'mention'=>TRUE,
									'hashtag'=>TRUE,
									'link'=>TRUE
								)
							),
							'active' => array(
								'primary_key' => 'comment_id',
								'user_from_field' => 'user_id_from',
								'user_to_field' => 'user_id_to',
								'type' => 'comment'
							),
							'notify' => array(
								'type' => 'u_comm',
								'primary_key' => 'comment_id' 
							)				
						);

	//Per item funcs
	public function can_delete($comment, $user) {
		if (!$user || !is_object($user)) return false;
		return $user->role == '1' || $user->role == '2' || $user->id == $comment->user_id_from;
	}
	
	public function is_liked($comment, $user) {
		$user_id = is_object($user) ? $user->id : $user;
		if (is_object($comment)) {
   	 		return $comment->get('likes')->count_by('user_id', $user_id);
	   	} else {
	   		return get_instance()->like_model->count_by(array('comment_id' => $comment, 'user_id' => $user_id));
	   	}
	}
	//End Per item funcs
	
	public function sample() {
		$item = new Model_Item();
		$item->_model = $this;
		$item->comment_id = 0;
		$item->comment = '';
		$item->time = date('Y-m-d H:i:s');
		$item->user_id_from = ($this->session->userdata('id')) ? $this->user->id : 0; 
		$item->user_from = $this->session->userdata('id') ? $this->user : null;
		$item->up_count = 1;
		return $item;
	}
	
	/* ================================= Events ============================ */
	
	protected function _run_before_create($data) {

		if (!isset($data['newsfeed_id'])) $data['newsfeed_id'] = 0;

		$data['time'] = date('Y-m-d H:i:s');
		if (!isset($data['user_id_from'])) $data['user_id_from'] = get_instance()->user->id;
		if (!isset($data['folder_id'])) {
			$data['folder_id'] = $this->newsfeed_model->select_fields('folder_id')->get($data['newsfeed_id'])->folder_id;
		}
		
		$data['user_id_to'] = $this->folder_model->select_fields('user_id')->get($data['folder_id'])->user_id;
		return parent::_run_before_create($data);
	}
	
	protected function _run_after_create($data) {
	
		//upvote comment by owner
		$this->like_model->insert(array('user_id'=>$data['user_id_from'], 'user_id_to'=>$data['user_id_from'], 'comment_id'=>$data['comment_id']));
		
		return parent::_run_after_create($data);
	}
	
	/* ================================ FILTERS ============================== */
	
	public function has_likes() {
		$this->db->where("EXISTS (SELECT 1 FROM likes WHERE likes.comment_id = comments.comment_id)", null, false);
		return $this;
	}
	
	public function filter_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("EXISTS (SELECT 1 FROM likes WHERE likes.comment_id = comments.comment_id AND likes.user_id = $user_id)", null, false);
		return $this;
	}
	
	public function not_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("NOT EXISTS (SELECT 1 FROM likes WHERE likes.comment_id = comments.comment_id AND likes.user_id = $user_id)", null, false);
		return $this;
	}

}

?>