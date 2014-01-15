<?php
require_once 'application/modules/fantoon-extensions/controllers/contest.php';

class Profile_contests extends Contest {
	
	public function contests($user_id) {
		$per_page = $this->config->item('connections_page_limit');
		$user = $this->user_model->get($user_id);
		$model = $this->get_model($per_page);
		
		$model = $model->filter_user($user_id);
		
		return $this->output($model, '/contests/'.$user->uri_name, $user);
	}
	
}