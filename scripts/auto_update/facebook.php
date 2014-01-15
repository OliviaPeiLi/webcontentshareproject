<?php
$new_server = true;
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';

get_instance()->load->library('Facebook_driver');
get_instance()->load->library('S3');
get_instance()->load->library('Scraper');

function doJob($folder) {
	echo "Updating: ".$folder->folder_id."\r\n";
	$updated_at = strtotime($folder->updated_at);
	mysql_query("UPDATE folder SET updated_at = NOW() WHERE folder_id = ".$folder->folder_id);
	try {
		$facebook = new Facebook_driver(array('access_token'=>$folder->fb_token));
		$posts = $facebook->get_wall();
	} catch (Exception $e) {
		echo "Invalid token \r\n";
		mysql_query("UPDATE users SET fb_token = '' WHERE id = ".$folder->user_id);
		continue;
	} 
	$posts = array_reverse($posts); //Add the most recent post last
	
	foreach ($posts as $post) {
		if (isset($post['link'])) {
			echo "Link: ".$post['link']."\n";
			echo "compare to: ".Url_helper::base_url('drop')."\n";
			$valid_url = Url_helper::valid_url($post['link']);
			$has_it = get_instance()->newsfeed_model->count_by(array('folder_id' => $folder->folder_id, 'link_url' => $valid_url));
			if ($has_it) {
				echo "missing:already added \n";
				continue;
			}
		} elseif (strtotime($post['created_at']) < $updated_at) {
			echo "missing: ".$post['created_at']." already added time\r\n";
			continue;
		} 
		$data = array(
			'type' => 'link',
			'link_type' => $post['type'],
			'activity' => array(
				'link' => array(
					'user_id_from' => $folder->user_id,
				)
			),
			'activity_user_id' => $folder->user_id,
			'user_id_from' => $folder->user_id,
			'folder_id' => $folder->folder_id,
			'orig_user_id' => $folder->user_id,
			'description' => $post['title'],
			'link_url' => isset($post['link']) ? $post['link'] : '',
			'complete' => '0',
		);
		$content = false;
		if ($post['type'] == 'text') {
			$data['activity']['link']['content'] = $post['text'];
		} else {
			$scraper = new Scraper();
			$driver = $scraper->driver($post['link']);
			if (method_exists($driver, 'get_embed')) {
				$embed = $driver->get_embed();
			} else {
				$embed = false;
			}
			if ($embed) {
				$imgs = $driver->get_images();
				$data['link_type'] = 'embed';
				$data['activity']['link']['media'] = $embed;
				$data['img'] = $imgs[0]['src'];
			} elseif ($post['type'] == 'image') {
				$data['activity']['link']['source_img'] = $post['image'];
				$data['img'] = $post['image'];
			} elseif (strpos(str_replace('https://', 'http://', $post['link']),  Url_helper::base_url('drop')) !== false) {
				preg_match('#drop/([0-9]+)[$?]#', $post['link'], $matches);
				$id = $matches[1];
				echo "Redropping: ".$id." to folder: ".$folder->folder_id."\r\n";
				$orig_feed = get_instance()->newsfeed_model->get($id);
				if ($orig_feed) {
					$id = get_instance()->newsfeed_model->redrop($orig_feed, $folder->user_id, $folder->folder_id, array(
						'link_url' => $post['link']
					));
					echo "Redropped: ".$id."\r\n";
				} else {
					echo "  Original feed not found \r\n";
				}
				continue;
			} else { //content
				$content = $scraper->get_html($post['link']);
				if (is_array($content)) {
					print_r($content);
					continue;
				}
			}
		}
		
		$data['activity_id'] = get_instance()->link_model->insert($data['activity']['link']);
		unset($data['activity']);
		$newsfeed_id = get_instance()->newsfeed_model->insert($data);
		echo "Interted: ".$newsfeed_id."\r\n";
		if ($post['type'] == 'content' && $content) {
			echo "uploading to s3\r\n";
			if ( ! S3::putObject($content, get_instance()->config->item('s3_bucket'), 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php', S3::ACL_PUBLIC_READ)) {
				echo "Could not upload content to S3 \r\n";
				continue;
			}
			get_instance()->link_model->update($data['activity_id'], array('content' => 'uploaded to S3'));
		}
	}
}

function get_latest_version() {
	return filemtime(BASEPATH.'../scripts/auto_update/facebook.php');
}

$source_id = mysql_fetch_object(mysql_query("SELECT id FROM rss_sources WHERE source = 'facebook.com'"))->id;
echo "\r\nFacebook source: ".$source_id."\r\n";

if (isset($_SERVER['argv'][3])) {
	echo "Testing with: ".$_SERVER['argv'][3]."\n";
	$folder = mysql_fetch_object(mysql_query("SELECT folder_id, user_id, users.fb_token, folder.updated_at FROM folder 
							JOIN users ON (users.id = folder.user_id AND users.fb_token != '') 
							WHERE rss_source_id = {$source_id} AND user_id = {$_SERVER['argv'][3]}"));
	if (!$folder) {
		die("User folder not found\n");
	}
	doJob($folder);
	die("DONE\n");
}

$latest = get_latest_version();
while (1) {
	if (get_latest_version() != $latest) {
		die("Restarting script to new version");
	}
	$start_time = time();
	$folders = mysql_query("SELECT folder_id, user_id, users.fb_token, folder.updated_at FROM folder 
							JOIN users ON (users.id = folder.user_id AND users.fb_token != '') 
							WHERE rss_source_id = {$source_id} ORDER BY updated_at ASC LIMIT 300");
	$num_fb_collections = 0;
	while ($folder = mysql_fetch_object($folders)) {
		$num_fb_collections++;
		doJob($folder);
	}
	
	$interval = max(array(0, 60 - $num_fb_collections));
	$end_time = time();
	
	if ($end_time < $start_time+$interval) {
		echo "Waiting: ".($interval - ($end_time - $start_time))."\r\n";
		sleep($interval - ($end_time - $start_time));
	}
}