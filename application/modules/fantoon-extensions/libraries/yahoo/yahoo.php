<?php
/**
 * Driver for the yahoo api
 */

require 'oauth_helper.php';

class Yahoo {
	public $config_dev = array(
		'OAUTH_CONSUMER_KEY' => 'dj0yJmk9NmRlMjhuV3FybVZMJmQ9WVdrOWFEQk9NR3RJTjJrbWNHbzlNVGt5TVRZNE5UTTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD0zYg--',
		'OAUTH_CONSUMER_SECRET' => '41d348e5f8e4ff4007d5e806652850a29a65d9a8',
	);
	public $config_test = array(
		'OAUTH_CONSUMER_KEY' => 'dj0yJmk9QkhZVVo3TWRHeUwwJmQ9WVdrOWRuSnlZVTQzTjJVbWNHbzlNVEl3TVRjek9EYzJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD03Ng--',
		'OAUTH_CONSUMER_SECRET' => '4ecd6ba74d9ac9f26a0002000831f48563354ab5',
	);
	//production key
	public $config = array(
		'OAUTH_CONSUMER_KEY' => 'dj0yJmk9QmN3SFBiNk91TlNpJmQ9WVdrOWIxZE9XamRpTldNbWNHbzlNemt4T0RVMU9UWXkmcz1jb25zdW1lcnNlY3JldCZ4PWMw',
		'OAUTH_CONSUMER_SECRET' => '54c9ad57e4a01d79177eafc7ca8bbb32927d4850',
	);
	public $callback_url = '/yahoo_email_auth';
	public $guid; //yahoo user di
	public $access_token;
	public $access_token_secret;
	
	private $ci;
	
	public function __construct($config=array()) {
		$this->ci = get_instance();
		$this->callback_url = rtrim(Url_helper::base_url().'/').$this->callback_url;
		if (ENVIRONMENT == 'development') {
			$this->config = array_merge($this->config_dev, (array) $config);
		} else if (ENVIRONMENT == 'staging') {
			$this->config = array_merge($this->config_test, (array) $config);
		} else {
			$this->config = array_merge($this->config, (array) $config);
		}
		$session_data = $this->ci->session->userdata('yahoo_data');
		if ($session_data) {
			$this->guid = $session_data['guid'];
			$this->access_token = $session_data['access_token'];
			$this->access_token_secret = $session_data['access_token_secret'];
		}
	}
	
	public function login() {
		$url = $this->getAuthUrl();
		if (is_array($url)) {
			die(print_r($url));
		} else {
			header('Location:'.$url);
		}
	}
	
	public function removeAccess()	{
		
		$this->ci = get_instance();
		$this->ci->session->unset_userdata('yahoo_data');
		
	}
	
	public function getAuthUrl() {
		$url = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->config['OAUTH_CONSUMER_KEY'];
		$params['oauth_callback'] = $this->callback_url;

		// compute signature and add it to the params list
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_signature'] = oauth_compute_hmac_sig('GET', $url, $params, $this->config['OAUTH_CONSUMER_SECRET'], null);
		
		// Pass OAuth credentials in a separate header or in the query string
		$query_parameter_string = oauth_http_build_query($params, FALSE);
		
		$header = build_oauth_header($params, "yahooapis.com");
		$headers[] = $header;		

		// POST or GET the request
		$request_url = $url . ($query_parameter_string ? ('?' . $query_parameter_string) : '' );
		$response = do_get($request_url, 443, $headers);

		// extract successful response
		if (! empty($response)) {
			list($info, $header, $body) = $response;
			if ($info['http_code'] != 200) {
				return array(
					'status' => false,
					'error' => $body
				);
			} else {
				$body_parsed = oauth_parse_str($body);
				if ($info['http_code'] == 200 && !empty($body)) {
					$this->ci->session->set_userdata('yahoo_temp_data', array(
						'xoauth_request_auth_url' => rfc3986_decode($body_parsed['xoauth_request_auth_url']),
						'request_token' => $body_parsed['oauth_token'],
						'request_token_secret' => $body_parsed['oauth_token_secret'],
					));
					return 'https://api.login.yahoo.com/oauth/v2/request_auth?oauth_version=1.0'
							.'&oauth_consumer_key='.$this->config['OAUTH_CONSUMER_KEY']
							.'&oauth_token='.$body_parsed['oauth_token']
							.'&oauth_nonce='.mt_rand()
							.'&oauth_timestamp='.time()
							.'&crumb=e5C4LgZ9/Lk';
				}
			}
		}
	}
	
	public function getAccessToken() {
		if ($this->access_token) {
			return $this->access_token;
		}
		$yahoo_temp_data = $this->ci->session->userdata('yahoo_temp_data');
		if (!$yahoo_temp_data || !@$_GET['oauth_verifier']) {
			return $this->login();
		}
		
		$request_token = $yahoo_temp_data['request_token'];
		$request_token_secret = $yahoo_temp_data['request_token_secret'];
		$oauth_verifier= $_GET['oauth_verifier'];
					
		$url = 'https://api.login.yahoo.com/oauth/v2/get_token';
		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->config['OAUTH_CONSUMER_KEY'];
		$params['oauth_token']= $request_token;
		$params['oauth_verifier'] = $oauth_verifier;

		// compute signature and add it to the params list
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_signature'] = oauth_compute_hmac_sig('GET', $url, $params, $this->config['OAUTH_CONSUMER_SECRET'], $request_token_secret);
		

		// Pass OAuth credentials in a separate header or in the query string
		$query_parameter_string = oauth_http_build_query($params, false);
		$headers[] = build_oauth_header($params, "yahooapis.com");

		// POST or GET the request
		$request_url = $url . ($query_parameter_string ? ('?' . $query_parameter_string) : '' );
		$response = do_get($request_url, 443, $headers);
		// extract successful response
		if (! empty($response)) {
			list($info, $header, $body) = $response;
			$body_parsed = oauth_parse_str($body);
			if ($info['http_code'] == 200 && !empty($body)) {
				$this->guid = $body_parsed['xoauth_yahoo_guid'];
				$this->access_token = urldecode($body_parsed['oauth_token']);
				$this->access_token_secret = $body_parsed['oauth_token_secret'];
				
				$this->ci->session->set_userdata('yahoo_data', array(
					'guid' => $this->guid,
					'access_token' => $this->access_token,
					'access_token_secret' => $this->access_token_secret,
				));
			}
		}
	}
	
	public function get_contacts() {
		
		if (!$this->access_token) {
			$this->getAccessToken();
		}
		$response = array();

		$url = 'http://social.yahooapis.com/v1/user/' . $this->guid . '/contacts;count=200';
		$params['format'] = 'json';
		$params['view'] = 'compact';
		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->config['OAUTH_CONSUMER_KEY'];
		$params['oauth_token'] = $this->access_token;

		// compute hmac-sha1 signature and add it to the params list
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_signature'] = oauth_compute_hmac_sig('GET', $url, $params, $this->config['OAUTH_CONSUMER_SECRET'], $this->access_token_secret);

		$query_parameter_string = oauth_http_build_query($params, true);
		$headers[] = build_oauth_header($params, "yahooapis.com");
		
		// POST or GET the request
		$request_url = $url . ($query_parameter_string ? ('?' . $query_parameter_string) : '' );
		$response = do_get($request_url, 80, $headers);
		
		// extract successful response
		if (! empty($response))
		{
			list($info, $header, $body) = $response;
			$ret = array();
			
			$body_response = json_decode($body);
			
			// get error message if there is
			if (isset($body_response->error))	{
				// check if access token is expired
				if (strpos($body_response->error->description,"signature_invalid"))	{
					// error signature - access
					// $this->login();
				return -6; // error signature
				}
			}
			
			foreach (json_decode($body)->contacts->contact as $contact) {
				$email = ''; $name = '';
				foreach($contact->fields as $field)
				{
					if($field->type=='email') {
						$email = $field->value;
					}
					if($field->type=='name') {
						$name = $field->value->givenName.' '.$field->value->middleName.' '.$field->value->familyName;
					}
				}
				$ret[$email] = $name;
			}
			return $ret;
		}
	}
}