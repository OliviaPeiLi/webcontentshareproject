<?php
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
	} else {
		define('ENVIRONMENT', 'development');
	}
}

ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
			
if (!defined('BASEPATH')) {
	if(ENVIRONMENT == 'staging') {
		define('BASEPATH', '/home/test.fandrop/current/system/');
	} elseif(ENVIRONMENT == 'production') {
		define('BASEPATH', '/home/fandrop/current/system/');
	} else {
		define('BASEPATH', __DIR__.'/../system/');
	}
}
if (!defined('APPPATH')) define('APPPATH', str_replace('system/', '', BASEPATH).'application/');

require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/pheanstalk/pheanstalk_init.php');

try {
	if (isset($_SERVER['WINDIR'])) { //Windows users
		$pheanstalk = false;
	} else {
		//Mac users, Testing and Production servers
		if (isset($new_server)) { //Defined by $this->is_mod_enabled in newsfeed_model
				if (isset($_SERVER['argv'])) echo "Opening pheanstalk connection to: 5.9.50.78\r\n"; 
				$pheanstalk = new Pheanstalk('5.9.50.78');
		} elseif (isset($self_server)) {
			if (ENVIRONMENT == 'production') {
				if (isset($_SERVER['argv'])) echo "Opening pheanstalk connection to: 192.168.0.3\r\n"; 
				$pheanstalk = new Pheanstalk('192.168.0.3'); //App 1
			} else {
				if (isset($_SERVER['argv'])) echo "Opening pheanstalk connection to: 127.0.0.1\r\n"; 
				$pheanstalk = new Pheanstalk('127.0.0.1');
			}
		} else {
			if (isset($_SERVER['argv'])) echo "Opening pheanstalk connection to: 127.0.0.1\r\n"; 
			$pheanstalk = new Pheanstalk('127.0.0.1');
		}
	}
} catch (Exception $e) {
	$pheanstalk = false;
}

if ( ! function_exists('get_instance')) {
	$config = array();
	if ( ! function_exists('log_message')) {
		function log_message($msg) {
			if ($msg != 'debug') {
				echo "LOG: ".$msg."\r\n";
			}
		}
	}
	if ( ! function_exists('load_class')){
		function load_class($class, $directory = 'libraries', $prefix = 'CI_') {
			return get_instance()->load->library($class);
		}
	}
	
	class CI_lang_lite {
		public function load($conf) { }
		public function line($val) { return false; }
	}
	class CI_exceptions_lite {
		public function show_error($heading, $message, $type) { 
			echo "[ERROR][$type] - $heading - ";
			print_r($message);
			echo " \r\n";
		}
	}
	class CI_cache_lite {
		public function delete($item) {}
		public function get($item) {}
	}
	class CI_session_lite {
		private $data;
		public function userdata($item) { return isset($this->data[$item]) ? $this->data[$item] : null; }
		public function set_userdata($item, $val) { $this->data[$item] = $val; }
	}
	
	class CI_config_lite {
		private $config = array(
			'modules_locations' => array(),
		);
		public function __construct() {
			if (!is_file(APPPATH.'config/config.php')) {
				die('Config file not found: '.APPPATH.'config/config.php'."\n");
			}
			include_once APPPATH.'config/config.php';
			$this->config = array_merge($this->config, $config);
		}
		public function slash_item($item) {
			return rtrim($this->item($item), '/').'/';
		}
		public function item($item) {
			if (isset($this->config[$item])) {
				return $this->config[$item];
			} else {
				echo "[error] config item not found: $item \r\n";
				return ;
			}
		}
		public function set($arr) {
			$this->config = array_merge($this->config, $arr);
		}
		public function site_url($src='') {
			return $this->base_url($src);
		}
		public function base_url($src='') {
			if (ENVIRONMENT == 'production') {
				return 'http://www.fandrop.com/'.$src;
			} else if (ENVIRONMENT == 'staging') {
				return 'http://test.fandrop.com/'.$src;
			} else {
				return 'http://localhost/'.$src;
			}
		}
	}
	
	class CI_load_lite {
		public function helper($name) {
			foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $module) {
				if (is_file($module.'/helpers/'.$name.'_helper.php')) {
					require_once $module.'/helpers/'.$name.'_helper.php';
					return ;
				} elseif (is_file($module.'/helpers/MY_'.$name.'_helper.php')) {
					require_once $module.'/helpers/MY_'.$name.'_helper.php';
				}
			}
			if (is_file(BASEPATH.'/helpers/'.$name.'_helper.php')) {
				require_once BASEPATH.'/helpers/'.$name.'_helper.php';
			}
		}
		
		public function model($name) {
			foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $module) {
				if (is_file($module.'/models/'.$name.'.php')) {
					require_once $module.'/models/'.$name.'.php';
					get_instance()->$name = new $name();
					return get_instance()->$name;
				}
			}
		}
		
		private $config_cache = array();
		public function config($name) {
			if (isset($this->config_cache[$name])) {
				return $this->config_cache[$name];
			}
			
			if (is_file(BASEPATH.'../application/config/'.$name.'.php')) {
				include BASEPATH.'../application/config/'.$name.'.php';
				if (!isset($config)) {
					echo "Config bad format: ".$name."\r\n";
					return ;
				}
				get_instance()->config->set($config);
			}
			foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $module) {
				if (is_file($module.'/config/'.$name.'.php')) {
					$config_main = $config;
					include $module.'/config/'.$name.'.php';
					$config = array_merge($config_main, $config);
					get_instance()->config->set($config);
				}
			}
			if (!isset($config)) {
				echo "Couldnt load config: ".$name."\r\n";
				return ;
			}
			if (!isset($config[$name])) {
				echo "Config not formatted correctly: ".$name."\r\n";
				$this->config_cache[$name] = $config;
				return $config;
			}
			$this->config_cache[$name] = $config[$name];
			return $config[$name];
		}
		
		public function library($name) {
			//echo "Loading lib: ".$name."\n";
			$ci = get_instance();
			if (isset($ci->$name)) {
				return $ci->$name;
			}
			if ($name == 'Lang') {
				$ci->$name = new CI_lang_lite();
				return $ci->$name;
			}
			if ($name == 'Exceptions') {
				$ci->$name = new CI_exceptions_lite();
				return $ci->$name;
			}
			if (is_file(BASEPATH.'/libraries/'.$name.'.php')) {
				require_once BASEPATH.'/libraries/'.$name.'.php';
			}
			if (is_file(BASEPATH.'/libraries/'.ucfirst($name).'.php')) {
				//echo "Adding system lib\n";
				require_once BASEPATH.'/libraries/'.ucfirst($name).'.php';
			}
			foreach (glob(BASEPATH.'../application/modules/*', GLOB_ONLYDIR) as $module) {
				if (is_file($module.'/libraries/'.$name.'.php')) {
					//echo "Loading custom lib 1\n";
					require_once $module.'/libraries/'.$name.'.php';
					if (strpos($name, '/') !== false) list(,$class) = explode('/', $name); else $class = $name;
					$ci->$class = new $class();
					return $ci->$class;
				} else if (is_file($module.'/libraries/'.ucfirst($name).'.php')) {
					//echo "Loading custom lib 2\n";
					$name = ucfirst($name);
					require_once $module.'/libraries/'.$name.'.php';
					$ci->$name = new $name();
					return $ci->$name;
				} else if (is_file($module.'/libraries/MY_'.$name.'.php')) {
					//echo "Adding extension 1\n";
					$name = 'MY_'.$name;
					require_once $module.'/libraries/'.$name.'.php';
					$ci->$name = new $name();
					return $ci->$name;
				} else if (is_file($module.'/libraries/MY_'.ucfirst($name).'.php')) {
					//echo "Adding extension 2\n";
					$name = 'MY_'.ucfirst($name);
					require_once $module.'/libraries/'.$name.'.php';
					$ci->$name = new $name();
					return $ci->$name;
				}
			}
			if (is_file(BASEPATH.'/libraries/'.ucfirst($name).'.php')) {
				$class = 'CI_'.$name;
				$ci->$name = new $class();
				//echo "Loaded system lib: ".$class."\n";
				return $ci->$name;
			}
			echo "[Error] Library not found: ".$name."\r\n";
		}
		
		public function get_package_paths() {
			return array(
				BASEPATH.'../application/modules/fantoon-extensions/'
			);
		}
	}
	
	class CI_lite {
		public $user = false;
		public $css_filenames = null;
		protected $mods = array();
		
		public function __get($var) {
			if ($var == 'db') {
				include(BASEPATH.'../application/config/database.php');
				include_once BASEPATH.'/database/DB.php';
				$this->$var = DB($db[$active_group]);
				return $this->$var;
			}
			if (strpos($var, '_model') !== false) {
				require_once BASEPATH.'/core/Model.php';
				require_once BASEPATH.'../application/core/MY_Model.php';
				$this->load->model($var);
				return $this->$var;
			}
			if (isset($this->{'MY_'.$var})) return $this->{'MY_'.$var};
			if (isset($this->{'MY_'.ucfirst($var)})) return $this->{'MY_'.ucfirst($var)};
			//print_r($this);
			$class = 'CI_'.$var.'_lite';
			$this->$var = new $class();
			
			return $this->$var;
		}
		
		public function is_mod_enabled($module) {
			if (!isset($this->mods[$module])) {
				$row = mysql_fetch_object(mysql_query("SELECT ".ENVIRONMENT." FROM modes_config WHERE name = '{$module}'"));
				$this->mods[$module] = $row ? $row->{ENVIRONMENT} : false;
			}
			return $this->mods[$module];
		}
	}
	
	$CI_lite = new CI_lite();
	
	function get_instance() {
		global $CI_lite;
		return $CI_lite;
	}
	
	//For the autoloading
	global $CFG;
	if ( ! is_a($CFG, 'MX_Config')) $CFG = get_instance()->config;
	require_once APPPATH.'modules/fantoon-extensions/helpers/helper.php';
	include_once APPPATH.'modules/fantoon-extensions/libraries/MX/Modules.php';
	
	function config_item($item) {
		get_instance()->config->item($item);
	}
}
