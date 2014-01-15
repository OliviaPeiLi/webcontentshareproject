<?php
require_once 'search.php';
class collections extends Search {
	
	public function rss_source() {
		$q = $this->input->get('q');
		$sources = $this->rss_source_model->select_fields(array('id', 'source as name'))
						->search($q)->jsonfy(10);
		echo json_encode($sources);
	}

}
