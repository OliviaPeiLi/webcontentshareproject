<?php

require_once 'search.php';
class Drops_search extends Search {

	public function source($source=null)  {
		$this->lang->load('newsfeed/newsfeed', LANGUAGE);

		$source = urldecode($source);

		return parent::template('search/source', array(
	        'source' => $source,
		), 'Fandrop - '. sprintf(Language_helper::lang('newsfeed_source_title'), $source));
    }

}
