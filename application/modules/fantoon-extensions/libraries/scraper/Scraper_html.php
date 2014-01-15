<?php
	
/**
 * HTML scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
 
if(class_exists('Scraper_html') != true) {
	
	class Scraper_html {
		
		private $doc;
		public $url;
		private $contents;
		
		public function __construct($contents, $url='', $encoding = 'utf8') {
			$this->contents = $contents;
			$this->url = $url ? $url : '';
			$doc = new DOMDocument('1.0',$encoding);
	        @$doc->encoding = $encoding;
	    	@$doc->loadHTML($contents);
	    	$this->doc = $doc;
		}
		
		public function get_images() {
			if (is_array($this->contents)) { //may be an error array
				return $this->contents;
			}
			$ret = array();
			$images = $this->doc->getElementsByTagName('img');
			//when user tries to clip a video for which we have driver the contents will be array
			//of the video site Api - we need to pull the html
			preg_match('#<meta.*?name="description".*?content="(.*?)"#msi', $this->contents, $matches);
			if (!isset($matches[1])) {
				preg_match('#<meta[ ]*content="(.*?)".*name="description"#msi', $this->contents, $matches);
			}
			if (!isset($matches[1])) {
				preg_match('#<meta.*?property="og\:description".*?content="(.*?)"#msi', $this->contents, $matches);
			}
			if (!isset($matches[1]) || !$matches[1]) {
				preg_match_all('#<p[^>]*>(.*?)</p>#msi', $this->contents, $matches);
				$max_content = '';
				if (isset($matches[1])) foreach ($matches[1] as $match) {
					//if (strlen($match) > strlen($max_content)) $max_content = $match;
					if (mb_strlen(strip_tags($match)) > 200) {
						$max_content = strip_tags($match); break;
					}
				}
				$matches[1] = $max_content;
			}
			$description = strip_tags(isset($matches[1]) ? $matches[1] : '');
			
			preg_match('#<title>(.*?)<#msi', $this->contents, $matches);
			$title = isset($matches[1]) ? $matches[1] : '';
			foreach ($images as $image) {
				if ( ! $image->attributes) continue;
				if ( ! $image->attributes->getNamedItem('src')) continue;
				$ret[] = array(
					'src' => Url_helper::make_full($this->url, $image->attributes->getNamedItem('src')->nodeValue),
					'alt' => $title,
					'description' => $description,
					'link' => $this->url,
					'type' => 0
				);
				if ($image->attributes->getNamedItem('data-src')) {
					$ret[] = array(
						'src' => Url_helper::make_full($this->url, $image->attributes->getNamedItem('data-src')->nodeValue),
						'alt' => $title,
						'description' => $description,
						'link' => $this->url,
						'type' => 0
					);
				}
			}
			preg_match_all('#background\:[ ]*url\((.*?)\)#msi', $this->contents, $matches);
			if (isset($matches[1])) foreach ($matches[1] as $match) {
				$ret[] = array(
					'src' => trim($match,'"\''),
					'alt' => $title,
					'description' => $description,
					'link' => $this->url,
					'type' => 0
				);
			}
			$ret = array_merge($ret, $this->get_videos());
			if (!$ret) {
				$ret[] = array(
					'src' => '',
					'alt' => $title,
					'description'=> $description,
					'link'=> $this->url,
					'type' => 0
				);
			}
			return $ret;
		}
		
		public function get_videos() {
			$ret = array();
			foreach ($this->doc->getElementsByTagName('iframe') as $item) {
				if (!$item->attributes || !$item->attributes->getNamedItem('src')) continue;
				$src = $item->attributes->getNamedItem('src')->nodeValue;
				if (strpos($src,'player.vimeo.com') !== false) {
					require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_vimeo.php');
					$parser = new Scraper_vimeo('', $src);
					$ret = array_merge($ret,$parser->get_images());
				} elseif (strpos($src,'youtube.com')) {
					require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_youtube.php');
					$parser = new Scraper_youtube('', $src);
					$ret = array_merge($ret,$parser->get_images());
				}
			}
			
			foreach ($this->doc->getElementsByTagName('embed') as $item) {
				$src = $item->attributes->getNamedItem('src')->nodeValue;
				if (strpos($src,'metacafe.com') !== false) {
					require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_metacafe.php');
					$parser = new Scraper_metacafe('', $src);
					$ret = array_merge($ret,$parser->get_images());
				} elseif (strpos($src,'d.yimg.com')) {
					$vars = $item->attributes->getNamedItem('flashvars')->nodeValue;
					require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_yahoo_vids.php');
					$parser = new Scraper_yahoo_vids('', $src.'?'.$vars);
					$ret = array_merge($ret,$parser->get_images());
				}
			}
			return $ret;
		}
		
		public function get_embed() {
			return false;
		}
		public function get_thumb() {
			return false;
		}
		
		public function get_css_files() {
			preg_match_all('#<link type="text/css"[a-z=" ]*href="(.*?)"#msi', $this->contents, $matches);
			return isset($matches[1]) ? $matches[1] : array();
		}
		
		public function get_css_images($css_file) {
			preg_match_all('#url\((.*?)\)#', $css_file, $matches);
			$ret = array();
			foreach ($matches[1] as $match) {
				$ret[] = trim(trim($match,'"'),"'");
			}
			return $ret;
		}
	}

}