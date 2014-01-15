<?php
/** 
 * Comment up/unup
 * @author radilr
 *
 */
require_once 'like.php';
class Drop extends Like {
	
	public function create($newsfeed_id) {

		$data = array('user_id'=>$this->user->id, 'newsfeed_id'=>$newsfeed_id);
		
		if($this->like_model->count_by($data)) {
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>false,'error'=>'You&#39;ve already upvoted this.')));
			} else {
				$newsfeed = $this->newsfeed_model->get($newsfeed_id);
				redirect('/drop/'.$newsfeed->url);
			}
			return;			
		}
		
		if ($this->is_mod_enabled('kissmetrics')) {
			//kissmetrics
			$this->load->library('KISSmetrics/km');
			$this->km->init($this->config->item('km_key'));
			$this->km->identify($this->user->uri_name);
			$this->km->record('upvote a drop');
		}
		
		$newsfeed = $this->newsfeed_model->get($newsfeed_id);
		$data['user_id_to'] = $newsfeed->user_id_from;
		$id = $this->like_model->insert($data);
		

        if($this->user->twitter_id > 0 && $this->user->twitter_activity=='1') {
        	$twitter = $this->load->library('twitter');
        	$twitter->post(sprintf($this->lang->line('comment_liked_drop_msg'), Url_helper::base_url($newsfeed->url), strip_tags($newsfeed->description)));
        }
        if ($this->user->fb_id > 0 && $this->user->fb_activity=='1') {
            $fb_url = 'https://graph.facebook.com/'.$this->user->fb_id.'/'.$this->config->item('fb_app_namespace').':upvote?access_token='.$this->config->item('access_token').'&method=post&drop='.urlencode(Url_helper::base_url().$newsfeed->url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fb_url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
        }
        
		if ($this->input->is_ajax_request()) {
			echo json_encode(array('status'=>true, 'like_id'=>$id, 'newsfeed_id'=>$newsfeed_id));
		} else {
			redirect('/drop/'.$newsfeed->url);
		}
   	}
	
	public function remove($newsfeed_id, $result=TRUE) {
		$data = array('user_id'=>$this->user->id, 'newsfeed_id'=>$newsfeed_id);
		
		$this->like_model->delete_by($data);
		
		if($result){
			echo json_encode(array('status'=>true));
		}else{
			return TRUE;
		}
	}
}
