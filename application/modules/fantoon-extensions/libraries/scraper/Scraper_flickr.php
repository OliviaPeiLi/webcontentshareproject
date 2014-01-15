<?php
	
/**
 * Flickr scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
class Scraper_flickr extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		preg_match('#shadowplay/(.*?)(/|$)#', $url, $matches);
		if (!isset($matches[1])) {
			preg_match('#stewart_swf(.*?)(_|"|&|$)#', $url, $matches);
		}
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		$xml = @simplexml_load_file("http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=bffa74ec32b6a1de0b62b2270ec390e8&photo_id=".$this->id);
		if (!$xml) return parent::get_images();
		return array(
				array(
					'src'=>"http://farm{$xml->photo['farm']}.static.flickr.com/{$xml->photo['server']}/{$this->id}_{$xml->photo['secret']}.jpg",
					'link'=>"http://www.flickr.com/photos/shadowplay/".$this->id,
					'description'=> (string) $xml->photo->description,
					'alt'=>(string)$xml->photo->title,
					'type'=>1
				)
			);
	}
	
	public function get_embed() {
		$xml = @simplexml_load_file("http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=bffa74ec32b6a1de0b62b2270ec390e8&photo_id=".$this->id);
		
		return '<object type="application/x-shockwave-flash" width="640" height="480" data="http://www.flickr.com/apps/video/stewart.swf?v=109786" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">'
				.'<param name="flashvars" value="intl_lang=en-us&photo_secret='.$xml->photo['secret'].'&photo_id='.$this->id.'"></param>'
				.'<param name="movie" value="http://www.flickr.com/apps/video/stewart.swf?v=109786"></param>'
				.'<param name="bgcolor" value="#000000"></param>'
				.'<param name="allowFullScreen" value="true"></param>'
				.'<embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/video/stewart.swf?v=109786" bgcolor="#000000" allowfullscreen="true" '
					.'flashvars="intl_lang=en-us&photo_secret='.$xml->photo['secret'].'&photo_id='.$this->id.'" height="480" width="640">'
				.'</embed></object>
		';
	}

	
}