<?php
class Api_objects
{

	public function convert($item, $full = false)
	{
		if (!$item) return new stdClass();
		if (is_array($item)) {
			$ret = array();
			foreach ($item as $_item) {
				$ret[] = $this->convert($_item, $full);
			}
			return $ret;
		}
		if ($item->_model) {
			if ($item->_model instanceof User_model && $full && $item->id == get_instance()->user->id) {
				$func = 'convert_me';
			} else {
				$func = 'convert_'.str_replace('_model', '', get_class($item->_model));
			}
			if (method_exists($this, $func)) {
				return $this->$func($item, $full);
			}
		}
		return $item->as_array();
	}

	public function convert_connection($item, $full = false) {
		$ci = get_instance();
		if ($ci->item()->id == $item->user1_id) {
			return array(
				'id' => $item->id,
				'user' => $this->convert($item->user2)
			);
		} else {
			return array(
				'id' => $item->id,
				'user' => $this->convert($item->user1)
			);
		}
	}

	public function convert_user($item, $full = false)
	{
		if ($full == -1) {
			return array(
				'id' => $item->id,
				'full_name' => $item->full_name,
				'avatar' => $item->avatar_73,
			);
		}
		$ci = get_instance();
		if ($full) {
			$ret = array(
					'id' => $item->id,
					'full_name' => $item->full_name,
					'about' => $item->about,
					'thumb' => $item->avatar_73,
					'fb_id' => $item->fb_id,
					'twitter_id' => $item->twitter_id,
					'stats' => $this->convert($item->user_stat, $full),
					'folders' => $this->convert($item->get('folders')->order_by('folder_id','desc')->get_all(10)),
				);
		} else {
			$ret = array(
					'id' => $item->id,
					'full_name' => $item->full_name,
					'thumb' => $item->avatar_73,
					'stats' => $this->convert($item->user_stat, $full),
				);
		}
		if ($ci->user) {
			$ret['is_following'] = $ci->user->is_following($item);
		}
		return $ret;
	}
	
	public function convert_me($item, $full = true) {
		$ret = array(
				'id' => $item->id,
				'full_name' => $item->full_name,
				'about' => $item->about,
				'thumb' => $item->avatar_73,
				'fb_id' => $item->fb_id,
				'twitter_id' => $item->twitter_id,
				'stats' => $this->convert($item->user_stat, $full),
				'folders' => $this->convert($item->get('folders')->order_by('folder_id','desc')->get_all(10)),
			);
		return $ret;
	}
	
	public function convert_user_stats($item, $full = false) {
		if ($full) {
			return array(
				'lists' => $item->user_id == get_instance()->user->id ? $item->collections_count : $item->public_collections_count,
				'drops' => $item->drops_count,
				'upvotes' => $item->upvotes_count,
				'mentions' => $item->mentions_count,
				'contents' => $item->contents_count,
				'followings' => $item->followings_count,
				'followers' => $item->followers_count,
			   );
		} else {
			return array(
				'lists' => $item->public_collections_count,
				'drops' => $item->drops_count,
				'upvotes' => $item->upvotes_count,
			   );
		}
	}

	public function convert_folder($item, $full = false)
	{
		if ($full == -1) {
			
			$ret = array(
				'folder_id' => $item->folder_id,
				'folder_name' => $item->folder_name,
				'upvotes_count' => $item->upvotes_count,
				'private'=> $item->private
			);

			if (!isset($item->recent_newsfeeds[0])) return $ret;

			if ($item->recent_newsfeeds[0]->link_type == 'text') {
				$ret['text'] = $item->recent_newsfeeds[0]->text;
			} else {
				$ret['thumb'] = $item->recent_newsfeeds[0]->img_320;
				$ret['mobile_thumb'] = $item->recent_newsfeeds[0]->_img_75;
			}

			return $ret;
		}
			$ci = get_instance();
			$ret = array(
				'folder_id' => $item->folder_id,
				'folder_name' => $item->folder_name,
				'folder_url' => $item->folder_url,
				'user' => $this->convert($item->user, false),
				'private' => $item->private,
				'is_open' => $item->is_open,
				'rss_source' => $item->rss_source,
				'hashtag' => $this->convert($item->hashtag),
				'hashtags' => $this->convert($item->hashtags),
				'sort_by' => $item->sort_by,
				'folder_contributors' => $item->folder_contributors,
				'is_liked' => $item->is_liked($ci->user),
				'upvotes_count' => $item->upvotes_count,
				'comments_count' => $item->comments_count,
				'is_in_progress' => $item->is_in_progress(),
				'newsfeeds_count' => $item->newsfeeds_count,
				'total_hits' => $item->total_hits,
				'recent_newsfeeds' => $item->recent_newsfeeds,
				'is_following' => $item->is_followed($ci->user),
			);

			foreach ($ret['recent_newsfeeds'] as & $newsfeed) {
				unset($newsfeed->_model);
			}

		return $ret;
	}
	
	public function convert_hashtag($item, $full = false) {
		if ($full == -1) {
			return array(
				'id' => $item->id,
				'hashtag' => $item->hashtag_name,
			);
		} else if ($full) {
			return array(
				'id' => $item->id,
				'hashtag' => $item->hashtag_name,
				'count' => $item->count,
			);
		} else {
			return array(
				'id' => $item->id,
				'hashtag' => $item->hashtag_name,
			);
		}
	}
	
	public function convert_mention($item, $full = false) {
		if ($full) {
			return $item->as_array();
		} else {

			$folder = $item->folder_id ? $this->convert($item->folder,true) : new stdClass();
			return $folder;

/*			return array(
				"folder_id"=>$folder['folder_id'],
				"folder_name"=>$folder['folder_name'],
				'upvotes_count'=>$folder['upvotes_count'],
				'thumb'=> ( count($folder['recent_newsfeeds']) > 0  && $folder['recent_newsfeeds'][0]->link_type != 'text' ? Html_helper::img_src($folder['recent_newsfeeds'][0]->_img_320) : '' ),
				'text'=> ( count($folder['recent_newsfeeds']) > 0  && $folder['recent_newsfeeds'][0]->link_type == 'text' ? $folder['recent_newsfeeds'][0]->description_plain : '')
			);*/
		}
	}
	
	public function convert_folder_user($item, $full = false) {
		return array(
			'id' => $item->id,
			'folder' => $this->convert($item->folder),
			'user' => $this->convert($item->user)
		);
	}

	public function convert_folder_contributor($item, $full = false) {
		return array(
			'id' => $item->id,
			'folder' => $this->convert($item->folder),
			'user' => $this->convert($item->user)
		);
	}

	public function convert_email_settings($item, $full = false) {
		return array(
				   'message' => $item->message,
				   'comment' => $item->comment,
				   'up_link' => $item->up_link,
				   'reply' => $item->reply,
				   'up_comment' => $item->up_comment,
				   'connection' => $item->connection,
				   'follow_folder' => $item->follow_folder,
			   );
	}

	public function convert_user_info($item, $full = false) {
		return array(
				   'user_id' => $item->id,
				   'user_name' => $item->first_name.' '.$item->last_name,
				   'thumb' => $item->avatar_42
			   );
	}

	public function convert_user_school($item, $full = false) {
		$ret = $this->convert($item);
		$ret['school'] = $this->convert($item->school);
		return $ret;
	}

	public function convert_newsfeed($item, $full = false) {

		$ci = get_instance();

		$data = array(
				   'newsfeed_id' => $item->newsfeed_id,
				   //'data' => $item->data,
				   'description' => $item->description,
					'img_thumb' => $item->_img_75,
					'img_thumb_big'=> $item->_img_320,
					'img_width' => $item->img_width,
					'img_height' => $item->img_height,
					'liked' => $item->is_liked($ci->user),
					'upvotes_count'=>$item->up_count,
				   'type' => $item->type,
					'activity_id'=> $item->activity_id,
					'link_type' => $item->link_type,
				   'time' => $item->time,
				   'time_ago' => Date_Helper::time_ago($item->time),
				   'user_id_from' => $item->user_id_from,
				   'folder' => $this->convert($item->folder),
				   'like_count' => $item->up_count,
				   'comment_count' => $item->comment_count,
				   'collect_count' => $item->collect_count,
				   'user_from' => $this->convert($item->user_from),
			   );
		
		if ($item->link_type == 'text') {
			$data['content'] = $item->activity->content;
		} elseif ($item->link_type == 'media') {
			$data['media'] = $item->activity->media;
		}
		return $data;
	}

	public function convert_activity($item, $full = false) {
		if (!$item) return ;
		if(isset($item->link_id))
		{
			$ret = array(
					   'link_id'=>$item->link_id,
					   'user_id_from'=>$item->user_id_from,
					   'user_id_to'=>$item->user_id_to,
						'link'=>$item->link,
						'media'=>$item->media,
					   'title'=>$item->title,
					   'text'=>$item->text,
					   'time'=>$item->time,
					   'source'=>$item->source,
				   );
			
			return $ret;
		}
		if(isset($item->photo_id))
		{
			return $item->as_array();
		}
	}

	public function convert_user_from($item, $full = false)
	{
		return array_merge($item->as_array(), array(
							   'thumb' => $item->avatar_73
						   ));
	}

	public function convert_comment($item, $full = false)
	{
		$ci = get_Instance();
		$ret = $item->as_array();
		$ret['user_from'] = $this->convert($item->user_from);
		$ret['user_to'] = $this->convert($item->user_to);
		$ret['is_liked'] = $item->is_liked($ci->user);
		unset($ret['parent_id']); //not supported right now
		return $ret;
	}

	public function convert_like($item, $full = false)
	{
		$ret = $item->as_array();

		//$ret['newsfeed'] = $item->newsfeed_id ? $this->convert($item->newsfeed) : new stdClass();
		//$ret['comment'] = $item->comment_id ? $this->convert($item->comment) : new stdClass();
		return $item->folder_id ? $this->convert($item->folder,true) : new stdClass();
		//$ret['user_from'] = $this->convert($item->user_from);
		///$ret['user_to'] = $this->convert($item->user_to);

/*		return array(
			"folder_id"=>$ret['folder']['folder_id'],
			"folder_name"=>$ret['folder']['folder_name'],
			'upvotes_count'=>$ret['folder']['upvotes_count'],
			'thumb'=> ( count($ret['folder']['recent_newsfeeds']) > 0  && $ret['folder']['recent_newsfeeds'][0]->link_type != 'text' ? Html_helper::img_src($ret['folder']['recent_newsfeeds'][0]->_img_320) : '' ),
			'text'=> ( count($ret['folder']['recent_newsfeeds']) > 0  && $ret['folder']['recent_newsfeeds'][0]->link_type == 'text' ? $ret['folder']['recent_newsfeeds'][0]->description_plain : '')
		);*/

		return $ret;
	}

	public function convert_message($item, $full = false)
	{
		$user_to = array();
		$user_from = array();
		foreach ($item->infos as $info)
		{
			$user_to[] = array(
							 'id' => $info->user_to->id,
							 'uri_name' => $info->user_to->uri_name,
							 'full_name' => $info->user_to->full_name,
							 'thumbnail' => $info->user_to->avatar_73,
						 );
		}
		$user_from = array(
						 'id' => $item->infos[0]->user_from->id,
						 'uri_name' => $item->infos[0]->user_from->uri_name,
						 'full_name' => $item->infos[0]->user_from->full_name,
						 'thumbnail' => $item->infos[0]->user_from->avatar_73,
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


	public function convert_message_inbox($item, $full = false)
	{
		$users = array();
		
		$msg_info = isset($item->msg_infos)  ? $item->msg_infos : $item->msg_info;
		
		$ret = array(
				   'id' => $item->id,
				   'thread_id' => $item->thread_id,
				   'msg_id' => $item->msg_id,
				   'users' => $item->users,
				   'msg_body' => nl2br(Text_Helper::character_limiter_strict($msg_info->message->msg_body, 70))
			   );
		return $ret;
	}

	public function convert_message_thread($item, $full = false)
	{
		//return $this->convert($item); // <= TO-DO

		$user_from = array(
						 'id' => $item->infos[0]->user_from->id,
						 'uri_name' => $item->infos[0]->user_from->uri_name,
						 'full_name' => $item->infos[0]->user_from->full_name,
						 'thumb' => $item->infos[0]->user_from->avatar_73
					 );

		$users = array();
		foreach ($item->msg_thread->users_data as $user)
		{

			$users[] = $this->convert($user);
		}

		$ret = array(
				   'msg_id' => $item->msg_id,
				   'thread_id'=> $item->thread_id,
				   'msg_body' => $item->msg_body,
				   'time' => $item->infos[0]->time,
				   'display_status' => $item->infos[0]->display_status,
				   'user_from' => $user_from,
			   );

		if(!empty($users))
		{
			$ret['users'] = $users;
		}
		return $ret;
	}

	public function convert_notification($item, $full = false) {
		$ret = array(
			'id' => $item->id,
			'user' => $item->user,
			'type' => $item->type,
			'read' => $item->read,
			'time' => $item->time,
		);
		foreach ($item->cache as $item=>$val) {
			$ret[$item] = $val;
		}
		
		if ($full) {
			$ret['item'] = $item->item;
		}
		
		return $ret;
	}

}