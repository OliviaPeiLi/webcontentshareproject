<?php
require_once 'application/modules/folder/controllers/folder.php';

class Profile_folder extends Folder {
	
	protected $default_sort = 'folder_id DESC';

	public function collections($user_id, $is_profile = false) {
		if ($this->is_mod_enabled('design_ugc')) $this->default_view = 'list_ugc';

		$page = $this->input->get('page', true, 0)+1;
		$drop_type = $this->input->get('type', true);
		$role = isset($this->user->role) ? $this->user->role : 0;
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__."_user_{$user_id}_page_{$page}_type_{$drop_type}_role_{$role}";

		$per_page = $this->config->item('profile_folders_limit');
		$user = $this->user_model->get($user_id);

		if ($user->role == '4') {
			$this->default_sort = 'folder_id:desc';
		}

		if(!$this->cache->get($cache_name)) {
			$folder_model = $this->get_model($per_page);
			
			$folder_model = $folder_model->filter_user($user_id);

			if ( $user_id != @$this->user->id )	{
				$folder_list = $folder_model->get_many_by(array('private'=>0));
			}	else {
				$folder_list = $folder_model->get_all();	
			}
		} else $folder_list = array();

		return $this->output( $folder_list, '/'.$user->uri_name,array(),$is_profile);
	}
	
	public function upvotes($user_id, $is_profile = false) {

		if ($this->is_mod_enabled('design_ugc')) $this->default_view = 'list_ugc';

		$page = $this->input->get('page', true, 0)+1;
		$drop_type = $this->input->get('type', true);
		$role = isset($this->user->role) ? $this->user->role : 0;
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__."_user_{$user_id}_page_{$page}_type_{$drop_type}_role_{$role}";

		$per_page = $this->config->item('profile_folders_limit');
		$user = $this->user_model->get($user_id);

		if ($user->role == '4') {
			$this->default_sort = 'folder_id:desc';
		}

		if(!$this->cache->get($cache_name)) {
			$folder_model = $this->get_model($per_page);
			$folder_model = $folder_model->filter_user($user_id);
//			$folder_model = $folder_model->upvotes();

			if ( $user_id != @$this->user->id )	{
				$folder_list = $folder_model->get_many_by(array('private'=>0));
			}	else {
				$folder_list = $folder_model->get_all();	
			}
		} else $folder_list = array();

		return $this->output( $folder_list, '/upvotes/'.$user->uri_name,array(),$is_profile);
	}
	
	public function mentions($user_id, $is_profile = false) {

		if ($this->is_mod_enabled('design_ugc')) $this->default_view = 'list_ugc';

		$page = $this->input->get('page', true, 0)+1;
		$drop_type = $this->input->get('type', true);
		$role = isset($this->user->role) ? $this->user->role : 0;
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__."_user_{$user_id}_page_{$page}_type_{$drop_type}_role_{$role}";

		$per_page = $this->config->item('profile_folders_limit');
		$user = $this->user_model->get($user_id);

		if ($user->role == '4') {
			$this->default_sort = 'folder_id:desc';
		}

		if(!$this->cache->get($cache_name)) {
			$folder_model = $this->get_model($per_page);
			$folder_model = $folder_model->filter_user($user_id);
			$folder_model = $folder_model->by_mentions();

			if ( $user_id != @$this->user->id )	{
				$folder_list = $folder_model->get_many_by(array('private'=>0));
			}	else {
				$folder_list = $folder_model->get_all();	
			}
		} else $folder_list = array();

		return $this->output( $folder_list, '/upvotes/'.$user->uri_name,array(),$is_profile);
	}


	public function contest_collections($contest_id) {

		$per_page = $this->config->item('profile_folders_limit');

		$page = $this->input->get('page', true, 0)+1;
		$drop_type = $this->input->get('type', true);
		$role = isset($this->user->role) ? $this->user->role : 0;
		$this->cache_name = $cache_name =  __CLASS__.'_'.__FUNCTION__."_user_{$user_id}_page_{$page}_type_{$drop_type}_role_{$role}";

		$this->default_sort = 'share_count:desc';
		
		if(!$this->cache->get($cache_name)) {
			$folder_model = $this->get_model($per_page, false);
			$folder_model->select_fields(array(
				'(SELECT SUM('.$this->newsfeed_model->share_count.') FROM newsfeed WHERE newsfeed.folder_id = folder.folder_id) as share_count'
			));
			
			$folder_model = $folder_model->filter_contest($contest_id);
			$folder_list = $folder_model->get_all();
		} else $folder_list = array();

		return $this->output( $folder_list, '/profile_folder/contest_collections/'.$contest_id);

	}
	
}