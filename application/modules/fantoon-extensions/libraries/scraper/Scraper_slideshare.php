<?php
	
class Scraper_slideshare extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		
		/*
		 	<embed width="384" height="311" allowfullscreen="true" allowscriptaccess="always" wmode="opaque" quality="high" bgcolor="#3B3835" name="player" id="player" style="border-right-width: 1px; border-right-style: solid; border-right-color: rgb(59, 56, 53); margin: 0px 0px 0.25em; " src="http://static.slidesharecdn.com/swf/ssplayer2.swf
		 		?hostedIn=slideshare&amp;
		 		totalSlides=80&amp;
		 		startSlide=1&amp;
		 		presentationId=12994622&amp;
		 		doc=trustmeimadesigner-120519130902-phpapp01&amp;
		 		userName=jasonspeaking&amp;
		 		stitle=trust-me-im-a-designer-9-principles-for-creative-credibility&amp;
		 		isAudio=0&amp;
		 		rel=1
		 	" type="application/x-shockwave-flash">
		 	
		 	http://images.slidesharecdn.com/trustmeimadesigner-120519130902-phpapp01/95/slide-1-728.jpg
		 	http://images.slidesharecdn.com/trustmeimadesigner-120519130902-phpapp01/95/slide-2-728.jpg
		 */
		
		
		preg_match('#doc=(.*?)&#', $contents, $matches);
		
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		return array(
				array(				
					'src'=> "http://images.slidesharecdn.com/{$this->id}/95/slide-1-728.jpg",
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}
	
}