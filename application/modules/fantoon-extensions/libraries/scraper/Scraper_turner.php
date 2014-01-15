<?php
	
class Scraper_turner extends  Scraper_html {
	
	private $id;
	private $path;
	
	public function __construct($contents, $url) {
		parent::__construct($contents, $url);
		
		preg_match('#data="(.*?)"#msi', $contents, $matches);
		$this->path = isset($matches[1]) ? $matches[1] : null;
		
		preg_match('#contentId=(.*?)&#msi', $contents, $matches);
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_embed() {
		return '<object width="416" height="374" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="ep">
		 	<param name="allowfullscreen" value="true" />
		 	<param name="allowscriptaccess" value="always" />
		 	<param name="wmode" value="transparent" />
		 	<param name="movie" value="'.$this->path.'?context=embed_edition&videoId='.$this->id.'" />
		 	<param name="bgcolor" value="#000000" />
		 	<embed src="'.$this->path.'?context=embed_edition&videoId='.$this->id.'" type="application/x-shockwave-flash" bgcolor="#000000" allowfullscreen="true" allowscriptaccess="always" width="416" wmode="transparent" height="374"></embed>
		 </object>';
	}
	
	public function get_images() {
		return array(
				array(
					'src'=> 'http://turner.com/sites/default/files/images/logoblack_0.jpg',
					'link'=>"",
					'description'=> "",
					'alt'=> "",
					'type'=>1
				)
			);
	}
	
}