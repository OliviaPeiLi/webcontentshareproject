<?php

class System_notification_model extends MY_Model
{

	public $templates = array('default','avatar','friends','password');

	/* =============================== EVENTS ======================= */
	
	public function _run_after_get($row) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->template)) $row->_template_name = $this->templates[$row->template];
	}
}
?>