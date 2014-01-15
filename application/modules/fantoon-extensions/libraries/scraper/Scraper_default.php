<?php
	
class Scraper_default extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		
		preg_match('#data-thumb="(.*?)"#', $contents, $matches);
		
		$this->thumb = isset($matches[1]) ? $matches[1] : '';
	}
	
	public function get_images()
	{
		if (!$this->thumb) {
			return parent::get_images();
		}
		return array(
				array(				
					'src'=> $this->thumb,
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}
	
}