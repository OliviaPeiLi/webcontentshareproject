<?php
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
		$_SERVER['HTTP_HOST'] = 'fandrop.com';
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
		$_SERVER['HTTP_HOST'] = 'test.fandrop.com';
	} else {
		define('ENVIRONMENT', 'development');
		$_SERVER['HTTP_HOST'] = 'localhost';
	}
}
if (!defined('BASEPATH')) {
	if(ENVIRONMENT == 'staging') {
		define('BASEPATH', '/home/test.fandrop/current/system/');
	} elseif(ENVIRONMENT == 'production') {
		define('BASEPATH', '/home/fandrop/current/system/');
	} else {
		define('BASEPATH', __DIR__.'/../../system/');
	}
}
if (!defined('APPPATH')) define('APPPATH', str_replace('system/', '', BASEPATH).'application/');

require_once APPPATH.'modules/fantoon-extensions/libraries/google/apiClient.php';
require_once APPPATH.'modules/fantoon-extensions/libraries/google/contrib/apiAnalyticsService.php';

$access_token_file = dirname(__FILE__).'/../../uploads/snapshots/google_access_token';

$client = new apiClient();
$client->setApplicationName("Fandrop");
$service = new apiAnalyticsService($client);

$client->setAccessToken(file_get_contents($access_token_file));

$token = json_decode($client->getAccessToken());
$client->refreshToken($token->refresh_token);

file_put_contents($access_token_file, $client->getAccessToken());