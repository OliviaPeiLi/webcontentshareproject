<?php
class Url_helper extends Helper {
	
	/**
	 * Makes full url of domain and relative link
	 * 
	 * @param string $domain
	 * @param string $src
	 * @example make_full('example.com/path1/index.php','../css/image.png') -> example.com/css/image.png
	 */
	public static function make_full($url, $src, $old=false) {
		$src = trim($src); $url = trim($url);
		if (strpos($url, '#') !== false) {
			list($url,) = explode('#', $url, 2);
		}
		if (!$src) {
			return $url;
		} elseif (strpos($src, 'javascript:') === 0) {
			return 'javascript:;';
		} elseif (strpos($src,'http') === 0) {
			return $src;
		} elseif (substr($src,0,2) == '//') {
			return 'http:'.$src;
		} elseif ($src[0] == '/') {
			$domain = str_replace(array('http://','https://'),'',$url);
			$domain = strpos($domain,'/') !== false ? substr($domain,0, strpos($domain,'/')) : $domain;
			return 'http://'.$domain.$src;
		} elseif (substr($src,0,3) == '../') {
			$domain = str_replace(array('http://','https://'),'',$url);
			$domain = str_replace(array('http://','https://'),'',$domain);
			if (strpos($domain,'/') !== false) {
				list($domain, $path) = explode('/',$domain, 2);
				$path = trim($path, '/');
			} else {
				$path = '';
			}
			
			while (substr($src,0,3) == '../') {
				$path = substr($path, 0, strrpos($path, '/'));
				$src = substr($src, 3);
			}
			return 'http://'.$domain.'/'.$path.'/'.$src;
		} else {
			$domain = str_replace(array('http://','https://'),'',$url);
			
			if ($old) {
				$domain = strpos($domain, '/') !== false ? substr($domain,0,strpos($domain,'/')) : $domain;
			} else {
				$domain = strpos($domain, '/') !== false ? substr($domain,0,strrpos($domain,'/')) : $domain;
			}
			
			return 'http://'.$domain.'/'.$src;
		}
	}

	public static function valid_url($link) {
		$valid_url = parse_url($link);
		if (!isset($valid_url['host'])) $link = 'http://www.'.(str_replace('www.', '', $link));
		$valid_url = parse_url($link);
	    $link = (isset($valid_url['scheme']) ? $valid_url['scheme'] : 'http' ).'://'.$valid_url['host']. (@$valid_url['path'] ? $valid_url['path'] : '/').(isset($valid_url['query']) ? '?'.$valid_url['query'] : '');
	   	if (strpos($link, '#') !== false) list($link,) = explode('#', $link, 2);
	   	return $link;
	}

    public static function url_title($str, $limit=null) {
		if ($limit) {
			$str = Text_Helper::character_limiter($str, $limit, '');
		}
		$replace	= '-';

		$trans = array(
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\s+'					=> $replace,
						'[^a-z0-9\-_]'			=> '',
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					);

		$str = strip_tags($str);

		foreach ($trans as $key => $val) {
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		return trim(stripslashes(strtolower($str)));
	}
}