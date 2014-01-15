<?php
/**
 * Newsfeed controller class
 *
 * Default funcs which can be extended:
 * index_get
 * index_post
 * index_delete
 */
require_once 'api.php';

class Newsfeed extends API {
	
	protected $default_sort = 'news_rank';
	protected $default_order = 'DESC';
	
	/*public function item_get($children=NULL, $child_id=NULL, $page_limit=10) {
		if ($children == 'downloadhtml') {
			$start = microtime(true);
			$scraper = $this->load->library('Scraper');
			$this->load->library('S3');
			$contents = $scraper->get_html($this->item()->activity->link);
			if (! S3::putObject($contents, Url_helper::s3_bucket(), 'uploads/screenshots/drop-'.$this->item_id.'/index.php', S3::ACL_PUBLIC_READ)) {
				return $this->response(array('status'=>false, 'error'=>'Could not upload to S3'), 200);
			}
			$this->item()->activity->update(array('content'=>'uploaded to S3'));
			return $this->response(array('status'=>true, 'newsfeed_id'=>$this->item_id, 'operation_time'=>microtime(true)-$start), 200);
	   	}
		return parent::item_get($children, $child_id, $page_limit);
	}*/

	public function index_get()	{

		$items = parent::index_get(10,TRUE);

		$out = array(
				'results' => (array)$items[0],
				'__count'=>$items[1]
			);

		$this->response(array("d"=>(array)$out),200);

	}
	
	public function index_post() {
		$post = $this->input->post();
		if (@!$post['folder_id']) $_POST['folder_id'] = '';
		if (@!$post['link_type']) $_POST['link_type'] = '';
		if (@!$post['description']) $_POST['description'] = '';
		
		if ($_POST['link_type'] == 'content') {
			$_POST['activity']['link']['content'] = 'uploaded to S3';
			$this->remove_watermarks = true;
		} elseif ($_POST['link_type'] == 'embed') {
			$_POST['link_url'] = trim($_POST['link_url']);
			$scraper = $this->load->library('Scraper');
			if (!$driver = $scraper->driver($_POST['link_url'])) {
				return $this->response(array('status'=>false,'error' => 'Could not connect to the URL'));
			}
			if (!$embed = $driver->get_embed()) {
				return $this->response(array('status'=>false,'error' => 'Video not recognized'));
			}
			$data = $driver->get_images();
			
			$_POST['activity']['link']['media'] = $embed;
			$_POST['img'] = $data[0]['src'];
			$this->remove_watermarks = true;
		} elseif ($_POST['link_type'] == 'image') {
			if (isset($_POST['img'])) $_POST['img'] = trim($_POST['img']);
			if (!@$post['img'] && !@$_FILES['img']) $_POST['img'] = '';
			$_POST['activity']['link']['source_img'] = @$_POST['img'];
		} else {
			if (@!$post['activity']) $_POST['activity'] = '';
			$this->remove_watermarks = true;
		}
		return parent::index_post();
	}
	
	public function item_post($child_model=null) {
		if ($child_model) return parent::item_post($child_model);
		if ($this->item_id) {
			$item = $this->item();
			if ($item->link_type != 'image') $this->remove_watermarks = true;
		}
		return parent::item_post();
	}
	
	public function item_delete($child_model=null) {
		if ($child_model) return parent::item_delete($child_model);
		if (!$this->item()->can_edit($this->user)) {
			return $this->response(array('status'=>false,'error'=>'You can`t delete this drop'));
		}
		return parent::item_delete();
	}
	
	public function hashtag_get() {
		
		$newsfeeds = array();
		$hashtags = $this->hashtag_model->top_hashtags()->get_all();
		foreach($hashtags as $hashtag) {
			$hashtag_newsfeed = $this->get_model(1)->get_by(array('newsfeed.hashtag_id' => $hashtag->id));
			if($hashtag_newsfeed){
				$newsfeeds[] = $hashtag_newsfeed;
			}
		}
		
		$last_newsfeed_id = $this->input->get('last_newsfeed_id');
		$other_newsfeeds = array();
		if( count($newsfeeds) <= 10 ) {
			$other_newsfeeds = $this->get_model(10-count($newsfeeds));			
			
			if($last_newsfeed_id) {
				$other_newsfeeds = $other_newsfeeds->get_many_by(array('newsfeed.hashtag_id'=>0, 'newsfeed_id <'=>$last_newsfeed_id));
			} else {
				$other_newsfeeds = $other_newsfeeds->get_many_by(array('newsfeed.hashtag_id'=>0));
			}
			$last_newsfeed_id = end($other_newsfeeds)->newsfeed_id;
		}
		
		$items = array_map(array($this, '_clean'), array_merge($newsfeeds, $other_newsfeeds));
		$this->response(array('results'=>$items), 200); // 200 being the HTTP response code
	}

	public function popular_get() {

		$model = $this->model();
		$user_id = $this->input->get('uid');
		$this->load->model($this->model());
		$items = $this-> {$this->model()};
		$items = $this->filter($items);
		if($this->input->get('page_limit') > 0)
		{
			$page_limit = $this->input->get('page_limit');
		}
		else
		{
			$page_limit = 10;
		}
		$items = $items->paginate($this->input->get('page'), $page_limit);
		$primary_key = $this->load->model($model)->primary_key();

		if($this->input->get('since_id') > 0)
		{
			$items = $items->order_by('news_rank', 'ASC')->get_many_by(array($primary_key.' >='=>$this->input->get('since_id')));
		}
		elseif($this->input->get('max_id') > 0)
		{
			$items = $items->order_by('news_rank', 'DESC')->get_many_by(array($primary_key.' <='=>$this->input->get('max_id')));
		}
		else
		{
			$items = $items->order_by('news_rank', 'DESC')->get_all();
		}
		$items = array_map(array($this, '_clean'), $items);
		$this->response(array('results'=>$items), 200); // 200 being the HTTP response code
	}

}