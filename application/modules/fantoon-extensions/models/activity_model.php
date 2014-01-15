<?php

class Activity_model extends MY_Model
{
	//relations
	protected $belongs_to = array(
								'folder',
								'user_from' => array('foreign_column'=>'user_id_from', 'foreign_model'=>'user'),
								'user_to' => array('foreign_column'=>'user_id_to', 'foreign_model'=>'user'),
							);
	protected $polymorphic_belongs_to = array(
											'activity' => array(
													'model_column' => 'type',
											)
										);
	protected $has_many = array(
							  'notifications' => array('foreign_column'=>'a_id', 'on_delete_cascade' => true),
						  );

	/* ========================== PER ITEM ============================= */
	
	
	/* =========================== SELECTS =========================== */
	
	public function sample() {
		return (object)array(
			"id"=>"",
			"af_type"=>"",
			"_activity"=>(object)array(
				"newsfeed_id"=>"",
				"_comment"=>(object)array(
					"newsfeed_id"=>""
				)
			),
			"thumb"=>"",
			"title"=>"",
			"user_id_from"=>"",
			"user_id_to"=>"",
			"user_from"=>(object)array(
				"url"=>"",
				"avatar_small"=>"",
				"full_name"=>"",
				"first_name"=>""
			),
			"type"=>"",
			"text"=>"",
			"time"=>"",
			"url"=>""
		);
	}
	
	public function jsonfy($limit=null) {
		$ret = array();
		$items = $this->get_all($limit);
		foreach ($items as $item) {
			$data = (object)array(
				"id" => $item->id,
				"af_type" => $item->af_type,
				"thumb" => $item->thumb,
				"title" => $item->title,
				"user_id_from" => $item->user_id_from,
				"user_id_to" => $item->user_id_to,
				"user_from"=>(object)array(
					"url" => $item->user_from->url,
					"avatar_small" => $item->user_from->avatar_small,
					"full_name" => $item->user_from->full_name,
					"first_name" => $item->user_from->first_name
				),
				"type" => $item->type,
				"text" => $item->text,
				"time" => Date_Helper::time_ago($item->time),
				"url" => $item->url,
			);

			if(isset($item->_activity->newsfeed_id) || isset($item->_activity->_comment) || strpos($item->url, '/drop/') !== false) {

				if(isset($item->_activity->newsfeed_id) && $item->_activity->newsfeed_id > 0) {
					$newsfeed_id = $item->_activity->newsfeed_id;
				} elseif(isset($item->_activity->_comment) && isset($item->_activity->_comment->newsfeed_id) && $item->_activity->_comment->newsfeed_id > 0) {
					$newsfeed_id = $item->_activity->_comment->newsfeed_id;
				} else {
					$parts = explode('/', $item->url);
					$newsfeed_id = $parts[(count($parts) - 1)];
				}
				// set newsfeed_id
				$data->newsfeed_id = $newsfeed_id;
			}
			$ret[] = $data;
		}
		return $ret;
	}
						  
	/* =========================== Filters ============================= */
	

	public function filter_type() {
		$this->db->where("(type = 'comment' OR type = 'like' OR (type = 'link' AND collect = '1') OR (type = 'photo' AND collect = '1'))");
		return $this;
	}

	public function user_id_from_in($arr) {
		$this->db->where_in('user_id_from', $arr);
		return $this;
	}
	
	/* ================================= EVENTS ============================== */
	
	protected function _run_before_delete($obj) {
		$this->refresh_cache($obj);
		return parent::_run_before_delete($obj);
	}
	
	protected function _run_after_set($data) {
		$this->refresh_cache($data);
		parent::_run_after_set($data);
	}

	public function _run_after_get($row) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->type) && isset($row->activity_id)) {
			$row->af_type = false;
			$row->_drop_type = false;
			$row->badge_active = false;
			$row->title = false;
			$row->url = false;
			$row->thumb = false;
			$row->text = false;
			if($row->type == 'connection' && isset($row->user_id_to)) {
				$user = $this->user_model->select_fields(array('id','avatar','uri_name','first_name','last_name'))->get($row->user_id_to);
				$row->thumb = $user->avatar_small;
				$row->url = $user->url;
				$row->title = false;
				$row->text = ' is following '.($row->user_id_to==$this->session->userdata('id')
											   ? 'you.'
											   : '<a href="'.$user->url.'">'.$user->full_name.'</a>'
											  );
			}
			elseif ($row->type == 'folder_user') {
				$folder = $this->folder_model->get($row->folder_id);
				$user = $this->user_model->select_fields(array('id','avatar','uri_name'))->get($folder->user_id);
				$row->thumb = @$user->avatar_small;
				$row->url = @$user->url;
				$row->title = false;
				$row->text = ' is now following folder <a href="'.$folder->get_folder_url().'">'.$folder->folder_name.'</a>';
			}
			elseif ( $row->type == 'link' || $row->type == 'photo' || $row->type == 'comment' || $row->type == 'like' || $row->type == 'newsfeed' || $row->type == 'collaboration_newsfeed') {
				if ($row->type == 'comment') {
					$comment = $row->activity;
					if ($comment->newsfeed_id > 0) {
						$newsfeed = $comment->newsfeed;
					}
					$action = ' commented ';
					$row->af_type = 'af_comment';
				} elseif($row->type == 'link' || $row->type == 'photo') {
					@$newsfeed = $row->activity->newsfeed;
					if ($row->collect == '0') {
						$action = ' added ';
						$row->af_type = 'af_drop';
					} else {
						$action = ' redropped ';
						$row->af_type = 'af_redrop';
					}
				} elseif($row->type == 'newsfeed' || $row->type == 'collaboration_newsfeed') {
					@$newsfeed = $row->activity;
					if($row->collect == '0') {
						$action = ' added ';
						$row->af_type = 'af_drop';
					} else {
						$action = ' redropped ';
						$row->af_type = 'af_redrop';
					}
				} elseif($row->type == 'like') {

					$like = $row->activity;
					$action = ' upvoted ';
					$row->af_type = 'af_upvote';

					if ($like->newsfeed_id > 0) {
						$newsfeed = $this->newsfeed_model->get($like->newsfeed_id);
					} elseif($like->comment_id > 0) {
						$comment = $like->comment;
						if (@$comment->newsfeed_id > 0) {
							$newsfeed = $comment->newsfeed;
						}
						$action = ' upvoted a comment on ';
					}
				}
				
				if (isset($newsfeed) && $newsfeed) {
					$row->drop_type = str_replace("tl","af",$newsfeed->_link_type_class);
					$row->thumb = @$newsfeed->img_bigsquare;
					$row->title = @$newsfeed->description;
					$row->url = @$newsfeed->url;
					
					$link_html = '<a href="#preview_popup" class="ticker_link_title link-popup" rel="popup" data-newsfeed_url="'.@$newsfeed->url.'" data-newsfeed_id="'.@$newsfeed->newsfeed_id.'" data-thumbnail="'.@$newsfeed->img_thumb.'" data-description="'.@str_replace('"', "'", $newsfeed->description).'">'.'a drop'.'</a>';
					
					$row->text = $action.$link_html.' in ';
					$folder = $this->folder_model->get(@$newsfeed->folder_id);

					if (isset($folder->folder_id)) {
						$row->text .= '<a href="'.$folder->get_folder_url().'">'.$folder->folder_name.'</a>';
					}

					if($row->thumb==false || $newsfeed->link_type == 'text') {
						$row->thumb = Url_helper::s3_url().'images/activity-text.png';
					}

				}
				elseif (isset($like->folder_id) && $like->folder_id ) {
					$folder = $this->folder_model->get(@$like->folder_id);
					$rows = $folder->get('newsfeeds')->with_thumbnail()->order_by('newsfeed_id','desc')->limit(1)->get_all();

					if ($rows) {
						$row->thumb =  $rows[0]->_img_square;
					} else {
						$row->thumb = Url_helper::s3_url().'images/activity-text.png';
					}
					$row->text = ' upvoted a list '. '<a href="' . $folder->get_folder_url() . '">' . $folder->folder_name . '</a>';
				}
			}
			elseif ($row->type == 'folder_contributor') {
				$folder = $this->folder_model->get($row->folder_id);
				$user = $this->user_model->select_fields(array('id','avatar','uri_name','first_name','last_name'))->get($row->user_id_to);
				$row->thumb = $user->avatar_small;
				$row->text = ' added <a href="'.$user->url.'">'.$user->first_name.'</a> as a collaborator in list <a href="'.$folder->get_folder_url().'">'.$folder->folder_name.'</a>';
			}			
			elseif ($row->type == 'badge') {
				$user = $this->user_model->select_fields(array('id','avatar','uri_name','first_name','last_name'))->get($row->user_id_from);
				$badge = $this->badge_model->get($row->activity_id);
				$row->thumb = $badge->img;
				$row->text = ' got a badge '.$badge->name;
			}
			else {
				//die('type not recognized: '.$row->type);
			}

		}
		else {
			//echo "MISS: ".$row->id."\r\n";
		}

		$row->testi = 'test';

	}
	
	private function refresh_cache($row) {
		$row = (Object) $row;
		$ci = get_instance();
		if (isset($row->user_id_to) && $ci->cache->get('home_activity_'.$row->user_id_to)) {
			$ci->cache->delete('home_activity_'.$row->user_id_to);
		}
		if (isset($row->user_id_to)) {
			//remove all user activity profile cache files
			for ($i=0;$i<=5;$i++) {
				$ci->cache->delete("activity_profile_{$row->user_id_to}_{$i}");
				$ci->cache->delete("activity_profile_{$row->user_id_from}_{$i}");
			}
		}
	}
	
}