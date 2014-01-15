<?php
require_once 'Scraper_default.php';	
class Scraper_ted extends Scraper_default {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);

		preg_match('#;su=(.*)&amp;vw#', $contents, $matches);
		if ( isset($matches[1]) ) {
			preg_match('#;su=(.*)#', $matches[1], $match);
			$this->thumb = isset($match[1]) ? $match[1] : '';
		}
	}
	
}
