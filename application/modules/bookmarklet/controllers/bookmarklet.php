<?php

class Bookmarklet extends MX_Controller
{

	public function __construct() {
		header("P3P: CP=”ALL ADM DEV PSAi COM OUR OTRo STP IND ONL”"); // <= IE sucks!
		parent::__construct();
		$this->output->enable_profiler(false);
		$this->lang->load('bookmarklet/bookmarklet', LANGUAGE);
	}
	
	/* ==================================== GET ================================ */
	
	function success($newsfeed_id=0) {
		if ($this->input->is_ajax_request()) {
			$this->load->view('success', array(
								  'newsfeed' => $this->newsfeed_model->get($newsfeed_id)
							  ));
		} else {
			$this->load->view('includes/template_external', array(
								  'main_content' => $this->is_mod_enabled('design_ugc') ? 'success_ugc' : 'success',
								  'newsfeed' => $this->newsfeed_model->get($newsfeed_id)
							  ));
		}
	}

	function index() {
		$folders = $this->folder_model->select_list($this->user->id);
		
		$top_hashtags = $this->hashtag_model->top_hashtags()->dropdown();

		$this->load->view('includes/template_external', array(
								'main_content' => $this->is_mod_enabled('design_ugc') ? 'bar_ugc' : 'bar',
								'options' => array(
									'folders' => $folders,
									'hashtags' => array_values($top_hashtags),
									'design_ugc' => $this->is_mod_enabled('design_ugc'),
								),
						  ));
	}

	function popup() {
		$url = $_SERVER['HTTP_REFERER'];

		$hashtags = $this->hashtag_model->top_hashtags()->dropdown();

		$this->load->view('includes/template_external', array(
								'main_content' => $this->is_mod_enabled('design_ugc') ? 'popup_ugc' : 'popup',
								'hashtags' => $hashtags,
								'url' => $url,
						  ));
	}
	
	function external_login() {
		$this->output->enable_profiler(FALSE);
		$message = '';
		$remember = true;
		if ($this->input->post('submit')) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			if (!$email || !$password) {
				$message = 'Please type in your email and password';
			} else {				
				if ($this->user_model->login($email, $password, $remember)) {
					Url_helper::redirect('/bookmarklet'); return;
				} else {
					$message = $this->lang->line('bookmarklet_login_invalid_err');
				}
			}
		}

		$this->load->view('includes/template_external', array(
					'main_content' => $this->is_mod_enabled('design_ugc') ? 'login_ugc' : 'login',
					'message' => $message
				));
	}
	
	/* =========================== CREATE ==========================*/ 
	
	public function create() {
		$this->benchmark->mark('request_handling_start');

		$post = $this->input->post();
		
		unset($post['description_orig']); //mentions
		
		if (isset($_REQUEST['img'])) {
			$post['img'] = $_REQUEST['img'];
		}
		
		if (isset($_REQUEST['activity']['link']['media'])) {
			$post['activity']['link']['media'] = urldecode($_REQUEST['activity']['link']['media']);
		}

		$post['user_id_from'] = $post['activity_user_id'] = $this->session->userdata('id');
		$post['description'] = strip_tags($post['description']);
		$post['news_rank'] = time();
		$post['time'] = date("Y-m-d H:i:s");
		if ($post['link_type'] == 'html' || $post['link_type'] == 'content') {
			if (!isset($post['activity']['link']['content'])) $post['activity']['link']['content'] = 'temp';
		}
		
		if (isset($post['folder_id'][0])) {
			$folder_name = is_array($post['folder_id'][0]) ? $post['folder_id'][0][0] : $post['folder_id'][0];
			if(!$folder_name || $folder_name == 'Click to Add'){
				die(json_encode(array('status'=>false,'error'=>'please create a list')));
			}
			if ($this->folder_model->count_by(array('folder_name'=>$folder_name,'user_id'=>$this->session->userdata('id')))) {
				die(json_encode(array('status'=>false,'error'=>'list already exists')));
			}
			$_POST['folder_id'] = 0;
			$post['folder_id'] = $this->folder_model->insert(array(
									 'user_id' => $this->session->userdata('id'),
									 'folder_name' => $folder_name,
								 ));
		} else {
			$folder_name = reset($post['folder_id']);
			$post['folder_id'] = key($post['folder_id']);
		}
		
		if (@$post['activity']['link']['media']) {
			$this->load->library('scraper');
			$driver = $this->scraper->driver($post['link_url'], $post['activity']['link']['media']);
			if (in_array(get_class($driver), array('Scraper_html','Scraper_google_charts'))) {
				if (!$embed = $driver->get_embed()) {
					$embed = $post['activity']['link']['media'];
				}
				$post['img'] = $driver->get_thumb();
				$post['link_type'] = 'html';
				$post['activity']['link']['content'] = $embed;
				$post['activity']['link']['media'] = '';
			} else {
				$images = $driver->get_images();
				$post['img'] = $images[0]['src'];
				$post['activity']['link']['media'] = $driver->get_embed() ? $driver->get_embed() : $post['activity']['link']['media'];
			}
		}
		
		//Validate
		$query = array(
					 'newsfeed.link_url' => $post['link_url'],
					 'newsfeed.user_id_from' => $post['user_id_from'],
					 'newsfeed.folder_id' => $post['folder_id'],
				 );
	   	if ($post['link_type'] == 'embed') {
			$query['links.media'] = $post['activity']['link']['media'];
		} elseif ($post['link_type'] == 'content') {
			$query['newsfeed.link_type'] = 'content';
			$query['links.time >'] = date('Y-m-d H:i:s', time()-60*60*2); //one screenshot per 2h
		} else {
			$query = false;
		}

		if ($query && $this->newsfeed_model->join('links', 'activity_id=link_id')->count_by($query)) {
			die(json_encode(array('status'=>false,'error'=>sprintf($this->lang->line('bookmarklet_update_already_shared_err'), ($post['link_type'] == 'embed' ? 'video' : $post['link_type'])))));
		}
		
		if (!$post['folder_id']) {
			if(!$folder_name) {
				die(json_encode(array('status'=>false,'error'=>$this->lang->line('bookmarklet_update_collection_no_exists_err'))));
			}
			if ($this->folder_model->count_by(array('folder_name'=>$folder_name,'user_id'=>$this->session->userdata('id')))) {
				die(json_encode(array('status'=>false,'error'=>$this->lang->line('bookmarklet_update_collection_exists_err'))));
			}

			$post['folder_id'] = $this->folder_model->insert(array(
									 'user_id' => $this->session->userdata('id'),
									 'private' => 0,
									 'folder_name' => $folder_name, //preg_replace('/[^A-Za-z0-9-_\s]/', '', $folder_name),
								 ));

		} else {
			if(!$this->folder_model->get($post['folder_id'])->can_add($this->user->id)) {
				die(json_encode(array('status'=>false,'error'=>$this->lang->line('bookmarklet_update_collection_no_exists_err'))));
			}
		}
		//End Validate
		
		$post['orig_user_id'] = $post['user_id_from'];
		//$post['activity']['link']['user_id_from'] = $post['user_id_from'];
		
		$model = $this->newsfeed_model;
		if (isset($post['img'])) {
			foreach ($model->behaviors['uploadable']['img']['thumbnails'] as $thumb => &$thumb_config) {
				unset($thumb_config['transform']['watermark']);
			}
		}
		
		if (! $id = $model->insert($post)) {
			die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
		}
		$newsfeed = $this->newsfeed_model->get($id);

		$folder = $newsfeed->folder;
		die(json_encode(array(
							'status' => true,
							'id' => $id,
							'thumb' => @$post['img'], 
							'url' => $newsfeed->url, 
							'link' => $newsfeed->link_url,
							'title' => $newsfeed->description,
							'title_nohtml' => strip_tags($newsfeed->description),
							'folder_id' => $newsfeed->folder_id, 'folder_name' => $folder->folder_name, 'folder_url' => $folder->get_folder_url(),
						)));
	}
	
	function add_image_after($newsfeed_id) {
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);
		$newsfeed->update(array('newsfeed_id'=>$newsfeed_id, 'img' => $newsfeed->activity->source_img));
		die(json_encode(array('status'=>true)));
	}

	function add_html_after($newsfeed_id) {
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);

		$this->load->library('jsend');
		$content = $this->jsend->getData($_REQUEST['content']);

		$newsfeed->activity->update(array('content' => $content));
	   	$job_id = $this->session->userdata('job_id');

		die(json_encode(array('status'=>true,'job_id'=>$job_id)));
	}

	function add_page_after($newsfeed_id) {
		if (!$this->is_mod_enabled('bookmark_html_page')) {
			die(json_encode(array('status'=>true ,'error' => 'Module not enabled')));
		}
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);

		$this->load->library('jsend');
		$content = $this->jsend->getData($_REQUEST['content']);

		//load s3 lib and upload to s3
		$this->load->library("s3");
		if ( ! S3::putObject($content, Url_helper::s3_bucket(), 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php', S3::ACL_PUBLIC_READ)) {
			die(json_encode(array('status'=>false,'error'=>'failed to upload to s3')));
			return false;
		}
		
		$newsfeed->activity->update(array('content' => 'uploaded to S3'));
		$job_id = $this->session->userdata('job_id');
		$job1_id = $this->session->userdata('job1_id');
		die(json_encode(array('status'=>true, 'screen_job_id'=>$job_id,'clean_job_id'=>$job1_id)));
	}
	
	/* =========================== UPDATE ======================== */
	
	
	
	/* =========================== DELETE ======================= */
	
	function delete($id) {
		$this->link_model->delete($id);
		die(json_encode(array('status'=>'OK')));
	}
	
	
	/*function info_dialog() {
		$data['first_visit'] = $this->input->get('f',true);
		$data['folders_dropdown'] = $this->folder_model->select_list($this->user->id, 'nolimit');
		$this->load->view('info_dialog',$data);
	}*/
	
	/*function embed_popup() {
		$data['folders_dropdown'] = $this->folder_model->select_list($this->user->id, 'nolimit');

		$this->load->view('includes/template_external', array(
							  'folders' => $data['folders_dropdown'],
							  'url' => @$_SERVER['HTTP_REFERER'],
							  'main_content' => 'embed_popup'
						  ));
	}*/

	/*function get_newsfeed($id) {
		$item = $this->newsfeed_model->get($id);
		die(json_encode(array(
							//'newsfeed'=>$item->as_array(),	   //RR - uncomment these for faster newsfeed refresh
							//'link' => $item->activity->as_array(),  //  when the ajaxList module is ready
							'thumb' => $item->activity->thumb
						)));
	}*/
}
