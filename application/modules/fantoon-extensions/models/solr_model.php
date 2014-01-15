<?php
/**
 * Solr_model 
 *   It is dummy model to handle solr search for multiple tables
 *   For searching of a specific table, the logic is implemented
 *   inside that table model. 
 * 
 */
class Solr_model extends MY_Model
{
	public function try_solr() {
		$ci = get_instance();
		$solr_conf = $ci->load->config('solr');
		$db = $this->load->database($solr_conf['address'], true);

		if ($db->connected) {
			$this->db = $db;
			return true;
		}
		
		return false;
	}

	public function select_search_fields($fields) {
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->solr_select = $fields;
		}
		return $this;
	}

	public function search($keyword) {

		if ($this->db instanceof CI_DB_solr_driver) {
			// set search criterial in all tables
			// ranking by specific number ($keyword^rank)
			$this->db->where(array(
				// people
				'first_name'    => "$keyword^100 $keyword*^50",
				'last_name'     => "$keyword^100 $keyword*^50",
				'uri_name'      => "*$keyword*^10",

				// newsfeed
				'title'         => "$keyword^30",
				'description'   => "$keyword^10",
				'content_plain' => "$keyword^10",

				// folder
				'folder_name'   => "*$keyword*^20"
			));
		}

		return $this;
	}

	public function get_all($limit = NULL) {
		if ($this->db instanceof CI_DB_solr_driver) {
			$ids = $this->db->get('*')->result(); 
			$this->_table = '*';
			if (!$ids) return array();
			return $ids;
		}
		return false;
	}

}
