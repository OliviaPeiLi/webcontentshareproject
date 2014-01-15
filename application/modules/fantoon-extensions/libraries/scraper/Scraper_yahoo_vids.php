<?php
	
/**
 * Facebook scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
class Scraper_yahoo_vids extends  Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url) {
		parent::__construct($contents, $url);
		preg_match('#vid=([0-9]*)($|&|")#', $contents, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	public function get_images() {
		if (!$this->id) return parent::get_images();
		$contents = file_get_contents("http://cosmos.bcst.yahoo.com/rest/v2/pops;id={$this->id};outputformat=json");
		if (!$contents) return parent::get_images();
		$data = json_decode($contents);
		$data = $data[0];
		return array(
				array(
					'src'=> str_replace('&amp;', '&', $data->images->source[0]->url),
					//'link'=>$this->url,
					'link'=>"http://d.yimg.com/nl/cbe/butterfinger/player.swf?vid={$this->id}",
					'description'=>$data->metadata->description,
					'alt'=> $data->metadata->title,
					'type'=>1
				)
			);
	}
	
	public static function get_url($link) {
		preg_match('#vid=([0-9]*)&lid=([0-9]*)$#', $link, $matches);
		$id = isset($matches[1]) ? $matches[1] : null;
		$lid = isset($matches[2]) ? $matches[2] : null;
		return "http://d.yimg.com/nl/cbe/butterfinger/player.swf?vid=$id";
	}

	
}