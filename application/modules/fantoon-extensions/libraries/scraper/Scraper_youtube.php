<?php
	
/**
 * Facebook scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
if(class_exists('Scraper_youtube') != true) { 
	class Scraper_youtube extends  Scraper_html {
		
		private $id;
		
		public function __construct($contents, $url) {
			parent::__construct($contents, $url);
			//Ray: fix for getting video id from url
			//preg_match('#v=([a-zA-Z0-9_-])($|&)#', $url, $matches);
			preg_match('#v=(.*?)($|&|\?|"|>)#', $url, $matches);
			
			if (!isset($matches[1])) {
				preg_match('#v/(.*?)($|&|\?|")#', $url, $matches);
			}
			
			if (!isset($matches[1])) {
				preg_match('#embed/(.*?)($|&|\?)#', $contents, $matches);
			}
			if (!isset($matches[1])) {
				preg_match('#video_id=(.*?)($|&|\?|")#', $contents, $matches);
			}
			if (!isset($matches[1])) {
				preg_match('#v/(.*?)($|&|\?|")#', $contents, $matches);
			}
			
			$this->id = isset($matches[1]) ? $matches[1] : null;
		}
		
		public function get_images() {
			if (!$this->id) return parent::get_images();
			$xml = @simplexml_load_file("https://gdata.youtube.com/feeds/api/videos/{$this->id}?v=2");
			if (!$xml) return parent::get_images();
			$ns = @$xml->getNamespaces(true);
		    $media = $xml->children($ns['media']);
			return array(
					array(
						'src'=>"http://i.ytimg.com/vi/{$this->id}/0.jpg",
						'link'=>$this->url,
						'description'=> (string)$media->group->description,
						'alt'=>(string)$xml->title,
						'type'=>1
					)
				);
		}
		
		public static function get_url($link) {
			preg_match('#v=(.*?)($|&)#', $link, $matches);
			$id = isset($matches[1]) ? $matches[1] : null;
			return "http://www.youtube.com/v/{$id}?f=videos&amp;app=youtube_gdata";
		}
		
		public function get_embed() {
			return '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$this->id.'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
		}
		
	}
}