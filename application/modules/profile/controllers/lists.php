<?php
class Lists extends MX_Controller {
	
	protected function template($view, $data=array()) {
		$folders = $this->user->get('folders')->order_by('position asc, folder_name asc','')->get_many_by(array('type'=>0));
		return parent::template("profile/lists/container_ugc", array_merge(array(
			'view' => $view,
			'folders' => $folders,
		), $data));
	}
	
	public function index() {
		if ($this->input->is_ajax_request()) {
			$this->folder_model;
			$folder_model = new Folder_model();
			$folder_model = $folder_model->with('user');
			$folder_model = $folder_model->select_list_fields();
			$folder_model = $folder_model->filter_user($this->user->id);
			$folder_model = $folder_model->order_by('updated_at', 'desc');
			
			$page = $this->input->get('page', true, 0)+1;
			$folder_model = $folder_model->paginate($page, 18);
			
			header( 'Content-Type: application/json' );
			echo json_encode( $this->folder_model->jsonfy( $folder_model->get_all() ) );
			return ;
		}
		return self::template("profile/lists/index");
	}
	
	public function create($folder_id='') {
		$folder = $this->folder_model->get($folder_id);
		if ($this->input->post()) {
			$post = $this->input->post();
			if ($data = $this->folder_model->validate($post)) {
				$hashtag_id = 0; $hashtag_ids = array();
				$top = array_flip($this->hashtag_model->top_hashtags()->dropdown('id','hashtag'));
				if (isset($post['folder_hashtags'])) {

					foreach ($post['folder_hashtags'] as $hashtag) {
						$hashtag_row = $this->hashtag_model->get_by(array('hashtag'=>str_replace('#', '_hash_', $hashtag)));
						if (!$hashtag_row) {
							$hashtag_id = $this->hashtag_model->insert(array('hashtag'=>str_replace('#', '_hash_', $hashtag)));
						} else {
							$hashtag_id = $hashtag_row->id;
						}
						$hashtag_ids[] = $hashtag_id;
						if (!isset($data['hashtag_id']) && isset($top[$hashtag])) {
							$data['hashtag_id'] = $top[$hashtag];
						}
					}
				}
				if (!isset($data['hashtag_id'])) $data['hashtag_id'] = $hashtag_id;
			
				$user_id = $folder ? $folder->user_id : $this->user->id;
				$post['position'] = 0;
				if (isset($post['after']) && $post['after']) {
					$pos = mysql_fetch_object(mysql_query("SELECT position FROM folder WHERE folder_id = ".$post['after']))->position;
					$post['position'] = $pos+1;
				}
				mysql_query("UPDATE folder SET position = position + 1 WHERE user_id = $user_id AND position >= ".$post['position']);
				
				if ($folder && $folder->can_edit($this->user)) {
					$folder->update($data);
					$id = $folder->folder_id;
				} else {
					$data['private'] = 1;
					$id = $this->folder_model->insert($data);
				}
				
				$this->folder_hashtag_model->delete_by(array('folder_id'=>$id));
				foreach ($hashtag_ids as $hashtag_id) {
					$this->folder_hashtag_model->insert(array('folder_id'=>$id, 'hashtag_id'=>$hashtag_id));
				}
				
				$folder = $this->folder_model->get($id);
				die(json_encode(array('status'=>true,'folder_id'=>$folder->folder_id)));
			} else {
				die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
			}
		}

		return self::template("profile/lists/create", array(
			'folder' => $folder
		));
	}
	
	public function update($folder_id) {
		$folder = $this->folder_model->get($folder_id);
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$per_page = 18;
		$page = $this->input->get('page',true, 0)+1;
		
		$newsfeeds = $this->newsfeed_model->select_list_fields()
						->order_by('position asc, newsfeed_id desc','')
						->paginate($page, $per_page)
						->get_many_by('folder_id', $folder->folder_id);
						
		if ($this->input->is_ajax_request()) {
			header( 'Content-Type: application/json' );
			echo json_encode( $this->newsfeed_model->jsonfy( $newsfeeds ) );
			return ;
		}

		$cover_newsfeed_id = false;

		if (count($folder->recent_newsfeeds) > 0 )	{
			$cover_newsfeed_id = $folder->recent_newsfeeds[0]->newsfeed_id;
		}
						
		return self::template("profile/lists/update", array(
			'folder' => $folder,
			'cover_newsfeed_id'=>$cover_newsfeed_id,
			'newsfeeds' => $newsfeeds,
			'per_page' => $per_page
		));
	}
	
	public function resort() {
		$ids = $this->input->post('folder_id');
		
		//update non loaded folders
		mysql_query("UPDATE folder SET `position` = `position` + ".count($ids)." 
					WHERE user_id = {$this->user->id} AND folder_id NOT IN (".implode(',', $ids).")");
		
		foreach ($ids as $pos=>$id) {
			mysql_query("UPDATE folder SET `position` = $pos WHERE user_id = {$this->user->id} AND folder_id = ".$id);
		}
		
		die(json_encode(array('status'=>true)));
	}
	
	public function unpublish($folder_id) {
		$folder = $this->folder_model->get($folder_id);
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$folder->update(array('private'=>1));
		$this->db->query("UPDATE user_stats SET 
							public_collections_count = public_collections_count - 1, 
							private_collections_count = private_collections_count + 1 
						WHERE user_id = ".$this->user->id);
		Url_helper::redirect('/manage_lists/'.$folder->folder_id);
	}
	
	public function publish($folder_id) {
		$folder = $this->folder_model->get($folder_id);
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$folder->update(array('private'=>0));
		$this->db->query("UPDATE user_stats SET 
							public_collections_count = public_collections_count + 1, 
							private_collections_count = private_collections_count - 1 
						WHERE user_id = ".$this->user->id);
		Url_helper::redirect('/manage_lists/'.$folder->folder_id);
	}
	
	public function set_as_cover($newsfeed_id) {
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);
		if (!$newsfeed->folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$conf = $newsfeed->_model->behaviors['cachable'][0];
		
		array_unshift($newsfeed->folder->_recent_newsfeeds, Cachable_Behavior::create_item($conf['data'], $newsfeed));
		for ($i=1;$i<count($newsfeed->folder->_recent_newsfeeds);$i++) {
			if ($newsfeed->folder->_recent_newsfeeds[$i]->newsfeed_id == $newsfeed_id) {
				unset($newsfeed->folder->_recent_newsfeeds[$i]);
			}
		}
		Cachable_Behavior::update_data($conf, $newsfeed, $newsfeed->folder->_recent_newsfeeds);
		
		die(json_encode(array('status'=>true)));
	}
	
	public function delete($folder_id) {

		$folder = $this->folder_model->get($folder_id);
		
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$folder->delete();
		$this->notification_model->delete_by(array("folder_id"=>$folder_id));

		if ($this->input->is_ajax_request()) {
			die(json_encode(array('status'=>true,'redirect_url'=>'/manage_lists')));
		} else {
			Url_helper::redirect('/manage_lists');
		}
		
	}
	
}