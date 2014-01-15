<?php 
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
}else{
   	define('ENVIRONMENT', 'development');
}

if(ENVIRONMENT == 'staging'){
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(ENVIRONMENT == 'production'){
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
	define('BASEPATH', __DIR__.'/../../system/');
}
include(BASEPATH.'../scripts/config.php');
include(BASEPATH.'../application/config/config.php');
include(BASEPATH.'../application/config/s3.php');
require_once BASEPATH.'libraries/Image_lib.php';
include_once BASEPATH.'../scripts/db.php';
require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/S3.php';
$s3 = new S3($config);
$config['s3_url'] = 'https://s3.amazonaws.com/fantoon';

$tmp_path = BASEPATH.'../uploads/';


//copy links to links_tmp
//generate thumbs
//move img data back to links
//generate thumbs on the links table (for the recent ones which has '/' in the url)

//UPDATE links SET s3_img = (SELECT img FROM links_tmp WHERE link_id = links.link_id)
//while (1) {
	$res = mysql_query("SELECT photo_id, img FROM photos WHERE thumb_generated=0 ORDER BY photo_id DESC LIMIT 50");
	//if (!$res) break;
	while ($row = mysql_fetch_assoc($res)) {
		if (!$row) break;
		$has_it = true;
		list($filename, $ext) = explode('.', $row['img']); $ext = '.'.$ext;
		echo "\r\n Photo ".$row['photo_id']."\r\n";
		
		$image_types = array('','_thumb','_small','_title','_square','_bigsquare','_watermark');
		foreach($image_types as $type){
			$img = 'photos/'.$filename.$type.$ext;
			$new_img = 'links/'.$filename.$type.$ext;
			$info = $s3->getObjectInfo($config['s3_bucket'], $img, false);
			if($info){
				$s3->copyObject($config['s3_bucket'], $img, $config['s3_bucket'], $new_img, 'public-read');
			}
		}
		echo "file: ".$config['s3_url'].'/'.$img."\r\n";
			
		mysql_query("UPDATE photos SET thumb_generated = 1 WHERE photo_id = ".$row['photo_id']);
		echo "  updated \r\n";
	}
	echo '#############DONE#################';
//	sleep(30);
//}
