<?php
class Analytics {
	
	private $access_token_file;
	//ga:57076778  -Fandrop.com
	//ga:57733203  - radils localhost
	//ga:60720147  - Rays server
	//private $profile_id = 'ga:57733203';
	private $profile_id = 'ga:57076778';
	
	public function __construct() {
		$this->access_token_file = BASEPATH.'../uploads/snapshots/google_access_token';
		if (ENVIRONMENT == 'development') {
			$this->profile_id = 'ga:57733203';
		} elseif (ENVIRONMENT == 'staging') {
			$this->profile_id = 'ga:72431747';
		} else {
			$this->profile_id = 'ga:57076778';
		}
		require_once 'apiClient.php';
		require_once 'contrib/apiAnalyticsService.php';
	}
	
	public function setAccessToken($token) {
		file_put_contents($this->access_token_file, $token);
		die("Token written to: ".$this->access_token_file);
	}
	
	public function login($client=null, $code=null) {
		if (!$client) {
			$client = new apiClient();
			$client->setApplicationName("Fandrop");
			$service = new apiAnalyticsService($client);
		}
		if (!$code && file_exists($this->access_token_file)) {
			$credentials = file_get_contents($this->access_token_file);
			$client->setAccessToken(file_get_contents($this->access_token_file));
			$token = json_decode($client->getAccessToken());
			if ($token->created+60*30 < time()) { //refresh token every 30mins
				$client->refreshToken($token->refresh_token);
				file_put_contents($this->access_token_file, $client->getAccessToken());
			}
		} else {
			$code = TRUE;
		}
		
		if ($code) {
			print_r('AUTH: '.@$_GET['code'].'<br/>');
			$token = $client->authenticate();
			if ($token) {
				file_put_contents($this->access_token_file, $token);
				print_r($token);
			} else {
				die('Did not login');
			}
			die('Authenticated');
		}
		return $client;
	}
	
	//http://ga-dev-tools.appspot.com/explorer/
	public function uniq_pv() {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		//if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '36,000';
		
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$data = $service->data_ga->get($this->profile_id,'2012-01-01',date('Y-m-d', time()+60*60*24), 'ga:pageviews');
		return $data['totalsForAllResults']['ga:pageviews'];	
	}
	
	public function get_page($page) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(array('http://','https://', $_SERVER['HTTP_HOST']),'/', $page);
		
		/*
		$cached_data = get_instance()->db->query("SELECT id, val, updated_at FROM ga_cache WHERE func = 'get_page' AND param = '$page'")->result();
		if ($cached_data && strtotime($cached_data[0]->updated_at) > time()-3600) {
			return $cached_data[0]->val;
		}*/
			
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		try {
			$data = $service->data_ga->get($this->profile_id,'2012-01-01',date('Y-m-d', time()+60*60*24), 'ga:pageviews', array('filters'=>"ga:pagePath=~".$page."*"));
			$val = $data['totalsForAllResults']['ga:pageviews'];
		} catch (Exception $e) {
			$val = -1;
		}
		
		//if (!$cached_data) {
		//	get_instance()->db->query("INSERT INTO ga_cache (func, param, val, updated_at) VALUES ('get_page', '$page', $val, NOW())");
		//} else {
		//	get_instance()->db->query("UPDATE ga_cache SET val = $val, updated_at = NOW() WHERE id = ".$cached_data[0]->id);
		//}
		return $val;
	}
	
	public function get_page_timeline($page, $from, $to) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, 'ga:pageviews', array('filters'=>"ga:pagePath==".$page,'dimensions'=>'ga:date'));
			foreach ($data['rows'] as &$row) {
				$y = substr($row[0], 0,4); $m = substr($row[0], 4,2); $d = substr($row[0], 6,2);
				$row[0] = strtotime("$y-$m-$d") * 1000;
			}
			return $data['rows'];
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function get_uniqviews_timeline($page, $from, $to) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, 'ga:uniquePageviews', array('filters'=>"ga:pagePath==".$page,'dimensions'=>'ga:date'));
			foreach ($data['rows'] as &$row) {
				$y = substr($row[0], 0,4); $m = substr($row[0], 4,2); $d = substr($row[0], 6,2);
				$row[0] = strtotime("$y-$m-$d") * 1000;
			}
			return $data['rows'];
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function get_avgtimeonpage_timeline($page, $from, $to) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, 'ga:avgTimeOnPage', array('filters'=>"ga:pagePath==".$page,'dimensions'=>'ga:date'));
			foreach ($data['rows'] as &$row) {
				$y = substr($row[0], 0,4); $m = substr($row[0], 4,2); $d = substr($row[0], 6,2);
				$row[0] = strtotime("$y-$m-$d") * 1000;
			}
			return $data['rows'];
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function get_entrances_timeline($page, $from, $to) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, 'ga:entrances', array('filters'=>"ga:pagePath==".$page,'dimensions'=>'ga:date'));
			foreach ($data['rows'] as &$row) {
				$y = substr($row[0], 0,4); $m = substr($row[0], 4,2); $d = substr($row[0], 6,2);
				$row[0] = strtotime("$y-$m-$d") * 1000;
			}
			return $data['rows'];
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function get_exits_timeline($page, $from, $to) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, 'ga:exits', array('filters'=>"ga:pagePath==".$page,'dimensions'=>'ga:date'));
			foreach ($data['rows'] as &$row) {
				$y = substr($row[0], 0,4); $m = substr($row[0], 4,2); $d = substr($row[0], 6,2);
				$row[0] = strtotime("$y-$m-$d") * 1000;
			}
			return $data['rows'];
		} catch (Exception $e) {
			return array();
		}
	}
	
	//to get metrics data
	public function get_number($page, $metric) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		
		$cached_data = get_instance()->db->query("SELECT id, val, updated_at FROM ga_cache WHERE func = 'get_number' AND param = '$metric:$page'")->result();
		if ($cached_data && strtotime($cached_data[0]->updated_at) > time()-3600) {
			return $cached_data[0]->val;
		}
		
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		try {
			$data = $service->data_ga->get($this->profile_id,'2012-01-01',date('Y-m-d', time()+60*60*24), $metric, array('filters'=>"ga:pagePath==".$page));
			$val = $data['totalsForAllResults'][$metric];
			if (!$cached_data) {
				get_instance()->db->query("INSERT INTO ga_cache (func, param, val, updated_at) VALUES ('get_number', '$metric:$page', $val, NOW())");
			} else {
				get_instance()->db->query("UPDATE ga_cache SET val = $val, updated_at = NOW() WHERE id = ".$cached_data[0]->id);
			}
			return $val;
		} catch (Exception $e) {
			return $cached_data ? $cached_data[0]->val : 0;
		}	
	}
	
	
	public function get_page_graph($page=null, $from, $to, $metric, $dimension, $max_results=null) {
		global $apiConfig;
		list($url,) = explode('/', str_replace(array('http://','www.'), '', $apiConfig['oauth2_redirect_uri']));
		if (str_replace('www.', '', @$_SERVER['HTTP_HOST']) != $url) return '-1';
		$page = str_replace(Url_helper::base_url(),'/', $page);
		$client = new apiClient();
		$client->setApplicationName("Fandrop");
		$service = new apiAnalyticsService($client);
		$client = $this->login($client);
		
		$from = $from ? $from : '2012-01-01';
		$to = $to ? $to : date('Y-m-d', time()+60*60*24);
		$opts = array(
			'sort' => '-'.$metric
		);
		if ($page) $opts['filters'] = "ga:pagePath==".$page;
		if ($dimension) $opts['dimensions'] = $dimension;
		if ($max_results) $opts['max-results'] = $max_results;
		try {
			$data = $service->data_ga->get($this->profile_id,$from,$to, $metric, $opts);
			return isset($data['rows']) ? $data['rows'] : array();
		} catch (Exception $e) {
			return array();
		}
	}
	
}