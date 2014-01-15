<?php
require_once 'Scraper_default.php';	
class Scraper_vine extends Scraper_default {
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		if (!$this->thumb) {
			preg_match('/property="og:image" content="(.*?)"/', $contents, $matches);
			$this->thumb = isset($matches[1]) ? $matches[1] : '';
		}
	}
	
	
	public function get_images() {
			if (!$this->thumb) {
				$this->thumb = '';//preg match contents
			}
			return array(
					array(
						'src'=>$this->thumb,
						'link'=>$this->url,
						'description'=> '',
						'alt'=>'',
						'type'=>1
					)
				);
		}
		
	public function get_embed() {
		return '<iframe width="560" height="315" src="'.$this->url.'/card" frameborder="0" allowfullscreen></iframe>';
	}
}