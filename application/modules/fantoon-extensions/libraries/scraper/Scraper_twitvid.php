<?php
require_once 'Scraper_default.php';	

class Scraper_twitvid extends Scraper_default {
	
	private $id;
	private $modified_url;
	
	public function __construct($contents, $url) {
		$this->modified_url = str_replace('autoplay=false','autoplay=true',$contents);
		parent::__construct($contents, $url);
	}

	public function get_embed() {
		return $this->modified_url;
	}
	
}
