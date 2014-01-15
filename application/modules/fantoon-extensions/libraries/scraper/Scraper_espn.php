<?php
require_once 'Scraper_default.php';	

class Scraper_espn extends Scraper_default {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		$this->thumb = str_replace('amp;h=', 'h=', $this->thumb);
    }
			
}
