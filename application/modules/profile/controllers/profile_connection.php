<?php
require_once 'application/modules/profile/controllers/profile.php';

class Profile_connection extends Profile {
	
	public function followings($user_id) {
		$per_page = $this->config->item('connections_page_limit');
		$user = $this->user_model->get($user_id);

		$users_model = $this->get_model($per_page);
		
		$users_model = $users_model->filter_followings($user_id);
		
		return $this->output($users_model, '/followings/'.$user->uri_name);
	}
	
	public function followers($user_id) {
		$per_page = $this->config->item('connections_page_limit');
		$user = $this->user_model->get($user_id);
		
		$users_model = $this->get_model($per_page);
		
		$users_model = $users_model->filter_followers($user_id);
		
		return $this->output($users_model, '/followers/'.$user->uri_name);
	}
	
}