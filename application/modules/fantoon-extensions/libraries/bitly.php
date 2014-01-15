<?php
class bitly {
	
	private $access_token = '714be966cdd5625ca373bbcb46fe27a3433aa0b8';
	private $client_id = 'dc33c2a608c859e868bd8b1f50c00d77f4566aea';
	private $client_secret = '3708c5de76db670886175b67a9a6815c39a08679';
	private $server = 'https://api-ssl.bitly.com';
	
	public function __construct() {
		if (ENVIRONMENT != 'production') {
			$this->client_id = 'fb389fbfcb4c0d6b023c09f141ec80d93fa1dbf8';
			$this->client_secret = '57b010dbec7aa937adbd36984e3126b091421748';
		}
	}
	
	public function shorten($url) {
		$res = file_get_contents($this->server.'/v3/shorten?access_token='.$this->access_token.'&longUrl='.Url_helper::base_url($url));
		return json_decode($res);
	}
	
}