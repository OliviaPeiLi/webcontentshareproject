<?php

require_once BASEPATH.'helpers'.DIRECTORY_SEPARATOR.'inflector_helper.php';

class DatabaseInterface {
	
	public $db;
	private $loaded_objects = array();
	private $_table = null;
	public $config = array();
	
	public function add_object($table, $override=array()) {
		$this->_table = $table;
		$this->db->insert($table, array_map(
			array($this, 'set_item_num'),	
			array_merge($this->config['objects'][$table], $override)
		));
		$id = $this->db->insert_id();
		$obj = $this->db->get_where($table, array($this->get_primary($table) => $id))->result_object();
		$this->loaded_objects[$table][] = $obj[0];
		return $obj[0];
	}
	
	private function get_primary($table) {
		foreach ($this->db->field_data($table) as $data) {
			if ($data->primary_key) return $data->name;
		}
	}
	
	private function set_item_num($item) {
		return str_replace('%i', isset($this->loaded_objects[$this->_table]) ? count($this->loaded_objects[$this->_table])+1 : 0, $item);
	}
	
	public function clean() {
		foreach ($this->loaded_objects as $table=>$objs) {
			$primary = $this->get_primary($table);
			foreach ($objs as $obj) {	
				$this->db->delete($table, array($primary => $obj->{$primary}));
			}
		}
	}
}