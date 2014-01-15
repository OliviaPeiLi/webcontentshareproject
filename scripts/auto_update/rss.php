<?php
$new_server = true;
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';

include_once BASEPATH.'/../application/modules/fantoon-extensions/libraries/S3.php';

include_once BASEPATH.'/../application/modules/fantoon-extensions/libraries/Scraper.php';
include_once BASEPATH.'/../application/modules/fantoon-extensions/libraries/scraper/Scraper_rss.php';

include_once BASEPATH.'/core/Model.php';
include_once BASEPATH.'/../application/core/MY_Model.php';
include_once BASEPATH.'/../application/modules/newsfeed/models/newsfeed_model.php';
$newsfeed_model = new Newsfeed_model();

function generate_screenshot($source_id, $newsfeed_id, $link) {
	echo "		Generating screenshot: ";
	global $pheanstalk;
	$job_id = 0;
	if ($pheanstalk) {
		$data = array(
					'newsfeed_id' => $newsfeed_id,
					'rss_source_id' => $source_id,
					'link' => $link,
				);
		try {
			$pheanstalk->useTube("rss_src-".ENVIRONMENT);
			$job_id = $pheanstalk->put(json_encode($data));
			$beanstalk_model = get_instance()->load->model('beanstalk_job_model');
			$beanstalk_model->insert(array(
										'job_id'=>$job_id,
										'data'=>serialize($data),
										'type'=>'rss_src',
										'created_at'=>date("Y-m-d H:i:s")
									));
		} catch (Exception $e) { }
	}
	
	//Use secondary screenshot generator
	if ($job_id) {
		echo 'job_id: '.$job_id;
	} else {
		echo "secondary script";
		if (strpos(PHP_OS, 'WIN') !== false) {
			$os = 'windows';
		} elseif (strpos(PHP_OS, 'Linux') !== false) {
			$os = 'linux';
		} elseif (strpos(PHP_OS, 'Darwin') !== false) {
			$os = 'mac';
		} else {
			echo 'Operating system not recognized';
			exit();
		}
		$upd_conf = get_instance()->load->config('uploads');
		$filename = uniqid().'.jpg';
		$url = Url_helper::base_url('/bookmarklet/snapshot_preview/'.$newsfeed_id);
		$phantom_js = "js/tests/$os/phantomjs js/tests/rasterize.js";
		if ($os == 'windows') $phantom_js = str_replace('/', "\\", $phantom_js);
		$phantom_js .= " '$url' ".$upd_conf['path'].$filename;
		system($phantom_js, $rtn);
		get_instance()->newsfeed_model->update($newsfeed_id, array('img' => '/uploads/'.$filename, 'complete'=> true));
	}
	echo "\r\n";
	
	return $job_id;
}

function get_latest_version() {
	return filemtime(BASEPATH.'../scripts/auto_update/rss.php');
}

$latest = get_latest_version();
while (1) {
	if (get_latest_version() != $latest) {
		die("Restarting script to new version");
	}
	$start_time = time();
	$sources = mysql_query("SELECT * FROM `rss_sources` 
								WHERE update_on >= 1 
								AND updated_at < DATE_SUB(NOW(), INTERVAL update_on HOUR)
							ORDER BY updated_at ASC LIMIT 30");
	
	echo "\r\n";
	while ($source = mysql_fetch_object($sources)) {
		echo "Updating: ".$source->source."\r\n";
		mysql_query("UPDATE rss_sources SET updated_at = NOW() WHERE id = ".$source->id);
		
		$contents = file_get_contents($source->source);
		if (!$contents) continue;
		
		$scraper_rss = new Scraper_rss($contents);
		$data = $scraper_rss->get_images();
		//sort from older to newer articles so we can add them in the db in that order;
		$data = array_reverse($data);
		
		$folders = array();
		$folders_res = mysql_query("SELECT folder_id, user_id FROM folder WHERE rss_source_id = ".$source->id);
		while ($folder = mysql_fetch_object($folders_res)) $folders[] = $folder;
		
		foreach ($data as $article) {
			
			$cached = Scraper::get_cache($article['link']);
			if (isset($cached['content'])) {
				$content = $cached['content'];
			} else {
				$scraper = new Scraper();
				$content = $scraper->get_html($article['link']);
			}
			$article_uploaded = false;
			
			echo "	Article: ".$article['link']."\r\n";
			
			foreach ($folders as $folder) {	
				mysql_query("UPDATE folder SET updated_at = NOW() WHERE folder_id = ".$folder->folder_id);
				$valid_url = Url_helper::valid_url($article['link']);
				$has_it = mysql_fetch_row(mysql_query("SELECT 1 FROM newsfeed WHERE folder_id = ".$folder->folder_id." AND link_url = '$valid_url'"));
				if ($has_it) continue;
				
				$newsfeed_id = $newsfeed_model->insert(array(
					'type' => 'link',
					'link_type' => 'content',
					'activity' => array(
						'link' => array(
							'user_id_from' => $folder->user_id,
						)
					),
					'activity_user_id' => $folder->user_id,
					'user_id_from' => $folder->user_id,
					'folder_id' => $folder->folder_id,
					'orig_user_id' => $folder->user_id,
					'description' => $article['alt'],
					'link_url' => $article['link'],
					'complete' => '0',
				));
				
				if ($article_uploaded) {
					echo "		Copying S3 obj: $article_uploaded -> $newsfeed_id \r\n";
					if (!S3::copyObject(
								   get_instance()->config->item('s3_bucket'), 'uploads/screenshots/drop-'.$article_uploaded.'/index.php', 
								   get_instance()->config->item('s3_bucket'), 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php',
								   S3::ACL_PUBLIC_READ
					 )) {
					 	echo "[error] failed to copy content in s3 \r\n";
					 	continue;
					 }
					 $img = mysql_fetch_object(mysql_query("SELECT img FROM newsfeed WHERE newsfeed_id = ".$article_uploaded))->img;
					 mysql_query("UPDATE newsfeed SET img = '$img', complete = 1 WHERE newsfeed_id = '{$newsfeed_id}'");
				} else {
					echo "		Uploading to S3: $newsfeed_id \r\n";
					if ( ! S3::putObject($content, get_instance()->config->item('s3_bucket'), 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php', S3::ACL_PUBLIC_READ)) {
						echo "[error] failed to upload content to s3 \r\n";
						continue;
					}
					
					generate_screenshot($source->id, $newsfeed_id, $article['link']);
					
					$article_uploaded  = $newsfeed_id;
				}
				
			} //end folders
		} //end articles
		
	} //End sources
	$interval = 5*60;
	$end_time = time();
	
	if ($end_time < $start_time+$interval) {
		echo "Waiting: ".($interval - ($end_time - $start_time))."\r\n";
		sleep($interval - ($end_time - $start_time));
	}
}