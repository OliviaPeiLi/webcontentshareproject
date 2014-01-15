<?php

$proxies = array( //http://hidemyass.com/proxy-list/search-226928
	//'177.103.230.173' => '3128',
	'203.191.150.11' => '8081',
	'211.167.112.18' => '80',
	'218.25.59.1' => '80',
	'89.22.236.172' => '8080',
	//'219.243.221.77' => '8080',
	'211.154.83.37' => '80',
	'211.167.112.16' => '82',
	'211.167.112.15' => '80',
	'211.154.83.37' => '82',
	//'94.180.114.186' => '3128',
	'61.55.141.10' => '80',
	'189.1.16.203' => '8080',
	'106.3.98.82' => '83',
	//'211.167.112.15' => '82',
	//'42.120.49.48' => '8000',
	'61.18.144.21' => '8909',
	'211.167.112.17' => '82',
/////////////
	'24.184.233.213' => '36455',
	'12.151.252.215' => '3128',
	'208.116.46.88' => '3128',
	//'199.119.76.59' => '3128',
	'68.205.142.144' => '17084',
	'216.25.32.242' => '3128', 
	'173.45.78.137' => '443',
	//'66.190.14.12' => '26815',
	//'219.243.220.13' => '8080',
	//'219.75.27.11' => '80',
	//'83.86.229.30' => '80',
	//'94.203.168.6' => '80',
	//'46.182.83.202' => '3128',
	//'222.255.134.76' => '3128',
	//'222.243.158.227' => '8123',
	//'88.86.95.98' => '8080',
	//'211.167.112.14' => '82',
	//'62.215.230.69' => '8080'
);

$agents = array(
	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
	'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:16.0) Gecko/20100101 Firefox/16.0 FirePHP/0.7.1',
	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
);

$urls = array(
	'http://www.fandrop.com/drop/19258444' => 'http://gorillamask.net/index.php',
);

function doRequest() {
	global $urls, $agents, $proxies;
	$referrer = array_rand($urls);
	$target = $urls[$referrer];
	$agent = $agents[array_rand($agents)];
	$proxy = array_rand($proxies);
	$port = $proxies[$proxy];
	echo "doRequest() at: ".date('H:i:s')."\r\n";
	echo "   Referrer: ".$referrer."\r\n";
	echo "   Target: ".$target."\r\n";
	echo "   Agent: ".$agent."\r\n";
	echo "   Proxy: ".$proxy.":".$port."\r\n";
	
	$url_arr = parse_url($target);
	$domain = @$url_arr['host'];
	
	$ch = curl_init(); 
		
	curl_setopt ($ch, CURLOPT_URL, $target); 
	curl_setopt ($ch, CURLOPT_HEADER, false); 
	curl_setopt ($ch, CURLOPT_FAILONERROR, TRUE); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
	
	curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt'); 
	curl_setopt($ch,CURLOPT_COOKIEFILE,'cookie.txt');
	
	if ($domain) {
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array(
			'Host: '.$domain,
		));
	}
				
	curl_setopt ($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt ($ch, CURLOPT_REFERER, $referrer);
	curl_setopt ($ch, CURLOPT_COOKIESESSION, TRUE);
	curl_setopt ($ch, CURLOPT_FORBID_REUSE, TRUE);
	curl_setopt ($ch, CURLOPT_PROXY, $proxy.":".$port);
	//curl_setopt($ch, CURLOPT_PORT, $port);	

	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec ($ch);
	
	//$this->_headers = curl_getinfo($ch);
	if (!$result) {
		echo "ERROR: ".curl_errno($ch).': '.curl_error($ch)."\r\n";
		unset($proxies[$proxy]);
		curl_close ($ch);
		return false;
	}
	curl_close ($ch);
	return true;
}


$limit = 2500;
$sleep_from=0;
$sleep_to=0;
for ($i=0;$i<$limit;$i++) {
	$start = microtime(true);
	if (doRequest()) {
		$req_time = microtime(true) - $start;
		$sleep = $sleep_to - round($req_time);
		if ($sleep > 0) $sleep = rand(round($sleep/3), round($sleep));
		echo "request done ".(round($req_time, 2))."s, sleeping ".$sleep."s \r\n"; 
		sleep($sleep);
	}
}