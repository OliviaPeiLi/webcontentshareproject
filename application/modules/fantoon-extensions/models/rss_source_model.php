<?php
/**
 * This table will be selected in the scripts/auto_update/rss.php script to auto populate collection
 * @author radilr
 *
 */
class Rss_source_model extends MY_Model {
	
	public $name_key = 'source';
	protected $has_many = array('folders');
	public $null_val  = '- No auto update -';
	
	/* ============== Select funcs ===================== */
	
	public function search($q) {
		$this->db->like('source', $q);
		return $this;
	}
	
	public function popular_dropdown() {
		return Array_Helper::array_merge_assoc(array(0 => $this->null_val), $this->order_by('update_on', 'asc')->limit(10)->dropdown());
	}
}