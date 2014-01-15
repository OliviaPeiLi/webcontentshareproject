<?php
class Html_helper extends Helper {
	/**
	 * adds requireJS array to the view
	 * if $ci->load->optimized_js_config (Set in libraries/MX/Controller.php) is true
	 * returns nothing because the grouped file is loaded in the header.php 
	 * @example requireJS(array('home', 'newsfeed/tile'))
	 */
	public static function requireJS($arr) {
		$ci = get_instance();
		if (!$arr) return '';
		
		//for encoded file names - replaces the development name with the encoded one
		//$ci->css_filenames is set in libraries/MX/Controller.php
		if ($ci->css_filenames) { 
			foreach ($arr as &$file) {
				if (isset($ci->css_filenames['/js/'.$file.'.js'])) {
					$file = str_replace(array('.js'),'',$ci->css_filenames['/js/'.$file.'.js'][2]);
				}
			}
		}
		
		if ($ci->input->is_ajax_request()) {
			return '<script type="text/javascript">require(["'.implode('","', $arr).'"]);</script>';
		} else {
			$ci->js_to_load = array_merge($ci->js_to_load, $arr);
			return ;
		}
	}
	
	public static function stylesheet($src) {
		$ci = get_instance();
		if ($ci->css_filenames) {
			$src = $ci->css_filenames['/css'.$src.'.css'][2].'?v='.$ci->css_filenames['/css'.$src.'.css'][0];
		}
		if (strpos($src, 'http://') === false && strpos($src, 'https://') === false ) {
			$src = $ci->css_base.$src.'.css';
		} 
		return '<link rel="stylesheet" href="'.$src.'" type="text/css"/>'."\r\n";
	}
	
	public static function og_meta($item) {
		$ci = get_instance();
		if ($item->_model instanceof Folder_model) {
			$type = 'collection';
			$url = $item->folder_url;
			$title = $description = $item->display_name;
			$thumb = $item->thumbnail;
		} elseif ($item->_model instanceof Newsfeed_model) {
			$type  = 'drop';
			$url = '/drop/'.$item->url;
			if ($item->folder->type == 1) { //sxsw
				$title = $item->title;
				$description = $item->title.' is in the VentureBeat #WinSXSW contest! Help us win the contest by sharing our video here at '.$url;
			} elseif ($item->folder->type) {
				$title = $item->title;
				$description = $item->title.' is in the '.$item->folder->folder_name.' contest! Help us win the contest by sharing our video here at '.$url;
			} else {
				$title = $description = strip_tags($item->description);
			}
			$thumb = strpos($item->img_thumb, 'http') !== false ? $item->img_thumb : Url_helper::base_url('/images/FD.png');
		} else {
			die('Content type not recognized: '.get_class($item->_model));
		}
		
		return 
			'<meta property="fb:app_id"      content="'.$ci->config->item('fb_app_key').'" />
			 <meta property="og:type"        content="'.$ci->config->item('fb_app_namespace').':'.$type.'" />
			 <meta property="og:url"         content="'.Url_helper::base_url($url).'?ext=fb&"/>
			 <meta property="og:title"       content="'.$title.'"/>
			 <meta property="og:image"       content="'.($thumb ?  $thumb : Html_helper::img_src('screenshotIcon.png')).'"/>
			 <meta property="og:description" content="'.$description.'"/>';
	}

	public static function img_src($src) {
		$ci = get_instance();
		if (strpos($src, 'http://') === false && strpos($src, 'https://') === false) {
			if ($ci->css_filenames && isset($ci->css_filenames['/images/'.$src])) {
				$src = $ci->css_filenames['/images/'.$src][2].'?v='.$ci->css_filenames['/images/'.$src][1];
			}
			$src = $ci->images_base.$src;
		}
		return $src;
	}
	
	public static function img($src, $attrs=array()) {
		$attrs['src'] = self::img_src($src);
		$attrs_str = '';
		foreach ($attrs as $key=>$val) {
			$attrs_str .= ' '.$key.'="'.$val.'"';
		}
		return '<img '.$attrs_str.'/>';
	}


	public static function item_data($obj, $data_array) {
		$ret = '';
		foreach ($data_array as $field) {
			if (strpos($field, '->')) {
				list($sub_obj, $sub_field) = explode('->', $field);
				$val = $obj->$sub_obj ? $obj->$sub_obj->$sub_field : '';
				$field = str_replace('->', '-', $field);
			} else {
				$val = $obj->$field;
			}
			$ret .= ' data-'.$field.'="'.str_replace('"', "'", $val).'"';
		}
		return $ret;
	}

	public static function link_preview_popup_data($newsfeed) {
		$attrs = array(
			'rel'=>'popup',
			'href'=> '#preview_popup',
			'data-url'=> '#preview_popup',
			'data-unscrollable'=>'1',
			'data-hidetitlebar'=>'1',
			'data-classname'=>'popup_link_preview',
			'data-class'=>$newsfeed->link_type_class,
			'data-group'=>'link_preview',
		);
		$ret = '';
		foreach ($attrs as $key=>$val) {
			$ret .= ' '.$key.'="'.$val.'"';
		}
		return $ret;
	}

	public static function iframe($src, $attrs = array()) {
		$attr_str = '';
		foreach ($attrs as $key=>$val) {
			$attr_str .= ' '.$key.'="'.$val.'"';
		}
		return '<iframe src="'.$src.'" '.$attr_str.'></iframe>';
	}

	public static function twitter_btn($item, $attrs = array()) {
		if (!isset($attrs['class'])) $attrs['class'] = '';
		$attrs['class'] .= ' share_btn share_twt_app';
		
		if ($item->_model instanceof Folder_model) {
			$tweet_url = Url_helper::base_url($item->folder_url);
			$tweet_text = $item->folder_name;
			if (isset($item->recent_newsfeeds) && count($item->recent_newsfeeds) == 0)	{
				$attrs['class'] .= ' inactive';
			}
		} elseif ($item->_model instanceof Newsfeed_model) {
			if ($item->short_url) {
				$tweet_url = strpos($item->short_url, 'http://') !== false ? $item->short_url : 'bit.ly/'.$item->short_url;
			} else {
				$tweet_url = Url_helper::base_url('/drop/'.$item->url);
			}
			
			if($item->folder->type == 1) { //Sxsw
				$tweet_text = $item->title.' is in the VentureBeat WinSXSW contest! Help us win the contest by sharing our video here at ';
				$attrs['data-hashtags'] = 'WinSXSW';
			} elseif($item->folder->type) {
				
				if ($item->folder->contest->url == 'fndemo') {
					//$attrs['data-via'] = 'foundersnetwork';
					//$attrs['data-hashtags'] = 'fnDemo';
					$handles = array(
						'zeb@renthackr.com' => array('@zeb', '@RentHackr'),
						'noman@sportslabinc.com' => array('@nomanahmad99', '@sportslabinc'),
						'ilias.pantelakis@gmail.com' => array('@iliaspantelakis', '@dealingers'),
						'ljadavji@turnyp.com' => array('@legendarylvj', '@turnyp'),
						'mikko@vuact.com' => array('@mhj', '@vuact'),
						'vijay@hungryglobetrotter.com' => array('@HungryGlobetrtr', '@HungryGlobetrtr'),
						'fabian@fiverun.com' => array('@fiverun', '@fiverun'),
						'mike@merxz.com' => array('@mike_mack', '@merxzology '),
						'jon@etceter.com' => array('@oleaga', '@etcetercom'),
						'scott@blogmutt.com' => array('@scodtt', '@blogmutt'),
						'erik@indiloop.com' => array('@erikashdown', '@indiloopmusic'),
						'ioannis@syntellia.com' => array('@ioanv', '@fleksy'),
						'greg.moore@fit3d.com' => array('@gmoore11', '@MyFit3D'),
						'matt@ahhha.com' => array('@matthewcrowe', '@ahhha'),
						'sameer@rivalme.com' => array('', '@RivalMe'),
						'jack.pien@eeme.co' => array('@j_pien', '@projecteeme'),
						'oscar@gradf.ly' => array('@oscar_pedroso', '@grad_fly'),
						'Hendrik@vilynx.com' => array('@hvanderm', '@vilynxapp'),
						'matt@karmastore.org' => array('@mattmonday', '@karmastore'),
						'cynthia@abbeypost.com' => array('@cynthiaschames', '@abbey_post'),
						'samar@good.co' => array('@samarbirwadker', '@ingoodco'),
						'blazej@sher.ly' => array('@blazej_os', '@sherlyfiles'),
						'steven@mediarelevance.com' => array('@clarkemartin', '@mediarelevance'),
						'mike@graphdat.com' => array('@codemoran', '@graphdat'),
						'mat@cloudability.com' => array('@matellis', '@cloudability'),
						'tracy@dishcrawl.com' => array('@ladyleet', '@dishcrawl'),
						'jack@amap.to' => array('@JackGonzalez', '@aMAPto'),
						'neha@raweng.com' => array('@nehasf', '@builtio'),
						'ruben@spreeify.com ' => array('@rubendua', '@spreeify'),
						'marc@venturocket.com' => array('@marchoag', '@venturocket'),
						'todd.b@in2Technologies.com' => array('@engagethesenses', '@engagethesenses'),
						'sschreiman@kerio.com' => array('@scottschreiman', '@samepageio'),
						'abel@pogoseat.com' => array('@cuskelly', '@pogoseat'),
						'mukta@TalentXp.com' => array('@MuktaGoel', '@TalentXp3'),
						'sdash@joinStampede.com' => array('@join_stampede', ''),
					);
					$founder = isset($handles[$item->sxsw_email]) && $handles[$item->sxsw_email][0] ? $handles[$item->sxsw_email][0] : $item->title;
					$startup = isset($handles[$item->sxsw_email]) ? ' '.$handles[$item->sxsw_email][1] : '';
					
					$tweet_text = 'I voted for '.$founder.$startup.' in @foundersnetwork\'s #fnDemo. Cast your vote here:';					
				} else {
					$tweet_text = $item->title.' is in the '.$item->folder->folder_name.' contest! Help us win the contest by sharing our video here at ';
				}
			} else {
				$tweet_text = strip_tags(@$item->description);
			}
		} else {
			die("Model not recognized: ".get_class($item->_model)." in html_helper");
		}
		// if the newsfeed was shared to twiter already, make it inactive
		if (get_instance()->user && $item->is_shared(get_instance()->user, 'twitter' ) ) {
			$attrs['class'] .= " inactive";
		}
		$attrs = array_merge(array(
			'data-url' => $tweet_url,
			'data-text' => $tweet_text,
			'data-count' => "none",
		), $attrs);
		
		return self::anchor(null, '<span class="ico"></span>', $attrs);
	}

	public static function pinterest_btn($item, $attrs = array()) {
		$CI = get_instance();
		
		$add_class = isset($attrs['class']) ? " " . $attrs['class'] : "";
		if ($item->_model instanceof Newsfeed_model) {
			if (!$item->complete) $add_class .= " inactive";

			// if the newsfeed was shared to pint already, make it inactive
			if ( $item->is_shared( $CI->user, 'pinterest' ) ) {
				$add_class .= " inactive";
			}
		}
		if ($item->_model instanceof Folder_model) {
			if (isset($item->recent_newsfeeds) && count($item->recent_newsfeeds) == 0)	{
				$add_class .= " inactive";
			}
		}
		
		unset($attrs['class']);
		
		if ($item->_model instanceof Newsfeed_model) {
			$url = Url_helper::base_url('/drop/'.$item->url);
			if($item->folder->type == 1) {
				$desc = $item->title.' is in the VentureBeat #WinSXSW contest! Help us win the contest by sharing our video here at ';
			} elseif($item->folder->type) {
				if ($item->folder->contest->url == 'fndemo') {
					$desc = $item->title.' is a hot startup in Founder`s Network. Please vote and share';
				} else {
					$desc = $item->title.' is in the '.$item->folder->folder_name.' contest! Help us win the contest by sharing our video here at ';
				}
				
			} else {
				$desc = strip_tags($item->description);
			}
			$url = urlencode($url);
			$media = urlencode($item->img_full);
			$desc = urlencode($desc);
		} elseif ($item->_model instanceof Folder_model) {
			$thumb = $item->get_thumbnail();
			if (!$thumb) return '';
			$url = urlencode(base_url($item->folder_url));
			$media = urlencode($thumb);
			$desc = urlencode($item->folder_name);
		} else {
			return '<!-- Item type not recognized -->';
		}
		
		$attrs = array_merge(array(
			'class' => "ext_pinterest share_btn pin-it-button" . $add_class,
			'target' => "_blank",
			'count-layout' => "none",
			'data-url' => $url,
			'data-media' => $media,
			'data-description' => $desc
		), $attrs);
		
		return self::anchor('', "<span></span>", $attrs);
	}

	public static function fb_like_btn($newsfeed, $attrs = array()) {
		$attrs = array_merge(array(
			'send' => "false", 'width' => "90", 'layout' => "button_count", 'show_faces' => "false",
		), $attrs);
		$attr_str = '';

		foreach ($attrs as $key=>$val) {
			$attr_str .= ' '.$key.'="'.$val.'"';
		}
		return '<fb:like class="fb-like" href="'.Url_helper::base_url('/drop/'.$newsfeed->url).'" '.$attr_str.'></fb:like>';
	}

	public static function fb_share_btn($item, $attrs = array()) {
		
		//The title and description here are from og:title and og:description tags in drop page.
		if (!isset($attrs['class'])) $attrs['class'] = '';
		
		if ($item->_model instanceof Folder_model) {

			$attrs['class'] .= " fb_share_collection";
			$attrs['data-folder_id'] = $item->folder_id;
			$attrs['data-url'] = $item->folder_url;

			// var_dump(count($item->recent_newsfeeds));

			if (isset($item->recent_newsfeeds) && count($item->recent_newsfeeds) == 0)	{
				$attrs['class'] .= ' disabled_bg';
			}

		} elseif ($item->_model instanceof Newsfeed_model) {
			$attrs['class'] .= " share_fb_app";
			$attrs['data-newsfeed_id'] = $item->newsfeed_id;
		} else {
			die("Model not recognized: ".get_class($item->_model)." in html_helper");
		}

		// if the newsfeed was shared to facebook already, make it inactive
		if (get_instance()->user && $item->is_shared(get_instance()->user, 'fb')) $attrs['class'] .= " disabled_bg";

		return self::anchor("", '<span class="ico"></span>', $attrs);
	}

	public static function gplus($item, $attrs = array()) {
		//title and description are from the og: tags - the same as facebook
		if (!isset($attrs['class'])) $attrs['class'] = '';
		$attrs['class'] .= ' share_gplus_app share_btn';

		if (!$item->complete) $attrs['class'] .= " inactive";
		else if ($item->is_shared(get_instance()->user, 'gplus')) $attrs['class'] .= " inactive";
		
		return self::anchor('', '<span class="ico"></span>', $attrs);
	}

	public static function likedin($item, $attrs = array()) {
		//title and description are from the og: tags - the same as facebook
		if (!isset($attrs['class'])) $attrs['class'] = '';
		$attrs['class'] .= ' share_likedin_app share_btn';
		
		if (!$item->complete) $attrs['class'] .= " inactive";
		else if ($item->is_shared(get_instance()->user, 'linkedin')) $attrs['class'] .= " inactive";
		
		return self::anchor('', '<span class="ico"></span>', $attrs);
	}
	
	public static function _parse_attributes($attributes, $javascript = FALSE) {
		if (is_string($attributes)) {
			return ($attributes != '') ? ' '.$attributes : '';
		}

		$att = '';
		foreach ($attributes as $key => $val) {
			if ($javascript == TRUE) {
				$att .= "$key=$val,";
			} else {
				$att .= ' '.$key.'="'.str_replace('"', '\"', $val).'"';
			}
		}

		if ($javascript == TRUE AND $att != '') {
			$att = substr($att, 0, -1);
		}

		return $att;
	}
	
	public static function tag($tag, $attrs=array(), $content='') {
		$always_close = array('textarea','a','select');
		return '<'.$tag.' '.self::_parse_attributes($attrs)
					.($content || in_array(strtolower($tag), $always_close)
						? '>'.$content.'</'.$tag.'>'
						: ($tag == 'form' ? '>' : '/>')
					);
	}
	
	public static function anchor($uri = '', $title = '', $attributes = array()) {
		$title = (string) $title;

		if ( ! is_array($uri)) {
			$site_url = ( ! preg_match('!^\w+://! i', $uri) && @$uri[0] != '#') ? Url_helper::base_url($uri) : $uri;
		} else {
			$site_url = Url_helper::base_url().$uri;
		}

		$attributes = array_merge($attributes, array(
			'href' => $site_url,
		));

		return self::tag('a', $attributes, $title ? $title : $site_url);
	}
}
