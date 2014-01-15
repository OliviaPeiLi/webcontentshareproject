<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Folder extends API
{
	protected $model = 'folder_model';
	
	protected function filter($items) {
		$get = $this->input->get();
		if (isset($get['filter']['hashtag'])) {
			$hashtag = $this->hashtag_model->get_by(array('hashtag'=>str_replace('#', '_hash_', $get['filter']['hashtag'])));
			$_GET['filter']['hashtag_id'] = $hashtag ? $hashtag->id : 0;
			unset($_GET['filter']['hashtag']);
		}
		return parent::filter($items);
	}

	public function item_get($children=null) {
		if (!$this->item()->can_view($this->session->userdata('id'))) {
			return $this->response(array('status' => false, 'error' => 'Not authorized: private folder'), 401);
		}
		return parent::item_get($children);
	}
	
	public function index_get($page_limit=10) {

		$type = $this->input->get('type',true,false);

		$model = $this->get_model($page_limit , 'folder.');	

		$full = true;

		if (isset($this->_get_args['filter']['is_landing'])) {
			$full = -1;
		}
		
		$items = $this->api_objects->convert($model->get_all(), $full);

		$out = array(
				'results' => (array)$items,
				'__count'=>$full == -1 ? count((array)$items) : 
			);

		if ($this->input->get("out"))	{
			$this->response((array)$out,200);
		}	else {
			$this->response(array("d"=>(array)$out),200);
		}
	}
	
	public function popular_get() {

		$page = $this->input->get('page', true, 1);
		$per_page = $this->input->get('page_limit', true, 10);
		$folders = array();
		
		if($page == 1) {

			$ids = array();
			$editors = $this->user_model->filter_editors()->get_all();
			foreach($editors as $editor){
				$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('user_id'=>$editor->id, 'newsfeeds_count > '=> 3));
				if ($folder) {
					$folders[] = $folder;
					$ids[] = $folder->folder_id;
					if (count($folders) >= $per_page) break;
				}
			}
			for ($i=count($folders); $i < $per_page; $i++) {
				$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('newsfeeds_count > '=> 3));
				if ($folder) {
					$folders[] = $folder;
					$ids[] = $folder->folder_id;
				}
			}
				
			$this->session->set_userdata('popular_collections_1', $ids);

		} else {

			$ids = $this->session->userdata('popular_collections_1', array());
			$folders = $this->folder_model->join_users()
				->select_fields(array('*',"IF (users.role > 0, 1, 0) as users_ranking"), false)
				->filter_out($ids)
				->paginate($page, $per_page)
				->order_by('users_ranking DESC, folder.ranking', 'DESC')
				->get_many_by(array('newsfeeds_count > '=> 3,'folder.private' => '0'));

		}

		$items = $this->api_objects->convert($folders);
		$this->response(array('results'=> $items), 200); // 200 being the HTTP response code
	}

	public function index_post()	{

		// create new collection
		$data = array();
		$post = $this->input->post();

		if ($data = $this->{$this->model()}->validate($post)) {
			
			$primary_key = $this->{$this->model()}->update_or_insert($data);

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

		    if (!isset($data['hashtag_id'])) {
		    	$data['hashtag_id'] = $hashtag_id;
		    }

			$this->folder_hashtag_model->delete_by(array('folder_id'=>$primary_key));

			foreach ($hashtag_ids as $hashtag_id) {
				$this->folder_hashtag_model->insert(array('folder_id'=>$primary_key, 'hashtag_id'=>$hashtag_id));
			}

			return $this->response(array('result'=>true,'id'=>$primary_key), 200);
		} else {
			return $this->response(array('result'=>false,'errors'=>Form_Helper::validation_errors()), 200);
		}

		// return parent::index_post($data);
	}

	public function folder_get() {

		$page = $this->input->get('page', true, 1);
		
		$per_page = $this->input->get('page_limit', true, 10);
		$folders = array();
		
		if($page == 1) {

			$ids = array();
			$editors = $this->user_model->filter_editors()->get_all();
			foreach($editors as $editor){

				if ($type == 'mentions')	{
					$this->folder_model->by_mentions();
				}

				$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('user_id'=>$editor->id, 'newsfeeds_count > '=> 3));
				if ($folder) {
					$folders[] = $folder;
					$ids[] = $folder->folder_id;
					if (count($folders) >= $per_page) break;
				}
			}
			for ($i=count($folders); $i < $per_page; $i++) {

				$folder = $this->folder_model->order_by('ranking','DESC')->get_by(array('newsfeeds_count > '=> 3));

				if ($folder) {
					$folders[] = $folder;
					$ids[] = $folder->folder_id;
				}
			}

				
			$this->session->set_userdata('popular_collections_1', $ids);

		} else {

			$ids = $this->session->userdata('popular_collections_1', array());

			$folders = $this->folder_model->join_users()
				->select_fields(array('*',"IF (users.role > 0, 1, 0) as users_ranking"), false)
				->filter_out($ids)
				->paginate($page, $per_page)
				->order_by('users_ranking DESC, folder.ranking', 'DESC')
				->get_many_by(array('newsfeeds_count > '=> 3,'folder.private' => '0'));

		}

		$items = $this->api_objects->convert($folders);
		$this->response(array('results'=> $items), 200); // 200 being the HTTP response code
	}

	public function hashtags_get() {

		$folders = array();

		$editors = $this->user_model->filter_featured()->dropdown('id','id');
		$hashtags = $this->hashtag_model->top_hashtags()->get_all();

		foreach ($hashtags as $hashtag) {
			$hashtag_folders = $this->folder_model
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

		$items = $this->api_objects->convert($folders);
		$this->response(array('results'=> $items), 200); // 200 being the HTTP response code
	}

	public function item_delete($child_model=null) {
		if ($child_model) return parent::item_delete($child_model);
		
		if (!$this->item()->can_edit($this->session->userdata('id'))) {
			return $this->response('Not authorized', 401);
		}
		return parent::item_delete();
	}

}