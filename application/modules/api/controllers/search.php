<?php
/**
 * Search controller class
 *
 * Default funcs which can be extended:
 * index_get
 * index_post
 * index_delete
 */
require_once 'api.php';
class Search extends API
{

    public function index_delete() {
        $this->response(array('status' => false, 'error' => 'Not authorized'), 401);
    }
	public function index_post() {
		$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
	}
	
	public function index_get() {
		$keyword = $this->input->get('q', true);
		$per_page = $this->input->get('page_limit', true, 10);
		$page = $this->input->get('page', true, 1);
		
		$start = microtime(true);
		$hashtags = $this->hashtag_model->search($keyword)->paginate($page, $per_page)->get_all();
		
		$hashtags_time = microtime(true) - $start;
		$this->folder_model = $this->folder_model;
		$folder_model = new Folder_model();
		$folder_solr = $folder_model->try_solr();
		$folders = $folder_model->search($keyword)
						->paginate($page, $per_page)
						->get_all(NULL, array('folder_id','folder_name','recent_newsfeeds'));
						//->get_many_by(array('newsfeeds_count >= ' => 4));
		
		$folders_time = microtime(true) - $start - $hashtags_time;
		$this->user_model = $this->user_model;
		$user_model = new User_model();
		$user_solr = $user_model->try_solr();
		$users = $user_model->search($keyword)
						//->select_fields(array('id','first_name','last_name','avatar'))
						->paginate($page, $per_page)
						->get_all();
		$users_time = microtime(true) - $start - $folders_time - $hashtags_time;
		
		$this->response(array(
			'results' => array(
				'hashtags' => $this->api_objects->convert($hashtags, -1),
				'lists' => $this->api_objects->convert($folders, -1),
				'users' => $this->api_objects->convert($users, -1),
				'debug' => array(
					'lists_solr' => $folder_solr,
					'users_solr' => $user_solr,
					'hashtags_time' => round($hashtags_time*1000, 2),
					'folders_time' => round($folders_time*1000, 2),
					'users_time' => round($users_time*1000, 2),
				)
			)
		), 200); // 200 being the HTTP response code
	}


    public function solr_get()
    {

        $keyword = $this->input->get('q', true);

        $solr_model = $this->solr_model;
		$solr_model = new Solr_model();

		if (!$keyword)	{

			$out = array(
				'results' => array(),
				'__count'=>array()
			);

			$this->response(array("d"=>(array)$out),200);

			// $this->response(array(
			// 	'results' => array()
			// ), 200); // 200 being the HTTP response code			
		}

		$keyword = trim($keyword);

		if ( $solr_model->try_solr() == false ) {
			return false;
		}

		$start = ( $this->input->get("page") * $this->input->get("pageSize") ) - $this->input->get("pageSize");

		// Quang: solr use text fields, not array
		// select_fields(array('table','id','newsfeed_id','folder_id'))
		$res = $solr_model->select_search_fields('table,id,newsfeed_id,folder_id')
			->search($keyword)
			// ->limit($this->config->item('autocomplete_search_limit'))
			// ->limit( $this->input->get('pageSize') )
			->paginate($this->input->get("page"), $this->input->get('pageSize'))
			->get_all();

		$ret = array();

		foreach ($res as $row) {
			if ( $row['table'] == 'newsfeed' ) {
				$row = $this->newsfeed_model->get_by(array('newsfeed_id' => $row['newsfeed_id']));
				$ret[] = array(
					"id" => $row->newsfeed_id,
					"name" => $row->description_plain,
					"folder_id"=>$row->folder_id,
					"img" => $row->_img_75,
					"url" => base_url(). 'drop/'.$row->url,
					"_type"=>"newsfeed"
				);
			} elseif ( $row['table'] == 'users'    ) {
				$row = $this->user_model->get_by(array('id' => $row['id']));
				$ret[] = array(
					"id" => $row->id,
					"name" => $row->full_name,
					"folder_id"=>"",
					"img" => $row->_avatar_73,
					"url" => $row->url,
					"_type"=>"users"
				);
			} else
			if ( $row['table'] == 'folder'   ) {
				$row = $this->folder_model->get_by(array('folder_id' => $row['folder_id']));
				$ret[] = array(
					"id" => $row->folder_id,
					"name" => $row->folder_name,
					"url" => $row->folder_url,
					"_type"=>"folder"
				);
			}
		}

		$out = array(
			'results' => (array)$ret,
			'__count'=>100
		);

		$this->response(array("d"=>(array)$out),200);

		// $this->response($out, 200); // 200 being the HTTP response code

    }

}