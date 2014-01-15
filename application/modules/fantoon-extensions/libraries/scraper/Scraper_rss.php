<?php
	
/**
 * HTML scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
 
if(class_exists('Scraper_rss') != true) {
	
	class Scraper_rss {
		
		private $doc;
		public $url;
		private $contents;
		
		public function __construct($contents, $url='', $encoding = 'utf8') {
			$this->contents = $contents;
			$this->url = $url ? $url : '';
	    	$this->doc = simplexml_load_string($contents);
		}
		
		public function get_images() {
			$ret = array();
			if ($this->doc->channel) {
				// Assign the channel data
				//$this->channel_data['title'] = $xml->channel->title;
				//$this->channel_data['description'] = $xml->channel->description;
	
				// Build the item array
				foreach ($this->doc->channel->item as $item) {
					$dc = $item->children('http://purl.org/dc/elements/1.1/');
					$media = $item->children('http://search.yahoo.com/mrss/');
					//$data['author'] = (string)$dc->creator;
					//$data['description'] = (string)$item->description;
					//$data['pubDate'] = (string)$item->pubDate;
					
					$link = (string)$item->link;
					$headers = get_headers_curl($link);
					if (isset($headers['location'])) $link = $headers['location'];
					 
					$ret[]  = array(
						'src' => (string)$media->thumbnail['url'], //thumb
						'link' => $link,
						'description' => (string)$item->description,
						'alt' => (string)$item->title,
						'type' => 0
					);
				}
			} else {
				// Assign the channel data
				//$this->channel_data['title'] = $xml->title;
				//$this->channel_data['description'] = $xml->subtitle;
	
				// Build the item array
				foreach ($this->doc->entry as $item) {
					//$data['id'] = (string)$item->id;
					//$data['pubDate'] = (string)$item->published;
					//$dc = $item->children('http://purl.org/dc/elements/1.1/');
					//$data['author'] = (string)$dc->creator;
					
					$link = (string)$item->link['href'];
					$headers = get_headers_curl($link);
					if (isset($headers['location'])) $link = $headers['location'];
					
					$ret[]  = array(
						'src' => '', //thumb
						'link' => $link,
						'description' => (string)$item->content,
						'alt' => (string)$item->title,
						'type' => 0
					);
				}
			}
			
			return $ret;
		}
	}
	
	function get_headers_curl($url) { 
	    $ch = curl_init(); 
	
	    curl_setopt($ch, CURLOPT_URL,            $url); 
	    curl_setopt($ch, CURLOPT_HEADER,         true); 
	    curl_setopt($ch, CURLOPT_NOBODY,         true); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	    curl_setopt($ch, CURLOPT_TIMEOUT,        15); 
	
	    $headers = curl_exec($ch); 
	    $headers = explode("\n", $headers); 
	    $ret = array($headers[0]);
	    
	    foreach ($headers as $header) {
	    	if (strpos($header, ':') === false) continue;
	    	list($key, $val) = explode(':', $header, 2);
	    	$ret[trim(strtolower($key))] = trim($val);
	    }
	    return $ret; 
	}

}