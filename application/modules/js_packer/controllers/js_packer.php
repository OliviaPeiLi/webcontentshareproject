<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Warning!!! Do NOT update anything in this file.
 */
class Js_packer extends MX_Controller
{

	private $exclude_names = array(
								 '/jquery.js', '/jquery.min.js', '/jquery.dev.js', '/jquery-ui.js', '/jquery-ui.min.js', '/profiler.css'
							 );
	private $js_folder, $css_folder, $images_folder;
	private $file_dates, $file_dates_file;
	private $file_counter = 0;
	private $updated_css = array(); //this array contains updated css files - used in clean cache
	private $updated_js = array();
	private $use_s3 = false;

	public function __construct() {
		parent::__construct();
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
		if (!defined('MP_DB_DEBUG')) define('MP_DB_DEBUG', true); 
			
		$config = $this->load->config('js_packer');
		$this->use_s3 = $config['use_s3'];
		$this->file_dates_file = BASEPATH.'../uploads/file_dates.php';
		if (is_file($this->file_dates_file)) {
			include $this->file_dates_file;
			$this->file_dates = $config['file_dates'];
		}
		
		$this->file_counter = count($this->file_dates);
		$this->js_folder = BASEPATH.'../js/modules';
		$this->css_folder = BASEPATH.'../css';
		$this->images_folder = BASEPATH.'../images';
		$this->load->library('S3');
	}
	
	public function post_to_newrelic() {
		$ch = curl_init("https://rpm.newrelic.com/deployments.xml");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:0d57e7ac18299fd7d0088951207701d8dee7fc1772fda3d'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
			'deployment[application_id]' => ENVIRONMENT == 'production' ? '1264760' : '1264656',
			'deployment[description]' => 'Regular deployment',
			'deployment[changelog]' => 'Some changes', //TO-DO generate a list of changed files
		)));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
		$res = curl_exec($ch);
	}

	public function index() {
		if ( ! isset($_SERVER['argv'])) exit('This script can be run with the console only');
		
		//Copy js from modules to base dir
		foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $path) {
			foreach (glob($path.'/js/*') as $file) {
				$dst = str_replace($path.'/js/', '', $file);
				if (strpos($dst, '~') !== false) continue;
				echo $dst."/\r\n";
				if (is_dir($file)) {
					if (!is_dir((BASEPATH.'../js/modules/'.$dst))) {
						mkdir(BASEPATH.'../js/modules/'.$dst);
					}
					foreach (glob($file.'/*') as $subfile) {
						if (is_dir($subfile)) {
							if (!is_dir(BASEPATH.'../js/modules/'.$dst.'/'.basename($subfile))) {
								mkdir(BASEPATH.'../js/modules/'.$dst.'/'.basename($subfile));
							}
							foreach (glob($subfile.'/*') as $subsubfile) {
								$dst_file = BASEPATH.'../js/modules/'.$dst.'/'.basename($subfile).'/'.basename($subsubfile);
								copy($subsubfile, $dst_file);
								chmod($dst_file, 0777);
								touch($dst_file, filemtime($subsubfile));
							}
						} else {
							$dst_file = BASEPATH.'../js/modules/'.$dst.'/'.basename($subfile);
							copy($subfile, $dst_file);
							chmod($dst_file, 0777);
							touch($dst_file, filemtime($subfile));
						}
					}					
				} else {
					$dst_file = BASEPATH.'../js/modules/'.$dst;
   			 		copy($file, $dst_file);
   			 		chmod($dst_file, 0777);
   			 		touch($dst_file, filemtime($file));
		   		}
			}
		}
		
		//Copy from modules to base dir
		foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $path) {
			foreach (glob($path.'/css/*') as $file) {
				$dst = str_replace($path.'/css/', '', $file);
				echo $dst."\r\n";
				if (is_dir($file)) {
					mkdir(BASEPATH.'../css/'.$dst);
					foreach (glob($file.'/*') as $subfile) {
						$dst_file = BASEPATH.'../css/'.$dst.'/'.basename($subfile);
						copy($subfile, $dst_file);
						chmod($dst_file, 0777);
						touch($dst_file, filemtime($subfile));
					}				
				} else {
					$dst_file = BASEPATH.'../css/'.$dst;
   			 		copy($file, $dst_file);
   			 		chmod($dst_file, 0777);
   			 		touch($dst_file, filemtime($file));
		   		}
			}
		}
		//Populate CSS array
		$css_files = array();
		foreach (glob($this->css_folder.'/NEW/*') as $file) {
			if (is_dir($file)) {
				foreach (glob($file.'/*') as $subfile) {
					if (is_dir($subfile)) {
						foreach (glob($subfile.'/*') as $subsubfile) {
							if (is_file($subsubfile)) {
								$css_files[] = 'NEW/'.basename($file).'/'.basename($subfile).'/'.basename($subsubfile);
							}
						}
					} else {
						$css_files[] = 'NEW/'.basename($file).'/'.basename($subfile);
					}
				}
			} else {
				$css_files[] = 'NEW/'.basename($file);
			}
		}
		
		echo "Pack JS \r\n";
		$this->pack_js($this->js_folder, true);
		
		echo "Upload static images";
		$this->upload_imgs($this->images_folder, true, $css_files);
			
		echo "Pack CSS \r\n";
		$this->pack_css($css_files);
		
		echo "Clean cache \r\n";
		$this->cache->clean();
		if ($this->is_mod_enabled('optimized_js')) {
			$files = $this->grouping->clean_updated_css_files($this->updated_css);
			echo implode("\n", $files);
			$files = $this->grouping->clean_updated_js_files($this->updated_js);
			echo implode("\n", $files);
		}
		
		echo "Post deploy info to new relic\r\n";
		$this->post_to_newrelic();
		
		//echo "Clear cached files\r\n";
		//$this->write_cached_config($this->cached_js);
		
		echo "DONE";
		
	}
	
	/*
	 * Will do it in future replace the ids and classnames to encoded strings
	 *
	public function prepare_names() {
		echo "Prepare id and class names: \t\n";
		$config = $this->load->config('js_packer');
		$this->encoded_class_names = $this->config->item('classes', 'js_packer');
		$this->encoded_id_names = $this->config->item('ids', 'js_packer');
		$config_filename = BASEPATH.'../application/modules/js_packer/config/js_packer.php';

		//$this->read_views();
		//$this->update_js_names();
		//$this->update_css_names();
		//test only
		$file = BASEPATH.'../application/views/admin/admin_aliases.php';
		$contents = file_get_contents($file);
		$contents = $this->change_attr('class', $contents);
		file_put_contents($file, $contents);
	}

	private function change_attr($attr, $contents) {
		//Prepare classes
		preg_match_all('#<[^>]*'.$attr.'="([^"]*?)"[^>]*>#msi', $contents, $matches);
		$file_classes = array();
		if (isset($matches[1])) foreach ($matches[1] as $match) {
			$classes = strpos($match, ' ') !== false ? explode(' ', $match) : array($match);
			foreach ($classes as $class) {
				if (isset($file_classes[$class])) continue;
				if (!isset($this->{'encoded_'.$attr.'_names'}[$class])) {
					echo "Adding new {$attr} to the config: {$class} \r\n";
					$this->{'encoded_'.$attr.'_names'}[$class] = 'ft'.uniqid();
					$config_contents = file_get_contents($config_filename);
					$config_contents = str_replace('); //End classes', "\t'{$class}' => '".$this->{'encoded_'.$attr.'_names'}[$class]."',\r\n\t); //End classes", $config_contents);
					file_put_contents($config_filename, $config_contents);
				}
				if ($this->{'encoded_'.$attr.'_names'}[$class]) {
					$file_classes[$class] = $this->{'encoded_'.$attr.'_names'}[$class];
				}
			}
		}
		$this->file_classes = $file_classes;
		$contents = preg_replace_callback('#<[^>]*(class[ ]*=[ ]*".*?('.implode('|', array_keys($file_classes)).').*?")[^>]*>#msi',array($this, 'file_classes_replace'), $contents);
		$contents = preg_replace_callback('#<[^>]*(class[ ]*=[ ]*".*?('.implode('|', array_keys($file_classes)).').*?")[^>]*>#msi',array($this, 'file_classes_replace'), $contents);
		$contents = preg_replace_callback('#<[^>]*(class[ ]*=[ ]*".*?('.implode('|', array_keys($file_classes)).').*?")[^>]*>#msi',array($this, 'file_classes_replace'), $contents);
		return $contents;
	}
	private $file_classes;
	private function file_classes_replace($matches) {
		$replaced = preg_replace('#([" ]*)'.$matches[2].'([ "]*)#msi', "$1".$this->file_classes[$matches[2]]."$2", $matches[1]);
		return str_replace($matches[1], $replaced, $matches[0]);
	}

	private function read_views($folder, $recursive=true) {
		if (!is_dir($folder)) return;
		$files = glob(rtrim($folder,'/').'/*.php');
		foreach ($files as $file) {
			echo $file."\r\n";
			$contents = file_get_contents($file);
			$contents = $this->change_attr('class', $contents);
			$contents = $this->change_attr('id', $contents);
			file_put_contents($file, $contents);
		}
		if ($recursive) {
			$folders = glob(rtrim($folder,'/').'/*', GLOB_ONLYDIR );
			foreach ($folders as $folder) $this->read_views($folder, $recursive);
		}
	}
	*/

	/**
	 * Change require definitions to new names
	 */
	private function encode_js_requires($file, $contents) {
		$contents = preg_replace_callback('#define\(\[([^\]]*?)\]#', array($this, 'encode_js_require'), $contents);
		$encoded = str_replace('.js', '', $this->get_encodedname($file));
		$contents = str_replace('define(', 'define("'.$encoded.'",', $contents);
		return $contents;
	}
	private function encode_js_require($matches) {
		$files = explode(",",$matches[1]);
		foreach ($files as $file) {
			if (strpos($file, '.js') !== false || !trim($file, "\"' ")) continue;
			$pure = $this->js_folder.'/'.trim($file, "\"' ").'.js';
			$miss_it = false;
			foreach ($this->exclude_names as $pattern) if (strpos($pure, $pattern) !== false) $miss_it = true;
			if ($miss_it) continue;
			$filename = $this->get_name($pure);
			if ( ! isset($this->file_dates[$filename]))
			{
				$encoded = $this->get_encodedname($pure);
				$this->file_dates[$filename] = array(0, 0, $encoded);
				//update the cached file dates config
				$contents = file_get_contents($this->file_dates_file);
				$contents = preg_replace('#\);#msi',"\t'{$filename}' => array(0, 0, '".$this->file_dates[$filename][2]."'),\r\n);", $contents);
				file_put_contents($this->file_dates_file, $contents);
			}
			if (!isset($this->file_dates[$filename][2]))
			{
				echo 'Not encoded: '.$filename;
				print_r($this->file_dates[$filename]);
			}
			$matches[0] = str_replace($file, '"'.str_replace('.js', '', $this->file_dates[$filename][2]).'"', $matches[0]);
		}
		return $matches[0];
	}

	private function encode_js_dynamic_css($file, $contents) {
		echo $file."\r\n";
		return preg_replace_callback("#loadCss\([^\"']*[\"']([^\"'].*?)[\"']#msi", array($this, 'rename_css'), $contents);
	}

	private function rename_css($matches) {
		$pure = $this->css_folder.str_replace(array('/css/','css/'), '/', trim($matches[1]," ."));
		$miss_it = false;
		foreach ($this->exclude_names as $pattern) if (strpos($pure, $pattern) !== false) return $matches[0];
		$filename = $this->get_name($pure);
		if ( ! isset($this->file_dates[$filename]))
		{
			if (!file_exists($pure))
			{
				echo "\r\n CSS not found ! - ".$pure."\r\n";
				return $matches[0];
			}
			$encoded = $this->get_encodedname($pure);
			$this->file_dates[$filename] = array(0, 0, $encoded);
			//update the cached file dates config
			$contents = file_get_contents($this->file_dates_file);
			$contents = preg_replace('#\);#msi',"\t'{$filename}' => array(0, 0, '{$encoded}'),\r\n);", $contents);
			file_put_contents($this->file_dates_file, $contents);
		}
		return str_replace($matches[1], $this->file_dates[$filename][2], $matches[0]);
	}

	/**
	 *   Rename the images ot their encoded names in css
	 */
	private function encode_css($file, $contents) {
		return preg_replace_callback('#\.*/images/[^\)"\']*#', array($this, 'rename_images'), $contents);
	}

	private function rename_images($matches) {
		$pure = $this->images_folder.str_replace('/images', '', trim($matches[0]," ."));
		$miss_it = false;
		foreach ($this->exclude_names as $pattern) if (strpos($pure, $pattern) !== false) return $matches[0];
		$filename = $this->get_name($pure);

		if ( ! isset($this->file_dates[$filename])) {
			if (!file_exists($pure)) {
				echo "\r\nImage not found ! - ".$pure."\r\n";
				return '';
			}
			$encoded = $this->get_encodedname($pure);
			$this->file_dates[$filename] = array(0, 0, $encoded);
			//update the cached file dates config
			$contents = file_get_contents($this->file_dates_file);
			$contents = preg_replace('#\);#msi',"\t'{$filename}' => array(0, 0, '{$encoded}'),\r\n);", $contents);
			file_put_contents($this->file_dates_file, $contents);
		}
		return '/images/'.$this->file_dates[$filename][2].'?v='.$this->file_dates[$filename][0];
	}



	/**
	 * Read encoded filenames config and return the encoded name
	 */
	private function get_encodedname($file) {
		if (!is_file($file)) {
			echo "File not found: ".$file."\r\n";
			return basename($file);
		}
		$filename = $this->get_name($file);
		if (!isset($this->file_dates[$filename][2])) {
			$no_encode = false;
			foreach ($this->exclude_names as $name) if (strpos($file, $name) !== false) $no_encode = true;
			
			if ($no_encode) {
				$encoded = basename($file);
			} else {
				$ext = substr($filename, strrpos($filename, '.'));
				$encoded = base64_encode($this->file_counter).$ext;
				$this->file_counter++;
			}
			
			//update the cached file dates config
			$contents = file_get_contents($this->file_dates_file);
			$contents = preg_replace('#\);#msi',"\t'{$filename}' => array(".filemtime($file).", 0, '{$encoded}'),\r\n);", $contents);
			file_put_contents($this->file_dates_file, $contents);
			
			$this->file_dates[$filename] = array(filemtime($file), 0, $encoded);
		}
		return $this->file_dates[$filename][2];
	}
	
	private function get_name($file) {
		return str_replace(array($this->js_folder, $this->css_folder, $this->images_folder), array('/js','/css','/images'), $file);
	}

	private function pack_js($folder, $recursive=true) {
		if (!is_dir($folder)) return;
		$files = glob(rtrim($folder,'/').'/*.js');
		foreach ($files as $file) {
			if (! $this->check_s3($file)) continue;
			$miss_it = false;
			foreach ($this->exclude_names as $pattern) if (strpos($file, $pattern) !== false) $miss_it = true;
			if (!$miss_it) {
				$contents = exec("java -jar application/third_party/yuicompressor/yuicompressor-2.4.7.jar ".$file);
				if (!$contents) {
					echo "Could not encode file !!!\r\n";
					$contents = file_get_contents($file);
				}
				// do global vars encoding here...
				$contents = $this->encode_js_requires($file, $contents);
				$contents = $this->encode_js_dynamic_css($file, $contents);
				//$contents = $this->encode_globals($file, $contents);
			} else {
				$contents = file_get_contents($file);
			}
			/*
			if (strpos($file, 'bookmarklet/external.js') !== false || strpos($file, 'bookmarklet/external_embed.js') !== false ) {
				echo "Updating bookmarklet.js \r\n";
				file_put_contents($file, $contents);
			}
			*/
			$module = substr($file, strrpos($file, 'modules/')+8);
			$this->updated_js[] = substr($module, 0, strrpos($module,'.'));
			$this->copy_to_s3($file, $contents, 'application/javascript');
		}

		if ($recursive)
		{
			$folders = glob(rtrim($folder,'/').'/*', GLOB_ONLYDIR );
			foreach ($folders as $folder) $this->pack_js($folder, $recursive);
		}
	}

	private function pack_css($files) {
		foreach ($files as $file) {
			$file = $this->css_folder.'/'.$file;
			if (! $this->check_s3($file)) continue;
			$this->updated_css[] = $this->get_name($file);
			$miss_it = false;
			foreach ($this->exclude_names as $pattern) if (strpos($file, $pattern) !== false) $miss_it = true;
			if (!$miss_it) {
				$contents = exec("java -jar application/third_party/yuicompressor/yuicompressor-2.4.7.jar ".$file);
				if (!$contents) {
					echo "Could not encode file !!!\r\n";
					$contents = file_get_contents($file);
				}
				$contents = $this->encode_css($file, $contents);
			} else {
				$contents = file_get_contents($file);
			}
			$this->copy_to_s3($file, $contents, 'text/css');
		}
	}

	private function upload_imgs($folder, $recursive=true, $css_files) {
		if (!is_dir($folder)) return;

		$files = glob(rtrim($folder,'/').'/*.*');
		foreach ($files as $file) {
			if (! $this->check_s3($file)) continue;
			$this->copy_to_s3($file, $file);
			foreach ($css_files as $css_file) { //refresh the image version in all css files where its used
				$css_file = $this->css_folder.'/'.$css_file;
				$contents = file_get_contents($css_file);
				//echo "Checking: ".$css_file."for ".$file."\r\n";
				if (strpos($contents, basename($file)) !== false) {
					$filename = $this->get_name($css_file);
					$this->file_dates[$filename][0] = 0; //will refresh the css too
				}
			}
		}

		if ($recursive) {
			$folders = glob(rtrim($folder,'/').'/*', GLOB_ONLYDIR );
			foreach ($folders as $folder) $this->upload_imgs($folder, $recursive, $css_files);
		}

	}

	/**
	 * Check if the file needs updating
	 */
	public function check_s3($file) {
		$filename = $this->get_name($file);
		if (strpos($file, '~') !== false) return false;
		if (!isset($this->file_dates[$filename]) || $this->file_dates[$filename][0] != filemtime($file)) {
			return true;
		}
		return false;
	}

	/**
	 * Copy updated js and css to s3 server. Cookieless static files improvement
	 */
	public function copy_to_s3($file, $contents, $content_type = null) {
		$encoded = $this->get_encodedname($file);
		$filename = $this->get_name($file);
		if (!$this->use_s3) {
			$target = BASEPATH.'../../../static/';
			if (strpos($file, '/css/') !== false) $target .= 'css/';
			elseif (strpos($file, '/js/') !== false) $target .= 'js/';
			elseif (strpos($file, '/images/') !== false) $target .= 'images/';
			
			if (is_file($contents)) $contents = file_get_contents($contents);
			if (!is_dir($target)) die($target.' path not found!');
			if (!is_dir($target.'js')) mkdir($target.'js', 0777);
			if (!is_dir($target.'css')) mkdir($target.'css', 0777);
			if (!is_dir($target.'images')) mkdir($target.'images', 0777);
			echo 'UPDATING: '.$filename.' => '.$target.$encoded."\r\n";
			file_put_contents($target.$encoded, $contents);
		} else {
			echo 'UPDATING: '.$filename.' => '.Url_helper::s3_url().$encoded."\r\n";
			if (!$content_type) {
				if (!S3::putObjectFile($contents, Url_helper::s3_bucket(), $encoded, S3::ACL_PUBLIC_READ)) {
					echo "ERROR UPDATING FILE";
					return ;
				}
			} else {
				if (!S3::putObjectString($contents, Url_helper::s3_bucket(), $encoded, S3::ACL_PUBLIC_READ, array(), $content_type)) {
					echo "ERROR UPDATING FILE";
					return ;
				}
			}
		}
		if (isset($this->file_dates[$filename]) && filemtime($file) != $this->file_dates[$filename][0]) {
			//update the cached file dates config
			$contents = file_get_contents($this->file_dates_file);
			$contents = preg_replace("#'".preg_quote($filename)."' => array\(\d+,\s\d+,#msi", "'{$filename}' => array(".filemtime($file).", ".($this->file_dates[$filename][1]+1).",", $contents);
			file_put_contents($this->file_dates_file, $contents);
			$this->file_dates[$filename][0] = filemtime($file);
			$this->file_dates[$filename][1]++;
		}


		return true;
	}
}