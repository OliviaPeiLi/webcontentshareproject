<?php
require_once 'lists.php';
class Lists_posts extends Lists {
	
	public function update($list_id, $newsfeed_url='') {
		$folder = $this->folder_model->get($list_id);
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		$newsfeed = false;
		if ($newsfeed_url) $newsfeed = $this->newsfeed_model->get_by(array('user_id_from'=>$this->user->id,'url'=>$newsfeed_url));
		
		if ($this->input->post()) {
			$post = $this->input->post();
			Cookie_helper::set_cookie('intscrp_lastCollection', $folder->folder_id, 7 * 24 * 3600);
			
			
			foreach ($post['item'] as $key=>$item) {
				unset($item['description_orig']);
				$item['folder_id'] = $folder->folder_id;
				$item['description'] = strip_tags($item['description']);
				$item['news_rank'] = time();
				$item['time'] = date("Y-m-d H:i:s");
				$item['activity']['link']['media'] = $_REQUEST['item'][$key]['activity']['link']['media'];
				$item['activity']['link']['content'] = $_REQUEST['item'][$key]['activity']['link']['content'];
	
				if($item['link_type'] == 'text') {
					$item['activity']['link']['content'] = nl2br($item['activity']['link']['content']);
				}
	
				$model = $this->newsfeed_model;
				if ($item['link_type'] != 'image') {
					foreach ($model->behaviors['uploadable']['img']['thumbnails'] as $thumb => &$thumb_config) {
						unset($thumb_config['transform']['watermark']);
					}
				}
				if (!in_array($item['link_type'], array('image','embed'))) {
					unset($item['img']);
				}
				
				if ($item['newsfeed_id']) {
					$update_data = array(
						'description' => $item['description']
					);
					if ($newsfeed->link_type == 'text') {
						$update_data['activity']['link']['content'] = $item['activity']['link']['content'];
					}
					$newsfeed->update($update_data);
				} else {
					if (!$id = $model->insert($item)) {
						die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
					}
					$newsfeed = $this->newsfeed_model->get($id);
					if($newsfeed->link_type == 'content') {
						$newsfeed->activity->update(array('content' => 'uploaded to S3'));
					}
				}
							
			}
			
			die(json_encode(array('status'=>true)));
		}
		
		self::template('add_posts', array(
			'folder' => $folder,
			'newsfeed' => $newsfeed,
		));
	}
	
	public function resort($list_id) {
		$folder = $this->folder_model->get($list_id);
		if (!$folder->can_edit($this->user)) {
			die(json_encode(array('status'=>'false','error'=>'You dont have permission to edit this list')));
		}
		
		if (!$folder) {
			die(json_encode(array('status'=>false,'error'=>'Folder not found')));
		}
		
		$ids = $this->input->post('newsfeed_id');
		
		//update non loaded folders
		mysql_query("UPDATE newsfeed SET `position` = `position` + ".count($ids)." 
					WHERE folder_id = {$folder->folder_id} AND newsfeed_id NOT IN (".implode(',', $ids).")");
		
		foreach ($ids as $pos=>$id) {
			mysql_query("UPDATE newsfeed SET `position` = $pos WHERE folder_id = {$folder->folder_id} AND newsfeed_id = ".$id);
		}
		
		die(json_encode(array('status'=>true)));
	}
	
}