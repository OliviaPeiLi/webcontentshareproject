<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API controller
 *
 * based on rest controller
 * @see https://github.com/philsturgeon/codeigniter-restserver
 *
 */

require_once(BASEPATH . '../application/modules/api/controllers/api.php');

class ADMIN extends API {

	//Config vars - List
	protected $list_fields = array();
	protected $list_actions = array('edit'=>'Edit','delete'=>'Delete');
	protected $list_collection_actions = array();
	protected $filters = array('primary_key'=>'primary_key');
	//Config vars - Form
	protected $form_fields = array();
	protected $filter_tabs = array();
	protected $edit_actions = array('save'=>'Save', 'delete'=>'Delete','index'=>'Back');
	//Config vars - export
	protected $export_csv = false;

	public $user = null; //The logged in user


	// Constructor function
	public function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(false);
		
		if(!in_array($this->user->role, array('2')))
		{
			Url_helper::redirect('/404');
		}

		//Language loading
		$this->lang->load('admin/admin', LANGUAGE);
		if(is_file($_SERVER['DOCUMENT_ROOT'].'/language/'.LANGUAGE.'/'.strtolower(get_class($this)).'_lang.php'))
		{
			$this->lang->load(strtolower(get_class($this)), LANGUAGE);
		}
		
		// Lets grab the config and get ready to party
		// remove this loading, adding it to API class so that it is loaded in
		// parent::__construct() function
		// $this->load->config('rest');

		// How is this request being made? POST, DELETE, GET, PUT?
		$this->request->method = $this->_detect_method();

		// Set up our GET variables
		$this->_get_args = array_merge($this->_get_args, $this->uri->ruri_to_assoc());

		//$this->load->library('security');
		$this->load->library('ft_admin');

		// Try to find a format for the request (means we have a request body)
		$this->request->format = $this->_detect_input_format();

		// Some Methods cant have a body
		$this->request->body = NULL;

		switch ($this->request->method)
		{
		case 'get':
			// Grab proper GET variables
			parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $get);

			// Merge both the URI segements and GET params
			$this->_get_args = array_merge($this->_get_args, $get);
			break;

		case 'post':
			$this->_post_args = $_POST;
			if(isset($_POST['search']) && $_POST['search'] == 'search')
			{
				// Grab proper GET variables
				parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $get);

				// Merge both the URI segements and GET params
				$this->_get_args = array_merge($this->_get_args, $get);
				break;
			}
			else
			{
				$this->request->format and $this->request->body = file_get_contents('php://input');
				break;
			}

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
				$this->_check_whitelist_auth();
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
			$this->response( array('status' => false, 'error' => 'Only AJAX requests are accepted.'), 'error' );
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

		if ($object_called+1 > 1)
		{
			$this->item_id = $object_called;
			$object_called = 'item';
		}

		$controller_method = $object_called . '_' . $this->request->method;

		//for search, it's POST method, but we need to list it anyway
		if(isset($_POST['search']) && $_POST['search'] == 'search')
		{
			$controller_method = $object_called.'_get';
		}

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

			$this->response(array('status' => false, 'error' => 'Invalid API Key.'), 'error');
		}

		// Sure it exists, but can they do anything with it?
		if ( ! method_exists($this, $controller_method))
		{
			$this->response(array('status' => false, 'error' => sprintf('Unknown method: %s', $controller_method)), 'error');
		}

		// Doing key related stuff? Can only do it if they have a key right?
		if (config_item('rest_enable_keys') AND ! empty($this->rest->key))
		{
			// Check the limit
			if (config_item('rest_enable_limits') AND ! $this->_check_limit($controller_method))
			{
				$this->response(array('status' => false, 'error' => 'This API key has reached the hourly limit for this method.'), 'error');
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
			$authorized OR $this->response(array('status' => false, 'error' => 'This API key does not have enough permissions.'), 'error');
		}

		// No key stuff, but record that stuff is happening
		else if (config_item('rest_enable_logging') AND $log_method)
		{
			$this->_log_request($authorized = TRUE);
		}

		// And...... GO!
		call_user_func_array(array($this, $controller_method), $arguments);
	}

	/**
	 * Default REST
	 */
	protected function model()
	{
		if ( ! $this->model)
		{
			$this->model = strtolower(get_class($this)) == 'admin' ?  false : singular(strtolower(get_class($this))).'_model';
		}
		return $this->model;
	}

	private $_item = null;
	public function item($item=null) {
		if ($item) { //Setter
			$this->_item = $item;
		}
		if ( ! $this->_item)  { //Getter
			$this->load->model($this->model());
			if ( $this->list_fields )
			{
				foreach($this->list_fields as $k=>$v)
				{
					$fields[] = $k;
					$select_fields = implode(",", $fields);
				}
			}
			else
			{
				$select_fields = $this->primary_key;
			}

			$model_name = $this->model();

			if (strpos($this->model(), '/') > 0)
			{
				$model_info = explode('/', $this->model);
				$model_name = $model_info[count($model_info)-1];
				//die($model_name);
			}

			if ( ! $this->_item = $this-> {$model_name}->get($this->item_id))
			{
				$this->response($this->lang->line('common_404'), 'error');
				return false;
			}
			if (!$this->check_access($this->_item))
			{
				$this->response($this->lang->line('common_404'), 'error');
				return false;
			}
		}
		return $this->_item;
	}

	protected function check_access($item)
	{
		return $this->user->role > 0;
	}

	protected function sort($items)
	{
		//die(print_r($items));
		$col = $this->input->get('orderby');
		$col = $col ? $col : $items->primary_key();
		$order = $this->input->get('order');
		$order = $order ? $order : 'desc';
		return $items->order_by($col, $order);
	}

	protected function filter($items, $filter = false) {
		//for search
		$filter = $filter ? $filter : $this->input->get();

		$select_fields = $this->list_fields ? implode(",", array_keys($this->list_fields)) : $this->primary_key;
		
		if(isset($filter['search'])) {
			$cond = $filter['cond'];
			unset($filter['search'], $filter['cond'], $filter['page']);
			foreach($filter as $key=>$val) {
				if(! $val) unset($filter[$key]);
			}
			if(isset($filter['primary_key'])) {
				$k = $items->primary_key();
				$v = $filter['primary_key'];
				$q = (strtotime($v) > 0 || !is_numeric($v)) ? "'" : "";
				switch($cond['primary_key']) {
				case "equals":
					$query = array($k, $v);
					break;
				case "more":
					$query = array("{$k} > {$q}{$v}{$q}");
					break;
				case "more_e":
					$query = array("{$k} >= {$q}{$v}{$q}");
					break;
				case "less":
					$query = array("{$k} < {$q}{$v}{$q}");
					break;
				case "less_e":
					$query = array("{$k} <= {$q}{$v}{$q}");
					break;
				case "not":
					$query = array("{$k} != {$q}{$v}{$q}");
					break;
				}
				$items = $items->_set_where($query);
			} else {
				foreach($filter as $k => $v) {
					$q = (strtotime($v) > 0 || !is_numeric($v)) ? "'" : "";
					switch($cond[$k]) {
						case "equals":
							if(isset($this->list_fields[$k]) && $this->list_fields[$k] == 'time') {
								$stime = strtotime($v);
								$query = array("YEAR({$k}) = '".date('Y', $stime)."' AND MONTH({$k}) = '".date('m', $stime)."' AND DAY({$k}) = '".date('d', $stime)."'");
							} else  {
								$query = array($k, $v);
							}
							break;
						case "starts":
							$query = array("{$k} LIKE '{$v}%'");
							break;
						case "ends":
							$query = array("{$k} LIKE '%{$v}'");
							break;
						case "more":
							$query = array("{$k} > {$q}{$v}{$q}");
							break;
						case "more_e":
							$query = array("{$k} >= {$q}{$v}{$q}");
							break;
						case "less":
							$query = array("{$k} < {$q}{$v}{$q}");
							break;
						case "less_e":
							$query = array("{$k} <= {$q}{$v}{$q}");
							break;
						case "not":
							$query = array("{$k} != {$q}{$v}{$q}");
							break;
						case "contains":
							$query = array("{$k} LIKE '%{$v}%'");
							break;
					}
					$items = $items->_set_where($query);
				}
			}
		}
		elseif($this->input->get('filter_by',true) && $this->input->get('filter_token',true))
		{
			$items = $items->_set_where(array($this->input->get('filter_by',true),$this->input->get('filter_token',true)));
		}
		else
		{
//			$items = $items->select_fields($select_fields);
		}
		return $items;
	}

	public function index_get($child_items = NULL, $view = 'list')
	{
		if (! $this->model())
		{
			return $this->load->view('layout', array('view'=>'home'));
		}
		$model = $this->model();
		$this->load->model($model);
		$model_name = $this->model();

		if (strpos($this->model(), '/') > 0)
		{
			$model_info = explode('/', $this->model);
			$model_name = $model_info[count($model_info)-1];
		}

		$items = $child_items ? $child_items : $this-> {$model_name};

		$items = $this->filter($items);
		$items = $items->paginate($this->input->get('page'), 20);
		$items = $this->sort($items);
		//die(print_r($items->db));
		$data['rows'] = $items->get_all();
		$data['pagination'] = $items->pagination->create_links();
		$this->response($data, $view);
	}

	public function item_get($children=NULL, $child_id=NULL)
	{
		if ($children)
		{
			if (method_exists($this, 'item_'.$children))
			{
				return $this-> {'item_'.$children}();
			}
			else
			{
				if (! count($this->item()-> {$children})) $this->response('Not found');
				$model = str_replace("_model", "", strtolower(get_class($this->item()-> {$children} [0]->_model)));
				modules::run('api/'.$model.'/index_get', $this->item()->get($children));
			}
		}
		else
		{
			$this->response($this->item(), 'form');
		}
	}

	public function create_get() {
		$item = new Model_Item();
		$item->_model = $this->{$this->model};
		if(count($this->form_fields) > 0 && array_values($this->form_fields) == $this->form_fields){
			foreach ($this->form_fields as $key=>$sub_form){
				if($sub_form[0] == 'Edit'){
					foreach ($sub_form as $field=>$type) {
						$item->$field = '';
					}
				}
			}
		}else{
			foreach ($this->form_fields as $field=>$type) {
				$item->$field = '';
			}
		}
		$item->{$item->_model->primary_key()} = 0;
		
		$this->response($item, 'form');
	}
	
	public function create_post() {
		$post = $this->input->post();
		$id = $this->{$this->model}->insert($post);
		Url_helper::redirect('/admin/'.strtolower(get_class($this)).'/'.$id);
	}
	
	/**
	 * Called when several items are selected and the list_collection_actions form is submitted
	 */
	public function index_post() {
		$action = $this->input->post('list_action');
		$items = $this->input->post('items');
		if ($action && count($items) && method_exists($this, 'index_post_'.$action)) {
			$this->{'index_post_'.$action}();
		} else {
			die('Action: '.$action.' doesnt have a callback');
		}
	}
	
	public function index_post_delete() {
		$items = $this->input->post('items');
		foreach ($items as $item) {
			$this->{$this->model}->delete($item);
		}
		echo json_encode(array('refresh'=>true));
	}
	
	
	public function item_post()
	{
		$post = $this->input->post();
		foreach($_FILES as $field=>$value){
			if($value['name'] == ''){
				unset($_FILES[$field]);
			}
		}
		foreach ($this->form_fields as $field=>$type) {
			if ($type == 'checkbox' && !isset($post[$field])) {
				$post[$field] = false;
			}
		}
		if ($this->item()->_model instanceof User_model) {
			$id = $this->session->userdata('id');
			$this->session->set_userdata('id', $this->item()->id);
		}
		$this->item()->update($post);
		if ($this->item()->_model instanceof User_model) {
			$this->session->set_userdata('id', $id);
		}
		$this->item($this->item()->_model->get($this->item()->primary_key()));
		$this->response($this->item(), 'form');
	}

	public function item_delete()
	{
		$this->item()->delete();
		if (strpos($_SERVER['HTTP_REFERER'], '/edit') !== false) {
			Url_helper::redirect('/admin/'.$this->router->fetch_class());
		} else {
			Url_helper::redirect($_SERVER['HTTP_REFERER']);
		}
	}

	/*
	 * response
	 *
	 * Takes pure data and optionally a status code, then creates the response
	 */
	public function response($output_data = array(), $view=null, $pagination_links = null)
	{
		// If data is empty and not code provide, error and bail
		if(empty($output_data)) return null;

		// all the compression settings must be done before sending any headers
		// if php is not handling the compression by itself
		/*
		if (@ini_get('zlib.output_compression') == FALSE) {
			// ob_gzhandler depends on zlib
			if (extension_loaded('zlib')) {
				// if the client supports GZIP compression
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE) {
				ob_start('ob_gzhandler');
			}
			}
		}
		*/
		if($this->input->get('order') == 'asc' || !$this->input->get('order'))
		{
			$data['order'] = 'desc';
		}
		else
		{
			$data['order'] = 'asc';
		}
		$data['pagination_links'] = $pagination_links;
		$data['data'] = $output_data;
		$data['view'] = $view;
		$data['export_csv'] = $this->export_csv;
		$data['list_fields'] = $this->list_fields;
		$data['form_fields'] = $this->form_fields;
		$data['filter_tabs'] = $this->filter_tabs;
		$data['list_actions'] = $this->list_actions;
		$data['edit_actions'] = $this->edit_actions;
		$data['filters'] = $this->filters;
		$data['list_collection_actions'] = $this->list_collection_actions;

		//Ray add the config for the edit here without an if - the same as list_actions
		//the var is declared in this class also not only in the extending classes
		return $this->load->view('admin/layout', $data);
	}

	function index_post_export_csv() {
		$fields = $this->input->post('fields');
		
		if (! $this->model())
		{
			die(json_encode(array('status'=>false, 'error'=>'<p>Wrong request. No model specified.</p>')));
		}
		$model = $this->model();
		$this->load->model($model);
		$model_name = $this->model();

		if (strpos($this->model(), '/') > 0)
		{
			$model_info = explode('/', $this->model);
			$model_name = $model_info[count($model_info)-1];
		}

		$filter = $this->input->post('filter_data');
		$items = $this-> {$model_name};
		$items = $this->filter($items, $filter);
		$items = $items->get_all();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment;filename='.strtolower(get_class($this)).'.csv');
        $out = fopen("php://output", 'w');

        $heading = array();
        foreach ($fields as $field_name => $active) {
        	if($active == 0) continue;
            $heading[] = $field_name;
        }
        fputcsv($out, $heading, ',', '"');

        foreach ($items as $row) {
        	$row_data = array();
            foreach ($fields as $field_name => $active) {
            	if($active == 0) continue;
                $row_data[$field_name] = str_replace("\r\n"," ", $row->{$field_name});
                $row_data[$field_name] = stripslashes($row_data[$field_name]);
                $row_data[$field_name] = str_replace('\"', '"', $row_data[$field_name]);
            }
            fputcsv($out, $row_data, ',', '"');
        }
        fclose($out);
	}

	protected function _prepare_default_auth()
	{
		// If whitelist is enabled it has the first chance to kick them out
		if (config_item('rest_ip_whitelist_enabled'))
		{
			$this->_check_whitelist_auth();
		}

		if($this->session->userdata('editor') == '1')
		{
			$this->load->model('user_model');
			$this->user = $this->user_model->get($this->session->userdata('id'));
			return;
		}

		if (!$this->session->userdata('id') || !in_array($this->user->role, array(1,2))) //Editor, Admin
		{
			$this->_force_login();
		}
		else
		{
			$this->user = $this->user_model->get($this->session->userdata('id'));
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
			header($this->lang->line('common_401_10'));
			header($this->lang->line('common_401_11'));
			exit;
		}
	}

	// Check if the client's ip is in the 'rest_ip_whitelist' config
	protected function _check_whitelist_auth()
	{
		$whitelist = explode(',', config_item('rest_ip_whitelist'));

		array_push($whitelist, '127.0.0.1', '0.0.0.0');

		foreach ($whitelist AS &$ip)
		{
			$ip = trim($ip);
		}

		return in_array($this->input->ip_address(), $whitelist);
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
		elseif ($this->config->item('rest_auth') == 'default')
		{
			header('Location: /admin/auth');
		}
		die('You need to login');
		$this->response(array('status' => false, 'error' => $this->lang->line('common_not_authorized')), 'error');
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
