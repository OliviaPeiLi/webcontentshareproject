<?php
	
/**
 * Facebook scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
class Scraper_metacafe extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		preg_match('#watch/(.*?)(/|$)#', $url, $matches);
		if (!isset($matches[1])) {
			preg_match('#watch/(.*?)(/|$)#', $contents, $matches);
		}
		if (!isset($matches[1])) {
			preg_match('#fplayer/(.*?)(/|$)#', $contents, $matches);
		}
		if (!isset($matches[1])) {
			preg_match('#indexedItemID=(.*?)&#', $contents, $matches);
		}
		if (!isset($matches[1])) {
			preg_match('#itemID=(.*?)&#', $contents, $matches);
		}
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		$xml = @simplexml_load_file("http://www.metacafe.com/api/item/{$this->id}");
		if (!$xml) return parent::get_images();
		$item = $xml->channel->item[0];
	    $ns = $xml->getNamespaces(true);
	    $media = $item->children($ns['media']);
		$content = $media->content->attributes();
		$thumbnail = $media->thumbnail->attributes();
		$description = $media->description;
	
		return array(
				array(
					'src'=>'http://s4.mcstatic.com/thumb/'.$this->id.'.jpg',
					'link'=>"http://www.metacafe.com/fplayer/".$this->id."/50_50_clip_thats_your_hook.swf",
					'description'=> (string) $media->description,
					'alt'=>(string)$item->title,
					'type'=>1
				)
			);
	}

	public static function get_url($link)
	{
		preg_match('#watch/(.*?)(/|$)#', $link, $matches);
		$id = isset($matches[1]) ? $matches[1] : null;
		return "http://www.metacafe.com/fplayer/".$id."/50_50_clip_thats_your_hook.swf";
	}
	
}