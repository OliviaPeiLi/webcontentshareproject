<?php
class Api_objects {
	
	public function convert($item) {
		if (!$item) return ;
		return $item->as_array();
	}
	
	public function convert_page($item, $full = false) {
		if (!$full) {
			return $item->as_array();
		} else {
			return array_merge($item->as_array(), array(
				'newsfeeds' => array_map(array($this, 'convert_newsfeed'), $item->newsfeed_tos),
				'thumb' => $item->avatar_preview
			));
		}
	}
	
	public function convert_stats($item, $full = false) {
		return array(
			'drops' => $item->total_drops,
			'likes' => $item->likes,
			'collections' => $item->collections
		);
	}
	
	public function convert_user($item, $full = false) {
		return array_merge($item->as_array(), array(
			'email_setting' => array_map(array($this, 'convert_email_settings'), $item->email_settings),
			'thumb' => $item->avatar_preview,
			'stats' => array_map(array($this, 'convert_stats'),$item->user_stats)
			//'lists' => array_map(array($this, 'convert'), $item->lists)
		));
	}
	
	public function convert_folder($item, $full = false) {
	$folder_newsfeed = @$item->get('newsfeeds')->order_by('time','DESC')->limit(1)->get_all();
	if(isset($folder_newsfeed)){
		$folder_img = @$folder_newsfeed[0]->img_thumb;
	
	}
		return array_merge($item->as_array(), array(
			//'newsfeeds' => array_map(array($this, 'convert_newsfeed'), $item->newsfeeds),
			//'user' => array_map(array($this, 'convert'), $item->folder_users)
			'url'=>$item->url,
			'folder_img'=> @$folder_img
		));
	}
	
	public function convert_topic($item, $full = false) {
		return array_merge($item->as_array(), array(
			'parent_topics' =>array_map(array($this, 'convert'), $item->parents) ,
			'child_topics' =>array_map(array($this, 'convert'), $item->children) ,
		));
	}
	
	public function convert_email_settings($item, $full = false){
	    return array(
			'message' => $item->message,
			'comment' => $item->comment,
			'up_link' => $item->up_link,
			'reply' => $item->reply,
			'up_comment' => $item->up_comment,
			'connection' => $item->connection,
			'follow_folder' => $item->follow_folder,
			'follow_list' => $item->follow_list
		);
	}
	
	public function convert_page_id_info($item, $full = false){
	    return array(
			'page_id' => $item->page_id,
		);
	}
	
	public function convert_page_info($item, $full = false){
	    return array(
			'page_id' => $item->page_id,
			'page_name' => $item->page_name
		);
	}
	
	public function convert_topic_info($item, $full = false){
	    return array(
			'topic_id' => $item->topic_id,
			'topic_name' => $item->topic_name
		);
	}
	
	public function convert_list_info($item, $full = false){
	    return array(
			'list_id' => $item->list_id,
			'list_name' => $item->list_name
		);
	}
	
	public function convert_user_info($item, $full = false){
	    return array(
			'user_id' => $item->id,
			'user_name' => $item->first_name.' '.$item->last_name,
			'thumb' => $item->avatar_small
		);
	}
	
	
	public function convert_user_link($item, $full = false){
	    if (!$item) return ;
		return $item->as_array();
	}
	
	public function convert_user_school($item, $full = false){		
		$ret = $this->convert($item);
		$ret['school'] = $this->convert($item->school);
		return $ret;
	}
	
	public function convert_page_user($item, $full = false){
	    return array_merge($item->as_array(), array(
			'pages' => $this->convert_page_info($item->page),
			'users' => $this->convert_user_info($item->user)
		));
	}
	
	public function convert_topic_page($item, $full = false){
	    return array_merge($item->as_array(), array(
			'pages' => $this->convert_page_info($item->page),
			'topics' => $this->convert_topic_info($item->topic)
			
		));
	}
	
	public function convert_list_page($item, $full = false){
	    return array_merge($item->as_array(), array(
			'page' => $this->convert_page_info($item->page),
			'list' => $this->convert_list_info($item->list)
		));
	}
	
	public function convert_list_users($item, $full = false){
	    return array_merge($item->as_array(), array(
			//'user' => $this->convert_user_info($item->user),
			//'list' => $this->convert_list_info($item->interests_list)
			'lists' => array_map(array($this, 'convert'), $item)
		));
	}
	
	public function convert_newsfeed($item, $full = false) {

		return array(
			'newsfeed_id' => $item->newsfeed_id,
			//'data' => $item->data,
			'type' => $item->type,
			'link_type' => $item->link_type,
			'time' => $item->time,
			'time_ago' => $item->_time_ago,
			'user_id_from' => $item->user_id_from,
			'activity' => $this->convert_activity($item->activity),
			'folder' => $this->convert($item->folder),
			'like_count' => $item->up_count,
			'comment_count' => $item->comment_count,
			'collect_count' => $item->collect_count,
			'user_from' => $this->convert_user_from($item->user_from),
			'page_to' => $this->convert_page_to($item->page_to)
		);
	}
	
	public function convert_activity($item, $full = false) {
		if (!$item) return ;
		if(isset($item->link_id)){
			$ret = array(
				'link_id'=>$item->link_id,
				'user_id_from'=>$item->user_id_from,
				'user_id_to'=>$item->user_id_to,
				'page_id_from'=>$item->page_id_from,
				'page_id_to'=>$item->page_id_to,
				'link'=>$item->link,
				'img'=>$item->img,
				'media'=>$item->media,
				'title'=>$item->title,
				'text'=>$item->text,
				'time'=>$item->time,
				'source'=>$item->source,
				'img_width'=>$item->img_width,
				'img_height'=>$item->img_height,
				'trimmed_left'=>$item->trimmed_left,
				'trimmed_top'=>$item->trimmed_top,
				'thumb_width'=>$item->img_width,
				'thumb_height'=>$item->img_height,
			);
			if($item->img_width > 500){
				$ret['thumb_width'] = 500;
				$ret['thumb_height'] = $item->img_height*500/$item->img_width;
			}
			return $ret;
		}
		if(isset($item->photo_id)){
			$ret = array_merge($item->as_array(), array(
						'thumb_width' => $item->full_img_width,
						'thumb_height' => $item->full_img_height
					));
			if($item->full_img_width > 500){
				$ret['thumb_width'] = 500;
				$ret['thumb_height'] = $item->full_img_height*500/$item->full_img_width;
			}
			return $ret;
		}
	}
	
	public function convert_user_from($item, $full = false) {
		return array_merge($item->as_array(), array(
			'thumb' => $item->avatar_preview
		));
	}
	
	public function convert_page_to($item) {
		if (!$item) return ;
		return array_merge($item->as_array(), array(
			'thumb' => $item->avatar_preview
		));

	}
	
	
	public function convert_comment($item, $full = false) {
		
		return array(
			'comment_id' => $item->comment_id,
			'link_id' => $item->link_id,
			'photo_id' => $item->photo_id,
			'comment' => $item->comment,
			'time' => $item->time,
			'user_id_from' => $item->user_id_from,
			'user_from' => $this->convert_user($item->user_from),
			'time_ago' => $item->time_ago,
			'like_count' => $item->like_count
		);
	}
	
	public function convert_like($item, $full = false) {
		$ret = array(
			'like_id' => $item->like_id,
			'link_id' => $item->link_id,
			'photo_id' => $item->photo_id,
			'comment_id' => $item->comment_id,
			'time' => $item->time,
			'user_id' => $item->user_id,
			'user_from' => $this->convert($item->user_from),
		);
		if ($item->link_id > 0){
			$ret['newsfeed'] = $this->convert_newsfeed($item->link->newsfeed);
			//$ret['activity'] = $item->link->newsfeed->activity;
		}
		if ($item->photo_id > 0){
			$ret['newsfeed'] = $this->convert_newsfeed($item->photo->newsfeed);
			//$ret['activity'] = $item->photo->newsfeed->activity;
		}

		return $ret;
	}
/*	
	public function convert_lists($item, $full = false){
	    return array_merge($item->as_array(), array(
			'pages' => array_map(array($this, 'convert_page_id_info'), $item->page)
		));
	}
*/	
	
	public function convert_message($item, $full = false) {
		$user_to = array();
		$user_from = array();
		foreach ($item->infos as $info) {
			$user_to[] = array(
				'id' => $info->user_to->id,
				'uri_name' => $info->user_to->uri_name,
				'full_name' => $info->user_to->first_name.' '.$info->user_to->last_name,
				'thumbnail' => $info->user_to->avatar_preview,
			);
		}
		$user_from = array(
				'id' => $item->infos[0]->user_from->id,
				'uri_name' => $item->infos[0]->user_from->uri_name,
				'full_name' => $item->infos[0]->user_from->first_name.' '.$item->infos[0]->user_from->last_name,
				'thumbnail' => $item->infos[0]->user_from->avatar_preview,
		);
		return array(
			'thread_id'=> $item->thread_id,
			'msg_id' => $item->msg_id,
			'msg_body' => $item->msg_body,
			'time' => $item->infos[0]->time,
			'display_status' => $item->infos[0]->display_status,
			'user_from' => $user_from,
			'user_to' => $user_to,
		);
	}
	
	
	public function convert_message_inbox($item, $full = false) {
		$users = array();
		foreach ($item->msg_thread->users_data as $user) {
				
				$users[] = $this->convert_user_info($user);
		}

		$ret = array(
			'thread_id'=> $item->thread_id,
			'msg_id' => $item->msg_id,
			'msg_body' => $item->msg_body,
			'time' => $item->infos[0]->time,
			'display_status' => $item->infos[0]->display_status,
		);
		if(!empty($users)){
			$ret['users'] = $users;
		}
		return $ret;
	}
	
	public function convert_message_thread($item, $full = false) {
		//return $this->convert($item); // <= TO-DO
		
		$user_from = array(
			'id' => $item->infos[0]->user_from->id,
			'uri_name' => $item->infos[0]->user_from->uri_name,
			'full_name' => $item->infos[0]->user_from->first_name.' '.$item->infos[0]->user_from->last_name,
			'thumb' => $item->infos[0]->user_from->avatar_preview
		);
		
		$users = array();
		foreach ($item->msg_thread->users_data as $user) {
				
				$users[] = $this->convert_user_info($user);
		}

		$ret = array(
			'msg_id' => $item->msg_id,
			'thread_id'=> $item->thread_id,
			'msg_body' => $item->msg_body,
			'time' => $item->infos[0]->time,
			'display_status' => $item->infos[0]->display_status,
			'user_from' => $user_from,
		);
		
		if(!empty($users)){
			$ret['users'] = $users;
		}
		return $ret;
	}
	
	public function convert_notification($item, $full = false) {
		$ret = $this->convert($item);

		$ret['user'] = $this->convert($item->user);
		$ret['page'] = $this->convert($item->page);
		$ret['activity'] = $this->convert_ticker($item->ticker);
		$ret['follow'] = $item->follow;
		$ret['display_time'] = $item->display_time;
		return $ret;
	}
	
	public function convert_ticker($item, $full = false) {
	//var_dump($item->activity);
		$ret = $this->convert($item);
		//if($item->activity != null){
			$ret['detail'] = @$this->convert($item->activity);
		//}
		return $ret;
	}
}