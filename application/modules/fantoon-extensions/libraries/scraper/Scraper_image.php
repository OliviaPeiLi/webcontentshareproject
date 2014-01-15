<?php

class Scraper_image extends Scraper_html {
	
	private $_url;
	
	public function __construct($contents, $url)
	{
		$this->_url = $url;
	}
	
	public function get_images()
	{
		return array(
				array(				
					'src'=> $this->_url,
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>2
				)
			);
	}
	
}
