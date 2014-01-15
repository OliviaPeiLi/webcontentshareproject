<?php
	
class Scraper_ign extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		//http://www.ign.com/articles/2012/09/14/the-a-z-of-bond
		//http://www.ign.com/videos/2012/05/21/james-bond-skyfall-first-trailer
	    preg_match('#url=(.*?)["&]#', $contents, $matches);
		if (isset($matches[1])) $this->id = $matches[1];
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		$contents = Scraper::request($this->id);
		preg_match('#property="og\:image" content="(.*?)"#', $contents, $matches);
		if (!isset($matches[1])) return parent::get_images();
		return array(
				array(				
					'src'=> $matches[1],
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}
	
}
