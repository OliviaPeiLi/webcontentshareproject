<?php
/** 
 * Comment up/unup
 * @author radilr
 *
 */
require_once 'like.php';
class Comment extends Like {
	
	public function create($comment_id, $result=TRUE) {
		$data = array('user_id'=>$this->user->id, 'comment_id'=>$comment_id);
		
		if($this->like_model->count_by($data)) {
			//It really needs to return an error message or the js breaks
			die(json_encode(array('status'=>false,'error'=>'You&#39;ve already upvoted this.')));
		}
		
		if ($this->is_mod_enabled('kissmetrics')) {
			//kissmetrics
			$this->load->library('KISSmetrics/km');
			$this->km->init($this->config->item('km_key'));
			$this->km->identify($this->user->uri_name);
			$this->km->record('upvote a comment');
		}
		
		$comment = $this->comment_model->get($comment_id);
		$data['user_id_to'] = $comment->user_id_from;
		$id = $this->like_model->insert($data);

		if($result){
			echo (json_encode(array('status'=>true, 'like_id'=>$id, 'comment_id'=>$comment_id)));
		}else{
			return $id;
		}

		if (! $this->input->is_ajax_request() )	{
			// redirect to referrer
			Url_helper::redirect($_SERVER['HTTP_REFERER']);
		}
		exit;
	}
	
	public function remove($comment_id, $result=TRUE) {

		$data = array('user_id'=>$this->user->id, 'comment_id'=>$comment_id);
		$this->like_model->delete_by($data);

		if($result){
			echo (json_encode(array('status'=>true)));
		}else{
			return TRUE;
		}

		if (! $this->input->is_ajax_request() )	{
			// redirect to referrer
			Url_helper::redirect($_SERVER['HTTP_REFERER']);
		}
		exit;	
	}
}
