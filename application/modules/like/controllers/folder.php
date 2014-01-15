<?php
/** 
 * Comment up/unup
 * @author radilr
 *
 */
require_once 'like.php';
class Folder extends Like {
	
	public function create($folder_id) {
		$data = array('user_id'=>$this->user->id, 'folder_id'=>$folder_id);
		
		if($this->like_model->count_by($data)) {
			//It really needs to return an error message or the js breaks
			if ($this->input->is_ajax_request()) {
				echo (json_encode(array('status'=>false,'error'=>'You&#39;ve already upvoted this.')));
			} else { //from landing page
				$folder = $this->folder_model->get($folder_id);
				redirect('/'.$folder->get_folder_url());
			}	
			return;
		}
		
		if ($this->is_mod_enabled('kissmetrics')) {
			//kissmetrics
			$this->load->library('KISSmetrics/km');
			$this->km->init($this->config->item('km_key'));
			$this->km->identify($this->user->uri_name);
			$this->km->record('upvote a list');
		}
		
		$folder = $this->folder_model->get($folder_id);
		$data['user_id_to'] = $folder->user_id;
		$id = $this->like_model->insert($data);
		
		if ($this->input->is_ajax_request()) {
			echo json_encode(array('status'=>true, 'like_id'=>$id, 'folder_id'=>$folder_id));
		} else { //from landing page
			redirect('/'.$folder->get_folder_url());
		}
	}
	
	public function remove($folder_id, $result=TRUE) {
		$data = array('user_id'=>$this->user->id, 'folder_id'=>$folder_id);
		
		$this->like_model->delete_by($data);
		if($result){
			die(json_encode(array('status'=>true)));
		}else{
			return TRUE;
		}
	}
}
