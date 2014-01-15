<?php
	
/**
 * General scraper class.
 * 
 * @desc Uses php cURL library to access remote sites and downloads data.
 * @author Radil Radenkov
 *
 */
class Scraper {
	
	private $_contents = null;
	private $_headers = null;
	private $_status = null;
	private $config = array(
		'errors'=>array(
			'failed_request'=>'Could not pull data from the specified url.',
			'bad_headers'=>'Wrong headers returned.',
			'no_data'=>'No data returned from the specified URL',
			'not_recognized_type'=>'Returned type not recognized.',
			'no_images'=>'No images found.',
		)
	);
	protected $_driver = null;
	
	public function driver($url='', $contents='', $encoding='utf8', $class=null) {
		if (! $this->_driver) {
			if (!$class) {
				$class = $this->get_class($url);
				if (in_array($class, array('html','default')) && $contents) $class = $this->get_class($contents);
			}
			include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_html.php');
			if ($class != 'html') {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_'.$class.'.php');
			}
			$class = 'Scraper_'.$class;
			if (!$contents) {
				if (! $this->get_contents($url) ) {
					return false;
				}
				$contents = $this->_contents;
			}
			if (in_array($this->_headers['content_type'], array('image/jpeg','image/gif','image/png'))) {
				$class = 'Scraper_image';
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/'.$class.'.php');
			}
			$this->_driver = new $class($contents, $url, $encoding);
		}
		return $this->_driver;
	}
	
  public function get_class($data) {
  		$xml = @simplexml_load_string($data);
  		if ($xml && $xml->channel) {
  			return 'rss';
  		} elseif (strpos($data,'media.mtvnservices.com/') !== false) {
			return 'mtv';
		} elseif (strpos($data,'.worldstarhiphop.com') !== false) {
			return 'worldstarhiphop';
		} elseif (strpos($data,'twitvid') !== false) {
			return 'twitvid';
		} elseif (strpos($data,'vbox7.com') !== false) {
			return 'vbox7';
		} elseif (strpos($data,'.ted.com') !== false) {
			return 'ted';
		} elseif (strpos($data,'.nbc.com') !== false) {
			return 'nbc';
		} elseif (strpos($data,'www.kyte.tv/f/') !== false) {
			return 'espn';
		} elseif (strpos($data,'facebook.com') !== false && strpos($data, 'facebook.com/sharer.php') === false) {
			return 'facebook';
		} elseif (strpos($data,'youtube.com') !== false) {
			return 'youtube';
		} elseif (strpos($data,'metacafe.com') !== false) {
			return 'metacafe';
		} elseif (strpos($data,'vimeo.com/') !== false || strpos($data, 'vimeocdn.com/') !== false) {
			return 'vimeo';
		} elseif (strpos($data,'video.yahoo.com') !== false || strpos($data, 'd.yimg.com/') !== false) {
			return 'yahoo_vids';
		} elseif (strpos($data,'google.com/finance/') !== false) {
			return 'google_charts';
		} elseif (strpos($data,'turner.com/') !== false) {
			return 'turner';
		} elseif (strpos($data,'realgravity.com') !== false) {
			return 'realgravity';
		} elseif (strpos($data,'hub.video.msn.com/embed/') !== false) {
			return 'smsn';
		} elseif (strpos($data,'flickr.com/') !== false && strpos($data, 'show.swf') === false) {
			return 'flickr';
		} elseif (strpos($data,'ted.com/') !== false) {
			return 'ted';
		} elseif (strpos($data,'viddler.com/simple/') !== false) {
			return 'viddler';
		} elseif (strpos($data,'slidesharecdn.com/swf/ssplayer2') !== false) {
			return 'slideshare';
		} elseif (strpos($data,'.ignimgs.com/src/core/swf/IGNPlayer.swf') !== false
				|| strpos($data,'media.ign.com/ev/prod/embed.swf') !== false
		) {
			return 'ign';
		} elseif (strpos($data,'.veoh.com/') !== false) {
			return 'veoh';
		} elseif (strpos($data,'.aol.com/video') !== false) {
			return 'aol';
		} elseif (strpos($data,'vine.co/v/') !== false) {
			return 'vine';
		} elseif (strpos($data,'s.wsj.net/media/swf/') !== false
		 		|| strpos($data,'dailymotion.com/embed/') !== false
				|| strpos($data,'c.brightcove.com/services/viewer') !== false
				|| strpos($data,'liveleak.com/') !== false
				|| strpos($data,'dmdentertainment.com/') !== false
				|| strpos($data,'washingtonpost.com') !== false
				|| strpos($data,'forbes.com/video') !== false
				|| strpos($data,'http://cfiles.5min.com/FlexPlayers/') !== false //http://www.huffingtonpost.com/
				|| strpos($data,'aniboom.com/') !== false
				|| strpos($data,'archive.org/') !== false
				|| strpos($data,'blinkx.com/') !== false
				|| strpos($data,'blip.tv/') !== false
				|| strpos($data,'blogtv.com/') !== false
				|| strpos($data,'.break.com/') !== false
				|| strpos($data,'.buzznet.com/') !== false
				|| strpos($data,'.cbsnews.com/') !== false
				|| strpos($data,'.collegehumor.com/') !== false
				|| strpos($data,'ebaumsworld.com/') !== false
				|| strpos($data,'engagemedia.org/') !== false
				|| strpos($data,'expotv.com/') !== false
				|| strpos($data,'.fotki.com/') !== false
				|| strpos($data,'.ordienetworks.com') !== false
				|| strpos($data,'.godtube') !== false
				|| strpos($data,'.hulu.com') !== false
				|| strpos($data,'.dmdentertainment.com') !== false
				|| strpos($data,'mayomo.com') !== false
				|| strpos($data,'.myspace.com') !== false
				|| strpos($data,'nbcolympics.com') !== false
				|| strpos($data,'.nytimes.com') !== false
				|| strpos($data,'.openfilm.com') !== false
				|| strpos($data,'.photobucket.com') !== false
				|| strpos($data,'.ignimgs.com') !== false
				|| strpos($data,'player.ooyala.com') !== false
				|| strpos($data,'schooltube.com') !== false
				|| strpos($data,'sevenload.com') !== false
				|| strpos($data,'.telly.com') !== false
				|| strpos($data,'.vh1.com') !== false
				|| strpos($data,'videojug.com/') !== false
				|| strpos($data,'vzaar.com') !== false
				|| strpos($data,'wildscreen.tv') !== false
				|| strpos($data,'wired.com') !== false
				|| strpos($data,'.worldstarhiphop.com') !== false
				|| strpos($data,'xtranormal.com') !== false || strpos($data,'x-webkit-airplay') !== false
				|| strpos($data,'zoopycdn.com') !== false
		) {
			return 'default';
		} else {
			return 'html';
		}
	}
	
	public function get_images($url) {
		include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_html.php');
		$CI =& get_instance();
		//Get contents and validate
		if (! $this->get_contents($url) ) {
			return array('status'=>false,'error'=>$this->config['errors']['failed_request']);
		}
		if (! isset($this->_headers['content_type'])) {
			return array('status'=>false,'error'=>$this->config['errors']['bad_headers']);
		}
		if (! $this->_contents) {
			return array('status'=>false,'error'=>$this->config['errors']['no_data']);
		}
		
		if(isset($this->_headers['content_type'])){ $content_type = $this->_headers['content_type']; }else{ $content_type=null; }
		$encoding = strpos($this->_headers['content_type'],'charset=') !== false ? substr($content_type, strpos($content_type,'charset=')+8) : 'utf-8';
		
		if (strpos($this->_headers['content_type'],'html') !== false) {
			if (strpos($url,'images.google.com')) {
				$html_parser = new Scraper_html($this->_contents, $url, $encoding);
				$data = $html_parser->get_images();
				if (strpos($url,'#') !== false) list($url,$dummy) = explode('#', $url, 2);
				$this->get_contents($url.'&start=2');
				$html_parser = new Scraper_html($this->_contents, $url, $encoding);
				$data = array_merge($data, $html_parser->get_images());
				$this->get_contents($url.'&start=3');
				$html_parser = new Scraper_html($this->_contents, $url, $encoding);
				$data = array_merge($data, $html_parser->get_images());
				$this->get_contents($url.'&start=4');
				$html_parser = new Scraper_html($this->_contents, $url, $encoding);
				$data = array_merge($data, $html_parser->get_images());
			} elseif (strpos($url,'facebook.com') !== false) {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_facebook.php');
				$parser = new Scraper_facebook($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			} elseif (strpos($url,'youtube.com') !== false) {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_youtube.php');
				$parser = new Scraper_youtube($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			} elseif (strpos($url,'metacafe.com') !== false) {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_metacafe.php');
				$parser = new Scraper_metacafe($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			} elseif (strpos($url,'vimeo.com/') !== false) {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_vimeo.php');
				$parser = new Scraper_vimeo($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			} elseif (strpos($url,'video.yahoo.com') !== false) {
				include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_yahoo_vids.php');
				$parser = new Scraper_yahoo_vids($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			} else {
				$parser = new Scraper_html($this->_contents, $url, $encoding);
				$data = $parser->get_images();
			}
			$added = array(); $arr = array();
			foreach ($data as $key=>$image_item) {
				if (isset($added[$image_item['src']])) {
					continue;
				} else {
					$added[$image_item['src']] = true;
					$image_item['src'] = $this->fix_src($image_item['src'], $url);
					$arr[] = $image_item;
				}
			}
			$data = $arr;
			if (!$data) {
				return array('status'=>false,'error'=>$this->config['errors']['no_images']);
			} else {
				return array(
					'status'=>true,
					'data'=>$data
				);
			}
		} elseif (in_array($this->_headers['content_type'], array('image/jpeg','image/gif','image/png','image/x-png'))) {
			return array(
					'status'=>true,
					'data'=>array(
						array(
							'src'=> $url,
							'alt'=> substr($url, strrpos($url, '/')+1),
							'description'=> '',
							'link'=> $url,
						)
					)
				);
		} else {
			return array('status'=>false,'error'=>$this->config['errors']['not_recognized_type'].': '.$this->_headers['content_type']);
		}
		
		return $this->_contents;
	}
	
	public static function get_url($data) {
		if (strpos($data['link'],'youtube.com') !== false) {
			include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_youtube.php');
			return Scraper_youtube::get_url($data['link']);
		} elseif (strpos($data['link'],'metacafe.com') !== false) {
			include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_metacafe.php');
			return Scraper_metacafe::get_url($data['link']);
		} elseif (strpos($data['link'],'vimeo.com') !== false) {
			include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_vimeo.php');
			return Scraper_vimeo::get_url($data['link']);
		} elseif (strpos($data['link'],'video.yahoo.com') !== false) {
			include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_yahoo_vids.php');
			return Scraper_yahoo_vids::get_url($data['link']);
		} else {
			return 'upload/'.$data['url'];
		}
	}
	
	public function get_css_images($url) {
		if (! $this->get_contents($url) ) {
			return array('status'=>false,'error'=>$this->config['errors']['failed_request']);
		}
		if (! isset($this->_headers['content_type'])) {
			return array('status'=>false,'error'=>$this->config['errors']['bad_headers']);
		}
		if (! $this->_contents) {
			return array('status'=>false,'error'=>$this->config['errors']['no_data']);
		}
		
		include_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_html.php');
		$parser = new Scraper_html($this->_contents);
		$css = $parser->get_css_files();
		$data = array();
		foreach ($css as $key=>$link) {
			$css[$key] = $this->fix_src($link, $url);
			$css_images = $parser->get_css_images($this->request($css[$key]));
			foreach ($css_images as $css_image) {
				$data[] = $this->fix_src($css_image, $css[$key]);
			}
		}
		return $data;
	}
	
	public function request($url, $anonimity=0, $cookie=null, $port=false) {
		if (strpos($url, 'wikipedia.org') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'ufunk.net') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'splashnology.com') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'getaddictedto.com') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'google.com/images') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'google.com/finance') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'myfunnyworld.net/') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'fanpop.com/') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'pistachios.se/') !== false && $anonimity == 0) $anonimity = 1;
		if (strpos($url, 'beautyoftheweb.co.in/') !== false && $anonimity == 0) $anonimity = 1;
		$url_arr = parse_url($url);
		$domain = @$url_arr['host'];
		
		if (strpos($url, 'google.com/images') === false && strpos($url, 'yimg.com') === false) {
			//$url = ltrim($url,'/');
			if (strpos($url, 'http://') === 0) $url = substr($url, 7);
			if (strpos($url, 'https://') === 0) $url = substr($url, 8);
			if (strpos($url, '#') !== false) list($url, $hash) = explode('#', $url, 2);
			$url = str_replace(array(' '), array('%20'), $url);
			if (strpos($url, '?') !== false) {
				list($path, $params) = explode('?', $url, 2);
				$path = str_replace('//', '/', $path);
				$url = $path.'?'.$params; 
			} else {
				$url = str_replace('//', '/', $url);
			}
			//$domain = strpos($url,'/') !== false ? current(explode('/', $url, 2)) : $url;
			$url = 'http://'.$url;
		} 
		
		/** Get headers **/
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER,  true);
		curl_setopt ($ch, CURLOPT_NOBODY, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
      	$ret = curl_exec($ch);
      	if ($ret) {
      		$headers = array();
      		$headers_str = explode("\n", $ret);
      		foreach ($headers_str as $header) {
      			if (strpos($header, ':') === false) continue;
      			list($key, $val) = explode(':', $header, 2);
      			$headers[strtolower($key)] = $val; 
      		}
      	}
      	
		/** Get body **/
      	$ch = curl_init(); 
		curl_setopt ($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_HEADER, false); 
		curl_setopt ($ch, CURLOPT_FAILONERROR, TRUE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false); 
      	if (isset($headers['content-encoding'])) {
      		curl_setopt($ch,CURLOPT_ENCODING , $headers['content-encoding']);
      	}
		if ($cookie) {
			$ckfile = tempnam ("/tmp", uniqid());
			$contents = '';
			foreach (explode(';', $cookie) as $cookie_item) {
				list($name, $val) = explode('=', $cookie_item, 2);
				$contents .= '.'.$domain.'	TRUE	/	FALSE	'.(time()+3600).'	'.$name.'	'.$val."\r\n";
			}
			$contents = trim($contents, "\r\n");
			file_put_contents($ckfile, $contents);
			//die($contents);
			curl_setopt($ch,CURLOPT_COOKIEJAR,$ckfile); 
			curl_setopt($ch,CURLOPT_COOKIEFILE,$ckfile);
		} else {
			curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt'); 
			curl_setopt($ch,CURLOPT_COOKIEFILE,'cookie.txt');
		}
		
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array(
			'Host: '.$domain,
		));
		
		if ($port) curl_setopt($ch, CURLOPT_PORT, $port);	
		switch($anonimity) {
			case 0:
				curl_setopt ($ch, CURLOPT_REFERER, 'http://www.fandrop.com');
				curl_setopt ($ch, CURLOPT_USERAGENT, "FanToon Scraper V1"); 
				break;
			case 1:
				curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.68 Safari/534.24');
				curl_setopt ($ch, CURLOPT_REFERER, '');
				curl_setopt ($ch, CURLOPT_COOKIESESSION, TRUE); 
				curl_setopt ($ch, CURLOPT_FORBID_REUSE, TRUE);
				break;
			case 2:
				curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.68 Safari/534.24');
				curl_setopt ($ch, CURLOPT_COOKIESESSION, TRUE);
				curl_setopt ($ch, CURLOPT_FORBID_REUSE, TRUE);
				curl_setopt ($ch, CURLOPT_PROXY, $proxies[0]);
				break;
			case 3:
				curl_setopt ($ch, CURLOPT_USERAGENT, text::randorm('alpha', rand(3,10)));
				curl_setopt ($ch, CURLOPT_COOKIESESSION, TRUE);
				curl_setopt ($ch, CURLOPT_FORBID_REUSE, TRUE);
				curl_setopt ($ch, CURLOPT_PROXY, array_rand($proxies));
				curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
				sleep(rand()*2);
				break;
		}
		$result = curl_exec ($ch);
		
		if (in_array(curl_errno($ch), array(22, 54)) && !$anonimity) {
			return $this->request($url,1, $port);
		}
		$this->_headers = curl_getinfo($ch);
		
		if (!$result && @ENVIRONMENT == 'development') {
			return array(
				'status' => false,
				'url' => $url,
				'error' => curl_errno($ch).': '.curl_error($ch)
			);
		} 
		
		curl_close ($ch);
		
		return $result;
	}
	
	public function headers($item=null, $default = null) {
		if ($item)
		{
			return isset($this->_headers[$item]) ? $this->_headers[$item] : $default;
		}
		else
		{
			return $this->_headers;
		}
	}
	
	public function contents($url=null) {
		if (!$this->_contents) {
			$this->_contents = $this->request($url,0);
		}
		return $this->_contents;
	}
	
	private function get_contents($url) {
		$contents = $this->request($url,0);
		$this->_contents = $contents;
		$this->_status = $this->_headers['http_code'];
		return $this->_status;
	}
	
	public function get_html($url='') {
		if (!$this->_contents) {
			$this->_contents = $this->request($url,0);
		}
		if (!$this->_contents) {
			return array('status'=>false, 'error' => 'The requested URL doesnt return any data');
		}
		if (is_array($this->_contents)) {
			return $this->_contents;
		}
		$contents = $this->_contents;
		$src_encoding = mb_detect_encoding($contents, mb_detect_order(), true);
		if ($src_encoding) {
			$contents = iconv($src_encoding, "UTF-8", $contents);
		}
				
		return $contents;
	}
	
	public function fix_src($src, $url) {
		if (!$src) {
			return $url;
		} elseif (strpos($src,'http') === 0) {
			return $src;
		} elseif (substr($src,0,2) == '//') {
			return 'http:'.$src;
		} elseif ($src[0] == '/') {
			$domain = str_replace(array('http://','https://'),'',$url);
			$domain = strpos($domain,'/') !== false ? substr($domain,0, strpos($domain,'/')) : $domain;
			return 'http://'.$domain.$src;
		} elseif (substr($src,0,3) == '../') {
			$domain = str_replace(array('http://','https://','www.'),'',$url);
			if (strpos($domain,'/') !== false) {
				list($domain, $path) = explode('/',$domain, 2);
			} else {
				$path = '';
			}			
			while (substr($src,0,3) == '../') {
				$src = substr($src, 3);
				$path = substr($path, 0, strrpos($path, '/'));
			}
			return 'http://www.'.$domain.'/'.$path.'/'.$src;
		} else {
			$domain = str_replace(array('http://','https://','www.'),'',$url);
			$domain = strpos($domain, '/') !== false ? substr($domain,0,strrpos($domain,'/')) : $domain;
			
			return 'http://www.'.$domain.'/'.$src;
		}
	}
	
	public static function update_cache($link, $data, $content='') {
		$ci = get_instance();
		$valid_url = parse_url($link);
		if (!isset($valid_url['host'])) $link = 'http://www.'.$link;
		$valid_url = parse_url($link);
    	$link = (isset($valid_url['scheme']) ? $valid_url['scheme'] : 'http' ).'://'.$valid_url['host']. (@$valid_url['path'] ? $valid_url['path'] : '/').(isset($valid_url['query']) ? '?'.$valid_url['query'] : '');
   		if (strpos($link, '#') !== false) list($link,) = explode('#', $link, 2);
		$link = mysql_real_escape_string($link);
				
		$cached_data = $ci->db->query("SELECT id, data, content FROM scraper_cache WHERE link = '$link'")->row();
		if ($cached_data) {
			$id = $cached_data->id;
			$media = json_decode($cached_data->data, true);
			if (!isset($data['images']) || !$data['images']) $data['images'] = $media['images']; 
			if (!isset($data['videos']) || !$data['videos']) $data['videos'] = $media['videos'];
			if (!$content) $content = $cached_data->content;
			
			$data = mysql_real_escape_string(json_encode($data));
			$content = mysql_real_escape_string($content);
			$ci->db->query("UPDATE scraper_cache SET data = '$data', content = '$content', updated_at = NOW() WHERE id = ".$id);
		} else {
			$data = array_merge(array('images'=>array(),'videos'=>array()), $data);
			
			$data = mysql_real_escape_string(json_encode($data));
			$content = mysql_real_escape_string($content);
			$ci->db->query("INSERT INTO scraper_cache (link, data, content, updated_at) VALUES('$link', '$data', '$content', NOW())");
		}
	}
	
	public static function get_cache($link) {
		$ci = get_instance();
		$valid_url = parse_url($link);
		if (!isset($valid_url['host'])) $link = 'http://www.'.$link;
		$valid_url = parse_url($link);
    	$link = (isset($valid_url['scheme']) ? $valid_url['scheme'] : 'http' ).'://'.$valid_url['host']. (@$valid_url['path'] ? $valid_url['path'] : '/').(isset($valid_url['query']) ? '?'.$valid_url['query'] : '');
		$link = mysql_real_escape_string($link);
		$ret = array('images'=>array(),'videos'=>array());
		$links = mysql_query("SELECT link_id, media, newsfeed.time,
										newsfeed.link_type, newsfeed.newsfeed_id, newsfeed.img, newsfeed.img_width, newsfeed.img_height
								FROM links 
								JOIN newsfeed ON (newsfeed.is_deleted = 0 AND newsfeed.type = 'link' AND newsfeed.activity_id = links.link_id)
								WHERE link = '$link' AND links.link_type IN ('embed','content','image')
								ORDER BY link_id DESC
								LIMIT 30
							");
		while ($row = mysql_fetch_object($links)) {
			if ($row->link_type == 'embed') {
				list($file, $ext) = explode('.', $row->img);
				$ret['videos'][] = array(
									'embed'=>$row->media,
									'thumb'=>Url_helper::s3_url().'links/'.$file.'_original.'.$ext
									);
			} elseif ($row->link_type == 'content') {
				if (!@$ret['content'] && strtotime($row->time) > time()-24*60*60) {
					$ret['content'] = @file_get_contents(Url_helper::s3_url().'uploads/screenshots/drop-'.$row->newsfeed_id.'/index.php');
				}
			} elseif ($row->link_type == 'image' && $row->img) {
				list($file, $ext) = explode('.', $row->img,2);
				$ret['images'][] = array(
									'src'=>Url_helper::s3_url().'links/'.$file.'_original.'.$ext,
									'width' => $row->img_width,
									'height' => $row->img_height
									);
			}
		}
		$data = mysql_fetch_object(mysql_query("SELECT data, content FROM scraper_cache WHERE link = '$link'"));
		
		if ($data) {
			$media = json_decode($data->data, true);
			if ($media['images']) $ret['images'] = array_merge($media['images'], $ret['images']);
			if ($media['videos']) $ret['videos'] = array_merge($media['videos'], $ret['videos']);
			if ($data->content) $ret['content'] = $data->content;
		}
		
		if (@$ret['content'] && !@$ret['images']) {
			require_once(BASEPATH.'../application/modules/fantoon-extensions/libraries/scraper/Scraper_html.php');
			$parser = new Scraper_html($ret['content'], $link);
			$ret['images'] = $parser->get_images();
		}
		
		return $ret ? $ret : false;
	}
	
	public static function clean_html($contents, $link) {
		$parsed = parse_url($link);
		$base_url = $parsed['scheme'] . "://" . $parsed['host'];
		$base_path = $base_url.$parsed['path'];

		$contents = preg_replace('#if[ ]*\([^\)]*(window.)*(top|parent|self)(.location)*[ ]*![=]+[ ]*(window.)*(self|top|parent)(.location)*[ ]*\)#', 'if (false)', $contents);
		$contents = preg_replace('#if[ ]*\((top|parent).frames.length#', 'if(0', $contents);
		$contents = preg_replace('#top[ ]*!=[ ]*self#', 'false', $contents);
		$contents = str_replace('if (window != window.top)', 'if (false)', $contents);
		$contents = str_replace('if (top.location != location)', 'if (false)', $contents);
		//$contents = str_replace('top.location', 'self.location', $contents);
		$contents = str_replace('should_wipe', 'false', $contents);
		$contents = str_replace('window.location.hostname', "'".($base_url)."'", $contents);
		$contents = str_replace('window.location[^\.]', '"'.$link.'"', $contents);
		$contents = str_replace('document.location.href', '"'.$base_url.'"', $contents);
		
		$contents = preg_replace("#<script[^>]*bookmarklet.js[^>]*></script>#","",$contents);
		$contents = preg_replace("#<link[^>]*bookmarklet/external.css[^>]*>#","",$contents);
		$contents = preg_replace("#<link[^>]*fandrop.com/css/[^>]*>#","",$contents);
		$contents = preg_replace('#<script type="text/javascript" src="http://www.wired.com/js/cn-fe-common/cn.js"></script>#msi', '', $contents);
		$contents = preg_replace('#<script type="text/javascript">[\r\n\s\t]+//<!--[\r\n\s\t]*var platformEnvironment.*?</script>#msi', '', $contents);
		$contents = preg_replace("#<script[^>]*ajs.php\?zoneid=[^>]*></script>#","",$contents);
		$contents = preg_replace("#<script[^>]*sitewide[^>]*></script>#","",$contents);
		$contents = preg_replace("#<script[^>]*ads[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*stats.pictorymag.com[^>]*></script>#","",$contents);
		$contents = preg_replace("#<script[^>]*asnc[^>]*></script>#","",$contents);
		$contents = preg_replace("#<script[^>]*ad.doubleclick.net[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*ad.yieldmanager.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*amgdgt.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*truste.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*/ad247[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*>[^<]*drupal.dart[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*a.tribalfusion.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*show_ads.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*/angelfire-main.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*adx.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*ROS/tags.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*displayAd.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*adjs.php[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*ads.yimg.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*KonaFlashBase.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*/ads.[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*rubiconproject.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*as.jivox.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*addthis.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*embed.newsinc.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*collective-media.net[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*bbelements.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*googletagservices.com[^>]*>[^<]*</script>#msi","",$contents);
		
		//Usual protection scripts
		$contents = preg_replace('#<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js"></script>#msi', '', $contents);
		$contents = preg_replace('#<script type=\'text/javascript\' src="[^>"]*ecomfw.min.js\?ver=3.3.1"></script>#msi', '', $contents);
		
		
		//social bars and plugins
		$contents = preg_replace("#<iframe[^<]*ads[^<]*</iframe>#msi","",$contents);
		$contents = preg_replace("#<!-- AddThis Button BEGIN -->(.*?)<!-- AddThis Button END -->#msi","",$contents);
		
		//$google_api_key = $this->config->item('google_api_key');
		//$contents = preg_replace('#maps\?(.*?)key=(.*?)"#msi', 'maps?$1key='.$google_api_key.'"', $contents);
		$contents = preg_replace("#<script[^>]*maps.google.com[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^>]*google-analytics.com/ga.js[^>]*>[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*gaJsHost[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*pageTracker[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*www.googletagservices.com/tag/js/gpt.js[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*googletag.[^<]*</script>#msi","",$contents);

    // http://dev.fantoon.com:8100/browse/FD-4287
    // document.write of this MTV site contains javascript to load some kind of 
    // content -> this cleaning break it
		if (! strpos($link, 'www.mtv.com/videos/wallpaper/')) 
			$contents = preg_replace("#document\.write[^\(]*\(.*?\)#msi","",$contents);
		
		$contents = preg_replace("#document\.body\.appendChild\(.*?\);#msi","",$contents);
		$contents = preg_replace("#d\.write[^\(=]*\([^;]*;#msi","",$contents);
		$contents = preg_replace("#<script[^<]*adinterax.com[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*displayAdSlot[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*<![^<]*HSW.ads.init[^<]*</script>#msi","",$contents);
		$contents = preg_replace("#<script[^<]*STNGYahooContentMatch[^<]*</script>#msi","",$contents);
		
		//Flash shouldnt be disabled: http://dev.fantoon.com:8100/browse/FD-4333
		//if (strpos($contents, 'AC_FL_RunContent') !== false) {
		//	$contents = preg_replace("#AC_FL_RunContent[^\)]*\)[;]*#msi","",$contents);
		//}
		
		//preg_match_all('#adinterax.com.*?</script>#msi', $contents, $matches);
		$contents = preg_replace("#Bonnier.Ads247.postAdLoad\(\);#msi","",$contents);
		
		//Site specific
		if (strpos($link, 'costaricatravelsite.com')) $contents = preg_replace('#<script[^>]*.js[^>]*></script>#msi', '', $contents);
		if (strpos($link, '.youku.com/')) $contents = preg_replace('#<script[^>]*common.js[^>]*></script>#msi', '', $contents);
		if (strpos($link, '.wallsave.com/')) $contents = preg_replace('#<script[^>]*wallsavenew.js[^>]*></script>#msi', '', $contents);
		if (strpos($link, '.brainyquote.com/')) $contents = preg_replace('#<script[^>]*/js/header_01\.js[^>]*></script>#msi', '', $contents);
		if (strpos($link, '.groupon.pl/')) $contents = preg_replace('#<script[^>]*allDeals_bundle.js[^>]*></script>#smi', '', $contents);
		if (strpos($link, '.rakuten.co.jp/')) $contents = str_replace('id="ritHdSearch"', 'id="ritHdSearch" style="height:auto"', $contents);
		if (strpos($link, '.boston.com/')) $contents = preg_replace('#<div class="bannerAd".*?</div>#smi', '', $contents);
		if (strpos($link, '.nytimes.com/')) $contents = preg_replace('#<script[^>]*common.js[^>]*></script>#smi', '', $contents);
		if (strpos($link, 'n4bb.com')) $contents = preg_replace('#<script[^>]*embed.js[^>]*></script>#smi', '', $contents);
		if (strpos($link, 'renren.com')) $contents = preg_replace('#<script[^>]*base-all2.js[^>]*></script>#smi', '', $contents);
		if (strpos($link, 'fitnesscheerleader.com/')) {
			$contents = str_replace('class="block-content"', 'class="block-content" style="height:auto"', $contents);
			$contents = str_replace('class="banner-image"', 'class="banner-image" style="height:auto"', $contents);
		}
		if (strpos($link, 'popsci.com/') !== false) {
			$contents = str_replace('.addClass("hidden")', '.removeClass("hidden")', $contents);
		}
		if (strpos($link, 'efax.com/') !== false) {
			$contents = str_replace('<body', '<body style="background-color: #1E65B4"', $contents);
		}
		if (strpos($link, 'homes.com/') !== false) {
			$contents = preg_replace('#<script[^>]*>[^<]*eval\([^<]*</script>#msi', '', $contents);
		}
		if (strpos($link, 'suntimes.com') !== false) {
			$contents = preg_replace("#<script[^<]*/partner/[^<]*</script>#msi","",$contents);
		}
		if (strpos($link, 'cbslocal.com') !== false) {
			$contents = preg_replace("#<script[^<]*global.js[^<]*</script>#msi","",$contents);
		}
		if (strpos($link, 'yahoo.com') !== false) {
			$contents = preg_replace("#<div id=\"adx_.*?</div>#msi","",$contents);
			$contents = preg_replace("#Y.later\(.*?</head>.*?}\);#","",$contents);
		}
		if (strpos($link, 'dell.com') !== false) {
			$contents = preg_replace('#<script[^>]*>[^<]*addPnLink\([^<]*</script>#msi', '', $contents);
			$contents = preg_replace('#<script[^>]*>[^<]*writeLocaleSelector\([^<]*</script>#msi', '', $contents);
		}
		if (strpos($link, 'howstuffworks.com') !== false) { //may move to global
			$contents = preg_replace('#<script[^>]*>[^<]*\$\("body"\).append\(.*?</script>#msi', '', $contents);
		}
		if (strpos($link, 'gigaom.com') !== false) {
			$contents = preg_replace('#id="main"#msi', 'id="main" style="width:960px"', $contents);
		}
		if (strpos($link, 'mashable.com') !== false) {
			$contents = preg_replace('#id=\'peek\'#msi', 'id="peek" style="width:960px"', $contents);
		}
		if (strpos($link, 'nationalgeographic.com') !== false) {
			$contents = preg_replace('#id="kids_header"#msi', 'id="kids_header" style="height:170px"', $contents);
		}
		if (strpos($link, 'alipay.com') !== false) {
			$contents = preg_replace('#class="fn-hide"#msi', '', $contents);
			$contents = preg_replace('#id="loginLoading"#msi', 'id="loginLoading" class="fn-hide"', $contents);
		}
		if (strpos($link, 'http://divine.malabargoldanddiamonds.com/') !== false) {
			$contents = preg_replace('#function CheckRegion\(lnkhtml,lnkhref\) {#msi', '$0'."\n\t\t".'$(".splash_container").hide(); $(".slider").fadeIn();', $contents);
		}
		
		//Proxies
		if (strpos($link, 'davidbowie.com/')) {
			$contents = preg_replace_callback("#(<script[^>]*src=\"(.*/js/js_qm.*?)\")#msi", create_function('$item', 'return str_replace($item[2], "'. Url_helper::base_url() . 'external/index.php?url=".Scraper::fix_src($item[2], "'.$link.'"), $item[0]);'), $contents);
		}
		if (strpos($link, 'pistachios.se/')) {
			$contents = preg_replace_callback("#(<script[^>]*src=[\"']([^>]*cargo\.site\.package\.js.*?)[\"'])#msi", create_function('$item', 'return str_replace($item[2], "'. Url_helper::base_url() . 'external/index.php?base='.urlencode($link).'&url=".Scraper::fix_src($item[2], "'.$link.'"), $item[0]);'), $contents);
		}
		
		$proxies = array('energycell.co.uk', '.seeof.com/', 'iqiyi.com/');
		foreach ($proxies as $proxy) {
			if ( strpos($link, $proxy) !== false ) { //bobef: #FD-1796
				$contents = preg_replace_callback("#(<img[^>]*src=\"(.*?)\")#msi", create_function('$item', 'return str_replace($item[2], "'. Url_helper::base_url() . 'external/index.php?url=".Scraper::fix_src($item[2], "'.$link.'"), $item[0]);'), $contents);
			}
		}
		
		//Slow load
		$slow = 'false';
		if (strpos($link, 'chanel.com') !== false || strpos($link, 'syfy.com') !== false ) {
			$slow = 'true';
		}
		
		//Fix flash base
		$js = '<script type="text/javascript">
			var els = document.getElementsByTagName("embed");
			for (var i=0; i< els.length; i++) {
				if (els[i].src.indexOf("http://s.ytimg.com/yt/swfbin/") > -1) {
					var v_id = els[i].getAttribute("flashvars").match(/&video_id=([^%]*?)($|&)/);
					var iframe = \'<iframe width="\'+els[i].offsetWidth+\'" height="\'+els[i].offsetHeight+\'" src="http://www.youtube.com/embed/\'+v_id[1]+\'" frameborder="0" allowfullscreen></iframe>\';
					var div = document.createElement("div");
						div.innerHTML = iframe;
					els[i].parentNode.replaceChild(div, els[i]);
				}
			}
		</script>';
		
		//Fix widgets
		$js .= '<script type="text/javascript">
					var els = document.querySelectorAll(".yui-carousel-item");
					for (var i=0; i< els.length; i++) {
						els[i].style.setProperty("position", "static");
					}
					var links = document.getElementsByTagName("a");
					for (var i=0;i<links.length;i++) {
						links[i].target = "_blank";
					}
		</script>';
		
		//Fix protected images
		$js .= '<script type="text/javascript">
					function make_full(src) {
						if (!src) return src;
						if (src.substring(0,3) == ".//") return "'.$link.'"+src.replace(".//","/");
						if (src.substring(0,2) == "//") return "http:"+src;
						if (/^\w+:/.test(src)) return src;
						var matches = src.match(/\.\.\//g);
						var base = "'.$link.'";
						if (matches) {
							src = src.substring(matches.length * 3);
							for (i = matches.length; i--;) base = base.substring(0, base.lastIndexOf("/"));
							return base+src;
						}
						return "Not recognized";
					} 
					var imgs = document.getElementsByTagName("img");
					for(var i=0;i<imgs.length;i++) {
						imgs[i].onerror = function() {
							this.src = "'.Url_helper::base_url().'external/index.php?url="+make_full(this.src);
						}
					}
				</script>';

		//Doc load, width/height
		$js .= '<script type="text/javascript">
						var max_ajax_requests = 20;
						var _open = XMLHttpRequest.prototype.open;
						window.XMLHttpRequest.prototype.open = function(type, url, async) {
							if (!max_ajax_requests--) return false;
							_open.call(this, type, url, async);
						}
						//RR breaks when zoom in FF
						//var html = document.documentElement;
						//html.style.overflow = "";
						
						document.body.style.overflow = "";
						document.body.style.height = "100%";
						if ("'.$link.'".indexOf("davidbowie.com/") > -1) {
							document.body.style.minHeight = "800px";
						}
						if ("'.$link.'".indexOf("divine.malabargoldanddiamonds.com/") > -1) {
							document.body.style.minHeight = "450px";
						}
						var _ft_w = Math.max(document.body.clientWidth, document.body.scrollWidth);
						var _ft_h = Math.max(document.body.clientHeight, document.body.scrollHeight);
						document.body.style.overflow = "hidden";
						//html.style.overflow = "hidden";
						
						var is_fandrop_loaded = false;
						window.onload = function() {
							window.setTimeout(function() {
								document.body.style.overflow = "";
								//html.style.overflow = "";
								console.info("ONLOAD", _ft_w, _ft_h, Math.max(document.body.clientHeight, document.body.scrollHeight));
								if (!_ft_h || !_ft_w || _ft_h < Math.max(document.body.clientHeight, document.body.scrollHeight)) {
									_ft_w = Math.max(document.body.clientWidth, document.body.scrollWidth);
									_ft_h = Math.max(document.body.clientHeight, document.body.scrollHeight);
									console.info("onload", _ft_w, _ft_h);
									parent.postMessage("{\"action\":\"doc_ready\", \"fandrop_message\": \"true\", \"width\": "+_ft_w+", \"height\": "+_ft_h+"}", "*");
								}
								document.body.style.overflow = "hidden";
								//html.style.overflow = "hidden";
							}, 500);
							
							if ('.$slow.') {
								window.setTimeout(function() { document.title = "(FT-LOADED)"; }, 5000);
							} else {
								document.title = "(FT-LOADED)"+_ft_w+"x"+_ft_h;
							}
							fandrop_load();
	   					};
	   					var _ft_resize_msgs = 5;
	   					window.onresize = function() {
							document.body.style.overflow = "scroll";
							if (_ft_resize_msgs && _ft_w != Math.max(document.body.clientWidth, document.body.scrollWidth)) {
								_ft_resize_msgs--;
		   						_ft_w = Math.max(document.body.clientWidth, document.body.scrollWidth);
								_ft_h = Math.max(document.body.clientHeight, document.body.scrollHeight);
								console.info("onresize", _ft_w, _ft_h);
								parent.postMessage("{\"action\":\"doc_ready\", \"fandrop_message\": \"true\", \"width\": "+_ft_w+", \"height\": "+_ft_h+"}", "*");
							}
							document.title = "(FT-LOADED)"+_ft_w+"x"+_ft_h;
							document.body.style.overflow = "hidden";
						}
	   					
	   					function fandrop_load() {
							if (is_fandrop_loaded) return;
							is_fandrop_loaded = true;
							removeAds();							
							//Site specific fixed
							if (location.href.indexOf("nymag.com/") > -1) {
								$(".contentPrimary .entry-medVert").prepend($(".primaryImageWrap"));
							}
							document.body.style["visibility"] = "visible";
							window.setTimeout(function() {
								document.body.style["visibility"] = "visible";
							}, 2000);
						}
						
						function removeAds() {
							var els = document.getElementsByClassName("ad-wrapper");
							for (var i=0;i<els.length;i++) {
								try { els[i].parentNode.removeChild(els[i]); } catch (e) {};
							}
							els = document.getElementsByClassName("ad-box");
							for (var i=0;i<els.length;i++) {
								try { els[i].parentNode.removeChild(els[i]); } catch (e) {};
							}
							els = document.getElementsByClassName("ad-box");
							for (var i=0;i<els.length;i++) {
								try { els[i].parentNode.removeChild(els[i]); } catch (e) {};
							}
							var el = document.getElementById("adx_ldo4_250237");
							if (el) el.parentNode.removeChild(el);
						}
						fandrop_load();
						window.setTimeout(function() { fandrop_load() }, 30*1000);
						'.self::communication_js().'
			</script>';


		
		if (mb_strripos($contents, '<html') === false) {
			$contents = "<html>".$contents."</html>";
		}
		
		// put base url to flash objects
		//$contents = preg_replace('#</object>#msi', '<param name="base" value="'.$link.'"/></object>', $contents);
		
		// put base url for a relative content
		$head = '<base href="'.$base_path.'">';
		if (!(preg_match('#<meta[^>]*Content-Type#msi', $contents) || preg_match('#<meta[^>]*charset#msi', $contents)) && mb_detect_encoding($contents)) {
			$head .= '<meta http-equiv="Content-Type" content="text/html; charset='.mb_detect_encoding($contents).'">';
		}
		//preg_match('#<head[^>]*>#', $contents, $matches, PREG_OFFSET_CAPTURE); //http://www.utsavfashion.com/store/sarees-large.aspx?icode=sxs519
		if (preg_match('#<head[^>]*>#i', $contents)) {
			//$pos = $matches[0][1]+mb_strlen($matches[0][0]);
		 	//$contents = mb_substr($contents, 0, $pos).$head.mb_substr($contents, $pos);
		 	$contents = preg_replace('#(<head[^>]*>)#i', '$1'.$head, $contents, 1);
		} elseif (preg_match('#<html[^>]*>#msi', $contents)) {
			$contents = preg_replace('#(<html[^>]*>)#', '$1<head>'.$head.'</head>', $contents, 1);
		}
		
		if (mb_strripos($contents, '</body>') !== false) {
			$contents = preg_replace('#(.*)</body>(.*?)#msi', '$1'.$js.'</body>' . '$2', $contents, 1);
		} elseif (mb_stripos($contents, '</html>')) {
			$contents = preg_replace('~(.*)' . preg_quote('</html>', '~') . '(.*?)~', '$1' . $js.'</html>' . '$2', $contents, 1);
		} else {
			$contents .= $js;
		}
		
		return $contents;
	}
	
	/**
	 * postMessage for bookmark page and html content
	 */
	public static function communication_js() {
		return '
				if (typeof window._ft_w == "undefined" || typeof window._ft_h == "undefined") {
					var _ft_w = Math.max(document.body.clientWidth, document.body.scrollWidth);
					var _ft_h = Math.max(document.body.clientHeight, document.body.scrollHeight);
				}
				document.body.style.overflow = "hidden";
				parent.postMessage("{\"action\":\"doc_ready\", \"fandrop_message\": \"true\", \"width\": "+_ft_w+", \"height\": "+_ft_h+"}", "*");
				
				function get_size() {
					document.body.style.overflow = "";
					document.documentElement.style.overflow  = "";
					_ft_w = Math.max(document.body.clientWidth, document.body.scrollWidth, document.documentElement.scrollWidth);
					_ft_h = Math.max(document.body.clientHeight, document.body.scrollHeight, document.documentElement.scrollHeight);
					document.body.style.overflow = "hidden";
					//document.documentElement.style.overflow  = "hidden"; //RR - breaks FF zoom
					parent.postMessage("{\"action\":\"doc_ready\", \"fandrop_message\": \"true\", \"width\": "+_ft_w+", \"height\": "+_ft_h+"}", "*");
				}

				function messageHandler(msg) {

					if (!msg) return;
					if (msg.data.indexOf("{") != 0) return;
					data = eval("("+msg.data+")");
					if (!data.fandrop_message) return;
					if (data.action == "get_size") {
						get_size();
					} else if (data.action == "zoom") {
						var zoom = parseFloat(data.zoom);
						console.info("ZOOM", zoom); 
						var embed = document.getElementsByTagName("embed");
						if (embed.length == 1 && embed[0].clientHeight > h-40 && embed[0].clientWidth > _ft_w-40) {
								window.setTimeout(function() {
									embed[0].Zoom(200-(zoom*100));
								}, 5000);
						} else {
							document.body.style.setProperty("zoom", zoom);
							document.body.style.setProperty("-moz-transform", "scale("+zoom+")");
							document.body.style.setProperty("-moz-transform-origin", "0 0");
							var iframes = document.getElementsByTagName("iframe");
							for (var i=0;i<iframes.length;i++) {
								try {
									if ( iframes[i].contentDocument ) {
     									iframes[i].contentDocument.getElementsByTagName("body")[0].style.setPropery("zoom", zoom);
									} else if ( iFrame.contentWindow ) {
										iframes[i].contentWindow.document.getElementsByTagName("body")[0].style.setProperty("zoom", zoom);
									} 
								} catch (e) {}
							}
						}
					}

				}

				if (window.addEventListener) {
					window.addEventListener("message", messageHandler);
				} else if (window.attachEvent) {
					window.attachEvent("onmessage", messageHandler);
				} else {
					window.onmessage = messageHandler;
				}';

	}
	
}

?>
