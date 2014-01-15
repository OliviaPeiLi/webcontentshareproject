<?php

class Fb_activity_model extends MY_Model {
    
    public function _run_after_get($row) {
    	if (!parent::_run_after_get($row)) return ;
    	$row->_data_type = 'activity';
    }
}