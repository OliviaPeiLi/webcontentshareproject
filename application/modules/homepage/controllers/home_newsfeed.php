<?php
/**
 * This file is for the newsfeed in homepage and landing page
 * @author radilr
 *
 */
require_once 'application/modules/newsfeed/controllers/newsfeed.php';

class Home_newsfeed extends Newsfeed {

	/**
	 * Returns newsfeeds sorted by time added
	 */
	public function recent($sort_by='time', $filter=null) {

		$view = $this->input->get('view', true, $this->default_view);
		$per_page = $this->config->item($view.'_newsfeed_limit', true, 20);
		$this->default_sort = $sort_by;
		$this->default_filter = $filter;
				
		if ($this->session->userdata('id')) {
			$check_folders = array_merge(
								 array_keys($this->folder_model->_set_where(array("user_id = {$this->user->id}"))->dropdown()),
								 $this->folder_user_model->_set_where(array("user_id = {$this->user->id}"))->dropdown('folder_id', 'folder_id')
							 );
		}
							 
		$newsfeed = $this->get_model($per_page);
		
		if ($this->session->userdata('id')) {
			$newsfeed = $newsfeed->folder_id_in($check_folders);
		}
		
		return $this->output($newsfeed, 'recent');
	}
	
	/**
	 * Returns newsfeeds sorted by popularity using the rank field
	 * rank field is calculated by the newsfeed_ranking async script
	 */
	public function popular($sort_by='time', $filter=null) {
		if ($this->is_mod_enabled('hashtag_landingpage_newsfeed')) return $this->hashtag($sort_by, $filter);
	
		$view = $this->input->get('view', true, $this->default_view);
		$per_page = $this->config->item($view.'_newsfeed_limit', true, 20);
		$this->default_sort = $sort_by;
		$this->default_filter = $filter;
		
		$newsfeeds = $this->get_model($per_page);
		
		return $this->output($newsfeeds, 'popular');
	}
	
	/**
	 * Returns 10 newsfeeds by each popular hashtag sorted by popularity
	 */
	public function hashtag($sort_by='time', $filter=null) {

		$newsfeeds = array();

		$this->default_sort = $sort_by;
		$this->default_filter = $filter;

		$hashtags = $this->hashtag_model->top_hashtags()->dropdown();
		foreach($hashtags as $hashtag_id=>$hashtag){
			$hashtag_newsfeed = $this->get_model(1)->get_by(array('newsfeed.hashtag_id'=>$hashtag_id));
			if($hashtag_newsfeed){
				$newsfeeds[] = $hashtag_newsfeed;
			}
		}
		
		$last_newsfeed_id = $this->input->get('last_newsfeed_id');

		$other_newsfeeds = array();

		if( count($newsfeeds) < 11 ) {

			$other_newsfeeds = $this->get_model()->limit(10-count($newsfeeds));			
			
			if($last_newsfeed_id) {
				$other_newsfeeds = $other_newsfeeds->get_many_by(array('newsfeed.hashtag_id'=>0, 'newsfeed_id <'=>$last_newsfeed_id));
			} else {
				$other_newsfeeds = $other_newsfeeds->get_many_by(array('newsfeed.hashtag_id'=>0));
			}
			
			$last_newsfeed_id = end($other_newsfeeds)->newsfeed_id;
		}
				
		return $this->output(array_merge($newsfeeds, $other_newsfeeds), 'hashtag', $last_newsfeed_id);
	}
	
	protected function get_model($per_page=0) {
		$newsfeed_model = parent::get_model($per_page);
		
		$newsfeed_model = $newsfeed_model->filter_complete();
		
		return $newsfeed_model;
	}
	
	protected function output($newsfeeds, $category, $last_newsfeed_id='') {
		$url = '/homepage/newsfeed/'.$category;
		
		$get = array();
		$last_newsfeed_id = $last_newsfeed_id ? $last_newsfeed_id : $this->input->get('last_newsfeed_id');
		if ($last_newsfeed_id) $get[] = 'last_newsfeed_id='.$last_newsfeed_id;
		if ($this->default_sort) $get[] = 'sort_by='.$this->default_sort;
		if ($this->default_filter) $get[] = 'type='.$this->default_filter;
		
		return parent::output($newsfeeds, $url, $get);
	}
	
}