<?php
	
class Scraper_yahoo_charts extends Scraper_html {
	
	private $id;
	
	public function __construct($contents, $url)
	{
		parent::__construct($contents, $url);
		/*
		 * sigDevEnabled=true&amp;
		 * changeSymbolEnabled=false&amp;
		 * state=symbol=%5EDJI;range=1d;compare=;indicator=volume;charttype=area;crosshair=on;ohlcvalues=0;logscale=off;source=undefined;	
		 */
		preg_match('#symbol=(.*?);#', $url, $matches);
		
		$this->id = isset($matches[1]) ? $matches[1] : null;
	}
	
	public function get_images()
	{
		if (!$this->id) return parent::get_images();
		return array(
				array(				
					'src'=>"http://chart.finance.yahoo.com/z?s={$this->id}&t=1y&q=&l=&z=l&a=v&p=s&lang=en-US&region=US",
					'link'=>"",
					'description'=> "",
					'alt'=>"",
					'type'=>1
				)
			);
	}
	/*
	public function get_embed() {
		return str_replace(array('width="526"','height="374"'), array('width="710"','height="500"'), @file_get_contents("http://www.ted.com/talks/embed/id/".$this->id));
	}
	*/
	
}