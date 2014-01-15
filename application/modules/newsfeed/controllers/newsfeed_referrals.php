<?php
class Newsfeed_referrals extends MX_Controller {
	
	public function index() {
		
	}
	
	public function create() {
		$post = $this->input->post();
		$newsfeed = $this->newsfeed_model->get($post['newsfeed_id']);
		if (!$newsfeed) {
			die(json_encode(array('status'=>false,'error'=>'newsfeed not found')));
		}
		
		$check_array = array('email'=>$post['email'],'newsfeed_id'=>$post['newsfeed_id']);
		$ref  = $this->newsfeed_referral_model->get_by($check_array);
		if (!$ref) {
			if ($this->input->post('referral_id') && $src_ref = $this->newsfeed_referral_model->get($this->input->post('referral_id'))) {
				$src_ref->update(array('points'=>$src_ref->points+10));
				$check_array['points'] = 10;
			}
			$id = $this->newsfeed_referral_model->insert($check_array);
			$ref = $this->newsfeed_referral_model->get($id);
		}
		
		if (!$ref->url) $ref->url = $ref->update_url();
		
		die(json_encode(array('status'=>true, 'url'=>$ref->url, 'id'=>$ref->id, 'long_url' => Url_helper::base_url('/drop/'.$newsfeed->url.'/'.$ref->name))));
	}
		
}