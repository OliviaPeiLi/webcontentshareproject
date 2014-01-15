<?php
class MY_Form_validation extends CI_Form_validation {
	protected $_error_prefix		= '';
	protected $_error_suffix		= '';
	
	public function get_data() {
		$ret = array();
		foreach ($this->_field_data as $field=>$data) {
			if (strpos($data['rules'], 'token_list') !== false) $data['postdata'] = (Array) $data['postdata'];
			if ($data['rules'] == 'checkbox') $data['postdata'] = (Bool) $data['postdata'];
			$ret[$field] = is_null($data['postdata']) ? "" : $data['postdata'];
		}
		return $ret;
	}
	
	public function reset() {
		$this->_error_array = array();
		return $this;
	}
	
	/**
	 * Custom Rules
	 */
	public function checkbox($val) {
		return (int)(Bool) $val;
	}
	public function ft_dropdown($arr) {
		return end(array_keys($arr));
	}
	
	public function datetime($vals) {
		if (!is_array($vals)) return $vals;
		return ($vals['date'] ? $vals['date'] : '0000-00-00').' '.($vals['time'] ? $vals['time'] : '00:00').':00';
	}
	
	public function token_list_insert($arr) {
		foreach ($arr as $key=>$val) {
			if (!$val) unset($arr[$key]);
		}
		return $arr;
	}
	
	public function token_list($arr, $key) {
		if (!is_array($arr)) return array();
		return array_map(create_function('$item', 'return array("'.$key.'" => $item);'), array_keys($arr));
	}
	
	/**
	 * Validation funcs
	 */
	public function special_char_space($str) {
		if (preg_match('/[^A-Za-z0-9-_]/', $str)) {
			$this->set_message('special_char_space', 'No special characters and space please');
			return FALSE;
		}
		return TRUE;
	}
	
	public function special_char($str) {
		if (preg_match('/[^A-Za-z0-9-_\s]/', $str)) {
			$this->set_message('special_char', 'No special characters please');
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Validation func
	 */
	public function unique_username($str) {
		//validate username in routes config
		foreach (Modules::list_modules() as $module) {			
			if ( Modules::match_routes($module, $str, true) ) {
				$this->set_message('unique_username', 'This username is already used');
				return FALSE;
			}
		}
		$ci = get_instance();
		if(
			$ci->user_model->count_by(array('uri_name'=>$str,'id <>'=>$ci->session->userdata('id')))
			|| $ci->contest_model->count_by(array('url'=>$str,'id <>'=>$ci->session->userdata('contest_id')))
		) {
			if ($ci->session->userdata('contest_id')) {
				$this->set_message('unique_username', 'This contest name is already used');
			} else {
				$this->set_message('unique_username', 'This username is already used');
			}
			return FALSE;
		}
		$ci->session->unset_userdata('unique_username');
		
		return TRUE;
	}
	
	public function unique_email($str) {
		$ci = get_instance();
		if($ci->user_model->count_by(array('email'=>$str,'id <>'=>$ci->session->userdata('id')))) {
			$this->set_message('unique_email', 'There is already a Fandrop account with this email.');
			return FALSE;
		}
		return TRUE;
	}
	
	public function validate_comment($str) {
		$ci = get_instance();
		$ci->lang->load('newsfeed/newsfeed_views', LANGUAGE);
		if($ci->lang->line('newsfeed_views_comm_msg_placeholder') == $str) {
			$this->set_message('validate_comment', 'The %s field is required.');
			return false;
		}
		return true;
	}
	
	public function check_thread($id) {
		$ci = get_instance();
		if(!$ci->msg_thread_model->count_by('thread_id', $id)) {
			$this->set_message('check_thread', 'Thread not found.');
			return false;
		}
		return true;
	}
	
	public function folder_name($input){
		$url = Url_helper::url_title($input);
		$ci = get_instance();
		if( ! str_replace(array('_','-'), '',$url)) {
			$this->set_message('folder_name', 'The %s is invalid');
			return FALSE;
		}
		
		$check_array = array(
			'folder_uri_name' => Url_helper::url_title($ci->input->post('folder_name')),
			'user_id' => $ci->session->userdata('id'),
			'folder_id <>' => $ci->input->post('folder_id')
		);
		
		if($ci->folder_model->count_by($check_array)) {
			$this->set_message('folder_name', 'A list with this name already exists');
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Validation for rss source. Called by callback_rss_source
	 * @see Folder_model
	 * @see folder->update()
	 */
	public function rss_source(& $input) {
		$ci = get_instance();
		if (!is_array($input)) {
			$input = (int) $input;
			return true;
		}
		
		if (isset($input[0])) {
			$url = parse_url(end($input[0]));
			if (!isset($url['host'])) {
				$this->set_message('rss_source', 'The %s doesnt appear to be a valid url');
				return FALSE;
			}
			if (!isset($url['scheme'])) $url['scheme'] = 'http';
			$url = $url['scheme'].'://'.$url['host'].(isset($url['path']) ? $url['path'] : '/');
			
			if (!$content = @file_get_contents($url)) {
				$this->set_message('rss_source', 'The %s doesn`t return any data');
				return FALSE;
			}
			
			$ci->load->library('scraper');			
			$driver = $ci->scraper->driver($url, $content);
			
			if (get_class($driver) != 'Scraper_rss') {
				$this->set_message('rss_source', 'The %s is not a valid RSS feed');
				return FALSE;
			}
			
			$source = $ci->rss_source_model->get_by(array('source' => $url));
			if ( ! $source || !$source->id) {
				$input = $ci->rss_source_model->insert(array('source'=> $url, 'update_on' => 12));
			} else {
				$input = $source->id;
			}
		} else {
			reset($input);
			$input = key($input);
			if ($input) {
				$folder_id = $ci->input->post('folder_id');
				$has_it = $ci->folder_model->count_by(array('user_id'=>$ci->user->id, 'rss_source_id'=>$input, 'folder_id <>' => $folder_id));
				if ($has_it) {
					$this->set_message('rss_source', 'You already have a collection from this source');
					return FALSE;
				}
	   		}
		}
		return TRUE;
	}
	
	public function hashtag(& $input) {
		$ci = get_instance();
		if (!is_array($input)) {
			$input = (int) $input;
			return true;
		}
		if (isset($input[0])) {
			$input = array('value' => $input[0][0]);
			$hashtag_name = str_replace('#', '_hash_', (preg_match('/^\#/i', $input['value']) ? $input['value'] : '#'.$input['value']));
			$match_hash = preg_match_all("/_hash_[a-zA-Z0-9\-\.]+(\/\S*)?/", $hashtag_name, $hash_token);
			if($match_hash) {
				if(!$hashtag = $ci->hashtag_model->get_by(array('hashtag' => $hashtag_name))) {
					$input = $ci->hashtag_model->insert(array('hashtag' => $hashtag_name));
				} else {
					$input = $hashtag->id;
				}
			} else {
				$this->set_message('hashtag', 'Invalid Hashtag');
				return FALSE;
			}
		} else {
			reset($input);
			$input = key($input);
		}
		return TRUE;
	}
	
	public function newsfeed_type($input) {
		$ci = get_instance();
		if (!$input || !in_array($input, $ci->newsfeed_model->link_types)) {
			$types = $ci->newsfeed_model->link_types;
			array_shift($types);
			$this->set_message('newsfeed_type', 'Link type not recognized shoud be: '.implode(', ', $types));
			return false;
		}
		return true;
	}
	
	public function newsfeed_activity($input) {
		$ci = get_instance();
		if (!is_array($input)) {
			$this->set_message('newsfeed_activity', 'Activity should be array');
			return false;
		}
		if (!isset($input['link'])) {
			$this->set_message('newsfeed_activity', 'Only "link" is supported for the moment');
			return false;
		}
		$type = @$this->_field_data['link_type']['postdata'];
		
		if ($type == 'html') {
			if (!$input['link']['content']) {
				$this->set_message('newsfeed_activity', '"content" is required');
				return false;
			}
		} elseif ($type == 'content') {
			if (!@$this->_field_data['link_url']) {
				$this->set_message('newsfeed_activity', '"link_url" is required');
				return false;
			}
		} elseif ($type == 'embed') {
			if (!$input['link']['media']) {
				$this->set_message('newsfeed_activity', '"Media" is required');
				return false;
			}
		} elseif ($type == 'text') {
			if (!$input['link']['content']) {
				$this->set_message('newsfeed_activity', '"content" is required');
				return false;
			}
		} elseif ($type == 'image') {
			
		}
		
		return true;
	}
	
	public function folder_id($input) {
		$ci = get_instance();
		$folder = $ci->folder_model->get($input);
    	if (!$folder) {
    		$this->set_message('folder_id', 'Folder doesnt exists');
			return false;
    	} 
    	if (!isset($_SERVER['argv']) && !$folder->can_add($ci->user->id)) {
    		$this->set_message('folder_id', 'You can`t add to this folder');
			return false;
    	}
    	return true;
	}
		
}