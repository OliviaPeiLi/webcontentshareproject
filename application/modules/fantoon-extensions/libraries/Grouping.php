<?php
class Grouping {
	
	public $current_js_group;
	public $css_base_path;
	public $js_base_path;
	private $css_filenames;
	
	private $ci;
		
	public function __construct($config=array()) {
		foreach ((Array) $config as $key=>$val) $this->$key = $val;
		$this->ci = get_instance();
		if (!$this->current_js_group) $this->current_js_group = $this->ci->current_js_group;
		if (!$this->css_base_path) $this->css_base_path = $this->ci->css_base_path;
		if (!$this->js_base_path) $this->js_base_path = $this->ci->js_base_path;
		if (!$this->css_filenames) $this->css_filenames = $this->ci->css_filenames;
	}
	
	public function get_css_cache_file() {
		$optimized_css = $this->css_base_path.'/cached/' .str_replace('/', '_', $this->current_js_group).'.css';
		if (is_file($optimized_css)) return $optimized_css;
		return false;
	}
	
	public function get_css_cache_url() {
		$optimized_css = $this->get_css_cache_file();
		if (!$optimized_css) return false;
		return str_replace('/', '_', $this->current_js_group).'.css?v='.filemtime($optimized_css);
	}
	
	public function check_css($css) {
		if ($this->is_css_updated($css)) {
			$this->clean_css_cache();
			if (!$css) return false;
			$this->group_css($css);
		}
		return true;
	}
	
	public function is_css_updated($css) {
		if (!$optimized_css = $this->get_css_cache_file()) return true;
		$cached = file_get_contents($optimized_css);
		$cached = explode(',', trim(substr($cached, 3, strpos($cached, '*/')-4)));
		foreach ($css as $file) {
			$file = ltrim($file, '/');
			if (strpos($file, '?') !== false) list($file,) = explode('?', $file,2);
			if (!$file) continue; //RR - to check why 1 file has no name
			if (!in_array($file, $cached)) {
				return true;
				break;
			}
		}
		if (count($cached) > count($css)) {
			return true;
		}
		return false;
	}
	
	public function clean_css_cache($file=null) {
		if (!$file) $file = $this->get_css_cache_file();
		if (!$file) return false;
		
		unlink($file);
		return true;
	}
	
	public function clean_updated_css_files($files) {
		if ($this->ci->css_filenames) {
			foreach ($files as &$file) {
				if (isset($this->ci->css_filenames[$file][2])) {
					$file = $this->ci->css_filenames[$file][2];
				}
			}
		}
		$cleaned_files = array();
		foreach (glob($this->css_base_path.'cached/*') as $cache_file) {
			$contents = file_get_contents($cache_file);
			foreach ($files as $file) {
				if (strpos($contents, $file) !== false) {
					$this->clean_css_cache($cache_file);
					$cleaned_files[] = basename($cache_file);
				}
			}
		}
		return $cleaned_files;
	}
	
	public function group_css($css) {
		$cache_name = str_replace('/', '_', $this->current_js_group);
		
		$added = array();
		$not_found = array();
		
		$contents = '';
		foreach ($css as $file) {
			if (strpos($file, $cache_name) !== false) continue;
			if (strpos($file, '?') !== false) list($file,) = explode('?', $file);
			if (isset($this->css_filenames['/css/'.$file][1])) {
				$file = $this->css_filenames['/css/'.$file][2];
			}
			$full_file = $this->css_base_path.str_replace('css/', '', $file);
			if (!is_file($full_file)) {
				$not_found[] = $full_file;
				continue;
			}
			$file_contents = file_get_contents($full_file);
			$added[] = basename($full_file);
			$contents .= $file_contents."\r\n";
		}
		if ($contents) {
			$contents = '/* '.implode(',', $added).' */'.$contents;
			file_put_contents($this->css_base_path.'cached/'.$cache_name.'.css', $contents);
		}
		if (ENVIRONMENT == 'production') {
			//rsync --delete -ae "ssh -i .ssh/deploy_key.rsa" /home/fandrop/static/css/cached/newsfeed_newsfeed_get.css root@174.129.20.240:/vz/private/141/home/fandrop/static/css/cached/
			$cmd = "rsync --delete -ae \"ssh -i /home/fandrop/.ssh/deploy_key.rsa\" ".$this->css_base_path."cached/".$cache_name.".css root@174.129.20.240:/vz/private/141/home/fandrop/static/css/cached/";
			system($cmd, $ret);
		}
		return true;
	}
	
	/* ================================= JAVASCRIPT ========================================== */
	public function get_js_cache_file() {
		$optimized_js = $this->js_base_path.'cached/'.str_replace('/', '_', $this->current_js_group).'.js';
		if (is_file($optimized_js)) return $optimized_js;
		return false;
	}
	
	public function get_js_cache_url() {
		$optimized_js = $this->get_js_cache_file();
		if (!$optimized_js) return false;
		//{$this->js_base}
		return "'main': 'cached/".str_replace('/', '_', $this->current_js_group)."'";
	}
	
	public function get_js_cache_version() {
		$optimized_js = $this->get_js_cache_file();
		if (!$optimized_js) return false;
		return filemtime($optimized_js);
	}
		
	public function check_js($js) {
		if ($this->is_js_updated($js)) {
			$this->clean_js_cache();
			if (!$js) return false;
			$this->group_js($js);
		}
		return true;
	}
	
	public function is_js_updated($js) {
		if (!$optimized_js = $this->get_js_cache_file()) return true;
		$cached = file_get_contents($optimized_js);
		$cached = explode(',', trim(substr($cached, 3, strpos($cached, '*/')-4)));
		foreach ($js as $file) {
			$file = ltrim($file, '/');
			if (strpos($file, '?') !== false) list($file,) = explode('?', $file,2);
			if (!$file) continue; //RR - to check why 1 file has no name
			if (!in_array($file, $cached)) {
				return true;
				break;
			}
		}
		if (count($cached) > count($js)) {
			return true;
		}
		return false;
	}
	
	public function clean_js_cache($file=null) {
		if (!$file) $file = $this->get_js_cache_file();
		if (!$file) return false;
		if (!is_file($file)) return false;
		
		unlink($file);
		return true;
	}
	
	public function clean_updated_js_files($files) {
		if ($this->ci->css_filenames) {
			foreach ($files as &$file) {
				if (isset($this->ci->css_filenames['/js/'.$file.'.js'][2])) {
					$file = str_replace('.js', '', $this->ci->css_filenames['/js/'.$file.'.js'][2]);
				}
			}
		}
		$cleaned_files = array();
		foreach (glob($this->js_base_path.'cached/*') as $cache_file) {
			$contents = file_get_contents($cache_file);
			foreach ($files as $file) {
				if (strpos($contents, '"'.$file.'"') !== false) {
					$this->clean_js_cache($cache_file);
					$cleaned_files[] = basename($cache_file);
				}
			}
		}
		//print_r($cleaned_files);
		return $cleaned_files;
	}
	
	public function group_js($js) {
		$cache_name = str_replace('/', '_', $this->current_js_group);
		$contents = '';
		$added = array();
		$added_all = array('jquery'=>true,'jquery-ui'=>true);
		$not_found = array();
		foreach ($js as $file) {
			if (strpos($file, $cache_name) !== false) continue;
			if (isset($added_all[$file])) continue;			
			if (!$full_file = $this->find_js_file($file)) {
				$not_found[] = $file;
				continue;
			}
			$file_contents = file_get_contents($full_file);
			$added[] = $file;
			
			if (ENVIRONMENT == 'development') {
				$contents .= str_replace("define(", "define('".$file."', ", $file_contents);
			} else {
				$contents .= $file_contents."\r\n";
			}
			$added_all[$file] = true;
			
			list($deps, $deps_contents, $deps_not_found) = $this->get_js_dependencies($file_contents, $added_all);
			$contents .= $deps_contents."\r\n";
			array_merge($not_found, $deps_not_found);
			foreach ($deps as $dep) {
				$added_all[$dep] = true;
			}
		}
		//print_r($added);
		//print_r($added_all);
		//die(print_r($not_found));
		if ($contents) {
			$contents = '/* '.implode(',', $added).' */'.$contents;
			file_put_contents($this->js_base_path.'cached/'.$cache_name.'.js', $contents);
		}
	}
	
	public function find_js_file($file) {
		$full_file = $this->js_base_path.$file.'.js';
		if (!is_file($full_file)) {
			if (ENVIRONMENT == 'development') {
				$found = false;
				foreach (Modules::list_modules() as $module) {
					$full_file = BASEPATH.'../application/modules/'.$module.'/js/'.$file.'.js';
					if (is_file($full_file)) {
						$found = true; break;
					}
				}
				if (!$found) return false;
			} else {
				return false;
			}
		}
		return $full_file;
	}
	
	public function get_js_dependencies($contents, $added) {
		$ret = array(array(), '', array());
		preg_match('#define\([^[)]*\[([^\]]*?)\]#', $contents, $matches);
		if (!isset($matches[0])) return $ret;
		if (strpos($matches[1], ',') !== false) $files = explode(",", $matches[1]); else $files = array($matches[1]);
		foreach ($files as $file) {
			$file = trim($file, ' "\'');
			if (isset($added[$file])) continue;
			if (!$full_file = $this->find_js_file($file)) {
				$ret[2][] = $file;
				continue;
			}
			$file_contents = file_get_contents($full_file);
			
			if (ENVIRONMENT == 'development') {
				$ret[1] .= str_replace("define(", "define('".$file."', ", $file_contents);
			} else {
				$ret[1] .= $file_contents;
			}
			$ret[0][] = $file;
			$added[$file] = true;
			
			
			list($deps, $deps_contents, $deps_not_found) = $this->get_js_dependencies($file_contents, $added);
			$ret[1] .= $deps_contents."\r\n";
			array_merge($ret[2], $deps_not_found);
			foreach ($deps as $dep) {
				$ret[0][] = $file;
				$added[$dep] = true;
			}
		}
		return $ret;
	}
}