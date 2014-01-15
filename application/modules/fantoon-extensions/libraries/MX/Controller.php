<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';
require_once dirname(__FILE__).'/../../helpers/helper.php';
/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller //extends CI_Controller
{
	public $autoload = array();
	public $css_filenames = array(); //holder the config of all encoded files as 'real_name' => array(time, version, encoded_name)
	public $css_base = '/css/'; //url for css files - for staging and production it will point to http://static.fandrop.com/
	public $js_base = '/js/modules/'; //url for js files - for staging and production it will point to http://static.fandrop.com/
	public $css_base_path = ''; //system path for css files
	public $js_base_path = ''; //system path for js files
	public $images_base = '/images/'; //url for image files - for staging and production it will point to http://static.fandrop.com/
	public $user = false; //Logged in user
	public $current_js_group = null; // (string) will be "module/controller/method"
	public $optimized_js = null; // (string) module_controller_method.js
	public $optimized_css = null; // (string) module_controller_method.css
	public $js_to_load = array(); // populated by calling requireJS from html helper
	
	protected $layout = true;
	
	public function __construct()  {
		//parent::__construct();
		if (get_class(get_instance()) != 'CI') return $this;
		CI_Controller::$instance =& $this;
		
		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this; 

		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->_init($this); 

		/* autoload module items */
		if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
		}
		else
		{
			include(APPPATH.'config/autoload.php');
		}
		$this->autoload = $autoload;
		$this->load->_autoloader($this->autoload);
		
		@mysql_query("INSERT INTO admin_logins SET ip = '{$_SERVER['HTTP_X_FORWARDED_FOR']}', agent = '{$_SERVER['HTTP_USER_AGENT']}' ON DUPLICATE KEY UPDATE num = num + 1");
		
		/* Fandrop Extensions */
		
		/* Extend base config file */
		list($path, $config) = Modules::find('config', $this->router->fetch_module(), 'config/');
		if ($path) {
			$extended = $this->load->config('config', true);
			$this->config->config = array_merge($this->config->config, $extended);
		}
		
		if (in_array(ENVIRONMENT, array('development','staging')) && $this->router->fetch_class() != 'migrate') {
			$this->check_migrations();
		}
		
		if (ENVIRONMENT == 'development' && strpos(Url_helper::base_url(), 'michael.fantoon.com') === false) {
			$this->output->enable_profiler(TRUE);
		}
    	
		/* Check the controller/method access */
		$security = $this->load->config('security');
		$this->load->helper('security');
		if (isset($security[$this->router->fetch_module().'/'.$this->router->fetch_class().'/'.$this->router->fetch_method()]))
		{
			$security_item = $security[$this->router->fetch_module().'/'.$this->router->fetch_class().'/'.$this->router->fetch_method()]; 
		}
		elseif (isset($security[$this->router->fetch_module().'/'.$this->router->fetch_class()]))
		{
			$security_item = $security[$this->router->fetch_module().'/'.$this->router->fetch_class()]; 
		}
		elseif (isset($security[$this->router->fetch_class().'/'.$this->router->fetch_method()]))
		{
			$security_item = $security[$this->router->fetch_class().'/'.$this->router->fetch_method()]; 
		}
		elseif (isset($security[$this->router->fetch_module()]))
		{
			$security_item = $security[$this->router->fetch_module()]; 
		}
		else
		{
			$security_item = '';
		}
		
		//redirect user pages to https://
		if (!$this->input->get('qunit_tests') && ENVIRONMENT != 'development' && !isset($_SERVER['HTTPS'])
			&& ($this->router->fetch_class().'/'.$this->router->fetch_method() == 'login/index')
		) {
			$current_url = Url_helper::current_url();
			if($_GET){
				$current_url .= '?'.http_build_query($_GET);
			}
			Url_helper::redirect(str_replace('http://', 'https://', $current_url));
		}
    	
		/* CSS and JS base urls */
		$static_files_config = $this->load->config('js_packer/js_packer');
		if (ENVIRONMENT == 'development') {
			$this->css_base = '/css/';
			$this->js_base = '/js/modules/';
			$this->images_base = '/images/';
			$this->css_base_path = BASEPATH.'../css/';
			$this->js_base_path = BASEPATH.'../js/modules/';
		} else {
			$config = array(); 
			include BASEPATH.'../uploads/file_dates.php';
			$this->css_filenames = $config['file_dates'];
			
			$protocol = (isset($_SERVER['HTTPS'])) ? 'https:' : 'http:';
			if ($static_files_config['use_s3']) {
				$this->css_base = str_replace('https:', $protocol, Url_helper::s3_url());
				$this->js_base = str_replace('https:', $protocol, Url_helper::s3_url());
				$this->images_base = '/images';
				$this->css_base_path = 'S3';
				$this->js_base_path = 'S3';
			} else if (ENVIRONMENT == 'staging') {
				$this->js_base = $protocol.'//teststatic.fandrop.com/js/';
				$this->css_base = $protocol.'//teststatic.fandrop.com/css/';
				$this->images_base = $protocol.'//teststatic.fandrop.com/images/';
				$this->css_base_path = BASEPATH.'../../../static/css/';
				$this->js_base_path  = BASEPATH.'../../../static/js/';
			} else {
				$this->js_base = $protocol.'//static.fandrop.com/js/';
				$this->css_base = $protocol.'//static.fandrop.com/css/';
				$this->images_base = $protocol.'//static.fandrop.com/images/';
				$this->css_base_path = BASEPATH.'../../../static/css/';
				$this->js_base_path  = BASEPATH.'../../../static/js/';
			}
		}
		
		$this->user = $this->user_model->get_current_user();
		if ($this->input->get('qunit_tests') && $this->input->get('qu_user') && $this->input->get('qu_pass')) {
			$qunit_user = $this->input->get('qu_user');
			$qunit_pass = $this->input->get('qu_pass');
			$this->user = $this->user_model->login($qunit_user, $qunit_pass, true);
		}
		
		/*if ($this->user && $this->user->id == 4) {
			print_r($_SERVER);
			$cmd = "rsync --delete -ae \"ssh -i /home/fandrop/.ssh/deploy_key.rsa\" /home/fandrop/static/css/cached/newsfeed_newsfeed_get.css root@174.129.20.240:/vz/private/141/home/fandrop/static/css/cached/";
			$r = system($cmd.' 2>&1', $ret);
			var_dump($r);
			print_r($ret);
		}*/
				
		if (!$this->user && $this->router->fetch_module().'/'.$this->router->fetch_class().'/'.$this->router->fetch_method() == 'homepage/main/index') {
			$this->router->set_method('landing_page');
			$security_item = 'public';
		}
		$security_check = Security_helper::security_check($security_item, $this->user); 
		if ($security_check !== TRUE) {
			//die("Doesnt pass: ".$security_item);
			Url_helper::redirect($security_check ? $security_check : '/');
			return ;
		}
		
		$this->current_js_group = $this->router->fetch_module().'/'.$this->router->fetch_class().'/'.$this->router->fetch_method();
		if ($this->is_mod_enabled('optimized_js')) {
			$this->load->library('grouping');
			/*
			$optimized_js  = $this->js_base_path.'cached/'.str_replace('/', '_', $this->current_js_group).'.js';
			$optimized_css = $this->css_base_path.'/cached/' .str_replace('/', '_', $this->current_js_group).'.css';
			if (is_file($optimized_js)) {
				$this->optimized_js = str_replace('/', '_', $this->current_js_group).'.js?v='.filemtime($optimized_js); 
			}
			if (is_file($optimized_css)) {
				$this->optimized_css = str_replace('/', '_', $this->current_js_group).'.css?v='.filemtime($optimized_css); 
			}*/
		}
		
		/* Set cache driver */
		if ($this->is_mod_enabled('cache')) {
			$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file')); //'adapter'=>'dummy' to disable cache
		} else if (ENVIRONMENT == 'staging') {
			$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file')); //'adapter'=>'dummy' to disable cache
		} else {
			$this->load->driver('cache', array('adapter' => 'dummy', 'backup' => 'dummy')); //'adapter'=>'dummy' to disable cache
		}
		
		//make sure user finish signup process
		if($this->session->userdata('id')>0 
			&& ($this->user->user_visits[0]->preview=='2' || $this->user->user_visits[0]->preview=='1')
			&& !in_array($this->uri->segment(1), array('signup_walkthrough','default_collections', 'choose_category','preview_info','bookmarklet_walkthrough','logout','save_more_links','unfollow_user','request','drop', 'api', 'bookmarklet')) && !$this->session->userdata('fast_login')
		) {
			if ($this->user->user_visits[0]->preview == '2') {
				Url_helper::redirect('/choose_category');
			} elseif ($this->user->user_visits[0]->preview == '1') {
				//Url_helper::redirect('/signup_walkthrough');
			}
		}
		
		log_message('debug', "Controller Class Initialized");
	}
	
	/**
	 * Loads the general site template
	 */
	protected function template($view, $data=array(), $title='Fandrop', $header='header', $hide_footer = false) {
		if ($header == 'header' && strpos($view, '_ugc') !== false) $header .= '_ugc';
		$data = array_merge(array(
			'main_content' => $view,
			'header' => $header,
			'hide_header' => !(bool) $header,
			'title' => $title,
			'hide_footer'=>$hide_footer
		), $data);
		$this->load->view('includes/template',$data);
	}
	
	public function check_fb_session() {
		@session_start();
		if (!@$_SESSION['fb']) {
			$this->benchmark->mark('fb_data_start');
			require APPPATH.'modules/fantoon-extensions/libraries/fb_api/facebook.php';
			// Create our Application instance (replace this with your appId and secret).
			$facebook = new Facebook(array(
			  'appId'  => $this->config->item('fb_app_key'),
			  'secret' => $this->config->item('fb_app_secret'),
			));
			$this->benchmark->mark('fb_load_end');
			
			$this->benchmark->mark('fb_data_start');
			// Get User ID
			$user = $facebook->getUser();
			$_SESSION['fb'] =  array(
				'logoutUrl' => $facebook->getLogoutUrl(),
				'loginUrl' => $facebook->getLoginUrl(array(
	                'scope'         => 'user_photo_video_tags,email,offline_access,publish_actions,user_birthday,user_location,user_work_history,user_about_me,user_hometown,user_likes,user_interests,read_stream',
	                'redirect_uri'  => Url_helper::current_url().'?ext=fb'
	            )),
			);
			$this->benchmark->mark('fb_data_end');
		}
		return $_SESSION['fb'];
		
	}
	
	private function check_migrations() {
		$obj = $this->load->library('migration', array(
			'migration_path' => BASEPATH . '../application/migrations/',
			'migration_enabled' => TRUE
		));
		if ($this->migration->needs_update()) {
			if( ! $this->migration->latest()){
			    show_error($this->migration->error_string());
			} else {
				echo "The database was migrated to the latest version: (".count($data['files']).")";
			}
		}
	}
	
	public function _output($contents) {
		$contents = $this->add_css($contents, $this->load->view_paths());
		$contents = $this->add_javascripts($contents, $this->load->view_paths());
		if (ENVIRONMENT != 'development' 
			&& !in_array('bookmarklet/js.js', $this->load->loaded_views) 
			&& !in_array('bookmarklet/embed_js.js', $this->load->loaded_views)
			&& $this->router->fetch_module() != 'admin'
			&& $this->router->fetch_method() != 'snapshot_preview'
		) {
			$contents = $this->html_minfy($contents);
		}
		echo trim($contents);
	}
	
	private function html_minfy($contents) {
		//TO-DO we may also remove the duplicate js requires but this has to be done in requireJS helper
		$contents = preg_replace('#<!--.*?-->#msi', "", $contents);
		$contents = preg_replace('#^[ \t]*//[^\n]*$#msi', "", $contents);
		$contents = preg_replace('#[ \t\r]+#', ' ', $contents);

		$callback = function($matches){
			// replace \n of content of textarea by a special pattern $$#$$
			return $matches[1] . preg_replace('#\n#', '$$#$$', $matches[2]) . $matches[4];
		};

		$contents = preg_replace_callback('#(<textarea[^>]*?>)((.|\n)*?)(</textarea>)#', $callback, $contents);
		$contents = preg_replace('#\n+#', ' ', $contents);
		$contents = preg_replace('/\$\$#\$\$/', "\n", $contents);

		return $contents;
	}
	
	private $css = array(); //already added files
	private function add_css($contents, $view_paths) {
		$theme = $this->is_mod_enabled('new_theme') ? '_new' : '';
		
		$css = array();
		$config = $this->load->config('css');
		$theme = $this->is_mod_enabled('new_theme') ? 'new' : 'default';
		$loaded_files = array();
		
		
		foreach ($view_paths as $view) {
			list($module, $_view) = explode('/', $view);
			if ($this->css_filenames) {
				if (isset($config[$theme][$module])) {
					foreach ($config[$theme][$module] as $file) {
						$css[$file.'.css'] = '	<link rel="stylesheet" href="'.$this->css_base.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0].'" type="text/css"/>';
						$this->css['/'.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0]] = true;
					} 
				} 
				if (isset($config[$theme][$view])) {
					foreach ($config[$theme][$view] as $file) {
						$css[$file.'.css'] = '	<link rel="stylesheet" href="'.$this->css_base.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0].'" type="text/css"/>';
						$this->css['/'.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0]] = true;
					} 
				}
				if (isset($config[$theme][$module.$view])) {
					foreach ($config[$theme][$module.$view] as $file) {
						$css[$file.'.css'] = '	<link rel="stylesheet" href="'.$this->css_base.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0].'" type="text/css"/>';
						$this->css['/'.$this->css_filenames['/css/'.$file.'.css'][2].'?v='.$this->css_filenames['/css/'.$file.'.css'][0]] = true;
					} 
				}
			} else {
				$config_files = $theme=='default' ? array($module, $view) : array();
				if (isset($config[$theme][$module])) $config_files = array_merge($config_files, $config[$theme][$module]);
				if (isset($config[$theme][$view])) $config_files = array_merge($config_files, $config[$theme][$view]);
				foreach ($config_files as $config_file) {
					if (! in_array($config_file, $loaded_files)) {
						$loaded_files[] = $config_file;
						if (strpos($config_file, 'http://') === false && strpos($config_file, 'https://') === false ) {
							if (!is_file(BASEPATH.'../css/'.$config_file.'.css')) continue;
							$config_file = $this->css_base.$config_file.'.css';
						} 
						if (!isset($this->css[$config_file])) {
							$css[$config_file] = '	<link rel="stylesheet" href="'.$config_file.'" type="text/css"/>'."\r\n";
							$this->css[$config_file] = true;
						}
					}
				}
			}
		}
		
		if ($this->is_mod_enabled('optimized_js') && $this->grouping->check_css(array_keys($css))) {
			$css = '<link rel="stylesheet" href="'.$this->css_base.'/cached/'.$this->grouping->get_css_cache_url().'" type="text/css"/>'."\r\n";
		} else {
			$css = implode('', $css);
		}
			
		if (preg_match('#</head>#msi', $contents)) {
			$contents = preg_replace('#<head>#msi', "<head>\r\n".$css."\r\n", $contents);
		} else if (!$this->input->is_ajax_request()) {
			$contents = $css.$contents;
		}
		return $contents;
	}
	
	private function add_javascripts($contents) {
		$js = '';
		if ($this->is_mod_enabled('optimized_js') && $this->grouping->check_js($this->js_to_load)) {
			$contents = preg_replace('#require.config\(\{(.*?)urlArgs\: "v=(0)"#msi', 'require.config({urlArgs: "v='.$this->grouping->get_js_cache_version().'"', $contents);
			$contents = preg_replace('#require.config\(\{(.*?)\}#msi', 'require.config({$1	,'.$this->grouping->get_js_cache_url()."\n\t\t\t\t\t}", $contents);
			$js .= '<script type="text/javascript">require(["main"]);</script>';
		} else if ($this->js_to_load) {
			$js .= '<script type="text/javascript">require(["'.implode('","', $this->js_to_load).'"]);</script>';
		}
		if (ENVIRONMENT === 'development' || ENVIRONMENT === 'staging') {
			$js .= 	"<script type=\"text/javascript\">
						if(typeof(console) != 'object' || typeof(console.log) == 'undefined') {
						    window.console = {};
						    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
						}
					</script>";
		} elseif (!isset($_GET['qunit_tests']) || !$_GET['qunit_tests']) {
			$js .= 	"<script type=\"text/javascript\">
					    window.console = {};
					    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
					    if (typeof Firebug != 'undefined' && typeof Firebug.Console != 'undefined') {
					    	Firebug.Console.logRow = function() {}
					    }
					</script>";	
		}

		$pos = mb_strrpos($contents, '</head>');
		if ($pos) {
			$contents = mb_substr($contents, 0, $pos).$js.mb_substr($contents, $pos);
		} else {
			//ajax request wont add js
			//$contents = $js.$contents;
		}
		return $contents;
	}
	
	public function is_mod_enabled($module) {
		return $this->load->is_mod_enabled($module);
	}
	
	public function __get($class) {
		if (strpos($class, '_model') !== FALSE) {
			$this->load->model($class);
		}
		return CI::$APP->$class;
	}
	
	public static function &get_instance()
	{
		return self::$instance;
	}
}
