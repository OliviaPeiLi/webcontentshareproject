<?php
	
/**
 * Facebook scraper class.
 * 
 * @desc Uses DOMHtml to load and parse the html doc.
 * @author Radil Radenkov
 *
 */
class Scraper_facebook extends Scraper_html {
	
	private $contents = null;
	
	public function __construct($contents) {
		$this->contents = preg_replace('/\\\/msi',"",$contents);
	}
	
	public function get_images() {
		preg_match_all('#u003cimg.*?src="(.*?)"#msi',$this->contents, $matches);
		
		preg_match('#<meta.*?name="description".*?content="(.*?)"#msi', $this->contents, $desc_matches);
		$description = isset($desc_matches[1]) ? $desc_matches[1] : '';
		
		$ret = array();
		if (isset($matches[1])) foreach ($matches[1] as $match) {
			$ret[] = array(
				'src' => $match,
				'alt' => '',
				'description' => $description,
			);
		}
		preg_match_all('#background-image: url\((.*?)\)#msi',$this->contents, $matches);
		if (isset($matches[1])) foreach ($matches[1] as $match) {
			$ret[] = array(
				'src' => $match,
				'alt' => '',
				'description' => $description,
			);
		}
		return $ret;
	}
	
}