<?

class School_model extends MY_Model {

	protected $primary_key = 'id';
	protected $name_key = 'name';
	//Relations
	protected $has_many = array(

							  'user_schools' => array('foreign_column'=>'school_id'),

						  );

	public function keyword($q) {
		$this->db->like($this->name_key, $q);
		return $this;
	}

}