<?php
$new_server = true;
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';

get_instance()->load->library('Twitter');
get_instance()->load->library('S3');
get_instance()->load->library('Scraper');

function doJob($folder) {
	$updated_at = strtotime($folder->updated_at);
	echo "Updating: ".$folder->folder_id." Twitter id: ".$folder->twitter_id."\r\n";
	mysql_query("UPDATE folder SET updated_at = NOW() WHERE folder_id = ".$folder->folder_id);
	
	$twitter = new Twitter(array('user_id' => $folder->twitter_id));
	$posts = $twitter->get_wall();
	$posts = array_reverse($posts); //Add the most recent post last
	
	foreach ($posts as $post) {
		if (isset($post['link'])) {
			$valid_url = Url_helper::valid_url($post['link']);
			$has_it = get_instance()->newsfeed_model->count_by(array('folder_id' => $folder->folder_id, 'link_url' => $valid_url));
			if ($has_it) {
				echo "missing: ".$post['link']." already added \r\n";
				continue;
			}
		} elseif (strtotime($post['created_at']) < $updated_at) {
			echo "missing: ".@$post['link']." ".$post['created_at']." already added updated\r\n";
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
		echo "Inserted: ".$newsfeed_id."\r\n";
		if ($post['type'] == 'content' && $content) {
			echo "uploading to s3\r\n";
			if ( ! S3::putObject($content, get_instance()->config->item('s3_bucket'), 'uploads/screenshots/drop-'.$newsfeed_id.'/index.php', S3::ACL_PUBLIC_READ)) {
				echo "Could not upload content to S3 \r\n";
				continue;
			}
			get_instance()->link_model->update($data['activity_id'], array('content' => 'uploaded to S3'));
		}
	}
	return true;
}

function get_latest_version() {
	return filemtime(BASEPATH.'../scripts/auto_update/twitter.php');
}

$source_id = mysql_fetch_object(mysql_query("SELECT id FROM rss_sources WHERE source = 'twitter.com'"))->id;
echo "\r\Twitter source: ".$source_id."\r\n";


if (isset($_SERVER['argv'][3])) {
	echo "Testing with: ".$_SERVER['argv'][3]."\n";
	$folder = mysql_fetch_object(mysql_query("SELECT folder_id, user_id, users.twitter_id, folder.updated_at FROM folder 
							JOIN users ON (users.id = folder.user_id AND users.twitter_id != '') 
							WHERE rss_source_id = {$source_id} AND user_id = {$_SERVER['argv'][3]}"));
	if (!$folder) {
		die("User folder not found\n");
	}
	doJob($folder);
	die("DONE\n");
}

$latest = get_latest_version();
$num_collections = 0;
while (1) {
	if (get_latest_version() != $latest) {
		die("Restarting script to new version");
	}
	$start_time = time();
	$folders = mysql_query("SELECT folder_id, user_id, users.twitter_id, folder.updated_at FROM folder 
							JOIN users ON (users.id = folder.user_id AND users.twitter_id != '') 
							WHERE rss_source_id = {$source_id} ORDER BY updated_at ASC LIMIT 140"); //twitter limit is 150 leaving 10 for new users
	$num_collections = 0;
	while ($folder = mysql_fetch_object($folders)) {
		$num_collections++;
		doJob($folder);
	}
	
	echo "Waiting 1 hour \n";
	//Twitter limit is 150 request per hour
	while (time() > $start_time + 3600) { //Checking for new users while waiting hour
		if ($num_collections < 150) {
			echo "Checking for new users\n";
			$folders = mysql_query("SELECT folder_id, user_id, users.twitter_id, folder.updated_at FROM folder 
								JOIN users ON (users.id = folder.user_id AND users.twitter_id != '') 
								WHERE rss_source_id = {$source_id} AND updated_at = '0000-00-00 00:00:00' ORDER BY updated_at ASC LIMIT 10");
			while ($folder = mysql_fetch_object($folders)) {
				echo "Adding a new user \n";
				$num_collections++;
				doJob($folder);
			}
		}
		sleep(60);
	}

}