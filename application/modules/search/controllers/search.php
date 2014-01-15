<?php
class Search extends MX_Controller {

	public function index() {

		$keyword = $this->input->get('q', true);
		$results = array();

		if ($keyword[0] == '#') { //http://dev.fantoon.com:8100/browse/FD-3930
			$hashtags = $this->hashtags($keyword);
			$results = array_merge($results, $hashtags);
			
		} else {

			if ($all = $this->search_all($keyword)) {
				// search all tables at once
				$results = array_merge($results, $all);
			} else {
				// search table separately
				if ($people = $this->people($keyword)) {
					$results[] = array("id" => '-1', "name" => 'People', "type" => 'heading');
					$results = array_merge($results, $people);
				}

				if ($collections = $this->collections($keyword)) {
					$results[] = array("id" => '-1', "name" => 'Collections', "type" => 'heading');
					$results = array_merge($results, $collections);
				}

				if ($hashtags = $this->hashtags($keyword)) {
					$results[] = array("id" => '-1', "name" => 'Hashtags', "type" => 'heading');
					$results = array_merge($results, $hashtags);
				}

				if ($drops = $this->drops($keyword)) {
					$results[] = array("id" => '-1', "name" => 'Drops', "type" => 'heading');
					$results = array_merge($results, $drops);
				}
			}
		}
		
		
		header('Content-type: application/json');
		echo json_encode($results);
	}
	
	public function main() {
		if ($this->is_mod_enabled('design_ugc')) {
			$keyword = $this->input->get('q', true);
			return parent::template('search/search_ugc', array(
				'keyword' => $keyword,
				'hide_footer'=>true
			));
		} else {
			$this->drops_search();
		}
	}
	
	public function hashtag($hashtag, $filter=null) {
		$_GET['q'] = '#'.$hashtag; //for collections
		
		if ($this->is_mod_enabled('design_ugc')) {
			return $this->main();
		}
		
		
		$hashtag = $this->hashtag_model->get_by(array('hashtag'=>'_hash_'.$hashtag));
		
		$page = $this->input->get('page',true,0)+1;
		$per_page = $this->config->item('drop_limit');
		
		$type = $this->input->get('type',true, $filter);
		
		$newsfeeds = $this->newsfeed_model->order_by('news_rank','desc')->paginate($page, $per_page);
		$newsfeeds = $newsfeeds->filter_hashtag($hashtag);
		
		if ($type) {
			$newsfeeds = $newsfeeds->filter_type($type);
		}
		
		if ($this->input->is_ajax_request()) {
			header( 'Content-Type: application/json' );
			echo json_encode( $newsfeeds->jsonfy() );
		} else {
			$newsfeeds = $newsfeeds->get_all();
			return parent::template('search/search_results', array(
				'search_category' => 'drops',
				'keyword' => $hashtag->hashtag_name,
				'has_results' => (bool) $newsfeeds,
				'newsfeeds' => $newsfeeds,
				'view' => 'postcard',
				'per_page' => $per_page,
				//'url' => $url,
				//'folder_info' => $folder_info
			), 'Fandrop - Search Results For '. $hashtag->hashtag_name);
		}
	}
	
	public function drops_search($filter=null) {

		//$this->output->enable_profiler(true);
		$keyword = trim($this->input->get('q',true));
		$keyword = preg_replace('/[^a-zA-Z0-9\'\s@#]/','',$keyword);
		if(!$keyword) {
			return Url_helper::redirect('/');
		}
		$page = $this->input->get('page',true,0)+1;
		$per_page = $this->config->item('drop_limit');
		
		$type = $this->input->get('type',true, $filter);
		$view = $this->input->get('view', true, 'postcard');
		
		$newsfeed_model = $this->newsfeed_model;
		$newsfeed_model = new Newsfeed_model();
		
		$newsfeed_model->try_solr();
		
		$newsfeeds = $newsfeed_model->select_search_fields(str_replace('#', '_hash_', $keyword))
						->search(str_replace('#', '_hash_', $keyword))
						->paginate($page, $per_page)->order_by('relevance', 'DESC');
			
		if ($type) {
			$newsfeeds = $newsfeeds->filter_type($type);
		}
		
		if ($this->input->is_ajax_request()) {
			header( 'Content-Type: application/json' );
			echo json_encode( $newsfeeds->jsonfy() );
		} else {
			$url = '/search/drops';
			$get = array('q='.urlencode($this->input->get('q',true)));
			if ($type) $get[] = 'type='.$type;
			$url .= '?'.implode('&', $get);
			
			if ($newsfeeds) {
				$newsfeeds = $newsfeeds->get_all();
			}
			
			$folder_info = FALSE;
			if ($this->input->get("folder_id"))	{
				$folder_info = $this->folder_model->get($this->input->get("folder_id"));
			}
						
			return parent::template('search/search_results', array(
				'search_category' => 'drops',
				'keyword' => $keyword,
				'has_results' => (bool) $newsfeeds,
				'newsfeeds' => $newsfeeds,
				'view' => $view,
				'per_page' => $per_page,
				'url' => $url,
				'folder_info' => $folder_info
			), 'Fandrop - Search Results For '. $keyword);
			
	   }
	   
	}
	
	public function collections_search($show_header = false) {

		$keyword = $this->input->get('q',true);
		$keyword = str_replace('#', '_hash_', $keyword);
		if (!$keyword) {
			return Url_helper::redirect('/');
		}
		
		$page = $this->input->get('page', 0)+1;
		$per_page = $this->config->item('collections_page_limit');
		
		$this->folder_model;
		$folder_model = new Folder_model();
		$folders = $folder_model->join_users()->select_list_fields()->search($keyword)
						->paginate($page, $per_page)->order_by('folder_id', 'desc')
						->get_many_by(array('newsfeeds_count >= ' => 4));
		
		if ($this->input->is_ajax_request()) {

			foreach ($folders as &$folder) {
				# code...
				$folder->str_time = date("F d",strtotime($folder->updated_at));
				$folder->full_name = $folder->{'users:first_name'} . " " . $folder->{'users:last_name'};
				$folder->avatar = $folder->user->avatar_42;
				$folder->is_shared_fb = $folder->is_shared(get_instance()->user, 'fb');
				$folder->is_shared_twitter = $folder->is_shared(get_instance()->user, 'twitter');
				 $folder->hashtags = array();

				foreach ($folder->folder_hashtags as $i=>$folder_hashtag) { 
					if (!$folder_hashtag->hashtag) continue;
					$folder->hashtags[] = $folder_hashtag->hashtag->hashtag;
				}
			}

			header( 'Content-Type: application/json' );
			die(json_encode( $folders ));
		} else {
			$template = $this->is_mod_enabled('design_ugc') ? 'folder/folder_list_ugc' : 'folder/folder_list';
			
			$this->load->view($template, array(
				'show_header' => $show_header,
				'folders' => $folders,
				'per_page' => $per_page,
				'url' => '/search/collections?'.implode('&', array('q='.$keyword)),
			));
	   }
	}
	
	/**
	 * Ajax functions
	 * @param unknown_type $keyword
	 */
	public function people($keyword) {

		$user_model = $this->user_model;
		$user_model = new User_model();

		$user_model->try_solr();
		
		$res = $user_model->select_fields(array('id','first_name','last_name','avatar','uri_name'))
			->search($keyword)
			->limit($this->config->item('autocomplete_search_limit'))
			->get_all();

		$ret = array();
		foreach ($res as $row) {
			$ret[] = array(
				"id" => $row->id,
				"name" => $row->full_name,
				"img" => $row->avatar_25,
				"url" => $row->url,
			);
		}

		return $ret;
	}

	public function get_hashtags() {
		if ($this->input->get('q')) { //token list
			$keyword = $this->input->get('q', true);
			
			$result = $this->hashtags($keyword);
	
			foreach ($result as $key => $value) {
				unset($result[$key]['url']);
			}
	
			die(json_encode($result));
		} else {                      //jquery autocomplete
			$keyword = $this->input->get('term', true);
			$result = $this->hashtags($keyword);
			$ret = array();
			$found = false;
			foreach ($result as $val) {
				$ret[] = $val['name'];
				if ($val['name'] == '#'.str_replace('#', '', $keyword)) $found = true;
			}
			if (!$found && $this->input->get('add')) {
				array_unshift($ret, '#'.str_replace('#', '', $keyword));
			}
			
			die(json_encode($ret));
		}
	}
	
	public function post_referrals($newsfeed_id) {
		$keyword = $this->input->get('term', true);
		$result = $this->newsfeed_referral_model
					//->search($keyword) RR - disabled by alexi request
					->get_many_by(array('newsfeed_id'=>$newsfeed_id,'email'=>$keyword));
		$ret = array();
		$this->load->library('bitly');
		foreach ($result as $row) {
			if (!$row->url) $row->url = $row->update_url(); 
			$ret[] = array(
				'name' => $row->name,
				'url' => $row->url,
				'points' => $row->points
			) ;			
		}
		
		die(json_encode($ret));
	}
	
	public function hashtags($keyword) {

		$ret = array();
		$res = $this->hashtag_model
				->search($keyword)
				->limit($this->config->item('autocomplete_search_limit'))
				->get_all();
		
		foreach ($res as $row) {
			$ret[] = array(
				"id" => $row->id,
				"name" => $row->_hashtag_name,
				"url" => $row->hashtag_url,
			);
		}
		
		return $ret;
	}
	
	public function collections($keyword) {

		$folder_model = $this->folder_model;
		$folder_model = new Folder_model;

		$folder_model->try_solr();

		$res = $folder_model->select_fields(array(
					"folder.folder_id", "folder.folder_name", "folder.private",
    				"CONCAT('/collection/',users.uri_name,'/',folder.folder_uri_name) as folder_url"
				))
				->join_users()
				->has_newsfeeds()
				->search(mysql_real_escape_string($keyword))
				->limit($this->config->item('autocomplete_search_limit'))
				->get_all();
		
		$ret = array();
		foreach ($res as $row) {
			$ret[] = array(
				"id" => $row->folder_id,
				"name" => $row->folder_name,
				"url" => $row->folder_url,
			);
		}
		
		return $ret;
	}
	
	public function drops($keyword) {
		$newsfeed_model = $this->newsfeed_model;
		$newsfeed_model = new Newsfeed_model();
		
		$newsfeed_model->try_solr();
	
		$res = $newsfeed_model->select_search_fields(str_replace('#', '_hash_', $keyword))
						->search(str_replace('#', '_hash_', $keyword))
						->order_by('relevance', 'DESC')
						->limit($this->config->item('autocomplete_search_limit'));
		$res = $newsfeed_model->get_all();

		$ret = array();
		
		foreach ($res as $row) {
			$ret[] = array(
				"id" => $row->newsfeed_id,
				"name" => $row->description_plain,
				"img" => $row->img_small,
				"url" => $row->url,
			);
		}
		return $ret;
	}

	public function search_all($keyword) {
		$solr_model = $this->solr_model;
		$solr_model = new Solr_model();

		if ( $solr_model->try_solr() == false ) {
			return false;
		}

		// Quang: solr use text fields, not array
		// select_fields(array('table','id','newsfeed_id','folder_id'))
		$res = $solr_model->select_search_fields('table,id,newsfeed_id,folder_id')
			->search($keyword)
			->limit($this->config->item('autocomplete_search_limit'))
			->get_all();

		$ret = array();
		foreach ($res as $row) {
			if ( $row['table'] == 'newsfeed' ) {
				$row = $this->newsfeed_model->get_by(array('newsfeed_id' => $row['newsfeed_id']));
				$ret[] = array(
					"id" => $row->newsfeed_id,
					"name" => $row->description_plain,
					"img" => $row->img_small,
					"url" => base_url(). 'drop/'.$row->url,
				);
			} elseif ( $row['table'] == 'users'    ) {
				$row = $this->user_model->get_by(array('id' => $row['id']));
				$ret[] = array(
					"id" => $row->id,
					"name" => $row->full_name,
					"img" => $row->avatar_25,
					"url" => $row->url,
				);
			} elseif ( $row['table'] == 'folder'   ) {
				$row = $this->folder_model->get_by(array('folder_id' => $row['folder_id']));
				$ret[] = array(
					"id" => $row->folder_id,
					"name" => $row->folder_name,
					"url" => $row->folder_url,
				);
			}
		}
		return $ret;
	}
	
}
