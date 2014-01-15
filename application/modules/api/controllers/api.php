<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API controller
 *
 * based on rest controller
 * @see https://github.com/philsturgeon/codeigniter-restserver
 *
 */

class API extends MX_Controller
{

	protected $model = NULL;
	protected $item_id = NULL;
	protected $rest_format = NULL; // Set this in a controller to use a default format
	protected $methods = array(); // contains a list of method properties such as limit, log and level
	protected $request = NULL; // Stores accept, language, body, headers, etc
	protected $response = NULL; // What is gonna happen in output?
	protected $rest = NULL; // Stores DB, keys, key level, etc
	protected $_get_args = array();
	protected $_post_args = array();
	protected $_put_args = array();
	protected $_delete_args = array();
	protected $_args = array();
	protected $_allow = TRUE;
	protected $cache_item;

	// List all supported methods, the first will be the default format
	protected $_supported_formats = array(
										'xml' => 'application/xml',
										'rawxml' => 'application/xml',
										'json' => 'application/json',
										'jsonp' => 'application/javascript',
										'serialized' => 'application/vnd.php.serialized',
										'php' => 'text/plain',
										'html' => 'text/html',
										'csv' => 'application/csv'
									);

	// Constructor function
	public function __construct()
	{
		parent::__construct();

		// Lets grab the config and get ready to party
		$this->load->config('api/rest');
		
		$this->output->enable_profiler(false);

		// How is this request being made? POST, DELETE, GET, PUT?
		$this->request = new stdClass;
		$this->request->method = $this->_detect_method();

		// Set up our GET variables
		$this->_get_args = array_merge($this->_get_args, $this->uri->ruri_to_assoc());

		//$this->load->library('security');

		// This library is bundled with REST_Controller 2.5+, but will eventually be part of CodeIgniter itself
		$this->load->library('api/format');
		$this->load->library('api/api_objects');

		// Try to find a format for the request (means we have a request body)
		$this->request->format = $this->_detect_input_format();

		// Some Methods cant have a body
		$this->request->body = NULL;

		switch ($this->request->method)
		{
		case 'get':
			// Grab proper GET variables
			parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $get);
			if (isset($get['callback'])) {
				$get['format'] = 'jsonp';
			}

			if (isset($get['format'])) {
				$this->rest_format = $get['format'];
				if ($get['format'] == 'jsonp' && isset($get['method'])) {
					$this->request->method = $get['method'];
					if ($get['method'] == 'post') {
						$_POST = $get;
						unset($_POST['method'], $_POST['format'], $_POST['callback'], $_POST['_']);
					}
				}
			}
			// Merge both the URI segements and GET params
			$this->_get_args = array_merge($this->_get_args, $get);
			break;

		case 'post':
			$this->_post_args = $_POST;

			$this->request->format and $this->request->body = file_get_contents('php://input');
			break;

		case 'put':
			// It might be a HTTP body
			if ($this->request->format)
			{
				$this->request->body = file_get_contents('php://input');
			}

			// If no file type is provided, this is probably just arguments
			else
			{
				parse_str(file_get_contents('php://input'), $this->_put_args);
			}

			break;

		case 'delete':
			// Set up out DELETE variables (which shouldn't really exist, but sssh!)
			parse_str(file_get_contents('php://input'), $this->_delete_args);
			break;
		}

		// Now we know all about our request, let's try and parse the body if it exists
		if ($this->request->format and $this->request->body)
		{
			$this->request->body = $this->format->factory($this->request->body, $this->request->format)->to_array();
		}

		// Merge both for one mega-args variable
		$this->_args = array_merge($this->_get_args, $this->_put_args, $this->_post_args, $this->_delete_args);

		// Which format should the data be returned in?
		$this->response = new stdClass;
		$this->response->format = $this->_detect_output_format();

		// Which format should the data be returned in?
		$this->response->lang = $this->_detect_lang();

		// Check if there is a specific auth type for the current class/method
		$this->auth_override = $this->_auth_override_check();

		// When there is no specific override for the current class/method, use the default auth value set in the config
		if ( $this->auth_override !== TRUE )
		{
			if ($this->config->item('rest_auth') == 'basic')
			{
				$this->_prepare_basic_auth();
			}
			elseif ($this->config->item('rest_auth') == 'digest')
			{
				$this->_prepare_digest_auth();
			}
			elseif ($this->config->item('rest_auth') == 'default')
			{
				$this->_prepare_default_auth();
			}
			elseif ($this->config->item('rest_ip_whitelist_enabled'))
			{
				if (!$this->_check_whitelist_auth()) {
					$this->_prepare_default_auth();
				}
			}
		}

		// Load DB if its enabled
		if (config_item('rest_database_group') AND (config_item('rest_enable_keys') OR config_item('rest_enable_logging')))
		{
			$this->rest->db = $this->load->database(config_item('rest_database_group'), TRUE);
		}

		// Checking for keys? GET TO WORK!
		if (config_item('rest_enable_keys'))
		{
			$this->_allow = $this->_detect_api_key();
		}

		// only allow ajax requests
		if ( ! $this->input->is_ajax_request() AND config_item('rest_ajax_only') )
		{
			$this->response( array('status' => false, 'error' => 'Only AJAX requests are accepted.'), 505 );
		}
	}

	/*
	 * Remap
	 *
	 * Requests are not made to methods directly The request will be for an "object".
	 * this simply maps the object and method to the correct Controller method.
	 */
	public function _remap($object_called, $arguments)
	{
		$pattern = '/^(.*)\.(' . implode('|', array_keys($this->_supported_formats)) . ')$/';
		if (preg_match($pattern, $object_called, $matches))
		{
			$object_called = $matches[1];
		}
		
		if ($object_called+1 > 1) {
			$this->item_id = $object_called;
			$object_called = 'item';
		} elseif (get_class($this) == 'Me' && !in_array($object_called, array('index')) ) {
			$this->item_id = $this->user->id;
			$arguments[] = $object_called;
			$object_called = 'item';
		}
		$controller_method = $object_called . '_' . $this->request->method;
		
		// Do we want to log this method (if allowed by config)?
		$log_method = ! (isset($this->methods[$controller_method]['log']) AND $this->methods[$controller_method]['log'] == FALSE);

		// Use keys for this method?
		$use_key = ! (isset($this->methods[$controller_method]['key']) AND $this->methods[$controller_method]['key'] == FALSE);

		// Get that useless shitty key out of here
		if (config_item('rest_enable_keys') AND $use_key AND $this->_allow === FALSE)
		{
			if (config_item('rest_enable_logging') AND $log_method)
			{
				$this->_log_request();
			}

			$this->response(array('status' => false, 'error' => 'Invalid API Key.'), 403);
		}

		// Sure it exists, but can they do anything with it?
		if ( ! method_exists($this, $controller_method))
		{
			$this->response(array('status' => false, 'error' => 'Unknown method: '.$controller_method), 404);
		}
		
		// Doing key related stuff? Can only do it if they have a key right?
		if (config_item('rest_enable_keys') AND ! empty($this->rest->key))
		{
			// Check the limit
			if (config_item('rest_enable_limits') AND ! $this->_check_limit($controller_method))
			{
				$this->response(array('status' => false, 'error' => 'This API key has reached the hourly limit for this method.'), 401);
			}

			// If no level is set use 0, they probably aren't using permissions
			$level = isset($this->methods[$controller_method]['level']) ? $this->methods[$controller_method]['level'] : 0;

			// If no level is set, or it is lower than/equal to the key's level
			$authorized = $level <= $this->rest->level;

			// IM TELLIN!
			if (config_item('rest_enable_logging') AND $log_method)
			{
				$this->_log_request($authorized);
			}

			// They don't have good enough perms
			$authorized OR $this->response(array('status' => false, 'error' => 'This API key does not have enough permissions.'), 401);
		}

		// No key stuff, but record that stuff is happening
		else if (config_item('rest_enable_logging') AND $log_method)
		{
			$this->_log_request($authorized = TRUE);
		}
		
		// And...... GO!
		if (!$this->_args || (isset($this->_args['page']) && $this->_args['page'] <= 10 )) {
			$this->cache_item = strtolower(get_class($this)).'_'.$controller_method.(isset($arguments['page']) ? '_'.$arguments['page'] : '');
		}
		/* // cause api headers response bug - not return header !
		if ($this->cache_item && $ret = $this->cache->get($this->cache_item)) {
			exit($ret);
		}
		*/
		call_user_func_array(array($this, $controller_method), $arguments);
	}

	/**
	 * Default REST
	 */
	protected function model()
	{
		if ( ! $this->model)
		{
			$this->model = strtolower(get_class($this)) == 'api' ?  false : strtolower(get_class($this)).'_model';
		}
		return $this->model;
	}

	private $_item = null;
	public function item($item=null) {
		if ($item) {
			$this->_item = $item;
		}
		else if ( ! $this->_item) {
			if ( ! $this->_item = $this-> {$this->model()}->get($this->item_id)) {
				return $this->response('Not found', 404);
			}
		}
		return $this->_item;
	}

	protected function filter($items)
	{
		$get = $this->input->get();
		if ($get['filter'] && is_array($get['filter'])) foreach ($get['filter'] as $field=>$val) {
			$this->db->where($field, $val);
		}
		return $items;
	}
	
	public function get_model($page_limit=10, $sort_table = '') {
		$model = $this->{$this->model()};
		$model = $this->filter($model);

		$page_limit = $this->input->get('page_limit', true, $page_limit);
		$page = $this->input->get('page', true, 1);
		$model = $model->paginate($page, $page_limit);

		$sort_by = $this->input->get('sort_by', true, $sort_table . $model->primary_key());
		$sort_order = $this->input->get('sort_order', true, 'desc');
		$model = $model->order_by($sort_by, $sort_order);
		
		return $model;
	}

	public function index_get($page_limit=10, $return_result = false) {
		if (! $this->model()) {
			return $this->load->view('api/test_page');
		}
		$model = $this->get_model($page_limit);	
		$items = $this->api_objects->convert($model->get_all());

		if ($return_result)	{
			return array($items,$model->pagination->total_rows);
		}

		$this->response(array(
			'results' => (array)$items
		), 200); // 200 being the HTTP response code
	}

	public function item_get($child_model=NULL, $page_limit=10) {
		if ($child_model) {
			$has_many = $this->item()->model->has_many();
			if (!isset($has_many[$child_model])) {
				$this->response("Child object not found: ".$child_model." of ".get_class($this->item()->_model), 404);
			}
			$controller = $this->get_child($has_many[$child_model]['foreign_model']);
			$_GET['filter'][$has_many[$child_model]['foreign_column']] = $this->item()->primary_key();
			return $controller->index_get($page_limit);
		} else {
			$this->response($this->api_objects->convert($this->item(), true), 200); // 200 being the HTTP response code
		}
	}
	
	public function get_child($child_model) {
		$child_controller = Inflector_helper::singular($child_model);
		if (!file_exists(dirname(__FILE__).'/'.$child_controller.EXT)) {
			$this->response("Child controller not found: ".$child_controller, 404);
		}
		include_once dirname(__FILE__).'/'.$child_controller.EXT;
		
		$controller = new $child_controller();
		$controller->user = $this->user;
		return $controller;
	}
	
	public function index_post() {

		$post = $this->input->post();

		if ($data = $this->{$this->model()}->validate($post)) {
			$primary_key = $this->{$this->model()}->update_or_insert($data);
			return $this->response(array('result'=>true,'id'=>$primary_key), 200);
		} else {
			return $this->response(array('result'=>false,'errors'=>Form_Helper::validation_errors()), 200);
		}

	}

	public $remove_watermarks;
	public function item_post($child_model=null) {
		$post = $this->input->post();
		if ($child_model) {
			$has_many = $this->item()->model->has_many();
			if (!isset($has_many[$child_model])) {
				$this->response("Child object not found: ".$child_model." of ".get_class($this->item()->_model), 404);
			}
			$controller = $this->get_child($child_model);
			$controller->response->format = 'boolean';
			
			unset($post['ci_csrf_token']);
			//@todo - this maybe should go to the model
			$objects = array();
			foreach ($post as $field=>$objs) {
				foreach ($objs as $key=>$val) {
					$objects[$key][$field] = $val;
				}
			}
			foreach ($this->item()->{$child_model} as $item) {
				$found = false;
				foreach ($objects as $key=>$object) {
					$is_same = true;
					foreach ($object as $field=>$val) {
						if ($item->$field != $val) { $is_same = false; break; }
					}
					if ($is_same) { $found = true; unset($objects[$key]); break; }
				}
				if (!$found) {
					$controller->item($item);
					$controller->item_delete();
				}
			}
			foreach ($objects as $object) {
				$object[$has_many[$child_model]['foreign_column']] = $this->item()->primary_key();
				$_POST = $object;
				$controller->item_post();
			}
			return $this->response(array('result'=>true), 200);			
		} else {
			$model = $this->model();
			$this->$model = $this->$model;
			$model = new $model();
			if ($this->remove_watermarks) {
				foreach ($model->behaviors['uploadable'] as $field=>$params) {
					foreach ($params['thumbnails'] as $thumb=>$thumb_attrs) {
						unset($model->behaviors['uploadable'][$field]['thumbnails'][$thumb]['transform']['watermark']);
					}
				}
			}
			if ($this->item_id) {
				$post[$model->primary_key()] = $this->item_id;
			}
			if ($data = $model->validate($post)) {
				$primary_key = $model->update_or_insert($data);
				$this->response(array('result'=>true,'id'=>$primary_key), 200);
			} else {
				$this->response(array('result'=>false,'errors'=>Form_Helper::validation_errors()), 200);
			}
		}
	}
	
	public function item_put($child_model) {
		$has_many = $this->item()->model->has_many();
		if (!isset($has_many[$child_model])) {
			$this->response("Child object not found: ".$child_model." of ".get_class($this->item()->_model), 404);
		}
		$controller = $this->get_child($has_many[$child_model]['foreign_model']);
		$_POST = $this->_put_args;
		$_POST[$has_many[$child_model]['foreign_column']] = $this->item()->primary_key();
		return $controller->item_post();
	}

	public function item_delete($child_model=null) {
		if ($child_model) {
			$has_many = $this->item()->model->has_many();
			if (!isset($has_many[$child_model])) {
				$this->response("Child object not found: ".$child_model." of ".get_class($this->item()->_model), 404);
			}
			$controller = $this->get_child($has_many[$child_model]['foreign_model']);
			$controller->response->format = 'boolean';
			foreach ($this->item()->{$child_model} as $item) {
				$controller->item($item);
				$controller->item_delete();
			}
			$this->response(array('result'=>true), 200);
		} else {
			$this->item()->delete();
			$this->response(array('result'=>true), 200);
		}
	}

	/*
	 * response
	 *
	 * Takes pure data and optionally a status code, then creates the response
	 */
	public function response($data = array(), $http_code = null)
	{
		// If data is empty and not code provide, error and bail
		if (empty($data) && $http_code === null)
		{
			$http_code = 404;

			//create the output variable here in the case of $this->response(array());
			$output = $data;
		}

		// Otherwise (if no data but 200 provided) or some data, carry on camping!
		else
		{
			// all the compression settings must be done before sending any headers
			// if php is not handling the compression by itself
			
			is_numeric($http_code) OR $http_code = 200;
			
			if ($this->response->format == 'boolean') {
				return isset($data['status']) ? $data['status'] : true;
			}

			// If the format method exists, call and return the output in that format
			if (method_exists($this, '_format_'.$this->response->format))
			{
				// Set the correct format header
				header('Content-Type: '.$this->_supported_formats[$this->response->format]);

				$output = $this-> {'_format_'.$this->response->format}($data);
			}

			// If the format method exists, call and return the output in that format
			elseif (method_exists($this->format, 'to_'.$this->response->format))
			{
				// Set the correct format header
				header('Content-Type: '.$this->_supported_formats[$this->response->format]);

				$output = $this->format->factory($data)-> {'to_'.$this->response->format}();
			}

			// Format not supported, output directly
			else
			{
				$output = $data;
			}
		}

		if ($this->rest_format != 'jsonp')
		{
			header('HTTP/1.1: ' . $http_code);
			header('Status: ' . $http_code);
		}
		
		if ($this->cache_item) {
			$this->cache->save($this->cache_item, $output);
		}
		exit($output);
	}

	/*
	 * Detect input format
	 *
	 * Detect which format the HTTP Body is provided in
	 */
	protected function _detect_input_format()
	{
		if ($this->input->server('CONTENT_TYPE'))
		{
			// Check all formats against the HTTP_ACCEPT header
			foreach ($this->_supported_formats as $format => $mime)
			{
				if (strpos($match = $this->input->server('CONTENT_TYPE'), ';'))
				{
					$match = current(explode(';', $match));
				}

				if ($match == $mime)
				{
					return $format;
				}
			}
		}

		return NULL;
	}

	/*
	 * Detect format
	 *
	 * Detect which format should be used to output the data
	 */
	protected function _detect_output_format()
	{
		$pattern = '/\.(' . implode('|', array_keys($this->_supported_formats)) . ')$/';

		// Check if a file extension is used
		if (preg_match($pattern, $this->uri->uri_string(), $matches))
		{
			return $matches[1];
		}

		// Check if a file extension is used
		elseif ($this->_get_args AND ! is_array(end($this->_get_args)) AND preg_match($pattern, end($this->_get_args), $matches))
		{
			// The key of the last argument
			$last_key = end(array_keys($this->_get_args));

			// Remove the extension from arguments too
			$this->_get_args[$last_key] = preg_replace($pattern, '', $this->_get_args[$last_key]);
			$this->_args[$last_key] = preg_replace($pattern, '', $this->_args[$last_key]);

			return $matches[1];
		}

		// A format has been passed as an argument in the URL and it is supported
		if (isset($this->_get_args['format']) AND array_key_exists($this->_get_args['format'], $this->_supported_formats))
		{
			return $this->_get_args['format'];
		}

		// Otherwise, check the HTTP_ACCEPT (if it exists and we are allowed)
		if ($this->config->item('rest_ignore_http_accept') === FALSE AND $this->input->server('HTTP_ACCEPT'))
		{
			// Check all formats against the HTTP_ACCEPT header
			foreach (array_keys($this->_supported_formats) as $format)
			{
				// Has this format been requested?
				if (strpos($this->input->server('HTTP_ACCEPT'), $format) !== FALSE)
				{
					// If not HTML or XML assume its right and send it on its way
					if ($format != 'html' AND $format != 'xml')
					{

						return $format;
					}

					// HTML or XML have shown up as a match
					else
					{
						// If it is truely HTML, it wont want any XML
						if ($format == 'html' AND strpos($this->input->server('HTTP_ACCEPT'), 'xml') === FALSE)
						{
							return $format;
						}

						// If it is truely XML, it wont want any HTML
						elseif ($format == 'xml' AND strpos($this->input->server('HTTP_ACCEPT'), 'html') === FALSE)
						{
							return $format;
						}
					}
				}
			}
		} // End HTTP_ACCEPT checking

		// Well, none of that has worked! Let's see if the controller has a default
		if ( ! empty($this->rest_format))
		{
			return $this->rest_format;
		}

		// Just use the default format
		return config_item('rest_default_format');
	}

	/*
	 * Detect method
	 *
	 * Detect which method (POST, PUT, GET, DELETE) is being used
	 */

	protected function _detect_method()
	{
		$method = strtolower($this->input->server('REQUEST_METHOD'));

		if ($this->config->item('enable_emulate_request') && $this->input->post('_method'))
		{
			$method =  $this->input->post('_method');
		}

		if (in_array($method, array('get', 'delete', 'post', 'put')))
		{
			return $method;
		}

		return 'get';
	}

	/*
	 * Detect API Key
	 *
	 * See if the user has provided an API key
	 */

	protected function _detect_api_key()
	{

		// Get the api key name variable set in the rest config file
		$api_key_variable = config_item('rest_key_name');

		// Work out the name of the SERVER entry based on config
		$key_name = 'HTTP_' . strtoupper(str_replace('-', '_', $api_key_variable));

		$this->rest->key = NULL;
		$this->rest->level = NULL;
		$this->rest->ignore_limits = FALSE;

		// Find the key from server or arguments
		if ($key = isset($this->_args[$api_key_variable]) ? $this->_args[$api_key_variable] : $this->input->server($key_name))
		{
			if ( ! $row = $this->rest->db->where('key', $key)->get(config_item('rest_keys_table'))->row())
			{
				return FALSE;
			}

			$this->rest->key = $row->key;

			isset($row->level) AND $this->rest->level = $row->level;
			isset($row->ignore_limits) AND $this->rest->ignore_limits = $row->ignore_limits;

			return TRUE;
		}

		// No key has been sent
		return FALSE;
	}

	/*
	 * Detect language(s)
	 *
	 * What language do they want it in?
	 */

	protected function _detect_lang()
	{
		if ( ! $lang = $this->input->server('HTTP_ACCEPT_LANGUAGE'))
		{
			return NULL;
		}

		// They might have sent a few, make it an array
		if (strpos($lang, ',') !== FALSE)
		{
			$langs = explode(',', $lang);

			$return_langs = array();
			$i = 1;
			foreach ($langs as $lang)
			{
				// Remove weight and strip space
				list($lang) = explode(';', $lang);
				$return_langs[] = trim($lang);
			}

			return $return_langs;
		}

		// Nope, just return the string
		return $lang;
	}

	/*
	 * Log request
	 *
	 * Record the entry for awesomeness purposes
	 */

	protected function _log_request($authorized = FALSE)
	{
		return $this->rest->db->insert(config_item('rest_logs_table'), array(
										   'uri' => $this->uri->uri_string(),
										   'method' => $this->request->method,
										   'params' => serialize($this->_args),
										   'api_key' => isset($this->rest->key) ? $this->rest->key : '',
										   'ip_address' => $this->input->ip_address(),
										   'time' => time(),
										   'authorized' => $authorized
									   ));
	}

	/*
	 * Log request
	 *
	 * Record the entry for awesomeness purposes
	 */

	protected function _check_limit($controller_method)
	{
		// They are special, or it might not even have a limit
		if ( ! empty($this->rest->ignore_limits) OR !isset($this->methods[$controller_method]['limit']))
		{
			// On your way sonny-jim.
			return TRUE;
		}

		// How many times can you get to this method an hour?
		$limit = $this->methods[$controller_method]['limit'];

		// Get data on a keys usage
		$result = $this->rest->db
				  ->where('uri', $this->uri->uri_string())
				  ->where('api_key', $this->rest->key)
				  ->get(config_item('rest_limits_table'))
				  ->row();

		// No calls yet, or been an hour since they called
		if ( ! $result OR $result->hour_started < time() - (60 * 60))
		{
			// Right, set one up from scratch
			$this->rest->db->insert(config_item('rest_limits_table'), array(
										'uri' => $this->uri->uri_string(),
										'api_key' => isset($this->rest->key) ? $this->rest->key : '',
										'count' => 1,
										'hour_started' => time()
									));
		}

		// They have called within the hour, so lets update
		else
		{
			// Your luck is out, you've called too many times!
			if ($result->count >= $limit)
			{
				return FALSE;
			}

			$this->rest->db
			->where('uri', $this->uri->uri_string())
			->where('api_key', $this->rest->key)
			->set('count', 'count + 1', FALSE)
			->update(config_item('rest_limits_table'));
		}

		return TRUE;
	}
	/*
	 * Auth override check
	 *
	 * Check if there is a specific auth type set for the current class/method being called
	 */

	protected function _auth_override_check()
	{

		// Assign the class/method auth type override array from the config
		$this->overrides_array = $this->config->item('auth_override_class_method');
		// Check to see if the override array is even populated, otherwise return false
		if ( empty($this->overrides_array) )
		{
			return false;
		}
		
		$method_check = $this->router->method;
		if ($method_check+1 != 1) $method_check = 'item';
		$method_check .= '_'.$this->request->method;
		
		// Check to see if there's an override value set for the current class/method being called
		if ( empty($this->overrides_array[$this->router->class][$method_check]) )
		{
			return false;
		}

		// None auth override found, prepare nothing but send back a true override flag
		if ($this->overrides_array[$this->router->class][$method_check] == 'none')
		{
			return true;
		}

		// Basic auth override found, prepare basic
		if ($this->overrides_array[$this->router->class][$method_check] == 'basic')
		{
			$this->_prepare_basic_auth();
			return true;
		}

		// Digest auth override found, prepare digest
		if ($this->overrides_array[$this->router->class][$method_check] == 'digest')
		{
			$this->_prepare_digest_auth();
			return true;
		}

		// Whitelist auth override found, check client's ip against config whitelist
		if ($this->overrides_array[$this->router->class][$method_check] == 'whitelist')
		{
			return $this->_check_whitelist_auth();
		}

		// Return false when there is an override value set but it doesn't match 'basic', 'digest', or 'none'.  (the value was misspelled)
		return false;
	}


	// INPUT FUNCTION --------------------------------------------------------------

	public function get($key = NULL, $xss_clean = TRUE)
	{
		if ($key === NULL)
		{
			return $this->_get_args;
		}

		return array_key_exists($key, $this->_get_args) ? $this->_xss_clean($this->_get_args[$key], $xss_clean) : FALSE;
	}

	public function post($key = NULL, $xss_clean = TRUE)
	{
		if ($key === NULL)
		{
			return $this->_post_args;
		}

		return $this->input->post($key, $xss_clean);
	}

	public function put($key = NULL, $xss_clean = TRUE)
	{
		if ($key === NULL)
		{
			return $this->_put_args;
		}

		return array_key_exists($key, $this->_put_args) ? $this->_xss_clean($this->_put_args[$key], $xss_clean) : FALSE;
	}

	public function delete($key = NULL, $xss_clean = TRUE)
	{
		if ($key === NULL)
		{
			return $this->_delete_args;
		}

		return array_key_exists($key, $this->_delete_args) ? $this->_xss_clean($this->_delete_args[$key], $xss_clean) : FALSE;
	}

	protected function _xss_clean($val, $bool)
	{
		if (CI_VERSION < 2)
		{
			return $bool ? $this->input->xss_clean($val) : $val;
		}
		else
		{
			return $bool ? $this->security->xss_clean($val) : $val;
		}
	}

	public function validation_errors()
	{
		$string = strip_tags($this->form_validation->error_string());

		return explode("\n", trim($string, "\n"));
	}

	// SECURITY FUNCTIONS ---------------------------------------------------------

	protected function _check_login($username = '', $password = NULL)
	{
		if (empty($username))
		{
			return FALSE;
		}

		$valid_logins = & $this->config->item('rest_valid_logins');

		if (!array_key_exists($username, $valid_logins))
		{
			return FALSE;
		}

		// If actually NULL (not empty string) then do not check it
		if ($password !== NULL AND $valid_logins[$username] != $password)
		{
			return FALSE;
		}

		return TRUE;
	}

	protected function _prepare_basic_auth()
	{
		// If whitelist is enabled it has the first chance to kick them out
		if (config_item('rest_ip_whitelist_enabled'))
		{
			$this->_check_whitelist_auth();
		}

		$username = NULL;
		$password = NULL;

		// mod_php
		if ($this->input->server('PHP_AUTH_USER'))
		{
			$username = $this->input->server('PHP_AUTH_USER');
			$password = $this->input->server('PHP_AUTH_PW');
		}

		// most other servers
		elseif ($this->input->server('HTTP_AUTHENTICATION'))
		{
			if (strpos(strtolower($this->input->server('HTTP_AUTHENTICATION')), 'basic') === 0)
			{
				list($username, $password) = explode(':', base64_decode(substr($this->input->server('HTTP_AUTHORIZATION'), 6)));
			}
		}

		if (!$this->_check_login($username, $password))
		{
			$this->_force_login();
		}
	}

	protected function _prepare_default_auth()
	{
		// If whitelist is enabled it has the first chance to kick them out
		if (config_item('rest_ip_whitelist_enabled') && $this->_check_whitelist_auth()) {
			$this->user = $this->user_model->get_by(array('role'=>2));
			return ;
		}
		
		if (!$this->session->userdata('id'))
		{
			if (isset($this->_args['session'])) {
				session_id($this->_args['session']);
			}
			session_start();
			
			if ($this->user_model->keep_login(@$_SESSION['uid'], @$_SESSION['ucode'])) {
				//Continue session
				$this->user = $this->user_model->get($this->session->userdata('id'));
			} else {
				$this->_force_login();
		   	}
		}
	}

	protected function _prepare_digest_auth()
	{
		// If whitelist is enabled it has the first chance to kick them out
		if (config_item('rest_ip_whitelist_enabled'))
		{
			$this->_check_whitelist_auth();
		}

		$uniqid = uniqid(""); // Empty argument for backward compatibility
		// We need to test which server authentication variable to use
		// because the PHP ISAPI module in IIS acts different from CGI
		if ($this->input->server('PHP_AUTH_DIGEST'))
		{
			$digest_string = $this->input->server('PHP_AUTH_DIGEST');
		}
		elseif ($this->input->server('HTTP_AUTHORIZATION'))
		{
			$digest_string = $this->input->server('HTTP_AUTHORIZATION');
		}
		else
		{
			$digest_string = "";
		}

		/* The $_SESSION['error_prompted'] variabile is used to ask
		  the password again if none given or if the user enters
		  a wrong auth. informations. */
		if (empty($digest_string))
		{
			$this->_force_login($uniqid);
		}

		// We need to retrieve authentication informations from the $auth_data variable
		preg_match_all('@(username|nonce|uri|nc|cnonce|qop|response)=[\'"]?([^\'",]+)@', $digest_string, $matches);
		$digest = array_combine($matches[1], $matches[2]);

		if (!array_key_exists('username', $digest) OR !$this->_check_login($digest['username']))
		{
			$this->_force_login($uniqid);
		}

		$valid_logins = & $this->config->item('rest_valid_logins');
		$valid_pass = $valid_logins[$digest['username']];

		// This is the valid response expected
		$A1 = md5($digest['username'] . ':' . $this->config->item('rest_realm') . ':' . $valid_pass);
		$A2 = md5(strtoupper($this->request->method) . ':' . $digest['uri']);
		$valid_response = md5($A1 . ':' . $digest['nonce'] . ':' . $digest['nc'] . ':' . $digest['cnonce'] . ':' . $digest['qop'] . ':' . $A2);

		if ($digest['response'] != $valid_response)
		{
			header('HTTP/1.0 401 Unauthorized');
			header('HTTP/1.1 401 Unauthorized');
			exit;
		}
	}

	// Check if the client's ip is in the 'rest_ip_whitelist' config
	protected function _check_whitelist_auth()
	{
		$whitelist = explode(',', config_item('rest_ip_whitelist'));

		array_push($whitelist, '127.0.0.1', '0.0.0.0');

		foreach ($whitelist AS &$ip) {
			$ip = trim($ip);
		}
		return in_array($this->input->ip_address(), $whitelist) || @in_array($_SERVER['HTTP_X_FORWARDED_FOR'], $whitelist);
	}

	protected function _force_login($nonce = '')
	{
		if ($this->config->item('rest_auth') == 'basic')
		{
			header('WWW-Authenticate: Basic realm="' . $this->config->item('rest_realm') . '"');
		}
		elseif ($this->config->item('rest_auth') == 'digest')
		{
			header('WWW-Authenticate: Digest realm="' . $this->config->item('rest_realm') . '", qop="auth", nonce="' . $nonce . '", opaque="' . md5($this->config->item('rest_realm')) . '"');
		}
		elseif ($this->config->item('rest_auth') == 'default' && $this->rest_format != 'jsonp')
		{
			header('Location: /api/auth');
		}
		$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
	}

	// Force it into an array
	protected function _force_loopable($data)
	{
		// Force it to be something useful
		if ( ! is_array($data) AND ! is_object($data))
		{
			$data = (array) $data;
		}

		return $data;
	}

	// FORMATING FUNCTIONS ---------------------------------------------------------

	// Many of these have been moved to the Format class for better separation, but these methods will be checked too

	// Encode as JSONP
	protected function _format_jsonp($data = array())
	{
		return $this->get('callback') . '(' . json_encode($data) . ')';
	}
}
