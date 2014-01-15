<?php

class Modules_config_model extends MY_Model
{
	protected $_table = 'modes_config';
	protected $primary_key = 'id';
	protected $name_key = 'name';
							
	/* ==================== EVENTS ==================== */
	
	public function _run_before_set($data) {
		unset($data['updated']);	   
		return parent::_run_before_set($data);
	}
	
	/* =================== FILTERS ======================== */
	
	public function search($name) {
		$this->db->like('name', $name);
		return $this;
	}

}