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
require_once BASEPATH.'../application/libraries/S3.php';
$s3 = new S3($config);

$tmp_path = BASEPATH.'../uploads/';

/* 1.
UPDATE  `photos` 
SET full_img = CONCAT('https://s3.amazonaws.com/fantoon-dev/links/',full_img)
WHERE  `full_img` NOT LIKE  '%/%'
LIMIT 100
 */

//2. deploy

/*
ALTER TABLE  `photos` ADD  `thumb_generated` TINYINT( 1 ) NOT NULL COMMENT  'Temporary field used forthumbs. Will be removed after they are generated.',
ADD INDEX (  `thumb_generated` )
 */

//3. this script

function resize_link_image($img, $width=null, $height=null, $thumb=false, $crop=false) {
	$obj = new CI_Image_lib();
	$conf = array(
		'image_library' => 'gd2',
		'source_image' => $img,
		'create_thumb' => TRUE,
		'thumb_marker' => '_'.$thumb,
		'maintain_ratio' => TRUE,
		'width' => $width,
		'height' => $height,
	);
	if ($crop) {
		list($w,$h) = getimagesize($img);
		if ($w > $h) $conf['width'] = 999999999; else $conf['height'] = 999999999;
	}
	$obj->initialize($conf);

	if ($obj->resize()) {
		$file = substr($img, 0, strrpos($img, '.')).'_'.$thumb.substr($img, strrpos($img, '.'));
	} else {
		echo "ERROR: ".$obj->display_errors();
		return  false;
	}
	if ($crop) {
		list($w,$h) = getimagesize($file);
		$obj = new CI_Image_lib();
		$obj->initialize(array(
			'maintain_ratio' => FALSE,
			'image_library' => 'gd2',
			'source_image' => $file,
			'width' => $width,
			'height' => $height,
			'x_axis' => ($w - $width)/2,
			'y_axis' => ($h - $height)/2
		));	
		if ($obj->crop()) {
			
		} else {
			echo "ERROR crop: ".$obj->display_errors();
			return false;
		}
	}
	return $file;
}

$thumbnails = array(
	'thumb'     => array(500, 999999999, false),
	'tile'      => array(200, 999999999, false),
	'small'     => array(50,  999999999, false),
	'square'    => array(50,  50,        true),
	'bigsquare' => array(112, 112,       true),
);

	$res = mysql_query("SELECT photo_id, img FROM photos WHERE thumb_generated = 0 ORDER BY photo_id DESC");
	if (!$res) break;
	while ($row = mysql_fetch_assoc($res)) {
		echo $config['s3_url'].'/photos/'.$row['img']."\r\n";
		if (! @fopen($config['s3_url'].'/photos/'.$row['img'],'r')) {
			echo "Copying photo: ".$row['photo_id']." \r\n";
			$contents = @file_get_contents($config['s3_url'].'/photos/'.$row['img']);
			file_put_contents($tmp_path.$row['img'], $contents);
			S3::putObject(S3::inputFile($tmp_path.$row['img']), $config['s3_bucket'], 'photos/'.$row['img'], S3::ACL_PUBLIC_READ);
			unlink($tmp_path.$row['img']);
			break;
		}
	}
	
/*
while (1) {
	$res = mysql_query("SELECT photo_id, img FROM photos WHERE thumb_generated = 0 ORDER BY photo_id DESC");
	$has_it = false;
	while ($row = mysql_fetch_assoc($res)) {
		if (!$row) break;
		if (strpos($row['img'], '/') === false) {
			echo "Already generated";
			mysql_query("UPDATE photos SET thumb_generated = 1 WHERE photo_id = ".$row['photo_id']." LIMIT 1");
			continue;
		}
		$has_it = true;
		$filename = substr($row['img'], strrpos($row['img'], '/')+1);
		list($filename, $ext) = explode('.', $filename); $ext = '.'.$ext;
		echo "\r\n PHOTO ".$row['photo_id']."\r\n";
		
		$contents = @file_get_contents($row['img']);
		if (!$contents) {
			echo "file not found! \r\n";
			mysql_query("UPDATE photos SET thumb_generated = 1 WHERE photo_id = ".$row['photo_id']." LIMIT 1");
			continue; 
		}
		file_put_contents($tmp_path.$filename.$ext, $contents);
		
		foreach ($thumbnails as $name=>$data) {
			$file = resize_link_image($tmp_path.$filename.$ext, $data[0], $data[1], $name, $data[2]);
			if (strpos($file, '/') !== false) $file = substr($file, strrpos($file, '/')+1);
			S3::putObject(S3::inputFile($tmp_path.$file), $config['s3_bucket'], 'photos/'.$file, S3::ACL_PUBLIC_READ);
			unlink($tmp_path.$file);
		}
		unlink($tmp_path.$filename.$ext);
			
		mysql_query("UPDATE photos SET thumb_generated = 1, img = '{$filename}{$ext}' WHERE photo_id = ".$row['photo_id']." LIMIT 1");
		echo "  updated \r\n";
	}
	if (!$has_it) break;
}
*/
