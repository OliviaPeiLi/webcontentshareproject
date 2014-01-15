<?php
require_once 'Scraper_default.php';

class Scraper_aol extends Scraper_default {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
	    preg_match('/\-([0-9]*?)(?|#|$)/msi', $url, $matches);
		if (isset($matches[1])) $this->id = $matches[1];
		preg_match('#property="og\:image" content="(.*?)"#', $contents, $matches);
		if (isset($matches[1])) {
			$this->thumb = substr($matches[1], 0, strrpos($matches[1], '.')).'_560_345'.substr($matches[1], strrpos($matches[1], '.'));
		}
	}
	
	public function get_embed() {
		return '<object width="560" height="345" id="FiveminPlayer" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">'
				.'<param name="allowfullscreen" value="true"/>'
				.'<param name="allowScriptAccess" value="always"/>'
				.'<param name="movie" value="http://embed.5min.com/'.$this->id.'/"/>'
				.'<param name="wmode" value="opaque" />'
				.'<embed name="FiveminPlayer" src="http://embed.5min.com/'.$this->id.'/" type="application/x-shockwave-flash" width="560" height="345" allowfullscreen="true" allowScriptAccess="always" wmode="opaque"></embed>'
			.'</object>';
	}
	
}
