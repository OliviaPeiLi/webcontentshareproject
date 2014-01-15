<?php

class Newsfeed_hashtag_model extends MY_Model {
	
	protected function _run_after_create($data) {
    	$ci = get_instance();
	    if ($ci->user && $ci->user->role == '1') {
		    $this->db->query("UPDATE newsfeed SET up_target = ".rand(20,50)." WHERE newsfeed_id = ".$data['newsfeed_id']);
	    } else {
		    $this->db->query("UPDATE newsfeed SET up_target = ".rand(5,15)." WHERE newsfeed_id = ".$data['newsfeed_id']);
	    }
	    return parent::_run_after_create($data);
    }
    
}