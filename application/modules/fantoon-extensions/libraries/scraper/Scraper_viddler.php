<?php
	
class Scraper_viddler extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		/*
		 * <object data="//www.viddler.com/simple/fd616780/" height="318" id="viddlerOuter-fd616780" type="application/x-shockwave-flash" width="530">	
		 * 	<param name="movie" value="//www.viddler.com/simple/fd616780/">
		 * 	<param name="allowScriptAccess" value="always"><param name="allowNetworking" value="all">
		 * 	<param name="allowFullScreen" value="true"><param name="flashVars" value="
		 * 			f=1&amp;openURL=78636048&amp;autoplay=f&amp;loop=0&amp;nologo=0&amp;hd=0">
		 * 	<object id="viddlerInner-fd616780"><video controls="controls" height="298" id="viddlerVideo-fd616780" poster="//www.viddler.com/thumbnail/fd616780/" src="//www.viddler.com/file/fd616780/html5mobile?openURL=78636048" type="video/mp4" width="530" x-webkit-airplay="allow"></video></object></object>
		 */
		preg_match('#simple/(.*?)(/|$|&)#', $url, $matches);
		if (!isset($matches[1])) preg_match('#simple/(.*?)(/|$|&)#', $contents, $matches);
		
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		return array(
				array(				
					'src'=>"http://cdn-thumbs.viddler.com/thumbnail_2_{$this->id}_v2.jpg",
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}
	
}