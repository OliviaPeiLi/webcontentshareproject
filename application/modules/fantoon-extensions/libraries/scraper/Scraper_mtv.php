<?php
require_once 'Scraper_default.php';	

class Scraper_mtv extends Scraper_default {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);

		preg_match('#data-thumb="(.*?)"#', $contents, $matches);
		$this->thumb = isset($matches[1]) ? $matches[1] : '';

		preg_match('#mgid\:hcx\:content\:mtv\.tv\:(.*?)"#', $contents, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
}
