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
//require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/S3.php';
//require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/cutycapt.php';
//$s3 = new S3($config);

while(1) {
/*
	if (isset($_SERVER['argv'][1])) {
	$res = mysql_pquery("SELECT newsfeed.newsfeed_id, links.link_id, newsfeed.link_type, newsfeed.img, newsfeed.img_width, newsfeed.img_height, links.link, links.source_img, newsfeed.failsafe, newsfeed.user_id_from, newsfeed.folder_id 
		FROM `newsfeed` JOIN links ON (newsfeed.type='link' AND newsfeed.activity_id = links.link_id) 
		WHERE newsfeed_id = {$_SERVER['argv'][1]}
		ORDER BY newsfeed.time DESC LIMIT 1");
	} else {
*/
	$res = mysql_pquery("SELECT newsfeed.newsfeed_id, links.link_id, newsfeed.link_type, newsfeed.img, newsfeed.img_width, newsfeed.img_height, links.link, links.source_img, newsfeed.failsafe, newsfeed.user_id_from, newsfeed.folder_id
		FROM `newsfeed` JOIN links ON (newsfeed.type='link' AND newsfeed.activity_id = links.link_id)  
		WHERE ( newsfeed.img_width <= 10 AND newsfeed.img_height <= 10 ) 
			AND newsfeed.time < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
			AND newsfeed.link_type <> 'text'
		ORDER BY newsfeed.time DESC LIMIT 30");
//	}
	
	while ($row = mysql_fetch_assoc($res)) {
		echo "ID: Newsfeed:".$row['newsfeed_id']."\r\n";
		if($row['failsafe'] == 0){
			$job_id = add_job($row);
			if (!mysql_pquery("UPDATE newsfeed SET failsafe = 1 WHERE newsfeed_id = ".$row['newsfeed_id'])) {
				throw new Exception( 'MySql error: ' . mysql_error() );
			}
		}else{
			if (!mysql_pquery("UPDATE links SET img = 'bad_drop.png', img_width = '500', img_height = '626' WHERE link_id = ".$row['link_id'])) {
				throw new Exception( 'MySql error: ' . mysql_error() );
			}
			if (!mysql_pquery("UPDATE newsfeed SET img = 'bad_drop.png', img_width = '500', img_height = '626' WHERE newsfeed_id = ".$row['newsfeed_id'])) {
				throw new Exception( 'MySql error: ' . mysql_error() );
			}
		}
	}
	
	sleep(30);

}


function add_job($newsfeed)
{
	$job_id = 0;
    //if ($this->is_mod_enabled('new_server')) 
    $new_server = true;
    include(BASEPATH.'../scripts/config.php');
    if ($pheanstalk)
    {
        $data = array(
                    'user_id'=>$newsfeed['user_id_from'],
                    'id'=> $newsfeed['link_id'],
                    'link'=>BASE_URL.'bookmarklet/snapshot_preview/'.$newsfeed['newsfeed_id'],
                    'folder_id'=>$newsfeed['folder_id']
                );
        try
        {
            $pheanstalk->useTube("ght-".ENVIRONMENT);
            $job_id = $pheanstalk->put(json_encode($data));
            /*
            $this->load->model('beanstalk_job_model');
            $this->beanstalk_job_model->insert(array('job_id'=>$job_id,
                                               'data'=>serialize($data),
                                               'type'=>'ght',
                                               'created_at'=>date("Y-m-d H:i:s")));
            */
        }
        catch (Exception $e)
        {
            //If beanstalk is not accessible (never)
            //we will upload the image later
        }
    }
    return $job_id;
}