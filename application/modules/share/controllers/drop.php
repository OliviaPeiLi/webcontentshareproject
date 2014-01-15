<?php
/** 
 * Share drop on a social networ facebook, twitter or pinterest
 * @author radilr, Ray
 */
require_once 'share.php';
class Drop extends Share {
	
	public function get($api) {
		$post = array(
			'api' => $api
		);

		if ($this->session->userdata('id')) {
			$post['user_id'] = $this->session->userdata('id');
		} elseif ($this->input->post('social_user_id')) {
			$post['social_user_id'] = $this->input->post('social_user_id');
		} else {
			die(json_encode(array('status' => false, 'error' => 'User not recognized')));
		}
		
		if ($this->input->post('newsfeed_id')) {
			$post['newsfeed_id'] = $this->input->post('newsfeed_id');
		} else if ($this->input->post('folder_id')) {
			$post['folder_id'] = $this->input->post('folder_id');
		} else {
			die(json_encode(array('status' => false, 'error' => 'Type not recognized')));
		}

        die(json_encode(array('status'=> ! (bool) $this->newsfeed_share_model->count_by($post))));
	}
	
	public function create($api) {

		$data = array(
			'api' => $api
		);

		if ($this->session->userdata('id')) {
			$data['user_id'] = $this->session->userdata('id');
		} elseif ($this->input->post('social_user_id')) {
			//make sure its valid request
			if ($api == 'twitter') {
				$twitter = $this->load->library('Twitter');
				$user_info = $twitter->get_user_info();
				if (!isset($user_info->id) || $user_info->id != $this->input->post('social_user_id')) {
					die(json_encode(array('status' => false, 'error' => 'Bad request')));
				}
			} elseif ($api == 'fb') {
				$facebook = $this->load->library('Facebook_driver');
				$user_info = $facebook->get_user_info();
				if (!isset($user_info->id) || $user_info->id != $this->input->post('social_user_id')) {
					die(json_encode(array('status' => false, 'error' => 'Bad request')));
				}
			} else {
				$ip = explode('.', isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
				$ip = $ip[0]+(255*$ip[1])+(255*255*$ip[2])+(255*255*255*$ip[3]);
				if ($ip != $this->input->post('social_user_id')) {
					die(json_encode(array('status' => false, 'error' => 'Bad request from: '.$ip)));
				}
			}
			$data['social_user_id'] = $this->input->post('social_user_id');
		} else {
			die(json_encode(array('status' => false, 'error' => 'User not recognized')));
		}
		
		if ($this->input->post('newsfeed_id')) {
			$data['newsfeed_id'] = $this->input->post('newsfeed_id');
		} else if ($this->input->post('folder_id')) {
			$data['folder_id'] = $this->input->post('folder_id');
		} else {
			die(json_encode(array('status' => false, 'error' => 'Type not recognized')));
		}
				
		if($this->newsfeed_share_model->count_by($data)) {
			echo json_encode(array('status'=>false,'error'=>'You&#39;ve already shared this.'));
			return;			
		}

		$id = $this->newsfeed_share_model->insert($data);

		if ($this->input->post('newsfeed_id')) {
			if ($this->input->post('referral')) {
				$ref = $this->newsfeed_referral_model->get($this->input->post('referral'));
				if ($ref) $ref->update(array('points' => $ref->points+10));
			}
			$newsfeed = $this->newsfeed_model->get($data['newsfeed_id']);
		}
		echo json_encode(array('status'=>true, 'id'=>$id, 'left'=>isset($newsfeed) ? strtotime($newsfeed->folder->ends_at)-time()+73200 : 0));
   	}
	
	public function remove($id) {

	}
}
