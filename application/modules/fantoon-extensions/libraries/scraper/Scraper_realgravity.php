<?php
	/*
	 * Fetch the config data for realgravity.com player
	 */
class Scraper_realgravity extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		preg_match('#config=(.*?)($|")#msi', $contents, $matches);
				
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		$xml = @simplexml_load_file("{$this->id}");
		if (!$xml) return parent::get_images();
		$playlist = (String) $xml->playlistfile;
		$playlist = @simplexml_load_file($playlist);
		if (!$playlist) return parent::get_images();
		
	    $ns = $playlist->getNamespaces(true);
	    $item = $playlist->channel->item[0];
	    $jwplayer = $item->children($ns['jwplayer']);
	
		return array(
				array(
					'src'=>(string)$jwplayer->image,
					'link'=>(string)$xml->{"sharing.link"},
					'description'=> (string) $xml->{"rg_vidtitle.videotitleheader"},
					'alt'=>(string)$xml->{"rg_vidtitle.videotitleheader"},
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