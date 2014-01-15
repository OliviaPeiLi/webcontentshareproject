<?php
/**
 * Driver for smsn class (http://msn.foxsports.com)
 */	
class Scraper_smsn extends  Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url) {
		parent::__construct($contents, $url);
		preg_match('#widgetId=(.*?)_player#msi', $contents, $matches);
		if (!isset($matches[1])) {
			preg_match('#player\.v=(.*?)&#', $contents, $matches);
		}
		if (!isset($matches[1])) {
			preg_match('#embed/(.*?)/#', $contents, $matches);
		}
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images() {
		if (!$this->id) return parent::get_images();
		/*
		$xml = simplexml_load_file("http://hub.video.msn.com/services/videodata/?ids=".$this->id);
		if (!is_object($xml->video)) return parent::get_images();
		$src ='preg_replace('#w=.*?&h=.*?&#i', 'w=448&h=252&', $xml->video->thumb),
	   */
		return array(
				array(
					'src'=> "http://img1.catalog.video.msn.com/Image.aspx?uuid={$this->id}&w=448&h=252&so=4",
					//'link'=>'http://vimeo.com/'.$this->id,
					'link'=>'', //$xml->video->url,
					'description'=> '',// $xml->video->description,
					'alt'=> '', //$xml->video->title,
					'type'=>1
				)
			);
	}
	
	public function get_embed() {
		return '<IFRAME width="448" height="252" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" src="http://hub.video.msn.com/embed/'.$this->id.'/?vars=bGlua292ZXJyaWRlMj1odHRwJTNBJTJGJTJGbXNuLmZveHNwb3J0cy5jb20lMkZ2aWRlbyUzRnZpZGVvaWQlM0QlN0IwJTdEJmJyYW5kPWZveHNwb3J0cyZjb25maWdOYW1lPXN5bmRpY2F0aW9ucGxheWVyJnN5bmRpY2F0aW9uPXRhZyZta3Q9ZW4tdXMmbGlua2JhY2s9aHR0cCUzQSUyRiUyRnd3dy5iaW5nLmNvbSUyRnZpZGVvcyZjb25maWdDc2lkPU1TTlZpZGVvJmZyPXNoYXJlZW1iZWQtc3luZGljYXRpb24%3D"></IFRAME>';
	}
	
}