<?php
class Newsfeed extends MX_Controller {
	protected $default_view = 'postcard';
	protected $default_sort = 'news_rank';
	protected $default_filter = null;
	
	/* =============================== LISTING ================================== */

	public function source($source) {
		$model = $this->get_model(12);
		$this->default_view = 'tile_new';
		$model = $model->filter_source($source);

		return $this->output($model, '/newsfeed/source/'.$source);
	}
	
	public function group($group) {
		$model = $this->get_model(12);
		$this->default_view = 'tile_new';
		$model = $model->filter_user_group($group);

		return $this->output($model, '/newsfeed/source/'.$group);
	}
	
	/**
	 * Used to get just needed fields for the newsfeed listing to make the json response smaller
	 * @see folder/controllers/folder_newsfeed.php
	 * @see homepage/controllers/home_newsfeed.php
	 */
	protected function get_model($per_page=0) {

		$drop_type = $this->input->get('type',true, $this->default_filter);
		$sort_by = $this->input->get('sort_by').' '.$this->input->get('order');
		$sort_by = strtolower(trim($sort_by) ? $sort_by : $this->default_sort);
		
		$newsfeed_model = $this->newsfeed_model;
		$newsfeed_model = new Newsfeed_model();
		
		//leave just the used thumbnails to make the json response smaller
		foreach ($newsfeed_model->behaviors['uploadable']['img']['thumbnails'] as $thumb=>$thumb_data) {
			if (!in_array($thumb, array('thumb','full','tile','576'))) unset($newsfeed_model->behaviors['uploadable']['img']['thumbnails'][$thumb]);
		}
		
		//Joins
		//$newsfeed_model = $newsfeed_model->with('folder')->join('users','users.id = newsfeed.user_id_from');
		$newsfeed_model = $newsfeed_model->select_list_fields();
		
		//Filters
		if($drop_type) {
			$newsfeed_model = $newsfeed_model->filter_type($drop_type);
		}
		
		//Pagination
		if ($per_page) {
			$page = $this->input->get('page',true, 0)+1;
			$newsfeed_model = $newsfeed_model->paginate($page, $per_page);
		}
		
		//Sort & Order
		if (!$sort_by) $sort_by = 'time';
		if (strpos($sort_by, 'asc') === false && strpos($sort_by, 'desc') === false) $sort_by .= ' desc';
		$sort_by = str_replace('share ', 'share_count ', $sort_by);
		$sort_by = str_replace('time ', 'newsfeed.newsfeed_id ', $sort_by);
		$newsfeed_model = $newsfeed_model->order_by($sort_by, '');
		
		return $newsfeed_model;
	}
	
	/**
	 * Used in the newsfeed listing for generating json or html response
	 */
	protected function output($newsfeeds, $url, $get = array()) {

		$page = $this->input->get('page',true, 0)+1;
		$view = $this->input->get('view', true, $this->default_view);

		if ($page > 1) { //$this->input->is_ajax_request()
			echo json_encode(is_array($newsfeeds) ? $this->newsfeed_model->jsonfy($newsfeeds) : $newsfeeds->jsonfy());
		} else {

			$sort_by = $this->input->get('sort_by', true, $this->default_sort);
			$drop_type = $this->input->get('type', true);
			
			if ($view != $this->default_view) $get[] = 'view='.$view;
			if ($sort_by != $this->default_sort) $get[] = 'sort_by='.$sort_by; 
			if ($drop_type) $get[] = 'type='.$drop_type;
			if ($get) $url .= '?'.implode('&', $get);

			$newsfeeds = is_array($newsfeeds) ? $newsfeeds : $newsfeeds->get_all();
			$per_page = $this->config->item($view.'_newsfeed_limit', 10, true);
			
			$this->load->view("newsfeed/newsfeed_general", array(
				'view' => $view,
				'url' => $url,
				'newsfeeds' => $newsfeeds,
				'per_page' => $per_page,
				'json' => true // Temporary while moving all newsfeed to json
			));

		}
	}
	
	/* ========================================== GET ============================= */

	/**
	 * Newsfeed Item (Drop page)
	 * @link /drop/some-drop-title
	 */
	public function get($newsfeed_id, $ref='') {
		//do several attempts because when dropping from internal scraper and second db is not yet updated
		//it returns 404 sometimes;
		for ($i=0; $i<=3; $i++) {
			if (preg_match('#[^0-9]#', $newsfeed_id)) {
				$newsfeed = $this->newsfeed_model->get_by(array('url'=>$newsfeed_id));
			} else {
				$newsfeed = $this->newsfeed_model->get($newsfeed_id);
			}
			if ($newsfeed) break;
		}

		if ($this->is_mod_enabled('design_ugc') && !$newsfeed->folder->type && strpos($_SERVER['HTTP_USER_AGENT'],"facebookexternalhit") === FALSE) {
			return Url_helper::redirect($newsfeed->folder->folder_url);
		}

		if(!$newsfeed) return show_404();
		
		if( $this->is_mod_enabled('exclusive_content') && !$this->session->userdata('id') && $newsfeed->folder->exclusive ) {
			Url_helper::redirect( '/signup?redirect_url=' . $newsfeed->url );
		}
		
		//update view counts
		if ($newsfeed->folder->type < 2) $newsfeed->user_from->icrease_view_count();
		
		$newsfeed->icrease_view_count();
		
		if ($ref && $referral = $this->newsfeed_referral_model->get_by(array('newsfeed_id'=>$newsfeed->newsfeed_id,'name'=>$ref))) {
			$referral->icrease_view_count();
		}
		
		//This cookie is checked in the iframe. If it doesnt exists the contents wont show to 
		//prevent phishing warnings
		setcookie('preview', $newsfeed->newsfeed_id, time()+300, '/');
		
		return parent::template('newsfeed/drop_page', array(
			'head_content' => Html_helper::og_meta($newsfeed),
			'newsfeed' => $newsfeed,
			'referral' => isset($referral) ? $referral : false,
			'prev_newsfeed' => $newsfeed->get_previous(),
			'next_newsfeed' => $newsfeed->get_next(),
			'contest' => $newsfeed->folder->type == 2 ? $newsfeed->folder->contest : null,
			'hide_header' => $newsfeed->folder->type ? '1' : false,
		), $newsfeed->description ? 'Fandrop - '. strip_tags($newsfeed->description) : $this->lang->line('interests_untitled_drop_title'));
	}

	public function popup_right($newsfeed_id, $show_comments = true) {

		$newsfeed = $this->newsfeed_model->get($newsfeed_id);
		if (!$newsfeed) return ;
		
		$newsfeed->icrease_view_count();
		$newsfeed->user_from->icrease_view_count();
		
		$same_source = $this->newsfeed_model->order_by('newsfeed_id','desc')->limit(4)->get_many_by(array('source_id'=>$newsfeed->source_id));
		$orig_user_newsfeeds = $this->newsfeed_model->order_by('newsfeed_id','desc')->limit(4)->get_many_by(array('user_id_from'=>$newsfeed->orig_user_id));
		
		$this->load->view('newsfeed/popup_right', array(
							  				'newsfeed'=>$newsfeed,
											'show_comments' => $show_comments,
											'same_source' => $same_source,
											'orig_user_newsfeeds' => $orig_user_newsfeeds,
						  				));
	}
	

   	/**
   	 * URL: /popup-info/$newsfeed_id
   	 * @param (int)$newsfeed_id
   	 */
	function get_post_details($newsfeed_id, $extended=false) {
		$this->load->model('newsfeed_model');
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);
		if ($extended) {
			
			$data = array(
				'id' => $newsfeed->newsfeed_id,
				'drop_link' => '/drop/'.$newsfeed->url,
				'source' => $newsfeed->source,
				'link' => $newsfeed->link_url,
				'user_link' => $newsfeed->user_from->url,
				'user_avatar' => $newsfeed->user_from->avatar_42,
				'user_fullname' => $newsfeed->user_from->full_name,
				'user_role' => $newsfeed->user_from->role == '1' ? 'Staff Writer' : '',
				'folder_name' => $newsfeed->folder->folder_name,
				'folder_url' => $newsfeed->folder->get_folder_url(),
				'drop_time' => Date_Helper::time_ago($newsfeed->time),
				'class' => $newsfeed->link_type_class,
				'can_edit' => $newsfeed->can_edit($this->user),
				'can_like' => $this->user ? true : false,
				'is_liked' => $newsfeed->is_liked($this->user),
						'like_count' => $newsfeed->up_count,
						'redrop_count' => $newsfeed->collect_count,
						'drop_desc' => $newsfeed->description,
				'drop_desc_plain' => strip_tags($newsfeed->description),
						'watermarked' => $newsfeed->link_type == 'image',
						'complete'=> $newsfeed->complete
			);

			if ($newsfeed->link_type == 'text') {
				$data['full_text'] = $newsfeed->activity->content;
			} else {
				if ($newsfeed->link_type != 'image') {
					$data['iframe'] = '/bookmarklet/snapshot_preview/'.$newsfeed_id;
				}
				$data['drop_image'] = $newsfeed->img;
				$data['coversheet_updated'] = $newsfeed->coversheet_updated;
				$data['width'] = $newsfeed->img_width;
				$data['height'] = $newsfeed->img_height;
			}
		} else {
			$data = array(
				'newsfeed_id' => $newsfeed->newsfeed_id,
				'source' => $newsfeed->source,
				'link' => $newsfeed->link_url
			);
				}

				// social status for both of 'extend' & non extend 
				$data['is_shared']		 = $newsfeed->is_shared($this->user);
				$data['is_twitter_shared'] = $newsfeed->is_shared($this->user, 'twitter');
				$data['is_pinit_shared']   = $newsfeed->is_shared($this->user, 'pinterest');

		echo json_encode($data);
	}
	
	/* ============================================= UPDATE ===================================== */
	
	public function update($id=null) {
		$id = $id ? $id : $this->input->get_post('id');
		$item = $this->newsfeed_model->get($id);
		if (!$item || !$item->newsfeed_id) {
			echo json_encode(array('status'=>false, 'error' => 'Drop not found'));
			return ;
		}
		if (!$item->can_edit($this->user)) {
			echo json_encode(array('status'=>false, 'error' => 'You cant edit this drop.'));
			return ;
		}
		$model = $this->newsfeed_model;
		if (isset($_POST['sub_type'])) {
			$model->validate['description']['rules'] = '';
		}
		if ($data = $model->validate($this->input->post())) {
			$item->update($data);
		} else {
			die(json_encode(array('status'=>false, 'error'=>Form_Helper::validation_errors())));
		}

		$item = $this->newsfeed_model->get($id);
		echo json_encode(array(
			'status' => true,
			'newsfeed_id' => $item->newsfeed_id,
			'url' => $item->url,
			'title' => $item->title,
			'description' => $item->description,
			'link_url' => $item->link_url,
			'limited_url' => Text_Helper::character_limiter_strict($item->link_url, 30),
			'source' => $item->source,
			'short_text' => Text_Helper::character_limiter_tag($item->description, 28),
			'longer_text' => Text_Helper::character_limiter_tag($item->description, 70),
		));
	}
	
	public function delete($id) {

		$newsfeed = $this->newsfeed_model->get($id);

		$conf = $newsfeed->_model->behaviors['cachable'][0];

		if ($newsfeed->folder->recent_newsfeeds)	{
			
			for ($i=0;$i<count($newsfeed->folder->recent_newsfeeds);$i++) {
				if ($newsfeed->folder->recent_newsfeeds[$i]->newsfeed_id == $id) {
					unset($newsfeed->folder->recent_newsfeeds[$i]);
				}
			}

			Cachable_Behavior::update_data($conf, $newsfeed, $newsfeed->folder->recent_newsfeeds);
		}

		$this->newsfeed_model->delete($id);
		die(json_encode(array('status'=>true, 'id'=>$id)));
	}
	
	/** ==================== Extra funcs ===================== */
	
	/**
	 * Returns cropped image for the emails
	 */
	public function thumb($newsfeed_id) {
		$item = $this->newsfeed_model->get($newsfeed_id);
		if ($item->link_type == 'image' && strpos($item->img_tile, '.gif') !== false) {
			Url_helper::redirect($item->img_thumb);
			return ;
		}
		if ($item->link_type == 'image' && $item->img_width < 200) {
			Url_helper::redirect($item->img_tile);
			return ;
		}
		
		$upd_path = $this->load->config('uploads');
		$img = $item->img_tile;
		if(strpos($img, 'https://s3.amazonaws.com/fantoon') !==false || strpos($img, 'https://d17tpoh2r6xvno.cloudfront.net/') !==false || strpos($img, 'https://s3.amazonaws.com/fantoon-dev') !==false )
		{
			$filename = str_replace('/','',substr($img, strrpos($img, '/')));
		}
		$tmpfname = str_replace('_tile','_nl',$filename);
		$tmpfname_path = $upd_path['path'].$tmpfname;
		
		//$tmpfname = tempnam($upd_path['path'], "");
		file_put_contents($tmpfname_path, file_get_contents($item->img_tile));
		$data = getimagesize($tmpfname_path);
		$img_lib = $this->load->library('image_lib', array(
				'source_image' => $tmpfname_path,
				//'dynamic_output' => TRUE,
				'height' => $data[1]-35 > 300 ? 300 : $data[1]-35
			));
		//die($tmpfname);
		//die(var_dump($data[1]));
		$img_lib->crop();
		$this->load->library("s3"); 
		//die('links'.'/'.$tmpfname);   
		if ( ! S3::putObject(S3::inputFile($tmpfname_path), Url_helper::s3_bucket(), 'links'.'/'.$tmpfname, S3::ACL_PUBLIC_READ)
		   )
		{
			return false;
		}
		unlink($tmpfname);
		Url_helper::redirect(Url_helper::s3_url().'links'.'/'.$tmpfname);
	}
		
}
