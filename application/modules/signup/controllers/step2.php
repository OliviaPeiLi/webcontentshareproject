<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Step2 extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->lang->load('signup/signup', LANGUAGE);
		if(!$this->user || $this->user->user_visits[0]->preview=='0')
		{
			Url_helper::redirect('/');
		}
	}
	
	/**
	 * Register Step 2 - Shows a list of categories for the user to choose
	 */
	public function index() {
		if ($this->input->post('hashtags')) {
			$this->user_visit_model->update($this->session->userdata('id'), array('preview'=>'1'));
	
			$power_user_ids = $this->get_power_users();
			if($this->user->fb_id == 0){
				Url_helper::redirect('/');
			}
			$this->is_mod_enabled('signup_invite') ? Url_helper::redirect('/signup_invite') : Url_helper::redirect('/');
			
		}
		
		return parent::template('signup/step2_topics', array(
		), 'Hashtags');
	}
	
	//To-do - optimize - some of this code can be in the models
	private function get_power_users() {
		if( ! $this->is_mod_enabled('category_power_users')) {
			return $this->config->item('power_users');
		}
		
		$user_ids = array();
		$hashtag_power_users = $this->config->item('hashtag_power_users');
		foreach($this->input->post('hashtags',true) as $hashtag) {
			if(isset($hashtag_power_users[$hashtag])) {
				foreach($hashtag_power_users[$hashtag] as $user_id) {
					$user_ids[$user_id] = $user_id;
				}
			}
			
			if($this->folder_model->count_by(array('user_id'=>$this->user->id,'folder_name'=>str_replace("_hash_","",$hashtag).' Collection')) == 0) {
				$folder_id = $this->folder_model->insert(array(
								'folder_name'=>str_replace("_hash_","",$hashtag).' Collection',
								'user_id'=>$this->user->id,
								'private'=>'0', 'editable'=>'1'
							));
			}
		}

		if ($user_ids && !empty($user_ids) && count($user_ids)<20) {
			$follow_users = $this->user_model->select_fields(array('id','email','avatar','first_name','last_name'))->get_many($user_ids);
			
			$this->system_notification_model->insert(array('user_id'=>$this->user->id,'template'=>2));
						
			foreach($follow_users as $friend) {
				if(isset($friend->id) && $this->connection_model->count_by(array('user1_id'=>$this->session->userdata('id'), 'user2_id'=>$friend->id)) == 0) {
					$this->connection_model->insert(array('user1_id'=>$this->session->userdata('id'), 'user2_id'=>$friend->id));
				}
			}
		}
		return  $user_ids;
	}
	
}