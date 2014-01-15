<?php

class Major_model extends MY_Model
{
	protected $primary_key = 'major_id';
	protected $name_key = 'major';
	
	public function keyword($q) {
		$this->db->like($this->name_key, $q);
		return $this;
	}

}