<?php
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
   	define('BASE_URL', 'http://www.fandrop.com/');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
   	define('BASE_URL', 'http://test.fandrop.com/');
}else{
   	define('ENVIRONMENT', 'development');
   	if (strpos(__DIR__, 'fantoon.loc') !== false) {
   		define('BASE_URL', 'http://ft/');
   	} elseif (strpos(__DIR__, 'dmitry') !== false) {
   		define('BASE_URL', 'http://dmitry.fantoon.com/');
   	} elseif (strpos(__DIR__, 'ray') !== false) {
   		define('BASE_URL', 'http://ray.fantoon.com/');
   	}
}

if(ENVIRONMENT == 'staging'){
	define('BASEPATH', '/home/test.fandrop/current/system/');
}elseif(ENVIRONMENT == 'production'){
	define('BASEPATH', '/home/fandrop/current/system/');
}else{
	define('BASEPATH', __DIR__.'/../../system/');
}

//Libraries CI support
if ( ! function_exists('get_instance')) {
	$config = array();
	if ( ! function_exists('log_message')) {
		function log_message($msg) { echo "LOG: ".$msg."\t\n"; }
	}
	class CI_lang_lite {
		public function load($conf) {}
		public function line($val) { return false; }
	}
	class CI_lite {
		public $lang;
		public function __construct() { $this->lang = new CI_lang_lite(); }
	}
	$CI_lite = new CI_lite();
	function get_instance() {
		global $CI_lite;
		return $CI_lite;
	}
}
//End libraries support

include(BASEPATH.'../scripts/db.php');
include(BASEPATH.'../application/config/uploads.php');
include(BASEPATH.'../application/config/config.php');
include(BASEPATH.'../application/config/s3.php');
require_once BASEPATH.'libraries/Image_lib.php';
include BASEPATH.'../application/modules/fantoon-extensions/libraries/Scraper.php';
require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/S3.php';
//require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/cutycapt.php';
//$s3 = new S3($config);

$new_server = true;
include(BASEPATH.'../scripts/config.php');

/**
 * failsafe field values
 * 0 - default value field not yet checked by the script
 * 1 - passed failsafe script and job is already sent to screenshot server
 * 2 - passed failsafe script twice and two jobs are sent 
 * -1 - passed failsafe and the image is not blank
 */

if (isset($_SERVER['argv'][1])) {
$res = mysql_pquery("SELECT newsfeed_id, link_type, activity_id, user_id_from, failsafe, img, img_width, img_height, folder_id 
	FROM `newsfeed` 
	WHERE newsfeed_id = {$_SERVER['argv'][1]}
	ORDER BY newsfeed.time DESC LIMIT 1");
} else {
$res = mysql_pquery("SELECT newsfeed_id, link_type, activity_id, user_id_from, failsafe, img, img_width, img_height, folder_id
	FROM `newsfeed`  
	WHERE (
			img = 'load_clip.png' OR img = '/images/load_clip.png' 
			OR img_width < 10 OR img_height < 10 
			OR (img_width = 980 AND img_height = 1956 AND newsfeed.failsafe >= 0)	
		)  
		AND newsfeed.time < DATE_SUB(NOW(), INTERVAL 15 MINUTE)
		AND newsfeed.link_type <> 'text'
		AND newsfeed.failsafe <= 2
	ORDER BY newsfeed.time DESC LIMIT 30");
}

while ($row = mysql_fetch_assoc($res)) {
	echo "ID: Newsfeed:".$row['newsfeed_id']."\r\n";
	if($row['failsafe'] > -1 && $row['img_height'] == 1956 && !is_blank($row['img'])){
		echo "image is OK so setting filesafe to -1\r\n";
		if (!mysql_pquery("UPDATE newsfeed SET failsafe = -1 WHERE newsfeed_id = ".$row['newsfeed_id'])) {
			throw new Exception( 'MySql error: ' . mysql_error() );
		}
	} else if($row['failsafe'] == 0) { //Attepmt 1
		if (!mysql_pquery("UPDATE newsfeed SET failsafe = 1 WHERE newsfeed_id = ".$row['newsfeed_id'])) {
			throw new Exception( 'MySql error: ' . mysql_error() );
		}
		$job_id = add_job($row);
	} else if($row['failsafe'] == 1) { //Attempt 2
		if (!mysql_pquery("UPDATE newsfeed SET failsafe = 2, img = 'bad_drop.png', img_width = '500', img_height = '626' WHERE newsfeed_id = ".$row['newsfeed_id'])) {
			throw new Exception( 'MySql error: ' . mysql_error() );
		}
		$job_id = add_job($row);
	} else if($row['failsafe'] > 1){ //Attempt 3 only mark them bad_drop
		if (!mysql_pquery("UPDATE newsfeed SET img = 'bad_drop.png', img_width = '500', img_height = '626' WHERE newsfeed_id = ".$row['newsfeed_id'])) {
			throw new Exception( 'MySql error: ' . mysql_error() );
		}
	}
}

function check_html($newsfeed_id){
	global $config;
	if(!file_exists($config['s3_url'].'/uploads/screenshots/drop-'.$newsfeed_id.'/index.php')){
		
		$newsfeed = mysql_fetch_object(mysql_pquery("SELECT link_url FROM newsfeed WHERE newsfeed_id = {$newsfeed_id}"));
		
		$scraper = new Scraper();
		$content = $scraper->get_html($newsfeed->link_url);
		if (! S3::putObject($content, $config['s3_bucket'], 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php', S3::ACL_PUBLIC_READ)) {
			echo 'Cannot upload HTML to S3';
		}
	}
}

function get_source_image($link_id, $url=null){
	$link_res = mysql_fetch_object(mysql_pquery("SELECT link,source_img FROM links WHERE link_id = {$link_id}"));
	return $link_res->source_img;
}


function is_blank($img) {
	global $config;
	if (strpos($img, 'load_clip.png') !== false) {
		return true;
	}
	$head = get_headers($config['s3_url'].'/links/'.$img, true);
	if ($head[0] == 'HTTP/1.0 403 Forbidden') {
		$head = get_headers($config['s3_url'].'/links/'.str_replace('.', '_original.', $img), true);
	}
	return $head['Content-Length'] < 5120;
}

function add_job($newsfeed) {
	global $pheanstalk;
	$job_id = 0;
    if ($pheanstalk) {
    	
    	check_html($newsfeed['newsfeed_id']);
    	
		$data = array(
					'user_id'=>$newsfeed['user_id_from'],
					'id'=> $newsfeed['activity_id'],
					'newsfeed_id' => $newsfeed['newsfeed_id'],
					'link'=>BASE_URL.'bookmarklet/snapshot_preview/'.$newsfeed['newsfeed_id'],
					'folder_id'=>$newsfeed['folder_id']
				);
    	if ($newsfeed['link_type'] == 'content') {
    		echo "Bookmark page... ";
            try {
                $pheanstalk->useTube("scr-".ENVIRONMENT);
                $job_id = $pheanstalk->put(json_encode($data));
            } catch (Exception $e) { }
    	} elseif($newsfeed['link_type'] == 'image') {
	    	echo "rebuild image... ";
	    	$rebuild_img_request = BASE_URL.'bookmarklet/add_image_after/'.$newsfeed['newsfeed_id'];
	    	echo $rebuild_img_request;
	    	file_get_contents($rebuild_img_request);
    	} else {
    		echo "Html snapshot... ";
    		try {
	            $pheanstalk->useTube("ght-".ENVIRONMENT);
	            $job_id = $pheanstalk->put(json_encode($data));
	        } catch (Exception $e) { }
    	}
    }
    echo $job_id."\r\n";
    return $job_id;
}