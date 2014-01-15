<?php

class Link_Model extends MY_Model
{
	protected $primary_key = 'link_id'; //To be removed when the migrations are ready and the database is updated. All primary keys should be set to "id"
	//Relations

	protected $belongs_to = array(
								'user_from' => array('foreign_column' => 'user_id_from', 'foreign_model' => 'user'),
							);

	protected $polymorphic_has_one = array(
										 'newsfeed' => array(
												 'model_column' => 'type',
												 'item_column' => 'activity_id'
										 ),
										 'ticker' => array(
												 'foreign_model' => 'activity',
												 'model_column' => 'type',
												 'item_column' => 'activity_id',
												 'on_delete_cascade' => true
										 )
									 );

	/* ============================ EVENTS ========================== */

	protected function _run_before_set($data) {
		if (isset($data['caption'])) {
			$data['text'] = $data['caption'];
			unset($data['caption']);
		}
		if (isset($data['text'])) {
			$data['text'] = str_replace('#','_hash_',$data['text']);
		}
		if (isset($data['link']) && $data['link']) {
			$data['link'] = 'http://'.str_replace(array('http://','https://'),'',$data['link']);
			if (!isset($data['source'])) {
				$parse = parse_url($data['link']);
				$data['source'] =str_replace('www.', '', @$parse['host']);
			}
		}
		if (isset($data['content']) && $data['content'] != null) {
			$data['content_plain'] = strip_tags($data['content']);
			if($data['content'] == null || $data['content'] == ''){
				unset($data['content']);
			}
		}

		return parent::_run_before_set($data);
	}

	public function _run_after_get($row=null) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->title)) $row->title = strip_tags($row->title);
		if ($row->newsfeed) {
			$row->_url = $row->_title_link = '/drop/'.$row->newsfeed->url;
		}
		
		if (isset($row->media)) {
			if (strpos($row->media, '<object') !== false && strpos($row->media, '<param name="movie"') == false) {
				preg_match('#data="(.*?)"#', $row->media, $matches);
				if (isset($matches[1])) {
					$row->media = str_replace("</object>", '<param name="movie" value="'.$matches[1].'"/></object>', $row->media);
				}
			}
		}
		
		if(isset($row->text)) {
			$row->_caption = $row->text;
			$row->_caption_plain = $row->_text_plain = strip_tags($row->text);
		}
		
		if (isset($row->link_type) && isset($row->content) && $row->link_type == 'text') {
			$row->content = strip_tags($row->content, '<br><a><strong><em>');
		}
	}

	protected function _run_after_set($data=null) {
		if (isset($data['content'])) { //content updated needs screenshot
			if ($data['content'] == 'temp') {
				
			} elseif ($data['content'] == 'uploaded to S3') {
				$job_id = $this->add_page_job($data);
			} else {
				$job_id = $this->add_html_job($data);
			}
		}
		return parent::_run_after_set($data);
	}
	
	private function add_html_job($data) {	
		$ci = get_instance();
		$newsfeed = $this->db->query("SELECT newsfeed_id, folder_id, activity_id FROM newsfeed WHERE activity_id = ".$data['link_id']." AND type = 'link'")->first_row();
		if (!$newsfeed || !$newsfeed->newsfeed_id) return ;
		$job_id = $this->generate_snapshot($newsfeed);
		$ci->session->set_userdata('job_id', $job_id);
		return $job_id;
	}
	
	public function generate_snapshot($newsfeed) {
		$ci = get_instance();
		$job_id = 0;
		$new_server = true;
		include(BASEPATH.'../scripts/config.php');
		if ($pheanstalk) {
			$data = array(
						'user_id'=>$ci->session->userdata('id'),
						'id'=> $newsfeed->activity_id,
						'newsfeed_id' => $newsfeed->newsfeed_id,
						'link'=> str_replace('https://', 'http://', Url_helper::base_url()).'bookmarklet/snapshot_preview/'.$newsfeed->newsfeed_id,
						'folder_id'=>$newsfeed->folder_id
					);
			try {
				$pheanstalk->useTube("ght-".ENVIRONMENT);
				$job_id = $pheanstalk->put(json_encode($data));
				$ci->load->model('beanstalk_job_model');
				$ci->beanstalk_job_model->insert(array(
													'job_id'=>$job_id,
													'data'=>serialize($data),
													'type'=>'ght',
													'created_at'=>date("Y-m-d H:i:s")
												));
			} catch (Exception $e) { }
		}
		
		//Use secondary screenshot generator
		if (!$job_id) {
			if (strpos(PHP_OS, 'WIN') !== false) {
				$os = 'windows';
			} elseif (strpos(PHP_OS, 'Linux') !== false) {
				$os = 'linux';
			} elseif (strpos(PHP_OS, 'Darwin') !== false) {
				$os = 'mac';
			} else {
				echo 'Operating system not recognized';
				exit();
			}
			$upd_conf = $ci->load->config('uploads');
			$filename = uniqid().'.jpg';
			$url = Url_helper::base_url().'/bookmarklet/snapshot_preview/'.$newsfeed->newsfeed_id;
			$phantom_js = "js/tests/$os/phantomjs js/tests/rasterize.js";
			if ($os == 'windows') $phantom_js = str_replace('/', "\\", $phantom_js);
			$phantom_js .= " '$url' ".$upd_conf['path'].$filename;
			system($phantom_js, $rtn);
			$ci->newsfeed_model->update($newsfeed->newsfeed_id, array('img' => '/uploads/'.$filename, 'complete'=> true));
		}
		return $job_id;
	}
	
	private function add_page_job($data) {
		$ci = get_instance();
		$newsfeed = $this->db->query("SELECT newsfeed_id, folder_id, link_url, activity_id FROM newsfeed WHERE activity_id = ".$data['link_id']." AND type = 'link'")->first_row();
		if (!$newsfeed || !$newsfeed->newsfeed_id) return ;
		
		$job_id = $this->generate_screenshot($newsfeed);
		$ci->session->set_userdata('job_id', $job_id);
		
		if (ENVIRONMENT != 'production') {
			$job1_id = $this->cache_page($newsfeed);
			$ci->session->set_userdata('job1_id', $job1_id);
		}
		
		return $job_id;
	}
	
	public function cache_page($newsfeed) {
		$job_id = 0;
		$self_server = true; //will send the requests to testing or app1 servers
		include(BASEPATH.'../scripts/config.php');
		if ($pheanstalk) {
			$data = array(
						'newsfeed_id' => $newsfeed->newsfeed_id,
						'link' => $newsfeed->link_url
					);
			try {
				$pheanstalk->useTube('cl-bm-'.ENVIRONMENT);
				$job_id = $pheanstalk->put(json_encode($data));
				$this->load->model('beanstalk_job_model');
				$this->beanstalk_job_model->insert(array(
													'job_id'=>$job_id,
													'data'=>serialize($data),
													'type'=>'cl-bm',
													'created_at'=>date("Y-m-d H:i:s")
												));
			} catch (Exception $e) { }
		}
		
		//second caching script
		if (!$job_id) {
			$cmd = "php scripts/clean_html 0 ".ENVIRONMENT." ".$newsfeed->newsfeed_id;
			ob_start();
			system($cmd, $ret);
			$ret = ob_get_clean();
		}
		return $job_id;
	} 
	
	public function generate_screenshot($newsfeed) {
		$ci = get_instance();
		$job_id = 0;
		$new_server = true;
		include(BASEPATH.'../scripts/config.php');
		if ($pheanstalk) {
			$data = array(
						'user_id'=>$ci->session->userdata('id'),
						'id'=> $newsfeed->activity_id,
						'newsfeed_id' => $newsfeed->newsfeed_id,
						'link' => str_replace('https://', 'http://', Url_helper::base_url()).'/bookmarklet/snapshot_preview/'.$newsfeed->newsfeed_id,
						'folder_id'=>$newsfeed->folder_id
					);
			//generate screenshot
			try {
				$pheanstalk->useTube("scr-".ENVIRONMENT);
				$job_id = $pheanstalk->put(json_encode($data));
				$ci->load->model('beanstalk_job_model');
				$ci->beanstalk_job_model->insert(array(
													'job_id'=>$job_id,
													'data'=>serialize($data),
													'type'=>'scr',
													'created_at'=>date("Y-m-d H:i:s")));
			} catch (Exception $e) { }
		}
		
		//Use secondary screenshot generator
		if (!$job_id) {
			if (strpos(PHP_OS, 'WIN') !== false) {
				$os = 'windows';
			} elseif (strpos(PHP_OS, 'Linux') !== false) {
				$os = 'linux';
			} elseif (strpos(PHP_OS, 'Darwin') !== false) {
				$os = 'mac';
			} else {
				echo 'Operating system not recognized';
				exit();
			}
			$upd_conf = $ci->load->config('uploads');
			$filename = uniqid().'.jpg';
			$url = Url_helper::base_url().'/bookmarklet/snapshot_preview/'.$newsfeed->newsfeed_id;
			$phantom_js = "js/tests/$os/phantomjs js/tests/rasterize.js";
			if ($os == 'windows') $phantom_js = str_replace('/', "\\", $phantom_js);
			$phantom_js .= " '$url' ".$upd_conf['path'].$filename;
			system($phantom_js, $rtn);
			$ci->newsfeed_model->update($newsfeed->newsfeed_id, array('img' => '/uploads/'.$filename, 'complete'=> true));
		}
		return $job_id;
	}

	function filter_source($source=null, $link_id) {
		if(!$source || !$link_id) return ;
		$this->db->join('newsfeed','newsfeed.activity_id = links.link_id');
		$this->db->join('folder', 'folder.folder_id = newsfeed.folder_id');
		$this->db->where("source='".$source."' AND link_id<".$link_id." AND complete='1' AND newsfeed.type='link' AND folder.private!='1'", NULL, FALSE);
		return $this;
	}	

}