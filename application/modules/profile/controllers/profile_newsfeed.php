<?php
require_once 'application/modules/newsfeed/controllers/newsfeed.php';

class Profile_newsfeed extends Newsfeed {
	protected $default_sort = 'newsfeed_id';
	protected $default_view = 'tile_new';
	
	public function drops($user_id, $filter=null) {
		$view = $this->input->get('view', true, $this->default_view);
		$per_page = $this->config->item($view.'_newsfeed_limit');
		$per_page = $per_page ? $per_page : 12;
		$this->default_filter = $filter;

		$user = $this->user_model->get($user_id);
		$newsfeed_model = $this->get_model($per_page)->filter_user($user_id);

		return $this->output($newsfeed_model, '/drops/'.$user->uri_name);
	}
	
	public function upvotes($user_id, $filter=null) {
		$this->default_view = $this->is_mod_enabled('design_ugc') ? 'ugc' : 'tile_new';
		$per_page = $this->config->item($this->default_view.'_newsfeed_limit');
		$per_page = $per_page ? $per_page : 12;
		$this->default_filter = $filter;
				
		$user = $this->user_model->get($user_id);
		$newsfeed_model = $this->get_model($per_page)->filter_user_likes($user_id);
		return $this->output($newsfeed_model, '/upvotes/'.$user->uri_name);
		
	}
	
	public function mentions($user_id, $filter=null) {
		$this->default_view = $this->is_mod_enabled('design_ugc') ? 'ugc' : 'tile_new';
		$per_page = $this->config->item($this->default_view.'_newsfeed_limit');
		$per_page = $per_page ? $per_page : 12;
		$this->default_filter = $filter;
		
		$user = $this->user_model->get($user_id);
		$newsfeed_model = $this->get_model($per_page)->filter_user_mentions($user_id);
		return $this->output($newsfeed_model, '/mentions/'.$user->uri_name);
	}
	
	protected function output($newsfeed_model, $url) {
		
		$get = array();
		if ($this->default_filter) $get[] = 'type='.$this->default_filter;
		
		return parent::output($newsfeed_model, $url, $get);
	}

	// Quang: profile top has 8 items, load 7th item if delete
	// return empty if no more drop
	public function feature_drops($limit = 8) {
		$user = $this->user;
		$feature_drops = $this->user_model->get_feature_drops($user, $limit);
		if ( ! isset($feature_drops[$limit-1])) {
			echo '';
			return ;
		}
		
		$this->load->view('profile/profile_top_element', array(
			'newsfeed' => $feature_drops[$limit-1]
		));
	}
	
	
}
