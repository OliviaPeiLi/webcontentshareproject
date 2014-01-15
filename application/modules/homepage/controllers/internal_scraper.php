<?php

class Internal_scraper extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->lang->load('homepage/internal_scraper', LANGUAGE);
	}
	
	function get_content($cached=false) {
		$link = $this->input->post('link');
		$link = Url_helper::valid_url($link);
		
		//Validate
		$query = array(
					 'newsfeed.link_url' => $link,
					 'newsfeed.user_id_from' => $this->session->userdata('id'),
					 'newsfeed.link_type' => 'content',
					  // 25-02-2013 - http://dev.fantoon.com:8100/browse/FD-3362 by Geno for second time.It was commented.
					 'newsfeed.folder_id' => $this->input->post('folder_id'),
					 'newsfeed.time > ' => date('Y-m-d H:i:s', time() - 60*60) //1 hour
				 );

		if ($this->newsfeed_model->count_by($query)) {
			die(json_encode(array('status'=>false,'error'=>$this->lang->line('link_page_already_shared_msg'))));
		}
	   	
		//End Validate
		$ret['status'] = true;
		$scraper = $this->load->library('Scraper');
		
		if ($cached) {
			$cached = Scraper::get_cache($link);
		}
		
		if (isset($cached['videos'][0])) {
			$ret['class'] = 'embed';
			$ret['media'] = $cached['videos'][0]['embed'];
			$ret['data'] = array(array('src'=>$cached['videos'][0]['thumb']));
			$ret['cached'] = true;
		} else if (isset($cached['content']) || isset($cached['images'][0])) {
			$ret['class'] = 'html';
			$ret['data'] = $cached['images'];
			$ret['content'] = htmlspecialchars(@$cached['content']);
			$ret['cached'] = true;
		} else {			
			$driver = $scraper->driver($link);
			if (!$driver) {
				die(json_encode(array('status'=>false,'error' => 'Could not connect to the URL')));
			}
			$ret['data'] = $driver->get_images();
			if (isset($ret['data']['status']) && !$ret['data']['status']) {
				echo json_encode($ret['data']);
				return ;
			}
			$embed = $driver->get_embed();
			if (isset($ret['data'][0]['type']) && $ret['data'][0]['type'] == 2) { //image
				$ret['class'] = 'image';
				$ret['image'] = $ret['data'][0]['src'];
			} elseif ($embed &&  $ret['data'][0]['type'] ==  1) {
				$ret['class'] = 'embed';
				$ret['media'] = $embed;
				$cache_media = array('videos'=>array(array('embed'=>$embed, 'thumb'=>$ret['data'][0]['src'] )));
				Scraper::update_cache($link, $cache_media);
			} else {
				$content = $scraper->get_html();
				if (isset($content['status']) && !$content['status']) {
					echo json_encode($content);
					return ;
				}
				$cache_media = array('images'=>array());
				foreach ($ret['data'] as $image) $cache_media['images'][] = array('src'=>$image['src']);
				Scraper::update_cache($link, $cache_media, $content);
				$content = htmlspecialchars($content); //bc of new relic
				$ret['class'] = 'html';
				$ret['content'] = $content;
				json_encode($ret);
			}
		}
		$ret = json_encode($ret);
		if (false && json_last_error()) {
			echo "Json error: ".json_last_error();
		} 
		echo $ret;
	}
	
	function index() {

		$post = $this->input->post();
		//RR - Alexi request- make description field not required for text posts
		if ($post['link_type'] == 'text' && !$post['description']) unset($post['description']);

		//!$post is added bc its null when user triees to upload file larger than php.ini:max_upload_size
		if (!$post || @$_FILES['temp_img']) { //Upload photo
			$this->load->model('newsfeed_model');
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
				$filename = str_replace(Url_helper::s3_url().'links/', '', $responce->img);	
				$filename = str_replace('_original.', '.', $filename);	
				die(json_encode(array('status'=>true, 'thumb'=>$responce->img, 'filename'=>$filename)));
			}
			
		} else if ($post['newsfeed_id']) {

			$newsfeed = $this->newsfeed_model->get($post['newsfeed_id']);

			if (!$newsfeed) {
				die(json_encode(array('status'=>false, 'error'=>'Newsfeed not found.')));
			} else if (!$newsfeed->can_edit($this->user)) {
				die(json_encode(array('status'=>false, 'error'=>'You cannot edit this newsfeed.')));
			}

			$update_data = array(
				'description' => $post['description'],
				'link_url'=>isset($post['link_url'])  ? $post['link_url'] : ''
			);

			if ($newsfeed->link_type == 'text') {
				$update_data['activity']['link']['content'] = $post['activity']['link']['content'];
			}

			$newsfeed->update($update_data);

	  	} else {

	  		//Share link
			$use_screenshot = true; //isset($post['use_screenshot']) && $post['use_screenshot'];
			unset($post['dropit'], $post['use_screenshot']);
			//Validate
			if ($post['img']) {
				$headers = @get_headers($post['img']);
				if (strpos($headers[0], '200 OK') === false) {
					die(json_encode(array('status'=>false, 'error'=>'Could not upload file.')));
				}
			}
			//End Validate
			$post['activity']['link']['user_id_from'] = $this->session->userdata('id');
			$post['activity_user_id'] = $this->session->userdata('id');
			$post['user_id_from'] = $this->session->userdata('id');
			if (isset($post['folder_id']) && !is_array($post['folder_id']) && $post['folder_id']) {
				
			} else if (isset($post['folder_id'][0])) {

				// conflict with fulscraper and internal scrapper
				$folder_name = is_array($post['folder_id'][0]) ? $post['folder_id'][0][0] : $post['folder_id'][0];

				if(!$folder_name || $folder_name == 'Click to Add'){
					die(json_encode(array('status'=>false,'error'=>'please create a list')));
				}

				if ($this->folder_model->count_by(array('folder_name'=>$folder_name,'user_id'=>$this->session->userdata('id')))) {
					die(json_encode(array('status'=>false,'error'=>'list already exists')));
				}

				$post['folder_id'] = $this->folder_model->insert(array(
										 'user_id' => $this->session->userdata('id'),
										 'folder_name' => $folder_name,
									 ));

			} elseif (isset($post['folder_id'])) {
				$folder_name = reset($post['folder_id']);
				$post['folder_id'] = key($post['folder_id']);
			} else {
				die(json_encode(array('status'=>false,'error'=>'please create a list')));
			}
			
			if ($post['folder_id']) $_POST['folder_id'] = $post['folder_id'];
			
			unset($post['description_orig']);
			if (isset($post['description'])) $post['description'] = strip_tags($post['description']);
			
			if ( $post['folder_id'] ) {
				Cookie_helper::set_cookie('intscrp_lastCollection', $post['folder_id'], 7 * 24 * 3600);
			}
			
			$post['news_rank'] = time();
			$post['time'] = date("Y-m-d H:i:s");
			$post['activity']['link']['media'] = $_REQUEST['activity']['link']['media'];
			$post['activity']['link']['content'] = $_REQUEST['activity']['link']['content'];

			$model = $this->newsfeed_model;
			if ($post['link_type'] != 'image') {
				foreach ($model->behaviors['uploadable']['img']['thumbnails'] as $thumb => &$thumb_config) {
					unset($thumb_config['transform']['watermark']);
				}
			}
			if ($post['link_type'] == 'html') {
				unset($post['img'], $post['activity']['link']['media']);
			} elseif ($post['link_type'] == 'content') {
				unset($post['img'],$post['activity']['link']['media']);
			} elseif ($post['link_type'] == 'embed') {
				unset($post['activity']['link']['content']);
			} elseif ($post['link_type'] == 'text') {
				unset($post['img'], $post['activity']['link']['media'], $post['link_url']);
				$post['activity']['link']['content'] = str_replace("\n", "<br />", $post['activity']['link']['content']);
			} elseif ($post['link_type'] == 'image') {
				unset($post['activity']['link']['media']);
			}
			
			if (isset($post['after']) && $post['after'] && $post['after'] != 'undefined') {
				$pos = mysql_fetch_object(mysql_query("SELECT position FROM newsfeed WHERE newsfeed_id = ".$post['after']))->position;
				mysql_query("UDATE newsfeed SET position = position + 1 WHERE folder_id = {$post['folder_id']} AND position > {$pos}");
				$post['position'] = $pos+1;
			}
			unset($post['after']);
			if (!$id = $model->insert($post)) {
				die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
			}
			$newsfeed = $this->newsfeed_model->get($id);

			if($post['link_type'] == 'content'){
				$scraper = $this->load->library('Scraper');
				$this->load->library('S3');
				if (! S3::putObject($post['activity']['link']['content'], Url_helper::s3_bucket(), 'uploads/screenshots/drop-'.$id.'/index.php', S3::ACL_PUBLIC_READ)) {
					die(json_encode(array('status'=>false, 'error'=>'Cant upload to s3')));
					return false;
				}
				if ($use_screenshot) {
					$newsfeed->activity->update(array('content' => 'uploaded to S3'));
				}
			}
	  	}
	
		$folder = $this->folder_model->get($post['folder_id']);

		die(json_encode(array(
							'status'=>true,
							'use_screenshot' => @$use_screenshot,
							'id'=>$newsfeed->newsfeed_id,
							'url' => '/drop/'.$newsfeed->url,
							'link' => @$post['link_url'],
							'folder_id' => $post['folder_id'], 'folder_name' => $folder->folder_name, 'folder_url' => $folder->get_folder_url(),
						)));
	
	}

}

