<?php

class Ban_site_model extends MY_Model {

	public function is_banned($url) {
		$parsed = parse_url($url);
		if (!isset($parsed['host'])) return false;
		$is_banned = $this->count_by(array('url'=>$parsed['host']));
		if (!$is_banned && strpos($parsed['host'], 'www.') !== false) {
			$is_banned = $this->count_by(array('url'=>str_replace('www.', '', $parsed['host'])));
		}
		return $is_banned;
	}

}
?>