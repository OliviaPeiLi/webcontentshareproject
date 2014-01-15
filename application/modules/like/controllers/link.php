<?php
/** 
 * Comment up/unup
 * @author radilr
 *
 */
require_once 'drop.php';
class Link extends Drop {
	
	public function create($link_id) {
		$newsfeed_id = $this->newsfeed_model->get_by(array('type'=>'link', 'activity_id'=>$link_id))->newsfeed_id;
		parent::create($newsfeed_id);
	}
	
	public function remove($link_id) {
		$newsfeed_id = $this->newsfeed_model->get_by(array('type'=>'link', 'activity_id'=>$link_id))->newsfeed_id;
		parent::remove($newsfeed_id);
	}
}
