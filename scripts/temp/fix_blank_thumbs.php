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
		echo "ERR: ".$obj->display_errors();
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
			echo "ERR: crop -> ".$obj->display_errors() . "\r\n";
			return false;
		}
	}
	return $file;
}
$thumbs = array(
	'thumb' 	=> array(500, 999999999, false),
	'tile' 		=> array(200, 999999999, false),
	'small' 	=> array(50, 999999999, false),
	'square' 	=> array(50, 50, true),
	'bigsquare' => array(112, 112, true)
);
$suff = '_thumb';
//copy links to links_tmp
//generate thumbs
//move img data back to links
//generate thumbs on the links table (for the recent ones which has '/' in the url)

//UPDATE links SET s3_img = (SELECT img FROM links_tmp WHERE link_id = links.link_id)
while (1) {
	$starttime = microtime(true);
	$res = mysql_query("SELECT link_id, img FROM links WHERE `thumb_generated` != 3 ORDER BY link_id DESC LIMIT 0,20");
	$endtime = microtime(true);
	$duration = $endtime - $starttime;
	if (!$res) break;
	$has_it = false;
	
	while ($row = mysql_fetch_assoc($res)) {
		if (!$row) break;
		if ($row['img'] == '') continue;
		$has_it = false;
		list($filename, $ext) = explode('.', $row['img']); $ext = '.'.$ext;
		echo "LINK ".$row['link_id'].": ";
		
		$contents = @file_get_contents($config['s3_url'].'/links/'.$filename.$suff.$ext);
		list($w, $h) = @getimagesize($config['s3_url'].'/links/'.$filename.$suff.$ext);
		echo $filename.$suff.$ext."\r\n";
		$err = false;
		if($contents)
		{
			if($w/$h > 1)
			{
				if($w > 1800 or $h > 900) {
					$err = true;
					echo "ERR: Wrong image dimensions: {$w}x{$h}\r\n";
				}
			} else
			{
				if($w > 900 or $h > 1800) {
					$err = true;
					echo "ERR: Wrong image dimensions: {$w}x{$h}\r\n";
				}
			}

			$handle = fopen("file", "w+"); 
			fwrite($handle, $contents); 
			fclose($handle);
			
			$filesize = round((@filesize('file')/1024), 2);
			unlink('file');
			if($filesize < 3) {
				$err = true;
				echo "ERR: Wrong image size: {$filesize}KB\r\n";
			}
			if($err)
			{
				$orig_file = @file_get_contents($config['s3_url'].'/links/'.$row['img']);
				if(!$orig_file)
				{
					echo "ERR: No orig file found\r\n";
				} else
				{
					file_put_contents($tmp_path.$filename.$ext, $orig_file);
					foreach($thumbs as $name => $conf)
					{
						$thumb = resize_link_image($tmp_path.$filename.$ext, $conf[0], $conf[1], $name, $conf[2]);
						if (strpos($thumb, '/') !== false) $thumb = substr($thumb, strrpos($thumb, '/')+1);
						S3::putObject(S3::inputFile($tmp_path.$thumb), $config['s3_bucket'], 'links/'.$thumb, S3::ACL_PUBLIC_READ);
						if(file_get_contents($tmp_path.$thumb)){
							echo "MSG: File ({$thumb}) was not generated;\r\n";
						}else{
							echo "MSG: File ({$thumb}) generated;\r\n";
						};
						unlink($tmp_path.$thumb);
					}
					unlink($tmp_path.$filename.$ext);
					mysql_query("UPDATE links SET thumb_generated = 3 WHERE link_id = ".$row['link_id']);
				}
			}
		} else
		{
			$err = true;
			echo "ERR: File not exist\r\n";
		}
		if(!$err) echo "MSG: OK\r\n";
		echo "----------\r\n";

	}
	$totalTime = microtime(true) - $starttime;
	echo "DB Query time: {$duration}\r\n";
	echo "Total time: {$totalTime}\r\n";
	//if ($err) break;
}
