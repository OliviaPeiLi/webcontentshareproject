<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MX_Controller {
	
	/* ============================== LISTING ============================ */
	
	public function index($newsfeed_id) {
		$this->get_saved_comment();

		$comments = $this->comment_model
						->order_by('comment_id', 'ASC')
						->get_many_by(array('newsfeed_id' => $newsfeed_id, 'newsfeed_id > '=>0));
		
		$this->load->view('comment/comments', array(
					'comments' => $comments,
				));
	}
	
	public function folder_comments($folder_id) {
		$this->get_saved_comment();
		
		$comments = $this->comment_model
						->order_by('comment_id', 'ASC')
						->get_many_by(array('folder_id' => $folder_id, 'folder_id > '=>0));
						
		$num_comments = $this->comment_model->count_by(array('folder_id' => $folder_id, 'folder_id > '=>0));
						
		$this->load->view('comment/comments_ugc', array(
					'folder_id' => $folder_id,
					'comments' => $comments,
					'num_comments' => $num_comments,
				));
	}
	
	private function get_saved_comment() {
		if ($this->user) {
			foreach ($_COOKIE as $k => $v) {
				if ( strpos($k,"comment_") === 0 && $v != "" ) {
					$time = str_replace("comment_","",$k);
					$data = explode("|~|",$v);
					if (!$this->session->userdata($k)) {
						$_POST = array('newsfeed_id' => $data[0], 'comment'=> $data[1]);
						$this->create();
						$this->session->set_userdata($k,TRUE);
					}
				}
			}
		}
	}
	
	/* ================================= UPDATE ================================= */
	
	public function create() {

		$post = $this->input->post();

		if (! $data = $this->comment_model->validate($post)) {
			if ($this->input->is_ajax_request()) {
				die( json_encode( array('status'=>false, 'error'=>Form_Helper::validation_errors()) ) );
			} else {
				return false;
			}
		}

		$comment = $this->comment_model->get($this->comment_model->insert($data));

		if ($this->is_mod_enabled('kissmetrics')) {
			//kissmetrics
			$this->load->library('KISSmetrics/km');
			$this->km->init($this->config->item('km_key'));
			$this->km->identify($this->user->uri_name);
			$this->km->record('made a comment');
		}
		
		//Publicate to facebook that the user commented
		if ($this->user->fb_id > 0 && $this->user->fb_activity == '1') {

			if (isset($data['newsfeed_id']))	{
				$newsfeed = $this->newsfeed_model->select_fields('url')->get($data['newsfeed_id']);
			}	else {
				$newsfeed = (object)array("url"=>"");
			}

			$data = array(
				'fb_id'=>$this->user->fb_id,
				'action'=>'comment',
				'link_url'=>Url_helper::base_url().'drop/'.$newsfeed->url,
			);

			if (isset($data['newsfeed_id']))	{
				$data['newsfeed_id']=$data['newsfeed_id'];
			}

			$this->fb_activity_model->insert($data);

		}

		if ($this->input->is_ajax_request()) {

			echo json_encode(array(
						'status' => true,
						'data' => array(
							'comment_id' => $comment->comment_id,
							'body' => $comment->comment,
							'del_url' => '/del_comm/'.$comment->comment_id,
							'like_url' => '/add_like/comment/'.$comment->comment_id,
							'unlike_url' => '/rm_like/comment/'.$comment->comment_id
				)));

			return ;
		} else {
			return false;
		}
	}
	
	/* ========================================= DELETE ======================================== */
	
	public function remove($comment_id) {
        echo json_encode(array('status'=> $this->comment_model->delete($comment_id) ));
	}

}