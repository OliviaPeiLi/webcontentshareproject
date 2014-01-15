<?php
/**
 * Profile edit funcs - mostly ajax responses
 * @link /account_options
 */
require_once 'profile.php';

class Profile_social extends Profile {
	
	/**
	 * Called via ajax - on disconnect from fb btn click
	 * @link /account_options
	 */
	public function disconnect_fb() {
		$facebook = $this->load->library('facebook_driver');
		if ($facebook->disconnect()) {
			$this->user_model->update($this->session->userdata('id'), array('fb_id'=>'0'));
			die(json_encode(array('status'=>true)));
		} else {
			die(json_encode(array('status'=>false, 'error' => 'Could not contact facebook')));
		}
	}
	
	/**
	 * Called via ajax - on enable fb_activity
	 * @link /account_options
	 */
	function enable_fb_activity() {
		$this->user->update(array('fb_activity'=>'1'));
		die(json_encode(array('status'=>true)));
	}
	
	/**
	 * Called via ajax - on disable fb_activity
	 * @link /account_options
	 */
	function disable_fb_activity() {
		$this->user->update(array('fb_activity'=>'0'));
		die(json_encode(array('status'=>true)));
	}
	
    function enable_twitter_activity() {
        $this->user_model->update($this->session->userdata('id'), array('twitter_activity'=>'1'));
        die(json_encode(array('status'=>true)));
    }

    function disable_twitter_activity() {
        $this->user_model->update($this->session->userdata('id'), array('twitter_activity'=>'0'));
        die(json_encode(array('status'=>true)));
    }

    function disconnect_twitter() {
        $this->user_model->update($this->session->userdata('id'), array('twitter_id'=>'0', 'twitter_token' => ''));
        die(json_encode(array('status'=>true)));
    }
    
}