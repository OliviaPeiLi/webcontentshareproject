<?php
	
/**
 * Facebook scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
class Scraper_vimeo extends  Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url) {
		parent::__construct($contents, $url);
		preg_match('#/([0-9]*)($|//|\?)#', $url, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : null;
		if (!$this->id) {
			preg_match('#player([0-9]*)_#msi', $contents, $matches);
			$this->id = isset($matches[1]) ? $matches[1] : null;
		}
		if (!$this->id) {
			preg_match('#clip_id=([0-9]*)&#msi', $contents, $matches);
			$this->id = isset($matches[1]) ? $matches[1] : null;
		}
	}
	public function get_images() {
		if (!$this->id) return parent::get_images();
		$data = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$this->id}.json"));
		
		return array(
				array(
					'src'=> $data[0]->thumbnail_large,
					//'link'=>'http://vimeo.com/'.$this->id,
					'link'=>"http://player.vimeo.com/video/".$this->id,
					'description'=> $data[0]->description,
					'alt'=> $data[0]->title,
					'type'=>1
				)
			);
	}
	
	public function get_embed() {
		return '<iframe src="http://player.vimeo.com/video/'.$this->id.'?badge=0" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	}
	
	public static function getLinkFromImage($img_url) {
		list($id, $size) = explode('_',$img_url,2);
		return 'http://vimeo.com/'.$id;
	}
	
	public static function get_url($link) {
		preg_match('#/([0-9]*)$#', $link, $matches);
		$id = isset($matches[1]) ? $matches[1] : null;
		return "http://player.vimeo.com/video/".$id;
	}
	
}