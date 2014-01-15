<?php
	
class Scraper_veoh extends Scraper_html {
	
	private $id, $image;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);

		// get video id
		preg_match('#watch/(.*)[/\?$]#', $url, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : null;

		// get image
		preg_match('#name="og\:image" content="(.*?)"#', $contents, $matches);
		$this->image = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images() {
		if (!$this->id) return parent::get_images();
		
		if (!$this->image) {
			$xml = @simplexml_load_file("http://www.veoh.com/rest/video/{$this->id}/details");
			if (!$xml) return parent::get_images();
			$this->image = $xml->video['fullHighResImagePath'];
		}
		return array(
				array(				
					'src'=> $this->image,
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}

	public function get_embed() {
		return
			'<object width="410" height="341" id="veohFlashPlayer" name="veohFlashPlayer">'
				.'<param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId='.$this->id.'&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param>'
				.'<param name="allowFullScreen" value="true"></param>'
				.'<param name="allowscriptaccess" value="always"></param>'
				.'<embed type="application/x-shockwave-flash"'
					.' src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId='.$this->id.'&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"'
					.' allowscriptaccess="always" allowfullscreen="true"'
					.' width="410" height="341" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed">'
				.'</embed>'
			.'</object>';
	}
	
}
