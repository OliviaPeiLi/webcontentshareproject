<?php
/**
 *  Folder (Collection) controller class
 */
require_once 'api.php';

class Top_hashtags extends API
{
	
	public function index_get() {

		$keyword = $this->input->get("q",true);

		$hashtags = $this->hashtag_model->top_hashtags()->get_all();

		if ($keyword)	{
			$keyword = "#" . ltrim( trim( $keyword), "#" );
			$hashtags = $this->hashtag_model->search($keyword)->get_all();
		}

		$this->response(array(
			'results' => $this->api_objects->convert($hashtags, true)
		), 200); // 200 being the HTTP response code

	}

	public function homepage_get()	{

		$folders = array();
		
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

	}

	public function item_delete() {
		$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
	}
	
	public function item_post() {
		$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
	}

}