<?php

class User_education_model extends MY_Model
{
	protected $_table = 'user_education';
    protected $primary_key = "id";
    
    public function _run_after_get($row=null) {
    	if (!parent::_run_after_get($row)) return ;
    	
	    $row->concentration = json_decode($row->concentration);
	    $row->classes = json_decode($row->classes);
	    
	    $row->major_string = '';
	    foreach($row->concentration as $major){
		    $row->major_string .= $major->name.' ';
	    }
    }
}

?>