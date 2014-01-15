<?php

class Photo_Model extends MY_Model
{
	protected $primary_key = 'photo_id';
		
	//Relations
	protected $belongs_to = array(
								'user_from' => array('foreign_column' => 'user_id_from', 'foreign_model' => 'user'),
								'folder'
							);

	protected $polymorphic_has_one = array(
										 'newsfeed' => array(
												 'model_column' => 'type',
												 'item_column' => 'activity_id'
										 ),
										 'ticker' => array(
												 'foreign_model' => 'activity',
												 'model_column' => 'type',
												 'item_column' => 'activity_id',
												 'on_delete_cascade' => true
										 )
									 );

	/* ========================== EVENTS ================================ */

	public function _run_after_get($row=null) {
		if (!parent::_run_after_get($row)) return ;
		$row->_title = '';
		$row->_text = '';
		if (isset($row->caption)) {
			$row->_title = $row->_text = $row->caption;
			$row->_caption_plain = $row->text_plain = strip_tags($row->caption);
		}
		$row->source = null;
	}

	protected function _run_before_set($data) {
		if (isset($data['caption']))
		{
			$data['caption'] = str_replace('#','_hash_',$data['caption']);
		}
		if (isset($data['link']) && !isset($data['source']))
		{
			$data['link'] = str_replace(array('http://','https://'),'',$data['link']);
			$data['link'] = 'http://'.$data['link'];
			$parse = @parse_url($data['link']);
			$data['source'] = isset($parse['host']) ? str_replace('www.', '', $parse['host']) : '';
		}
		return parent::_run_before_set($data);
	}
	
	protected function _run_after_set($row=null) {
		if (isset($row['img']) || isset($row['caption'])) {
			$newsfeed = $this->newsfeed_model->get_by(array('activity_id' => $row['photo_id'], 'type'=>'photo'));
			$newsfeed_update = array();	
			if (isset($row['caption'])) {
				$newsfeed_update['title'] = $newsfeed_update['description'] = $row['caption'];
			}
			if (!empty($newsfeed_update)) $newsfeed->update($newsfeed_update);
		}
		return parent::_run_after_set($row);
	}

}