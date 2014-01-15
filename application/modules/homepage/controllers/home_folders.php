<?php
require_once 'application/modules/folder/controllers/folder.php';

class Home_Folders extends Folder {
	
	/**
	 * Popular collections in homepage. Also called on autoscroll
	 * @link /
	 */
	public function popular_folders() {
		$this->default_view = 'popular';
		$per_page = 20;
		$page = $this->input->get('page', true, 0);
		$folders = array();
		
		if($page == 0) {
			$ids = array();
			$editors = $this->user_model->filter_editors()->get_all();
			foreach($editors as $editor){
				$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('user_id'=>$editor->id));
				if ($folder) {
					$folders[] = $folder;
					$ids[] = $folder->folder_id;
				}
			}
				
			$this->session->set_userdata('popular_collections_1', $ids);
		} else {

			$ids = $this->session->userdata('popular_collections_1', array());

			$folders = $this->folder_model->join_users()->select_featured_fields()
				->filter_out($ids)
				->paginate($page, 20)
				->order_by('users_ranking DESC, folder.ranking', 'DESC')
				->get_many_by(array('newsfeeds_count > '=> 3,'folder.private' => '0'));

		}
		
		return parent::output($folders, '/popular_collections');
	}
	
	/**
	 * Loaded by homepage
	 */
	public function hashtag($sort_by='time', $filters=null) {

		$this->default_view = 'list_ugc';
		$this->default_sort = 'ranking desc';
		$folders = array();
		
		$editors = $this->user_model->filter_featured()->dropdown('id','id');
		$hashtags = $this->hashtag_model->top_hashtags()->get_all();
		foreach ($hashtags as $hashtag) {
			$folder = $this->get_model(1)->get_by(array(
							'hashtag_id'=>$hashtag->id,
							'private'=>'0', 
							'newsfeeds_count > ' => 4,
							'user_id' => $editors
						));
			if ($folder) $folders[] = $folder;
		}
		
		return $this->output($folders, '/home/folders/hashtag/');
	}
	
	/**
	 * Loaded by landing page
	 */
	public function ugc_top() {
		$folders = array();
		$this->default_view = 'ugc_top';
		/*
		$folders = array();
		$editors = $this->user_model->filter_editors()->dropdown('id','id');
		$ids = array(0);
		$watchdog = 100;
		while ($watchdog && count($folders) < 7) {
			$watchdog--;
			if (!$editors) break;
			$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('folder_id NOT'=>$ids,'user_id'=>$editors));
			if ($folder) {
				$ids[] = $folder->folder_id;
				if ($folder->latest_drop) {
					unset($editors[$folder->user_id]);
					$folders[] = $folder;
				}
			}
		}*/
		$role = isset($this->user->role) ? $this->user->role : 0;
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__.'_role_'.$role;
		if(!$this->cache->get($cache_name)) {
			$folders = $this->folder_model->order_by('updated_at','desc')->get_many_by(array('is_landing'=>1));
		}
		return parent::output($folders, '');
	}
	
	/**
	 * Loaded by landing page
	 */
	public function ugc_hashtags() {
		$folders = array();
		$this->default_view = 'ugc_hashtags';
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__.'_role_'.$role;
		
		if(!$this->cache->get($cache_name)) {
			$editors = $this->user_model->filter_featured()->dropdown('id','id');
			
			$hashtags = $this->hashtag_model->top_hashtags()->get_all();
			foreach ($hashtags as $hashtag) {
				$hashtag_folders = $this->folder_model
					//->order_by('ranking','DESC')
					->order_by('folder_id', 'DESC') //As discussed with Alexi
					->limit(4)
					->get_many_by(array('user_id'=>$editors, 'hashtag_id'=>$hashtag->id, 'private'=>'0', 'newsfeeds_count >='=>1));
					
				if (count($hashtag_folders) > 0) {
					$folders[] = array(
						'hashtag' => $hashtag,
						'folders' => $hashtag_folders
					);
				}
			}
		}
		return $this->output($folders, '');
	}
}