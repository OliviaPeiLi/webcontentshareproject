<?php

class Contest extends MX_Controller {
	
	/* ==================================== Listing ========================= */
	
	protected function get_model($per_page) {
		$contest_model = $this->contest_model;
		$contest_model = new Contest_model();
		
		return $contest_model;
	}
	
	protected function output($model, $url, $user=null) {
		$page = $this->input->get('page',true, 0)+1;
		
		if ($page > 1) { //$this->input->is_ajax_request()
			echo json_encode($model->jsonfy());
		} else {
			$this->load->view("contest/list", array(
				'url' => $url,
				'user' => $user,
				'contests' => $model->get_all(),
			));
		}
	} 
	
	
	/* ===================================== Get ============================ */
	
	public function get($url) {
		$contest = $this->contest_model->get_by(array('url'=>$url));
		if (!$contest->id) {
			return $this->load->view('includes/template', array(
				'title' => 'Fandrop',
				'main_content' => 'profile/errors_404',
				'header' => 'header'
			));
		}
		return parent::template('contest/get', array(
			'contest' => $contest,			
			'hide_header' => '1',
		), $contest->name);
	}

	/* ====================================== Create ================================= */
	
	public function create() {
		if (@$_FILES['temp_logo']) { //Upload Logo
			$config = $this->contest_model->behaviors['uploadable'];
			$_FILES['logo'] = $_FILES['temp_logo'];
			$responce = Uploadable_Behavior::do_upload(array(), $config);
			if (!$responce) {
				die(json_encode(array('status'=>false, 'error'=>'Could not upload file.')));
			} else {
				$filename = substr($responce->logo, strrpos($responce->logo, '/')+1);
				die(json_encode(array('status'=>true, 'filename'=>$filename, 'thumb'=>$responce->logo_thumb)));
			}
		} else if ($this->input->post('Save')) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('url', 'Url', 'required|trim|alpha_dash');
			$this->form_validation->set_rules('logo', 'Logo', 'required|trim');
			$this->form_validation->set_rules('categories', 'Categories', 'token_list_insert');
			$this->form_validation->set_rules('is_open', 'Open', 'checkbox');
			if ( $this->form_validation->run()) {
				$post = $this->form_validation->get_data();
				$model = $this->contest_model;
				$model = new Contest_model();
				unset($model->behaviors['uploadable']['logo']);
				$contest_id = $model->insert($post);
				$contest = $this->contest_model->get($contest_id);
				Url_helper::redirect('/'.$contest->url);
			}
		}
		$this->load->view('includes/template', array(
			'hide_header' => '1',
			'title' => 'Contest Submission Form',
			'main_content' => 'contest/create'
		));	
	}
	
	/* =================================== Update ============================= */
	
	function update() {
		if (@$_FILES['temp_logo']) { //Upload Contest Logo
			$config = $this->contest_model->behaviors['uploadable'];
			$_FILES['logo'] = $_FILES['temp_logo'];
			$responce = Uploadable_Behavior::do_upload(array(), $config);
			if (!$responce) {
				die(json_encode(array('status'=>false, 'error'=>'Could not upload file.')));
			} else {
				$filename = substr($responce->logo, strrpos($responce->logo, '/')+1);
				die(json_encode(array('status'=>true, 'filename'=>$filename, 'thumb'=>$responce->logo_thumb)));
			}
		}
		
		$post = $this->input->post();
		$this->session->set_userdata('contest_id', $post['id']);
		if (!$data = $this->contest_model->validate($post)) {
			die(json_encode(array('status'=>false, 'error'=>Form_Helper::validation_errors())));
		}
		
		$this->contest_model->update($post['id'], $data);
		
		$contest = $this->contest_model->get($post['id']);
		
		die(json_encode(array('status'=>true, 'url'=>$contest->url)));
	}
	
	/* =================================== Add =================================== */
	
	function add_item($contest_url, $uri) {
		if (isset($_FILES['temp_img'])) {
			$config = $this->newsfeed_model->behaviors['uploadable'];
			/*foreach ($config['img']['thumbnails'] as &$thumb_conf) {
				if (isset($thumb_conf['transform']['watermark'])) unset($thumb_conf['transform']['watermark']);
			}*/
			unset($config['img']['thumbnails']);
			$_FILES['img'] = $_FILES['temp_img'];
			$responce = Uploadable_Behavior::do_upload(array(), $config);
			if (!$responce) {
				die(json_encode(array('status'=>false, 'error'=>'Could not upload file.')));
			} else {
				die(json_encode(array('status'=>true, 'thumb'=>$responce->img)));
			}
		}
		
		$contest = $this->contest_model->get_by(array('url'=>$contest_url));
		$folder = $this->folder_model->get_by(array('folder_uri_name'=>$uri));
		if (!$contest->id || !$folder->folder_id) {
			return parent::template('profile/errors_404');
		}
		if (!$folder->can_add($this->user)) {
			return parent::template('profile/errors_404');
		}
		
		if ($this->input->post('Preview')) {
			$this->load->library('form_validation');
			//@todo - make validation for urls, youtube etc.
			$this->form_validation->set_rules('title', 'Name', 'required|trim');
			$this->form_validation->set_rules('sxsw_email', 'Email', 'valid_email|required|trim');
			//$this->form_validation->set_rules('link_url', 'URL', 'required|trim');
			//$this->form_validation->set_rules('description', 'Description', 'required|trim');
			$this->form_validation->set_rules('youtube_url', 'Youtube or Vimeo video link', 'required|trim');
			$this->form_validation->set_rules('img', 'Logo', 'required|trim');
			
			if ( $this->form_validation->run()) {
				$post = $this->form_validation->get_data();
				$this->load->library('scraper');
				$driver = $this->scraper->driver($post['youtube_url']);
				if (!$driver) {
					die('Could not connect to the URL');
				}
				$embed = $driver->get_embed();
				$data = $driver->get_images();
				if (!$embed) {
					die('Could not process video');
				}
				$post['activity']['link']['media'] = htmlspecialchars($embed);
				$post['img_tile'] = $post['img'];
				$post['img'] = $data[0]['src'];
				$post['folder_id'] = $folder->folder_id;
				unset($post['youtube_url']);
				
				$this->newsfeed_model;
				$sample = new Model_Item();
				
				$sample->description = '';
				$sample->link_url = '';
				foreach ($post as $key=>$val) {
					$sample->$key = $val;
				}
				$sample->_model = $this->newsfeed_model;
				$sample->newsfeed_id = -1;
				$sample->url = 0;
				$sample->complete = 1;
				$sample->activity_id = 0;  //extend sample
				$sample->folder_id = $folder->folder_id;
				$sample->fb_share_count = 0;
				$sample->twitter_share_count = 0;
				$sample->pinterest_share_count = 0;
				$sample->gplus_share_count = 0;
				$sample->linkedin_share_count = 0;
				$sample->email_share_count = 0;
				$sample->link_type = 'embed';
				$sample->coversheet_updated = false;
				$sample->top_prize = '';
				$sample->share_goal = 0;
				$sample->img_width = 0;
				$sample->img_height = 0;
				$sample->short_url = 0;
				$sample->uniqview = 0;
				$sample->activity = new Model_Item();
				$sample->activity->_model = $this->link_model;
				$sample->activity->media = $embed;
								
				$this->newsfeed_model->_run_after_get($sample);
								
				return $this->load->view('includes/template', array(
					'main_content' => 'contest/preview',
					'newsfeed' => $sample,
					'contest' => $contest,
					'form_data' => $post,
					'hide_header' => '1',
					'hide_footer' => '1',
					'title' => 'fandrop: '. strip_tags($sample->description),
				));
			}
		}
		
		$this->load->view('includes/template', array(
			'main_content' => 'contest/add_item',
			'contest' => $contest,
			'hide_header' => '1',
			'title' => 'Contest Submission Form',
		));	
	}
	
	function save() {
		$post = $this->input->post();
		$img_tile = $post['img_tile'];
		unset($post['Save']);
		unset($post['img_tile']);
    	$post['activity']['link']['media'] = html_entity_decode($_REQUEST['activity']['link']['media']);
    	$post['activity_user_id'] = $post['user_id_from'] = $this->folder_model->get($post['folder_id'])->user_id;
    	$post['link_type'] = 'embed';
    	$post['link_url'] = str_replace(' ','',$post['link_url']);
    	$post['complete'] = '1';

    	//$post['hashtag_id'] = $this->hashtag_model->get_by(array('hashtag'=>'_hash_WinSXSW'))->id;
    	//$post['description'] = $post['description'].' _hash_WinSXSW';

    	$id = $this->newsfeed_model->insert($post);
    	$newsfeed = $this->newsfeed_model->get($id);
    	
    	$tile_file_name = str_replace(Url_helper::s3_url(), '', $img_tile);
    	$file_name = str_replace(Url_helper::s3_url(), '', $newsfeed->img_tile);
    	S3::copyObject(Url_helper::s3_bucket(), $tile_file_name, s3_bucket(), $file_name, S3::ACL_PUBLIC_READ);
    	
    	Url_helper::redirect('/drop/'.$newsfeed->url);

	}

	function dashboard($contest_url, $uri) {
		$contest = $this->contest_model->get_by(array('url'=>$contest_url));
		if (!$contest) show_404();
		
		$folder = false;
		if ($contest_url != $uri) { //specific folder dashboard
			$folder = $this->folder_model->get_by(array('folder_uri_name' => $uri));
			if ($folder->type < 1) show_404();
		}
		
		$newsfeeds = $this->newsfeed_model
						//->select_dashboard_fields() RR was used in sxsw
						->order_by('(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count+email_share_count)','DESC');
		if ($folder) {
			$newsfeeds = $newsfeeds->get_many_by(array('folder_id'=> $folder->folder_id));
		} else {
			$newsfeeds = $newsfeeds->get_many_by('folder_id IN (SELECT folder_id FROM folder WHERE contest_id = '.$contest->id.')');
		}
		
		$this->load->view('includes/template', array(
			'hide_header' => '1',
			'title' => ($folder ? $folder->folder_name : $contest->name).' Dashboard',
			'main_content' => 'contest/dashboard',
			'contest' => $contest,
			'folder' => $folder,
			'drops' => $newsfeeds
		));
	}
	
	public function sxsw_dashboard($uri) {
		$folder = $this->folder_model->get_by(array('folder_uri_name' => $uri));
		if ($folder->type != 1) show_404();
		$newsfeeds = $this->newsfeed_model;
		$newsfeeds->select_fields(array("*", 
										"(SELECT SUM(fb_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as total_fb_share_count",
										"(SELECT SUM(pinterest_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as total_pinterest_share_count",
										"(SELECT SUM(twitter_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as total_twitter_share_count",
										"(SELECT SUM(gplus_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as total_gplus_share_count",
										"(SELECT SUM(linkedin_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as total_linkedin_share_count",
										"(SELECT SUM(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as share_count"))->order_by('share_count','DESC');
											
		$newsfeeds = $newsfeeds->get_many_by(array('folder_id'=> $folder->folder_id));
		$this->load->view('includes/template', array(
			'hide_header' => '1',
			'title' => 'SXSW Dashboard',
			'main_content' => 'sxsw_dashboard',
			'folder' => $folder,
			'companies' => $newsfeeds
		));
	}

}