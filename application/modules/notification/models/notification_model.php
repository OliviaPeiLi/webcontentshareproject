<?php

class Notification_model extends MY_Model
{
	//relation
	protected $belongs_to = array(
		//RR - this should be polymorphic
		'user_from' => array(
			'foreign_model' => 'user',
			'foreign_column' => 'user_id_from'
		),
		'user_to' => array(
			'foreign_model' => 'user',
			'foreign_column' => 'user_id_to'
		),
		//'ticker' => array( 'foreign_model' => 'activity', 'foreign_column' => 'a_id' ),
		//'newsfeed' => array('foreign_column' => 'newsfeed_id', 'foreign_model' => 'newsfeed', 'on_delete_cascade' => false)
	);
		
	protected $has_many = array(
		'sending_emails' => array('foreign_column'=>'notification_id'),
	);
	
	public $notification_types = array(
		'folder_contributor'     => 'folder_contributor', // someone added you as a collaborator in his list
		'newsfeed'               => 'newsfeed',           // someone dropped in list
		'collaboration_newsfeed' => 'newsfeed',           // someone dropped in list
		'follow'                 => 'connection',         // someone is following you
		'follow_folder'          => 'folder_user',        // someone is following your list
		'badge'                  => 'badge',              // You got a badge
		'message'                => 'msg_content',        // someone sent you a message
		'link_like'              => 'like',               // someone upvoted your drop
		'photo_like'             => 'like',               // someone upvoted your drop *********
		'link_comm_like'         => 'like',               // someone upvoted your comment
		'folder_like'            => 'like',               // someone upvoted your folder
		'u_comm'                 => 'comment',            // someone commented your drop *******
		'at_comm'                => 'mention',            // someone mentioned you
		'at_drop'                => 'newsfeed',           // someone mentioned you
		'redrop'			 	=> 'newsfeed'
	);
	
	function sample () {
 		$sample = new Model_Item();
 		$sample->_model = $this;
 		$sample->id = -1;
 		$sample->user_id_from = -1;
 		$sample->type = '';
 		$sample->item_id = -1;
 		$sample->converted_time = '';
 		$sample->user = (Object) array(
 			'url' => '',
 			'full_name' => '',
 			'avatar' => '',
 		);

 		Uploadable_Behavior::_run_after_get($sample->user, get_instance()->user_model->behaviors['uploadable']);
 		
 		$sample->folder = (Object) array(
 			'folder_url' => '',
 			'folder_name' => '',
 		);
 		$sample->newsfeed = (Object) array(
 			'newsfeed_id' => -1,
			'link_type' => '',
			'description' => '',
			'url' => '',
			'img' => '',
			'complete' => 0,
 		);

 		Uploadable_Behavior::_run_after_get($sample->newsfeed, get_instance()->newsfeed_model->behaviors['uploadable']);
 		
 		$sample->badge = (Object) array(
 			'name' => '',
 		);

 		$sample->msg_content = (Object) array(
 			'thread_id' => -1,
 			'msg_body' => '',
 		);

 		$sample->comment = (Object) array(
 			'comment' => '',
 		);
 		
 		return $sample;
 	}

 	public function on_newsfeed_name_change( $newsfeed )	{

		$newsfeed = array(
			'newsfeed_id' => $newsfeed->newsfeed_id,
			'link_type' => $newsfeed->link_type,
			'description' => $newsfeed->description,
			'url' => $newsfeed->url,
			'img' => $newsfeed->img,
			'complete' => $newsfeed->complete,
		);		

		$result = mysql_query("SELECT `id`,`cache` FROM `notifications` WHERE `newsfeed_id` = '{$newsfeed['newsfeed_id']}'");

		while ( $row = mysql_fetch_object($result) )	{

			$cache = $row->cache;
			$arr_chache = array();

			if ($cache)	{
				$cache = json_decode($cache);
				$cache->newsfeed = $newsfeed;
			}

			$cache = mysql_real_escape_string(json_encode($cache));
			//mysql_query("UPDATE `notifications` SET `cache` = '{$cache}' WHERE `id` = '{$row->id}' LIMIT 1");
		}

 	}

 	public function on_folder_name_change( $folder )	{

 		$_folder = array(
			'folder_url' => $folder->folder_url,
			'folder_name' => $folder->folder_name,
		);

		$result = mysql_query("SELECT `id`,`cache` FROM `notifications` WHERE `folder_id` = '{$folder->folder_id}'");

		while ( $row = mysql_fetch_object($result) )	{

			$cache = $row->cache;
			$arr_chache = array();

			if ($cache)	{
				$cache = json_decode($cache);
				$cache->folder = $_folder;
			}

			$cache = mysql_real_escape_string(json_encode($cache));

			//mysql_query("UPDATE `notifications` SET `cache` = '{$cache}' WHERE `id` = '{$row->id}' LIMIT 1");
		}	

 	}

 	public function on_user_change()	{

		$user = array(
			'url' => '/'.$this->user->uri_name,
			'full_name' => $this->user->first_name.' '.$this->user->last_name,
			'avatar' => $this->user->avatar
		);

		$result = mysql_query("SELECT `id`,`cache` FROM `notifications` WHERE `user_id_from` = '{$this->user->id}'");

		while ( $row = mysql_fetch_object($result) )	{

			$cache = $row->cache;
			$arr_chache = array();

			if ($cache)	{
				$cache = json_decode($cache);
				$cache->user = $user;
			}

			$cache = mysql_real_escape_string(json_encode($cache));
			//mysql_query("UPDATE `notifications` SET `cache` = '{$cache}' WHERE `id` = '{$row->id}' LIMIT 1");

		}

 	}
 	
 	public function get_folder($notification) {

 		if (isset($notification->item->folder_id) && $notification->item->folder_id != false) {
 			return $notification->item->folder;
 		} else	{

 			if (!isset($notification->cache['newsfeed']))	{
 				$notification->cache['newsfeed'] = $this->get_newsfeed($notification);
 			}

			// get folder info
	 		$folder = $this->newsfeed_model->get($notification->cache['newsfeed']->newsfeed_id);
			$notification->cache['folder'] = $folder;

			$cache = json_encode( $notification->cache );
			//mysql_query( "UPDATE notification set cache='" . $cache . "' WHERE `id` = '{$notification->id}'");

		 	return $this->folder_model->get($folder->folder_id);
 		}

 		return (Object) array(
 			'folder_url' => '',
 			'folder_name' => '',
 		);

 	}
 	
 	public function get_newsfeed($notification) {

		$like = $this->get_item($notification);
		$newsfeed = isset($like->comment) ? $like->comment->newsfeed : $like->newsfeed;

		$notification->cache['newsfeed'] = $newsfeed;

		$cache = json_encode( $notification->cache );
		//mysql_query( "UPDATE notification set cache='" . $cache . "' WHERE `id` = '{$notification->id}'");	

		return $newsfeed;

 		 return (Object) array(
 			'newsfeed_id' => -1,
			'link_type' => '',
			'description' => '',
			'url' => '',
			'img' => '',
			'complete' => 0,
 		);
 	}
 	
 	public function get_user($notification) {

 		$notification->cache['user'] = $notification->user_from;

		$cache = json_encode( $notification->cache );
		//mysql_query( "UPDATE notification set cache='" . $cache . "' WHERE `id` = '{$notification->id}'"); 	

		return $notification->cache['user'];

 		return (Object) array(
 			'url' => '',
 			'full_name' => '',
 			'avatar' => '',
 		);
	
	}

	public function get_comment($notification) {
		$comment_id = $this->get_item($notification)->comment_id;
		return $this->comment_model->get($comment_id); //->comment;
	}
 	
 	/* ===================== PER ITEM FUNCS ============================== */
 	
 	public function get_item($notification) {
 		$model = $this->notification_types[$notification->type];
 		return $this->{$model.'_model'}->get($notification->item_id);
 		return null;
 	}
 	
 	/* ======================= SELECTS ===================================== */
 	
	//RR - new notification grouping logic
	//Quang - remove offset logic
	public function get_grouped($user_id) {
		$items = $this->order_by('id','desc')->get_many_by(array('user_id_to'=>$user_id));
		$ret = array();
		foreach ($items as $row) {
			$ret[date('Y-m-d', strtotime($row->time))][] = $row;
		}
		return $ret;
	}
	
	/* =============================== Filters ======================= */
	public function filter_home() {
		$this->db->where(array('user_id_to' => $this->session->userdata('id')))
				->order_by('id','desc')->limit(10);
		return $this;
	}
	
	/* ================================= Events ============================= */
	
	protected function _run_before_create($data) {


		if (!isset($data['user_id_from'])) $data['user_id_from'] = get_instance()->user->id;
		
		$user = mysql_fetch_object(mysql_query("SELECT uri_name, first_name, last_name, avatar FROM users WHERE id = ".$data['user_id_from']));
		
		$model = get_instance()->{$this->notification_types[$data['type']].'_model'};
		$item = $model->get($data['item_id']);
		
		$cache = array(
			'user' => array(
				'url' => '/'.$user->uri_name,
				'full_name' => $user->first_name.' '.$user->last_name,
				'avatar' => $user->avatar
			)
		);
		
		$newsfeed_id = isset($item->newsfeed_id) && $item->newsfeed_id ? $item->newsfeed_id : 0; 
		$folder_id = isset($item->folder_id) && $item->folder_id  ? $item->folder_id : 0;

		if (isset($item->comment_id) && $item->comment_id && $item->comment) {
			$newsfeed_id = @$item->comment->newsfeed_id;
		}
		
		if ($newsfeed_id) {
			$newsfeed = mysql_fetch_object(mysql_query("SELECT newsfeed_id, folder_id, link_type, description, url, img, complete FROM newsfeed WHERE newsfeed_id = ".$newsfeed_id));
			$cache['newsfeed'] = array(
				'newsfeed_id' => $newsfeed->newsfeed_id,
				'link_type' => $newsfeed->link_type,
				'description' => $newsfeed->description,
				'url' => $newsfeed->url,
				'img' => $newsfeed->img,
				'complete' => $newsfeed->complete,
			);

			if (!$folder_id) $folder_id = $newsfeed->folder_id;

			$data['folder_id'] = $folder_id;
			$data['newsfeed_id'] = $newsfeed->newsfeed_id;

		}
		
		if ($folder_id) {
			$folder = mysql_fetch_object(mysql_query(
				"SELECT folder_id, user_id, CONCAT('/', users.uri_name, '/', folder_uri_name) as folder_url, folder_name
				FROM folder JOIN users ON (users.id = folder.user_id) 
				WHERE folder_id = ".$folder_id
			));
			$cache['folder'] = array(
				'folder_url' => $folder->folder_url,
				'folder_name' => $folder->folder_name,
			);
		}
		
		if ($data['type'] == 'badge') {
			$cache['badge'] = array(
				'name' => $item->name,
			);
		} elseif ($data['type'] == 'message') {
			$cache['msg_content'] = array(
				'msg_body' => $item->msg_body,
				'thread_id' => $item->thread_id,
			);
		} elseif ($data['type'] == 'u_comm') {
			$cache['comment'] = array(
				'comment' => $item->comment,
			);
		} elseif ($data['type'] == 'link_comm_like') {
			$cache['comment'] = array(
				'comment' => $item->comment->comment,
			);
		}
		
		$data['cache'] = json_encode($cache);
		
		return parent::_run_before_create($data);
	}
	/**
	 * Usualy when a notification is created an email to the user should be sent
	 */
	protected function _run_after_create($data) {

		// user_id_from information
		$user	   = $this->user_model->select_fields(array('first_name','last_name','avatar','uri_name'))->get($data['user_id_from']);
		$user_url   = $user->url;
		$first_name = $user->first_name;
		$last_name  = $user->last_name;
		$thumbnail  = $user->avatar_73;
		$name	   = $first_name.' '.$last_name;

		$type			= $data['type'];
		$notification_id = $data['id'];
		$cache = json_decode($data['cache']);

		// user_id_to information
		$email_settings = $this->email_setting_model->get_by(array('user_id'=>$data['user_id_to']));
		$send_to_user   = $this->user_model->get($data['user_id_to']);

		$user_to = $this->user_model->select_fields(array('first_name','uri_name','last_name','avatar','uri_name'))->get($data['user_id_to']);

		// email_settings is available
		if ( $email_settings && $data['user_id_from'] != $data['user_id_to'] && !isset($_SERVER['argv']) ) { //dont send email from scripts

			$unsubscribe_link = Url_helper::base_url().'unsubscribe_email?u='.$send_to_user->id.'&e='.$email_settings->id;

			$msg_data = array(
				'user_link'=>Url_helper::base_url($user_url),
				'name'=>$name,
				'user_name'=>$send_to_user->first_name,
				'thumbnail'=>$thumbnail,
				'unsubscribe_link'=>$unsubscribe_link
			);

			// if ( isset($cache->newsfeed) ) {
			if ( isset($cache->folder) ) {
				// we haven't individual pages for drops
				// $msg_data['link_url'] = Url_helper::base_url().'drop/'.$cache->newsfeed->url;
				$msg_data['link_url'] = Url_helper::base_url($cache->folder->folder_url);
				$msg_data['folder_name'] = $cache->folder->folder_name;
			}

			$this->load->library('parser');
			$email_template = '';

 			// we haven't cooments on drop
 			// but we have comments on lists
			// comments to my post
			if ( isset($email_settings->comment) && $email_settings->comment == '1' && $type == 'u_comm')  {
				$subject = sprintf('%s commented to your list', $name);
				$msg_data['comment'] = $cache->comment->comment;
				$email_template = 'email_templates/comment_template';
			}

			// posts are upvoted
			if ( isset($email_settings->up_link) && $email_settings->up_link == '1' && $type == 'link_like')  {
				$subject = sprintf('Your drop is liked by %s', $name);
				//$msg_data['type'] = '<a href="'.Html_helper::base_url().'drop/'.$cache->newsfeed->url.'">drop</a>';
				$msg_data['type'] = '<a href="'.Html_helper::base_url($cache->folder->folder_url).'">' . $cache->folder->folder_name . '</a> list';
				$email_template = 'email_templates/up_template';
			}

			// comments are upvoted
			if ( isset($email_settings->up_comment) && $email_settings->up_comment == '1' && $type == 'link_comm_like')  {
				$subject = sprintf('Your comment is liked by %s', $name);
				$email_template = 'email_templates/comment_upvote_template';
			}

			// follow/connection
			if ( isset($email_settings->connection) && $email_settings->connection == '1' && $type == 'follow')  {
				$subject = sprintf('You are followed by %s', $name);
				$email_template = 'email_templates/connection_template';
			}

			// collection contributor from ajax || newsfeed_model
			if ( isset($email_settings->collaboration) && $email_settings->collaboration == '1' && ( $type == 'folder_contributor' || $type == 'newsfeed' ) )  {
				if ($cache->folder) {
					$subject = sprintf('%s add you as collaborator of a list', ucfirst($user_to->first_name." ".$user_to->last_name));
					$msg_data['folder_link'] = Url_helper::base_url($cache->folder->folder_url);
					$msg_data['folder_name'] = $cache->folder->folder_name;
					$email_template = 'email_templates/collaborator_template';
				}
			}

			// collections are followed
			if ( isset($email_settings->follow_folder) && $email_settings->follow_folder == '1' && $type == 'follow_folder') {
				$subject = sprintf('Your list liked by %s', $name);
				$msg_data['folder'] = '<a href="'.Url_helper::base_url($cache->folder->folder_url).'">'.$cache->folder->folder_name.'</a>';
				$email_template = 'email_templates/follow_folder_template';
			}

			// collections are liked
			if ( isset($email_settings->folder_like) && $email_settings->folder_like == '1' && $type == 'folder_like') {
				$subject = sprintf('Your list is upvoted by %s', $name);
				$msg_data['folder'] = '<a href="'.Url_helper::base_url($cache->folder->folder_url).'">'.$cache->folder->folder_name.'</a>';
				$email_template = 'email_templates/up_folder_template';
			}

			// message
			if ( isset($email_settings->message) && $email_settings->message == '1' && $type == 'message')  {
				$subject = sprintf(get_instance()->lang->line('message_send_msg_subject'), $name);
				$msg_data['message'] = nl2br($cache->msg_content->msg_body);
				$email_template = 'email_templates/message_template';
			}

			if ( $email_template !== '' && $send_to_user->email) {

				$this->load->helper('email');
				$msg = $this->parser->parse($email_template, $msg_data, TRUE);

				$this->sending_email_model->insert(
					array(
						'email'=>$send_to_user->email,
						'subject'=>$subject,
						'message'=>$msg,
						'notification_id'=>$notification_id
					)
				);
				
				//http://dev.fantoon.com:8100/browse/FD-5130#comment-20446
				//Email_helper::SendEmail($send_to_user->email, $subject, $msg);
			
			}
		}

		return parent::_run_after_create($data);
	}
	
	protected function _run_after_set($data) {
		$this->refresh_cache($data);
		return parent::_run_after_set($data);
	}
	
	
	public function jsonfy($limit=NULL) {

		if (is_array($limit)) {
			$res = $limit;
		} else {
			$res = $this->get_all($limit);
		}
		
		foreach ($res as $k=>&$row) {
			if ($row->type == 'follow') {
				$row->is_following = $this->user_model->is_following( get_instance()->user, $row->user_id_from);
			}
			unset($row->_model);
		}

		return $res;
	}
	
	//for follow flag
	public function _run_after_get($row=null) {

		if (!parent::_run_after_get($row))  return ;
		
		if (isset($row->cache)) {
			$cache = (Array) json_decode($row->cache);
			foreach ($cache as $obj=>$data) {
				if ($obj == 'newsfeed') {
					Uploadable_Behavior::_run_after_get($data, get_instance()->newsfeed_model->behaviors['uploadable']);
				} elseif ($obj == 'user') {
					Uploadable_Behavior::_run_after_get($data, get_instance()->user_model->behaviors['uploadable']);
				}
				$row->{'_'.$obj} = $data;
			}
			$row->cache = $cache;
		}

		// if (!isset($row->cache['folder']) && isset($row->cache['newsfeed']))	{
		// 	$folder = $this->newsfeed_model->select_fields("folder_id")->get($row->cache['newsfeed']->newsfeed_id);
		// 	$row->cache['folder'] = $this->folder_model->select_fields("folder_id","folder_name","folder_uri_name")->get($folder->folder_id);
		// }
		
		if (date('Y-m-d', strtotime($row->time)) == date('Y-m-d')) {
			$row->_display_time = 'Today';
			$row->_converted_time = date('H:i A', strtotime($row->time));
		} else if (date('Y-m-d', strtotime($row->time)) === date("Y-m-d", strtotime("yesterday"))) {
			$row->_display_time = 'Yesterday';
			$row->_converted_time = date('H:i A', strtotime($row->time));
		} else {
			$row->_display_time = date('F d Y', strtotime($row->time));
			$row->_converted_time = date('M j, Y, H:i A', strtotime($row->time));
		}

		return $row;
	}
	
	protected function _run_before_delete($obj) {
		$this->refresh_cache($obj);
		return parent::_run_before_delete($obj);
	}
	
	private function refresh_cache($row) {
		$arr = array();
		if (!is_array($row)) foreach ($row as $key=>$val) $arr[$key] = $val; else $arr = $row;
		$ci = get_instance();
		if (isset($arr['user_id_to'])){
			$ci->cache->delete('notifications_read_' . $arr['user_id_to']);
			$ci->cache->delete('notifications_view_' . $arr['user_id_to']);
		}
	}

}
