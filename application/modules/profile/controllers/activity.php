<?php
class Activity extends MX_Controller {
	
	public function index($profile_id=0) {
		$page_limit = 16;
		$page = $this->input->get('page', true, 0)+1;

		if($profile_id != $this->session->userdata('id')) {
			$friend_activity = $this->activity_model->paginate($page, $page_limit)->order_by('id', 'DESC')
				->user_id_from_in($profile_id);
		} else {
			$followings = $this->user->user_followings;
			$friend_activity = null;
			$followings_id = array();
			foreach($followings as $user){
				$followings_id[$user->user2_id] = $user->user2_id;
			}
			if(!empty($followings_id)){
				$friend_activity = $this->activity_model->paginate($page, $page_limit)->filter_type()
					->user_id_from_in($followings_id)
					->order_by('id', 'DESC');
			}
		}
		
		if ($this->input->is_ajax_request()) {
			echo json_encode($friend_activity->jsonfy());
		} else {
			$this->load->view('profile/activity', array(
				'activity' => $friend_activity ? $friend_activity->get_all() : '',
				'profile_id' => $profile_id,
			));
		}
	}
}