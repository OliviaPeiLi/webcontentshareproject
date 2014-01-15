<?php
/**
 * A base model to provide the basic CRUD 
 * actions for all models that inherit from it.
 *
 * @package CodeIgniter
 * @subpackage MY_Model
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @modified Phil Sturgeon <http://philsturgeon.co.uk>
 * @modified Dan Horrigan <http://dhorrigan.com>
 * @modified Adam Jackett <http://darkhousemedia.com>
 * @copyright Copyright (c) 2011, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Model_Item {
	
	public function __toString() {
		$name_key = $this->_model->name_key;
		if (strpos($name_key, ' as ') !== false) list(,$name_key) = explode(' as ', $name_key);
		return $this->$name_key;
	}
	
	public function __get($var)	{
		$cache_name = '_'.$var;
		if (isset($this->$cache_name)) return $this->$cache_name;
		
		if ($model = $this->get($var)) {
			if (get_class($model) == 'Model_Item') {
				$this->$cache_name = $model;
			} else {
				$this->$cache_name = preg_match('#.*(s|ren)$#', $var) ? $model->get_all() : $model->get_by(array()); 
			}
			return $this->$cache_name;
		} elseif (method_exists($this->_model, 'get_'.$var)) {
			//if (!isset($this->{'_'.$var})) {
			//	$this->{'_'.$var} = $this->_model->{'get_'.$var}($this);
			//} 
			//return $this->{'_'.$var};
			return $this->_model->{'get_'.$var}($this);
		} else {
			throw new Exception('"'.$var. '" field is not set in '.get_class($this->_model), 0);
		}
	}
	
	public function get($field) {
		return $this->{'_model_'.$field} = $this->_model->get_relation($field, $this);
	}
	
	public function primary_key() {
		return $this->{$this->_model->primary_key()};
	}
	
	public function delete() {
		return $this->_model->delete($this->primary_key());
	}
	
	public function update($data) {
		return $this->_model->update($this->primary_key(), $data);
	}
	
	public function as_array() {
		$ret = array();
		foreach ($this as $key=>$val) if ($key[0] != '_') $ret[$key] = $val;
		return $ret;
	}
	
	public function __call($method, $args) {
		$args = array_merge(array($this), $args);
		return call_user_func_array(array($this->_model, $method), $args);
	}
}

class MY_Model extends CI_Model {
	
	/**
	 * The database table to use, only
	 * set if you want to bypass the magic
	 *
	 * @var string
	 */
	protected $_table;
		
	/**
	 * The primary key, by default set to
	 * `id`, for use in some functions.
	 *
	 * @var string
	 */
	protected $primary_key = 'id';
	
	protected $default = array();
	
	/**
	 * Behaviors
	 */
	public $behaviors = array();
	
	/**
	 * Relations
	 */
	
	/**
	 * @var array(
	 * 		'column_name' => array(
	 * 			'foreign_model' => 
	 * 			'foreign_column' => (string|(column_name)_id) the column name defaults to column_name + _id
	 * 			'on_delete_cascade' => (boolean|false) deletes the record in the foreign table if true.
	 * 		)
	 * )
	 */
	protected $belongs_to = array();
	
	/**
	 * @var array(
	 * 		'column_name' => array(
	 * 			'foreign_model' => 
	 * 			'foreign_column' => 
	 * 			'on_delete_cascade' => (boolean|false) deletes the record in the foreign table if true.
	 * 		)
	 * )
	 */
	protected $has_one = array();
	
	/**
	 * @var array(
	 * 		'column_name(s)'=>array(
	 * 				'foreign_model' =>
	 * 				'foreign_column' => (string|(table)_id) - the column name in the foreign table defaults to singular of the column_name + _id
	 * 	  			'on_delete_cascade' => (boolean|true) deletes the record in the foreign table if true.
	 *      ) 
	 * )
	 */
	protected $has_many = array();
	
	/**
	 * @var array(
	 * 		'column_name(s)' => array(
	 * 			'through' => (string|(table(s))_(column_name(s))) - the connection table used for the relation
	 * 			'self_column' =>
	 * 			'foreign_column' =>
	 * 		)
	 * )
	 */
	protected $many_to_many = array();
	private $many_to_many_data = array();
	
	/**
	 * @var array(
	 * 		'column_name'=> array(
	 * 			'model_column' => (string|(column_name)_model) - the column which holds the model name
	 * 			'item_column' => (string|(column_name)_id) - the column which holds the id to the foreign table
	 * 		)
	 * )
	 */
	protected $polymorphic_belongs_to = array();
	
	/**
	 * @var array(
	 * 		'column_name'=> array(
	 * 			'model_column' => (string|(column_name)_model) - the column which holds the model name
	 * 			'item_column' => (string|(column_name)_id) - the column which holds the id to the foreign table
	 * 		)
	 * )
	 */
	protected $polymorphic_has_one = array();
	
	/**
	 * @var array(
	 * 		'column_name'=> array(
	 * 			'model_column' => (string|(column_name)_model) - the column which holds the model name
	 * 			'item_column' => (string|(column_name)_id) - the column which holds the id to the foreign table
	 * 		)
	 * )
	 */
	protected $polymorphic_has_many = array();
	
	/**
	 * An array of functions to be called before
	 * a record is created.
	 *
	 * @var array
	 */
	protected $before_create = array();
	
	/**
	 * An array of functions to be called after
	 * a record is created.
	 *
	 * @var array
	 */
	protected $after_create = array();
	
	/**
	 * An array of functions to be called before
	 * a record is retrieved.
	 *
	 * @var array
	 */
	protected $before_get = array();
	
	/**
	 * An array of functions to be called after
	 * a record is retrieved.
	 *
	 * @var array
	 */
	protected $after_get = array();

	protected $before_set = array();
	protected $after_set = array();	
	
	/**
	 * An array of validation rules
	 *
	 * @var array
	 */
	protected $validate = array();

	/**
	 * Skip the validation
	 *
	 * @var bool
	 */
	protected $skip_validation = FALSE;
	
	protected $pagination = null;

	/**
	 * Wrapper to __construct for when loading
	 * class is a superclass to a regular controller,
	 * i.e. - extends Base not extends Controller.
	 * 
	 * @return void
	 */
	public function MY_Model() { $this->__construct(); }

	/**
	 * The class constructer, tries to guess
	 * the table name.
	 */
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('inflector');
		
		$this->_fetch_table();
		
		$this->_set_default_relation_data();
		
		foreach (get_instance()->load->get_package_paths(TRUE) as $path) {
			if (is_file($path.'models/behaviors/behavior.php')) {
				require_once $path.'models/behaviors/behavior.php';
			}
		}
		foreach ($this->behaviors as $behavior=>$config) {
			foreach (get_instance()->load->get_package_paths(TRUE) as $path) {
				if (is_file($path.'models/behaviors/behavior.php')) {
					require_once $path.'models/behaviors/'.$behavior.'.php';
				}
			}
		}
	}
	
	public function primary_key() {
		return $this->primary_key;
	}
	
	public function table() {
		return $this->_table;
	}
	
	public function has_many() {
		return $this->has_many;
	}
	
	public function select_fields($fields, $escape = true){
	    $this->db->select($fields, $escape);
	    return $this;
	}
	
	private $added_tables = array();
	public function add_table($table) {
		$model = singular($table).'_model';
		$fields = $this->db->query('SHOW COLUMNS FROM '.$table)->result();
		$this->db->select($this->_table.'.*');
		$this->db->from($table);
		$this->added_tables[] = $table;
		foreach ($fields as $field) {
			$this->db->select($table.'.'.$field->Field.' as `'.$table.':'.$field->Field.'`');
		}
		
		$this->load->model($model);
		
		return $this;
	}
	
	public function get_relation($field, $data) {
		if (isset($this->belongs_to[$field])) {
			$model = $this->load->model($this->belongs_to[$field]['foreign_model'].'_model');
			foreach ($data as $f=>$val) if (strpos($f, $model->table().":") !== false) {
				return $model->set_result($data);
			}
			if (in_array($model->table(), $this->added_tables)) {
				$model = $model->set_result($data);
			} else {
				$model->db->where(array( $model->primary_key => $data->{$this->belongs_to[$field]['foreign_column']} ));
			}
			return $model;
		}
		if (isset($this->has_one[$field])) {
			$model = $this->load->model($this->has_one[$field]['foreign_model'].'_model');
			$model->db->where(array( $this->has_one[$field]['foreign_column'] => $data->{$this->primary_key} ));
			return $model;
		}
		if (isset($this->has_many[$field])) {
			$model = $this->load->model($this->has_many[$field]['foreign_model'].'_model');
			$model->db->where(array( $this->has_many[$field]['foreign_column'] => $data->{$this->primary_key} ));
			return $model;
		}
		if (isset($this->many_to_many[$field])) {
			$model = $this->load->model($this->many_to_many[$field]['foreign_model'].'_model');
			$model->db
					->select($model->table().'.*')
					->join($this->many_to_many[$field]['through'], $model->table().'.'.$model->primary_key().'='.$this->many_to_many[$field]['through'].'.'.$this->many_to_many[$field]['foreign_column'] )
					->where(array( $this->many_to_many[$field]['through'].'.'.$this->many_to_many[$field]['self_column'] => $data->{$this->primary_key} ));
			return $model;
		}
		if (isset($this->polymorphic_belongs_to[$field])) {
			$model = $this->load->model($data->{$this->polymorphic_belongs_to[$field]['model_column']}.'_model');
			if (in_array($model->table(), $this->added_tables)) {
				$model = $model->set_result($data);
			} else {
				$model->db->where(array( $model->primary_key => $data->{$this->polymorphic_belongs_to[$field]['item_column']} ));
			}
			return $model;
		}
		if (isset($this->polymorphic_has_one[$field])) {
			$model = $this->load->model($this->polymorphic_has_one[$field]['foreign_model'].'_model');
			$model->db->where(array(
								$this->polymorphic_has_one[$field]['item_column'] => $data->{$this->primary_key},
								$this->polymorphic_has_one[$field]['model_column'] => singular($this->_table),								
							));
			return $model;
		}
		if (isset($this->polymorphic_has_many[$field])) {
			$model = $this->load->model($data->{$this->polymorphic_has_many[$field]['foreign_model']}.'_model');
			$model->db->where(array(
								$this->polymorphic_has_many[$field]['item_column'] => $data->{$this->primary_key},
								$this->polymorphic_has_many[$field]['model_column'] => singular($this->_table),								
							));
			return $model;
		}
		return false;
	}
	
	public function paginate($page=1, $per_page=25, $config = array()) {
		if ($page < 1) $page = 1;
		
		$count = $this->db->count_all_results($this->_table, false);
		
		$this->db->limit($per_page)->offset( ($page-1)*$per_page );
		
		$pagination = $this->load->library('pagination');
		$query = array();
		$get = $this->input->get() ? $this->input->get() : array();
		foreach ($get as $key=>$val) if (!$val || $key == 'page') unset($get[$key]);
		$this->pagination = $pagination->initialize(array_merge(array(
			'base_url' => '/'.$this->router->uri->uri_string.'?'.http_build_query($get),
			'total_rows' => $count,
			'per_page' => 20,
		), $config));
		 
		return $this;
	}
	
	public function with($model, $type=null) {
		if (isset($this->polymorphic_has_one[$model])) {
			$foreign_table = $this->load->model($this->polymorphic_has_one[$model]['foreign_model'].'_model')->table();
			$this->db->join($foreign_table, 
				"(".$foreign_table.".".$this->polymorphic_has_one[$model]['item_column']." = ".$this->_table.".".$this->primary_key." AND type = '".singular($this->_table)."'", $type);
		} elseif (isset($this->belongs_to[$model])) {
			$foreign_model = $this->load->model($this->belongs_to[$model]['foreign_model'].'_model');
			$this->added_tables[] = $foreign_model->table();
			$this->db->join($foreign_model->table(), "(".$foreign_model->table().".".$foreign_model->primary_key()."=".$this->_table.".".$this->belongs_to[$model]['foreign_column'], $type);
		} else {
			throw new Exception("You can use with only for related models", 0);
		}
		return $this;
	}
	
	public function join($table, $criteria) {
		$this->db->join($table, $criteria);
		return $this;
	}
	
	
	/**
	 * Get a single record by creating a WHERE clause with
	 * a value for your primary key
	 *
	 * @param string $primary_value The value of your primary key
	 * @return object
	 */
	public function get($primary_value) {
		$this->_run_before_get();
		$res = $this->db->where($this->primary_key, $primary_value)
						->get($this->_table)
						->result('Model_Item');
		$row = $res ? $res[0] : null;
		$this->_run_after_get($row);
		return $row;
	}
	
	/**
	 * Get a single record by creating a WHERE clause by passing
	 * through a CI AR where() call
	 *
	 * @param string $key The key to search by 
	 * @param string $val The value of that key
	 * @return object
	 */
	public function get_by() {
		$where = func_get_args();
		$this->_set_where($where);
		
		$this->_run_before_get();
		if ( ! $res = $this->db->limit(1)->get($this->_table)->result('Model_Item')) return ;
		$row = $res[0];
		$this->_run_after_get($row);
		return $row;
	}
	
	/**
	 * Similar to get(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $values The value of that key
	 * @return array
	 */
	public function get_many($values) {
		if (!$values) return array();
		$this->db->where_in($this->primary_key, $values);
		
		return $this->get_all();
	}
	
	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 */
	public function get_many_by() {
		$where =& func_get_args();
		$this->_set_where($where);
		
		return $this->get_all();
	}
	
	/**
	 * Get all records in the database
	 *
	 * @return array
	 */
	public function get_all($limit = NULL) {
		$this->_run_before_get();
		if ($limit !== NULL) $this->db->limit($limit);
		$result = $this->db->get($this->_table)->result('Model_Item');
		$this->_run_after_get($result);
		return $result;
	}
	
	public function jsonfy($limit = NULL) {
		$res = $this->get_all($limit);
		foreach ($res as &$row) {
			unset($row->_model);
		}
		return $res;
	}
	
	public function query($sql) {
		$this->_run_before_get();
		$result = $this->db->query($sql)
							->result('Model_Item');
		$this->_run_after_get($result);
		return $result;
	}
	
	public function set_result($result) {
		$this->_run_before_get();
		$obj = new Model_Item();
		foreach ($result as $field=>$val) if (strpos($field, $this->_table.':') !== false) {
			$obj->{str_replace($this->_table.':', '', $field)} = $val;
		}
		$result = array($obj);
		$this->_run_after_get($result);
		return $result[0];
	}
	
	/**
	 * Count the number of rows based on a WHERE
	 * criteria
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return integer
	 */
	public function count_by() {
		$where = func_get_args();
		if($where != null){
		    $this->_set_where($where);
		}
		return $this->db->count_all_results($this->_table);
	}
	
	/**
	 * Return a count of every row in the table
	 *
	 * @return integer
	 */
	public function count_all() {
		return $this->db->count_all($this->_table);
	}
	
	/**
	 * Insert a new record into the database,
	 * calling the before and after create callbacks.
	 * Returns the insert ID.
	 *
	 * @param array $data Information
	 * @return integer
	 */
	public function insert($data, $skip_validation = FALSE) {
		$valid = TRUE;
		
		if ($skip_validation === FALSE) {
			$valid = $this->_run_validation($data);
		}

		if($valid) {
			$data = $this->_run_before_create($data);
			$not_escaped = array();
			$_data = array();
			foreach ($data as $field => $value)
			{
				if (is_array($value)) continue; //one to many connection
				if (preg_match('#^"[a-zA-Z0-9_-]*"$#', $value))
				{
					$not_escaped[$field] = trim($value,'"');
				} else {
					$_data[$field] = $value;
				}
			}
			$this->db->set($_data)->set($not_escaped, NULL, false)->insert($this->_table);
			$data[$this->primary_key()] = $this->db->insert_id();
			$this->_run_after_create($data);
			return $data[$this->primary_key()];
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Similar to insert(), just passing an array to insert
	 * multiple rows at once. Returns an array of insert IDs.
	 *
	 * @param array $data Array of arrays to insert
	 * @return array
	 */
	public function insert_many($data, $skip_validation = FALSE) {
		$ids = array();
		
		foreach ($data as $row) {
			$ids[] = $this->insert($row, $skip_validation);
		}
		
		return $ids;
	}
	
	public function update_or_insert($data)
	{
		if (isset($data[$this->primary_key]) && $data[$this->primary_key])
		{
			$this->update($data[$this->primary_key], $data);
		}
		else 
		{
			$data[$this->primary_key] = $this->insert($data);
		}
		return $data[$this->primary_key];
	}
	
	/**
	 * Update a record, specified by an ID.
	 *
	 * @param integer $id The row's ID
	 * @param array $array The data to update
	 * @return bool
	 */
	 //added 4th parameter to escape or not the sql for queries like "hits = hits+1"
	public function update($primary_value, $data, $skip_validation = FALSE, $escape = TRUE) {
		$valid = TRUE;

		if ($skip_validation === FALSE) {
			$valid = $this->_run_validation($data);
		}

		if ($valid) {
			$data[$this->primary_key] = $primary_value;
			$data = $this->_run_before_set($data);
			unset($data[$this->primary_key]);
			$not_escaped = array();
			$_data = array();
			foreach ($data as $field => $value)
			{
				if (is_array($value)) continue;
				if (preg_match('#^"[\(\)]+"$#', $value))
				{
					$not_escaped[$field] = trim($value,'"');
				} else {
					$_data[$field] = $value;
				}
			}
			
			$this->db->where($this->primary_key, $primary_value)
							->set($_data, NULL, $escape)
							->set($not_escaped, NULL, false)
							->update($this->_table);
			$data[$this->primary_key] = $primary_value;
			$data = $this->_run_after_set($data);
		} else {
			return FALSE;
		}
	}	
	/**
	 * Update a record, specified by $key and $val.
	 *
	 * @param string $key The key to update with
	 * @param string $val The value
	 * @param array $array The data to update
	 * @return bool
	 */
	public function update_by() {
		$args =& func_get_args();
		$data = array_pop($args);
		
		if ($this->_run_validation($data)) {
			$data = $this->_run_before_set(is_array($args[0]) ? array_merge($data, $args[0]) : $data);
			if (is_array($args[0])) foreach ($args[0] as $field=>$val) unset($data[$field]);
			$not_escaped = array();
			foreach ($data as $field => $value)
			{
				if (preg_match('#^".*"$#', $value))
				{
					$not_escaped[$field] = trim($value,'"');
					unset($data[$field]);
				}
			}
			$this->_set_where($args);
			$res = $this->db->set($data)->set($not_escaped, NULL, false)->update($this->_table);
			
			$this->_run_after_set(array_merge($data, $args[0]));
			return $res;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Updates many records, specified by an array
	 * of IDs.
	 *
	 * @param array $primary_values The array of IDs
	 * @param array $data The data to update
	 * @return bool
	 */
	public function update_many($primary_values, $data, $skip_validation) {
		$valid = TRUE;
		
		if($skip_validation === FALSE) {
			$valid = $this->_run_validation($data);
		}
			
		if($valid) {
			$not_escaped = array();
			foreach ($data as $field => $value)
			{
				if (preg_match('#^".*"$#', $value))
				{
					$not_escaped[$field] = trim($value,'"');
					unset($data[$field]);
				}
			}
			return $this->db->where_in($this->primary_key, $primary_values)
							->set($data)->set($not_escaped, NULL, false)
							->update($this->_table);
		
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Updates all records
	 *
	 * @param array $data The data to update
	 * @return bool
	 */
	public function update_all($data)
	{
		$not_escaped = array();
		foreach ($data as $field => $value)
		{
			if (preg_match('#^".*"$#', $value))
			{
				$not_escaped[$field] = trim($value,'"');
				unset($data[$field]);
			}
		}
		return $this->db->set($data)->set($not_escaped, NULL, false)
						->update($this->_table);
	}
	
	/**
	 * Delete a row from the database table by the
	 * ID.
	 *
	 * @param integer or model_item $obj 
	 * @return bool
	 */
	public function delete($obj)
	{

		if (!is_object($obj)) $obj = $this->get($obj);
		if (!$obj) return false;
		if (!$obj->{$this->primary_key}) return false;
		
		$obj = $this->_run_before_delete($obj);

		if (!$obj) return false;
		if (!$obj->{$this->primary_key}) return false;
		

		$this->db->where($this->primary_key, $obj->{$this->primary_key})->delete($this->_table);
		
		return $this->_run_after_delete($obj);
	}
	
	/**
	 * Delete rows from the database table by the
	 * key and value.
	 *
	 * @param string $key
	 * @param string $value 
	 * @return bool
	 */
	public function delete_by() {
		$where = func_get_args();
		$this->_set_where($where);
		$result = $this->db->get($this->_table);
		
		while ($obj = mysql_fetch_object($result->result_id)) {
			if (!$this->_run_before_delete($obj)) continue;
			$this->db->where($this->primary_key, $obj->{$this->primary_key})->delete($this->_table);
			$this->_run_after_delete($obj);
		}
		return true;
	}
	
	/**
	 * Delete many rows from the database table by 
	 * an array of IDs passed.
	 *
	 * @param array $primary_values 
	 * @return bool
	 */
	public function delete_many($primary_values) {
		$result = $this->get_many($primary_values);
		if ( empty($result) ) return false;

		foreach ($result as $obj) {
			$obj = $this->_run_before_delete($obj);

			if (!$obj) continue;
			if (!$obj->{$this->primary_key}) continue;

			$this->db->where($this->primary_key, $obj->{$this->primary_key})->delete($this->_table);

			$this->_run_after_delete($obj);

			//return $this->db->where_in($this->primary_key, $primary_values)
			//  ->delete($this->_table);
		}

		return true;
	}
	
	protected function _run_before_delete($obj) {
		foreach ($this->behaviors as $behavior=>$config) {
			$obj = call_user_func_array(array($behavior.'_Behavior', '_run_before_delete'), array($obj, $config));
			if (!$obj) return $obj;
		}
		foreach ($this->has_many as $model=>$data) {
			if (! $data['on_delete_cascade']) continue;
			$model = $this->load->model($data['foreign_model'].'_model');
			$model->delete_by(array($data['foreign_column'] => $obj->{$this->primary_key}));
		}
		foreach ($this->has_one as $model=>$data) {
			if (! $data['on_delete_cascade']) continue;
			$model = $this->load->model($data['foreign_model'].'_model');
			$model->delete_by(array($data['foreign_column'] => $obj->{$this->primary_key}));
		}
		foreach ($this->belongs_to as $model=>$data) {
			if ( ! $data['on_delete_cascade']) continue;
			$model = $this->load->model($data['foreign_model'].'_model');
			$model->delete($obj->{$data['foreign_column']});
		}
		foreach ($this->many_to_many as $model=>$data) {
			if ( ! $data['on_delete_cascade']) continue;
			$model = $this->load->model($data['through'].'_model');
			$model->delete_by(array($data['self_column'] => $obj->{$this->primary_key}));
		}
		foreach ($this->polymorphic_belongs_to as $model=>$data) {
			if ( ! $data['on_delete_cascade']) continue;
			$model = $this->load->model($obj->{$data['model_column']}.'_model');
			$model->delete($obj->{$data['item_column']});
		}
		foreach ($this->polymorphic_has_many as $model=>$data) {
			if ( ! $data['on_delete_cascade']) continue;
			$this->load->model($data['foreign_model'].'_model');
			$this->{$data['foreign_model'].'_model'}->delete_by(array($data['model_column'] => singular($this->_table), $data['item_column'] => $obj->{$this->primary_key}));
		}
		foreach ($this->polymorphic_has_one as $model=>$data) {
			if ( ! $data['on_delete_cascade']) continue;
			$this->load->model($data['foreign_model'].'_model');
			$model = $this->{$data['foreign_model'].'_model'};
			$model->delete_by(array($data['model_column'] => singular($this->_table), $data['item_column'] => $obj->{$this->primary_key}));
		}
		return $obj;
	}
	
	protected function _run_after_delete($obj) {
		foreach ($this->behaviors as $behavior=>$config) {
			$ret = call_user_func_array(array($behavior.'_Behavior', '_run_after_delete'), array($obj, $config));
			if (!$ret) return false;
		}
		return true;
	}
	
	/**
	 * Retrieve and generate a dropdown-friendly array of the data
	 * in the table based on a key and a value.
	 *
	 * @return void
	 */
	function dropdown($key=null, $value=null, $token=false) {

		$key = $key ? $key : $this->primary_key;
		$value = $value ? $value : $this->name_key;

		$select_obj = array($key == $this->primary_key ? $this->_table.'.'.$key : $key, $value);

		$query = $this->db->select($select_obj)->get($this->_table);
		if (strpos($value, ' as ') !== false) {
			list(,$value) = explode(' as ', $value, 2);
		}
		
		$options = array();
		
		foreach ($query->result() as $row) {
			if ($token) {
				$options[] = array('id'=>$row->{$key}, 'name'=>$row->{$value});
			} else {
				$options[$row->{$key}] = $row->{$value};
			}
		}

		return $options;
	}
	
	/**
	 * Orders the result set by the criteria,
	 * using the same format as CI's AR library.
	 *
	 * @param string $criteria The criteria to order by
	 * @return void
	 * @since 1.1.2
	 */
	public function order_by($criteria, $order = 'ASC') {
		$this->db->order_by($criteria, $order);
		return $this;
	}
	public function group_by($criteria) {
		$this->db->ar_groupby[] = $criteria;
		return $this;
	}
	
	/**
	 * Limits the result set by the integer passed.
	 * Pass a second parameter to offset.
	 *
	 * @param integer $limit The number of rows
	 * @param integer $offset The offset
	 * @return void
	 * @since 1.1.1
	 */
	public function limit($limit, $offset = 0) {
		$this->db->limit($limit, $offset);
		return $this;
	}

	/**
	 * Tells the class to skip the insert validation
	 *
	 * @return void
	 */
	public function skip_validation() {
		$this->skip_validation = TRUE;
		return $this;
	}
	
	/**
	 * Runs the before create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 */
	protected function _run_before_create($data) {
		foreach ($this->before_create as $method) {
			call_user_func_array(array($this, $method), array(&$data));
		}
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_before_create'), array(&$data, $config));
		}
		$data = $this->_run_before_set($data);
		return $data;
	}
	
	/**
	 * Runs the after create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 */
	protected function _run_after_create($data) {
		foreach ($this->after_create as $method) {
			call_user_func_array(array($this, $method), array($data));
		}
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_after_create'), array( & $data, $config));
		}
		$data = $this->_run_after_set($data);
	}
	
	/**
	 * Runs the before get actions.
	 *
	 * @return void
	 */
	private function _run_before_get()
	{
		foreach ($this->before_get as $method)
		{
			$this->$method();
		}
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_before_get'), array($config));
		}
	}
	
	/**
	 * Runs the after get actions.
	 *
	 * @return void
	 */
	public function _run_after_get($row)
	{
		if($row == FALSE) return;
		
		if(is_array($row)) {
			foreach ($row as &$_row) {
				$_row = $this->_run_after_get($_row);
			}
			return ;
		}
		
		$row->_model = $this;
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_after_get'), array($row, $config));
		}
		foreach ($this->after_get as $method)
		{
			call_user_func_array(array($this, $method), array($row));
		}
		return $row;
	}
	
	protected function _run_before_set($data) {
		$this->load->helper('array');
		if ($this->default && $data) $data = Array_Helper::array_merge_assoc($this->default, $data);
				
		foreach ($this->polymorphic_belongs_to as $field => $field_info) {
			if (isset($data[$field])) {
				$this->load->model(key($data[$field]).'_model');
				$data[$field.'_id'] = $this->{key($data[$field]).'_model'}->update_or_insert(current($data[$field]));
				$data[isset($field_info['model_column']) ? $field_info['model_column'] : $field.'_model'] = key($data[$field]); 
			}
		}
		
		foreach ($this->before_set as $method)
		{
			call_user_func_array(array($this, $method), array(&$data));
		}
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_before_set'), array(& $data, $config));
		}
		
		//Clean
		foreach ($this->polymorphic_belongs_to as $field => $field_info) {
			if (isset($data[$field])) unset($data[$field]);
		}
		foreach ($this->many_to_many as $field => $field_info) {
			if (isset($data[$field])) {
				$this->many_to_many_data[$field] = $data[$field];
				unset($data[$field]);
			}
		}
		return $data;
	}
	
	protected function _run_after_set($data) {
		foreach ($this->many_to_many_data as $field => $value)
		{
			$through = isset($this->many_to_many[$field]['through']) ? $this->many_to_many[$field]['through'] : $this->_table.'_'.$field;
			$this->db->where(singular($this->_table).'_id', $data[$this->primary_key])
				->delete($through);
			foreach ($value as $item)
			{
				$this->db->insert($through, array(
					singular($field).'_id' => $item,
					singular($this->_table).'_id' => $data[$this->primary_key]
				));
			}
		}
		
		foreach ($this->has_many as $field => $value)
		{
			if (!isset($data[$field])) continue;
			$this->load->model($value['foreign_model'].'_model');
			$current_items = $this->{$value['foreign_model'].'_model'}->get_many_by(array(
				$value['foreign_column'] => $data[$this->primary_key]
			));
			$new_items = $data[$field]; //only new items will be left here
			foreach ($current_items as $current_item) { //Delete
				$is_found = false;
				foreach ($new_items as $k=>$new_item) { //Search for the current in all new items
					$is_same = true;
					foreach ($new_item as $new_item_field=>$new_item_val) {
						if ($current_item->$new_item_field != $new_item_val) {
							$is_same = false; break;
						}
					}
					if ($is_same) { //Found it - no need so search more
						unset($new_items[$k]); //not a new item
						$is_found = true; break; 
					}
				}
				if (!$is_found) { //Item is removed
					$current_item->delete();
				}
			}
			foreach ($new_items as $new_item) { //Add the new items
				$new_item[$value['foreign_column']] = $data[$this->primary_key];
				$this->{$value['foreign_model'].'_model'}->insert($new_item);
			}
		}
		foreach ($this->after_set as $method) {
			call_user_func_array(array($this, $method), array($data));
		}
		foreach ($this->behaviors as $behavior=>$config) {
			call_user_func_array(array($behavior.'_Behavior', '_run_after_set'), array(&$data, $config));
		}
	}
	
	public function validate($data) {
		if ($this->_run_validation($data)) {
			return get_instance()->form_validation->get_data();
		}
		return FALSE;
	}
	
	public function validate_arr($data=array()) {
		$data = $data ? $data : $_POST;
		$rules = array();
		foreach ($this->validate as $field=>$rule) if (isset($data[$field])) {
			$rule['field'] = $field;
			if (isset($rule['messages'])) {
				foreach ($rule['messages'] as $func=>$msg) get_instance()->form_validation->set_message($func, $msg);
				unset($rule['messsages']);
			}
			$rules[] = $rule;
		}
		return $rules;
	}
	
	/**
	 * Runs validation on the passed data.
	 *
	 * @return bool
	 */
	private function _run_validation($data) {
		$ci = get_instance();
		if($this->skip_validation) {
			return TRUE;
		}
		$ci->load->library('form_validation');
		
		$rules = $this->validate_arr($data);
		
		if(!empty($rules)) {
			foreach($data as $key => $val) {
				$_POST[$key] = $val;
			}
			
			if(is_array($rules)) {
				$ci->form_validation->set_rules($rules);				
				return $ci->form_validation->reset()->run();
			} else {
				return $ci->form_validation->reset()->run($rules);
			}
		} else {
			return TRUE;
		}
	}

	/**
	 * Fetches the table from the pluralised model name.
	 *
	 * @return void
	 */
	private function _fetch_table() {
		if ($this->_table == NULL) {
			$class = preg_replace('/(_m|_model)?$/i', '', get_class($this));
			
			$this->_table = plural(strtolower($class));
		}
	}
	
	private function _set_default_relation_data() {
		//Set default relations data
		foreach ($this->has_many as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->has_many[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => true,
				'foreign_model' => singular($field_name),
				'foreign_column' => singular($this->_table).'_id'
			), $field_info);
			$this->has_many[$field_name] = $field_info;
		}
		foreach ($this->has_one as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->has_one[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => true,
				'foreign_model' => singular($field_name),
				'foreign_column' => singular($this->_table).'_id'
			), $field_info);
			$this->has_one[$field_name] = $field_info;
		}
		foreach ($this->many_to_many as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->many_to_many[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => false,
				'foreign_model' => singular($field_name),
				'through' => $this->_table.'_'.$field_name,
				'self_column' => singular($this->_table).'_id',
				'foreign_column' => singular($field_name).'_id'
			), $field_info);
			$this->many_to_many[$field_name] = $field_info;
		}
		foreach ($this->belongs_to as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->belongs_to[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => false,
				'foreign_model' => $field_name,
				'foreign_column' => $field_name.'_id'
			), $field_info);
			$this->belongs_to[$field_name] = $field_info;
		}
		foreach ($this->polymorphic_belongs_to as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->polymorphic_belongs_to[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => false,
				'model_column' => $field_name.'_model',
				'item_column' => $field_name.'_id',
			), $field_info);
			$this->polymorphic_belongs_to[$field_name] = $field_info;
		}
		foreach ($this->polymorphic_has_many as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->polymorphic_has_many[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => false,
				'foreign_model' => singular($field_name),
				'model_column' => 'item_model',
				'item_column' => 'item_id',
			), $field_info);
			$this->polymorphic_has_many[$field_name] = $field_info;
		}
		foreach ($this->polymorphic_has_one as $field_name => $field_info) {
			if (!is_array($field_info)) {
				unset($this->polymorphic_has_one[$field_name]);
				$field_name = $field_info;
				$field_info = array();
			}
			$field_info = array_merge(array(
				'on_delete_cascade' => false,
				'foreign_model' => singular($field_name),
				'model_column' => 'item_model',
				'item_column' => 'item_id',
			), $field_info);
			$this->polymorphic_has_one[$field_name] = $field_info;
		}
	}
	
	/**
	 * Sets where depending on the number of parameters
	 *
	 * @return void
	 */
	// Quang change because we need to extends some function in child class
	// so that _set_where is necessary. When this libraries is expanded all 
	// functions, this could be set as 'private' again
	// private function _set_where($params) {
	public function _set_where($params) {
		if(count($params) == 1) {
			/*
			foreach ($params as $key=>$val) {
				if (isset($this->belongs_to[$key]))
			}
			*/
			if (is_array($params[0])) {
				foreach ($params[0] as $key=>$param) {
					if (is_array($param)) {
						$this->db->where_in($key, $param);
					} else {
						$this->db->where($key, $param);
					}
				}
			} else {
				$this->db->where($params[0]);
			}
		} else {
			$this->db->where($params[0], $params[1]);
		}
		return $this;
	}

	//RR - why?
	public function get_select_array($select, $where)
	{
		$this->db->select($select);
		$this->db->from($this->_table);
		$this->db->where($where);

		$query = $this->db->get();
		return $query->result_array();
	}
}
