<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Loader class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Loader.php
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
class MX_Loader extends CI_Loader
{
	protected $_module;
	protected $_ci_view_paths;
	protected $_ci_models;
	
	public $_ci_plugins = array();
	public $_ci_cached_vars = array();
	public $loaded_views = array();
	
	private $info = array();
	private $template_var = array();
	
	public function __construct() {
		parent::__construct();
		
		/* set the module name */
		$this->_module = CI::$APP->router->fetch_module();
		
		/* add this module path to the loader variables */
		$this->_add_module_paths($this->_module);
		
	}
	
	/** Initialize the module **/
	public function _init($controller) {
		$this->initialize();
		/* references to ci loader variables */
		foreach (get_class_vars('CI_Loader') as $var => $val) {
			if ($var != '_ci_ob_level') $this->$var =& CI::$APP->load->$var;
		}
		
		/* set a reference to the module controller */
 		$this->controller = $controller;
 		$this->__construct();
	}

	/** Add a module path loader variables **/
	public function _add_module_paths($module = '') {
		
		if (empty($module)) return;
		
		foreach (Modules::$locations as $location => $offset) {
			
			/* only add a module path if it exists */
			if (is_dir($module_path = $location.$module.'/')) {
				array_unshift($this->_ci_model_paths, $module_path);
			}
		}
	}	
	
	/** Load a module config file **/
	public function config($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE) {
		return CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
	}

	/** Load the database drivers **/
	public function database($params = '', $return = FALSE, $active_record = NULL) {
		
		if (class_exists('CI_DB', FALSE) AND $return == FALSE AND $active_record == NULL AND isset(CI::$APP->db) AND is_object(CI::$APP->db)) 
			return;

		require_once BASEPATH.'database/DB'.EXT;

		if ($return === TRUE) return DB($params, $active_record);
			
		CI::$APP->db = DB($params, $active_record);
		
		return CI::$APP->db;
	}

	/** Load a module helper **/
	public function helper($helper) {
		if (is_array($helper)) return $this->helpers($helper);
		
		if (isset($this->_ci_helpers[$helper]))	return;
		if (strpos($helper, '/') !== false) {
			list($module, $helper) = explode('/', $helper);
		} else {
			$module = $this->_module;
		} 
		list($path, $_helper) = Modules::find($helper.'_helper', $module, 'helpers/');
		
		if ($path) {
			Modules::load_file($_helper, $path);
			$this->_ci_helpers[$_helper] = TRUE;
		}
		
		if ($path === FALSE || strpos($_helper, 'MY_') !== false) {
			return parent::helper($helper.'_helper');
		}
	}

	/** Load an array of helpers **/
	public function helpers($helpers) {
		foreach ($helpers as $_helper) $this->helper($_helper);	
	}

	/** Load a module language file **/
	public function language($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {
		return CI::$APP->lang->load($langfile, $idiom, $return, $add_suffix, $alt_path, $this->_module);
	}
	
	public function languages($languages) {
		foreach($languages as $_language) $this->language($language);
	}
	
	public function get_package_paths($include_base = FALSE)
	{
		$paths = $include_base === TRUE ? $this->_ci_library_paths : $this->_ci_model_paths;
		if ($include_base) {
			$paths[] = APPPATH.'modules/fantoon-extensions/';
		} else {
			$paths[] = APPPATH.'modules/fantoon-extensions/';
		}
		return $paths;
	}
	
	/** Load a module library **/
	public function library($library = '', $params = NULL, $object_name = NULL) {
		
		if (is_array($library)) return $this->libraries($library);		
		
		$class = strtolower(basename($library));

		if (isset($this->_ci_classes[$class]) AND $_alias = $this->_ci_classes[$class])
			return CI::$APP->$_alias;
			
		($_alias = strtolower($object_name)) OR $_alias = $class;
		
		list($path, $_library) = Modules::find($library, $this->_module, 'libraries/');
		
		/* load library config file as params */
		if ($params == NULL) {
			list($path2, $file) = Modules::find($_alias, $this->_module, 'config/');	
			($path2) AND $params = Modules::load_file($file, $path2, 'config');
		}
			
		if ($path === FALSE || strpos($_library, 'MY_') !== false) {
			$this->_ci_load_class($library, $params, $object_name);
			$_alias = $this->_ci_classes[$class];
			CI::$APP->$_alias = get_instance()->$class;
		}
		
		if ($path) {
			Modules::load_file($_library, $path);
			
			$library = ucfirst($_library);
			CI::$APP->$_alias = new $library($params);
			
			$this->_ci_classes[$class] = $_alias;
			get_instance()->$class = CI::$APP->$_alias;
		}
		
		
		return CI::$APP->$_alias;
    }

	/** Load an array of libraries **/
	public function libraries($libraries) {
		foreach ($libraries as $_library) $this->library($_library);	
	}

	/** Load a module model **/
	public function model($model, $object_name = NULL, $connect = FALSE) {
		
		if (is_array($model)) return $this->models($model);

		($_alias = $object_name) OR $_alias = basename($model);

		if (in_array($_alias, $this->_ci_models, TRUE)) 
			return CI::$APP->$_alias;
			
		/* check module */
		list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
		
		if ($path == FALSE) {
			
			/* check application & packages */
			parent::model($model, $object_name);
			CI::$APP->$_alias = get_instance()->$model;
		} else {
			
			class_exists('CI_Model', FALSE) OR load_class('Model', 'core');
			
			if ($connect !== FALSE AND ! class_exists('CI_DB', FALSE)) {
				if ($connect === TRUE) $connect = '';
				$this->database($connect, FALSE, TRUE);
			}
			
			Modules::load_file($_model, $path);
			
			$model = ucfirst($_model);
			CI::$APP->$_alias = new $model();
			
			$this->_ci_models[] = $_alias;
		}
		 
		return CI::$APP->$_alias;
	}

	/** Load an array of models **/
	public function models($models) {
		foreach ($models as $_model) $this->model($_model);	
	}

	/** Load a module controller **/
	public function module($module, $params = NULL)	{
		
		if (is_array($module)) return $this->modules($module);

		$_alias = strtolower(basename($module));
		CI::$APP->$_alias = Modules::load(array($module => $params));
		return CI::$APP->$_alias;
	}

	/** Load an array of controllers **/
	public function modules($modules) {
		foreach ($modules as $_module) $this->module($_module);	
	}

	/** Load a module plugin **/
	public function plugin($plugin)	{
		
		if (is_array($plugin)) return $this->plugins($plugin);		
		
		if (isset($this->_ci_plugins[$plugin]))	
			return;

		list($path, $_plugin) = Modules::find($plugin.'_pi', $this->_module, 'plugins/');	
		
		if ($path === FALSE AND ! is_file($_plugin = APPPATH.'plugins/'.$_plugin.EXT)) {	
			show_error("Unable to locate the plugin file: {$_plugin}");
		}

		Modules::load_file($_plugin, $path);
		$this->_ci_plugins[$plugin] = TRUE;
	}

	/** Load an array of plugins **/
	public function plugins($plugins) {
		foreach ($plugins as $_plugin) $this->plugin($_plugin);	
	}

	/** Load a module view **/
	public function view($view, $vars = array(), $return = FALSE)
	{
		$path = $this->is_mod_enabled('new_theme') ? 'views_new' : 'views';
		list($path, $_view) = Modules::find($view, $this->_module, $path.'/');
		if ($path != FALSE) {
			$this->_ci_view_paths[$path] = TRUE;
			$view = $_view;			
		}

		if($this->is_mod_enabled('undefined_var_checker'))
		{
			//$viewpath = realpath(BASEPATH."../$path$view".".php");
			$viewpath = realpath($path . $view . ".php");
			//print_r($path); print_r($view);
			//print_r($viewpath); exit();
			$viewpath = preg_split("/application/", $viewpath);

			// passed variables to view
			$passed_var = $this->_ci_object_to_array($vars);
			if (is_array($this->template_var) && is_array($passed_var)) {
				$this->template_var = array_merge($passed_var, $this->template_var);
			}
			else if (!is_array($passed_var)) {
				$this->template_var[] = $passed_var;
			}
			else {
				$this->template_var = $passed_var;
			}

			$passed_var = $this->template_var;

			$viewpath =  trim(@$viewpath[1], DIRECTORY_SEPARATOR); 
			$viewpath = str_replace('\\', '/', $viewpath); //for windows paths

			// activate checker (in undefined_var_model)
			$this->load->model('undefined_var_model');
			$this->undefined_var_model->set_debugger($viewpath, $passed_var);
		}

		/**** get view name and variables passed to the view ****/
		if($this->is_mod_enabled('views_variables'))
		{
			$variables = $vars;

			if(is_array($vars)){
				//we can retrieve also the variable type 
				/*
				foreach($vars as $key=>$value){
					$variables[$key] = gettype($value); 
				}
				*/
				$variables = implode(",", array_keys($vars));
			}
			
			$count_loaded = isset($this->info[$view]) ? $this->info[$view]['count_loaded'] : 0;
			$count_loaded ++;

			$this->info[$view] = array(			
				'controller' => $this->uri->rsegment(1),
				'function' 	=> $this->uri->rsegment(2),
				'module'	=> $this->_module,	
				'url'		=> $this->uri->uri_string(),
				'view_location'	=> $path,
				'view'		=> $view,
				'count_loaded'=> $count_loaded,	
				'vars' 		=> $variables,	
			);
		}

		/*** end  views_variables module code ***/


		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}
	
	/** Get the list of views for current page **/
	public function view_paths($set=null) {
		if ($set) {
			$this->loaded_views = array_merge($this->loaded_views, $set);
		}
		return $this->loaded_views;
	}

	public function _ci_is_instance() {}

	public function _ci_get_component($component) {
		return CI::$APP->$component;
	} 

	public function __get($class) {
		if ($class == 'load') return $this;
		if (isset($this->controller) && isset($this->controller->$class)) return $this->controller->$class;
		//if (isset(get_instance()->$class)) 
		return get_instance()->$class;
		//return CI::$APP->$class;
	}

	private $css = array();
	public function _ci_load($_ci_data) {
		
		extract($_ci_data);
		
		if (isset($_ci_view)) {
			$_ci_path = '';
			$_ci_file = strpos($_ci_view, '.') ? $_ci_view : $_ci_view.EXT;
			foreach (array_reverse($this->_ci_view_paths) as $path => $cascade) {				
				if (file_exists($view = $path.$_ci_file)) {
					$_ci_path = $view;
					break;
				}
				
				if ( ! $cascade) break;
			}
		} elseif (isset($_ci_path)) {
			
			$_ci_file = basename($_ci_path);
			if( ! file_exists($_ci_path)) $_ci_path = '';
		}

		if (empty($_ci_path)) {
			throw new Exception('Unable to load the requested file: '.$_ci_file);
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		if (isset($_ci_vars)) 
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, (array) $_ci_vars);
		
		extract($this->_ci_cached_vars);
		
		$requireJS_module = rtrim(str_replace(array('views/','views_new/'), '', substr($_ci_path, 0, strrpos($_ci_path, '/')+1)),'/');
		$requireJS_module = substr($requireJS_module, strrpos($requireJS_module, '/')+1).'/'.str_replace('.php', '', $_ci_file);
		//Check if the requireJS definition exists
		$requireJS_module_exists =  is_file(BASEPATH.'../js/modules/'.$requireJS_module.'.js');
		//if (preg_match('#<script.*?require\(\[.*?'.$requireJS_module.'.*?\]#msi', $data)) {
		ob_start();
		if ($this->is_mod_enabled('view_debug') && strpos($_ci_path, 'template.php') === false 
			&& strpos($_ci_path, 'includes/header') === false && strpos($_ci_path, 'includes/header') === false
			&& strpos($_ci_path, 'bookmarklet/') === false
		) {
			echo "<!-- begin of ( {$_ci_path} ) -->\n";
		} 
		
		if ((bool) @ini_get('short_open_tag') === FALSE AND CI::$APP->config->item('rewrite_short_tags') == TRUE) {
			eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		} else {
			include($_ci_path); 
		}
		
		if ($this->is_mod_enabled('view_debug') && strpos($_ci_path, 'bookmarklet/') === false) {
			echo "<!-- end of ( {$_ci_path} ) -->\n";
		} 
		
		log_message('debug', 'File loaded: '.$_ci_path);
		
		$this->loaded_views[] = $requireJS_module;
		
		if (ob_get_level() > $this->_ci_ob_level + 1) {
			//ob_end_flush();
			$contents = ob_get_clean();
			if ($_ci_return) return $contents;
			echo $contents;
		} else {
			$contents = ob_get_contents();
			ob_clean();
			if ($_ci_return) return $contents;
			get_instance()->output->append_output($contents);
		}
	}
	
	/** Autoload module items **/
	public function _autoloader($autoload) {
		
		$path = FALSE;
		
		if ($this->_module) {
			
			list($path, $file) = Modules::find('constants', $this->_module, 'config/');	
			
			/* module constants file */
			if ($path != FALSE) {
				include_once $path.$file.EXT;
			}
					
			list($path, $file) = Modules::find('autoload', $this->_module, 'config/');
		
			/* module autoload file */
			if ($path != FALSE) {
				$autoload = array_merge(Modules::load_file($file, $path, 'autoload'), $autoload);
			}
		}
		
		/* nothing to do */
		if (count($autoload) == 0) return;
		
		/* autoload package paths */
		if (isset($autoload['packages'])) {
			foreach ($autoload['packages'] as $package_path) {
				$this->add_package_path($package_path);
			}
		}
				
		/* autoload config */
		if (isset($autoload['config'])) {
			foreach ($autoload['config'] as $config) {
				$this->config($config);
			}
		}

		/* autoload helpers, plugins, languages */
		foreach (array('helper', 'plugin', 'language') as $type) {
			if (isset($autoload[$type])){
				foreach ($autoload[$type] as $item) {
					$this->$type($item);
				}
			}
		}	
			
		/* autoload database & libraries */
		if (isset($autoload['libraries'])) {
			if (in_array('database', $autoload['libraries'])) {
				/* autoload database */
				if ( ! $db = CI::$APP->config->item('database')) {
					$db['params'] = 'default';
					$db['active_record'] = TRUE;
				}
				$this->database($db['params'], FALSE, $db['active_record']);
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			/* autoload libraries */
			foreach ($autoload['libraries'] as $library) {
				$this->library($library);
			}
		}
		
		/* autoload models */
		if (isset($autoload['model'])) {
			foreach ($autoload['model'] as $model => $alias) {
				(is_numeric($model)) ? $this->model($alias) : $this->model($model, $alias);
			}
		}
		
		/* autoload module controllers */
		if (isset($autoload['modules'])) {
			foreach ($autoload['modules'] as $controller) {
				($controller != $this->_module) AND $this->module($controller);
			}
		}
		
	}
	
	private $_modules = array();
	public function is_mod_enabled($module) {
		$this->load->model('modules_config_model');
		if (isset($this->_modules[$module])) return $this->_modules[$module]; //cache
		$this->_modules[$module] = false;

		if(isset($this->cache)) {
			$cache_key = 'modules_config_'.$module;
			if(!$module_data = $this->cache->get($cache_key)) {
				$module_data = $this->modules_config_model->get_by(array('name' => $module));
				$this->cache->save($cache_key, $module_data);
			}
		} else {
			$module_data = $this->modules_config_model->get_by(array('name' => $module));
		}
		
		if ($module_data)
		{
			if ($module_data->custom != '')
			{
				list($field, $val) = explode('=', $module_data->custom, 2);
				if ($this->user &&$field=='user') {
					if (@$this->user->id == $val) $this->_modules[$module] =  true;
				} elseif ($this->user &&$field == 'role') {
					$roles = strpos($val, ',') !== false ? explode(',', $val) : array($val);
					foreach ($roles as &$role) $role = trim($role);
					if (in_array($this->user->role, $roles)) $this->_modules[$module] =  true;
				} elseif ($this->user && $field == 'users') {
					if (in_array($val, explode(',', $val))) $this->_modules[$module] =  true;
				} elseif ($field == 'HTTP_HOST') {
					if ($_SERVER['HTTP_HOST'] == $val) $this->_modules[$module] =  true;
				} elseif ($field == 'HTTP_HOSTS') {
					if (in_array($_SERVER['HTTP_HOST'], explode(',', $val))) $this->_modules[$module] =  true;
				}
			} elseif ($module_data->{ENVIRONMENT} == 1) {
				$this->_modules[$module] =  true;  		//Enabled for the current ENVIRONMENT
			}
			//if (empty($this->_modules[$module])) return true;   //Enabled for all
			//if (in_array(@$this->user->id, $this->_modules[$module])) return true; //Enabled for current user
		}
		return $this->_modules[$module];
	}
	
	/**
	 * Save information regarding views and variables in csv file
	 */
	function __destruct() 
	{
		if($this->is_mod_enabled('views_variables'))
		{

			//save information into csv file
			if($this->info){

				$controller = $this->uri->rsegment(1);
				$function = $this->uri->rsegment(2);

				//$folder = getcwd() . DIRECTORY_SEPARATOR . "view_variables" . DIRECTORY_SEPARATOR;

				$folder = str_replace('system/', '', BASEPATH) . "view_variables/";
				$filename = $controller."_".$function."_".date('d_m_his').".csv";

					
				$fp = fopen($folder.$filename, 'w');
				
				//insert header
				fputcsv($fp, array('controller', 'function', 'module', 'url', 'view_location', 'view',  'count_loaded', 'vars'));

				//insert rows
				foreach ($this->info as $fields) {
					fputcsv($fp, $fields);
				}

				fclose($fp);
			}

		}
	}
}

/** load the CI class for Modular Separation **/
(class_exists('CI', FALSE)) OR require dirname(__FILE__).'/Ci.php';