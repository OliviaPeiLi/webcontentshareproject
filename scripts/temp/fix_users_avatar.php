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

$tmp_path = BASEPATH.'../uploads/';

function resize_link_image($img, $width=null, $height=null, $thumb=false, $crop=false) {
	$obj = new CI_Image_lib();
	$obj->initialize(array(
		'image_library' => 'gd2',
		'source_image' => $img,
		'create_thumb' => TRUE,
		'thumb_marker' => '_'.$thumb,
		'maintain_ratio' => TRUE,
		'width' => $crop ? ($width >= $height ? $width : 10000) : $width,
		'height' => $crop ? ($width < $height ? $height : 10000) : $height,
		'no_enlarge' => true
	));

	if ($obj->resize()) {
		$file = substr($img, 0, strrpos($img, '.')).'_'.$thumb.substr($img, strrpos($img, '.'));
	} else {
		echo "ERROR: ".$obj->display_errors();
		return  false;
	}
	if ($crop) {
		$obj = new CI_Image_lib();
		$obj->initialize(array(
			'maintain_ratio' => FALSE,
			'image_library' => 'gd2',
			'source_image' => $file,
			'width' => $width,
			'height' => $height,
		));	
		if ($obj->crop()) {
			
		} else {
			echo "ERROR crop: ".$obj->display_errors();
			return false;
		}
	}
	return $file;
}

function get_fb_avatar($id){
	$facebookUrl = "https://graph.facebook.com/".$id; 
	$str = file_get_contents($facebookUrl); 
	$result = json_decode($str);  
	//var_dump($result->username); 
	$pic = 'https://graph.facebook.com/'.$result->username.'/picture?type=large';
	echo $pic;
	return $pic;
}

$res = mysql_query("SELECT id, avatar, fb_id FROM users WHERE fb_id > 0 AND political_view = '' ORDER BY id DESC LIMIT 100");
while ($row = mysql_fetch_assoc($res)) {
	mysql_query("UPDATE users SET political_view = 'none' WHERE id = ".$row['id']);
	$filename = $row['avatar'];
	if (! @fopen($config['s3_url'].'/users/'.$filename,'r')) {
		echo "\r\n USER ".$row['id'];
		file_put_contents($tmp_path.$filename, file_get_contents(get_fb_avatar($row['fb_id'])));
		echo "|file downloaded";
		$small = resize_link_image($tmp_path.$filename, 30, 30, 'small', true);
		$preview = resize_link_image($tmp_path.$filename, 50, 50, 'preview', true);
		$badge = resize_link_image($tmp_path.$filename, 80, 80, 'badge', true);
		$thumb = resize_link_image($tmp_path.$filename, 190, 500, 'thumb');
		foreach (array($filename, $small, $preview, $badge, $thumb) as $file) {
			if (strpos($file, '/') !== false) $file = substr($file, strrpos($file, '/')+1);
			if ( ! S3::putObject(S3::inputFile($tmp_path.$file), $config['s3_bucket'], 'users/'.$file, S3::ACL_PUBLIC_READ)) {
		    	echo "uploading to S3 error";
		    	continue;
			}
			unlink($tmp_path.$file);
		}
	}
	mysql_query("UPDATE users SET avatar = '$filename' WHERE id = ".$row['id']);
	echo " | updated";
}