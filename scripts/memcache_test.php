<?
define('BASEPATH', __DIR__.'/../system/');
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
	} else {
		define('ENVIRONMENT', 'development');
	}
}

class Config {
	public $config;
	public function load($file) {
		include BASEPATH.'../application/config/'.$file.'.php';
		$this->config[$file] = $config;
		return true;
	}
}
class codeIgniter {
	public $config;
	public function __construct() {
		$this->config = new Config();
	}
}
$ci = new codeIgniter();

function get_instance() {
	global $ci;
	return $ci;
}

function log_message($type, $msg) {
	echo $type.": ".$msg."\r\n";
}

function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$size = strlen( $chars );
	$str = '';
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	return $str;
}

class CI_Driver {}
require_once BASEPATH.'libraries/Cache/drivers/Cache_memcached.php';

$cache = new CI_Cache_memcached();

if (!$cache->is_supported()) {
	echo "Memache not supported";
	die();
}

$str = rand_string(100); //100b
echo "Config: ";
print_r($ci->config->config['memcached']);

//print_r($cache->_memcached->getServerList());
//echo "Info: "; var_dump($cache->cache_info()); echo "\r\n";
//$str = rand_string(100*1000); //100k

$start = microtime(true);
$res = $cache->save('app2', $str);
echo "Write 100k: res - ".($res ? 'true' : 'false')." time: ".(microtime(true)-$start)." \r\n";

$start = microtime(true);
$res = $cache->get('app2');
echo "Read res - ".($res ? ($res==$str ? 'true' : 'err') : 'false')." time: ".(microtime(true)-$start)." \r\n";

$start = microtime(true);
$res = $cache->delete('app2');
echo "Delete res - ".($res ? 'true' : 'false')." time: ".(microtime(true)-$start)." \r\n";


if ($sec = $cache->get('second')) {
	echo "second already set: ".$sec."\r\n";
	$cache->delete('second');
} else {
	$start = microtime(true);
	$res = $cache->save('second', $str);
	echo "Wrote second ".($res ? 'true' : 'false')." time: ".(microtime(true)-$start)." \r\n";
}