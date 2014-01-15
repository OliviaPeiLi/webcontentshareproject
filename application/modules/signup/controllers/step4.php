<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Step4 extends MX_Controller {
	
	public function index() {
		if(!$this->session->userdata('id')) Url_helper::redirect('/');
		
		$facebook = $this->load->library('Facebook_driver');
		$friends = $facebook->get_user_friends();
		
		$registered = array();
		$not_registered = array();
		
		foreach ($friends->data as $friend) {
			$user = $this->user_model->get_by(array('fb_id' => $friend['id']));
			if ($user) {
				$registered[] = $user;
			} else {
				$not_registered[] = array(
					'id' => $friend['id'],
					'name' => $friend['name'],
				);
			}
		}
		
		$num_invited = $this->alpha_user_model->count_by(array('user_id' => $this->session->userdata('id')));
		
		return parent::template('signup/step4_invite', array(
			'results' => $not_registered,
			'registered' => $registered,
			'num_invited' => $num_invited,
		), 'Invite');
		
	}
}