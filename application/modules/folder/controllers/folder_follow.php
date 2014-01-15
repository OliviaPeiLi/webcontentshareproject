<?php
class Folder_follow extends MX_Controller {
	
	/**
	 * @since 7/23/2012 RR moved to preAjax in js no need to return HTML
	 */
	public function create($folder_id) {
		$folder = $this->folder_model->get($folder_id);
				
		$folder_data = array('folder_id'=>$folder_id, 'user_id' => $this->user->id);
		
		if( $this->folder_user_model->count_by($folder_data)) {
			if ($this->input->is_ajax_request()) {
				die(json_encode(array('status'=>false,'error'=>'You are already following this folder')));
			} else {
				return Url_helper::redirect($folder->folder_url);
			}
			
		}
		$id = $this->folder_user_model->insert($folder_data);
		
		if ($this->input->is_ajax_request()) {
			die(json_encode(array('status'=>true)));
		} else {
			return Url_helper::redirect($folder->folder_url);
		}
	}

	/**
	 * @since 7/23/2012 RR moved to preAjax in js no need to return HTML
	 */
	public function delete($folder_id) {
		
		$this->folder_user_model->delete_by(array('folder_id'=>$folder_id, 'user_id' => $this->user->id));
		
		die(json_encode(array('status'=>true)));
	}
}