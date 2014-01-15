<?php

class Newsletter_model extends MY_Model {
	
	/**
	 * Used in the admin
	 */
	public function get_precent_sent($row) {
		$all_users = $this->user_model->count_all();
		$sent_to = $this->user_model->count_by(array('newsletter_time >=' => $row->newsletter_time));
		return $sent_to/$all_users; 
	}

}