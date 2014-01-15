<?php
/**
 * This file contains logic for internal site functions like preview iframe
 */
class Internal extends MX_Controller {
	
	public function html_preview($newsfeed) {
		$upd_conf = $this->config->item('uploads');
		$this->load->library("s3");
		$this->load->library("Scraper");
		$contents = file_get_contents(str_replace('https', 'http', Url_helper::s3_url()).'uploads/screenshots/drop-'.$newsfeed->newsfeed_id.'/index.php');
		$link = $newsfeed->link_url;
		$contents = Scraper::clean_html($contents, $link);
		
		if (strpos($contents, '<base href=') === false) {
			preg_match( '/<head(?: .*)?>/Umi', $contents, $matches, PREG_OFFSET_CAPTURE );
			$pos = $matches[0][1] + mb_strlen( $matches[0][0] );
			$contents = mb_substr($contents, 0, $pos). '<base href="'.$link.'"/>' .mb_substr($contents, $pos);
		}

		echo $contents;
	}

	public function snapshot_preview($id) {
		$this->output->enable_profiler(false);
		if (function_exists('newrelic_ignore_transaction')) {
			newrelic_ignore_transaction(TRUE); 
		}
		$allowed = array(
			'fantoon.com', 'fandrop.com', 'fantoon.local', 'ft', 'fantoon.loc'
		);
		$allowed_ips = array(
			'5.9.50.78',	  //Scripts server IP 
			'173.255.253.241',//Dev server IP 
			'192.168.0.3',//App1 IP
			//'213.240.193.166' //Radils IP
		);
		$allow = false;
		foreach ($allowed as $server) {
			if (strpos(@$_SERVER['HTTP_REFERER'], $server) !== false || strpos(@$_SERVER['http_referer'], $server) !== false) {
				$allow = true;
			}
		}
		if ($this->session->userdata('id')) $allow = true;
		foreach ($allowed_ips as $ip) if (@$_SERVER['HTTP_X_FORWARDED_FOR'] == $ip) $allow = true;
		if (@$_SERVER['REMOTE_ADDR'] == '127.0.0.1') $allow = true;
		if (@$_COOKIE['preview'] == $id) $allow = true;
		if (!$allow) {
			echo "<html><head><title>Error</title></head><body><h1>This page is visible only in fandrop.com</h1></body></html>";
			die();
		}
		$this->load->library('scraper');
		$item = $this->newsfeed_model->get($id);
		if ($item->link_type == 'content') {
			if ($this->is_mod_enabled('live_drops')) {
				$contents = $this->scraper->get_html($item->link_url);
				if (is_array($contents) && isset($contents['status']) && $contents['status'] == false) {
					die(print_r($contents));
				}
				$contents = Scraper::clean_html($contents, $item->link_url);
				echo $contents;
				return ;
			} else {
				//Will enable when clean_html script is ready and tested
				$url = str_replace('https', 'http', Url_helper::s3_url()).'uploads/screenshots/drop-'.$id.'/index_updated.php';
				if (@fopen($url, 'r')) {
					Url_helper::redirect($url);
					return ;
				} else {
					return $this->html_preview($item);
				}
			}
		}
		$content = $item->link_type == 'html' ? $item->activity->content : $item->activity->media;
		
		if($item->link_type == 'embed') {
			if (strpos($item->activity->media, 'youtube.com/') !== false) {
				preg_match('#src="([^\"]+)"#is', $content, $url);
				if($url[1] && strpos($url[1], 'opaque') === false) {
					$src = (strpos($url[1], '?') !== false) ? $url[1] . '&wmode=opaque' : $url[1] . '?wmode=opaque';
					//if ($this->input->get('autoplay') == '1') { //RR - we allways need autoplay
						$src .= '&autoplay=1';
					//}
					$content = str_replace($url[1], $src, $content);
				}
			}
		}

		$this->load->view('snapshot', array('item' => $item, 'content'=> $content));
	}
	
}