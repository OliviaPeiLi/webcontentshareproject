<?php
/**
 * This file appends retweets and reshares of sxsw posts to share counts
 * 
 * Twitter:
 * 		http://urls.api.twitter.com/1/urls/count.json?url=https://www.fandrop.com/drop/21021305
 * will return total tweets + retweets
 */
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';

if (ENVIRONMENT == 'production') {
	$base_url = "https://www.fandrop.com/drop/";
} elseif (ENVIRONMENT == 'staging') {
	$base_url = "https://test.fandrop.com/drop/";
} else {
	//$base_url = "https://localhost/drop/";
	$base_url = "https://www.fandrop.com/drop/";
}

$contest = mysql_fetch_object(mysql_query("SELECT id FROM contests WHERE url = 'cite'"));
echo "FNDemo contest: ".$contest->id."\r\n";
$drops = mysql_query("SELECT * FROM newsfeed WHERE folder_id IN (SELECT folder_id FROM folder WHERE contest_id = $contest->id)");

while ($drop = mysql_fetch_object($drops)) {
	echo "Fix drop: ".$drop->newsfeed_id."\r\n";
	
	$json = file_get_contents("http://urls.api.twitter.com/1/urls/count.json?url=".$base_url.$drop->url);
	$data = json_decode($json);
	if ($drop->twitter_share_count < $data->count) {
		$orig_count = mysql_fetch_object(mysql_query("SELECT COUNT(id) as num FROM newsfeed_shares WHERE api = 'twitter' AND newsfeed_id = ".$drop->newsfeed_id))->num;
		$new_count = $orig_count + min(array(25, $data->count - $orig_count));
		if ($drop->twitter_share_count < $new_count) {
			echo "	Twitter: ".$drop->twitter_share_count." -> ".$new_count."\r\n";
			mysql_query("UPDATE newsfeed SET twitter_share_count = ".$new_count." WHERE newsfeed_id = ".$drop->newsfeed_id);
		}
	}
	
	//https://graph.facebook.com/https://www.fandrop.com/drop/21089905
	//https://graph.facebook.com/fql?q=SELECT%20url,%20share_count,%20like_count%20FROM%20link_stat%20WHERE%20url='https://www.fandrop.com/drop/21089905'
	//test was done from henry gale reshared judys share - share count wasnt updated at all
	//liikin judy's post updated to count 1 instantly
	$json = file_get_contents("https://graph.facebook.com/".$base_url.$drop->url);
	$data = json_decode($json);
	if ($drop->fb_share_count < @$data->shares) {
		$orig_count = mysql_fetch_object(mysql_query("SELECT COUNT(id) as num FROM newsfeed_shares WHERE api = 'fb' AND newsfeed_id = ".$drop->newsfeed_id))->num;
		$new_count = $orig_count + min(array(25, $data->shares - $orig_count));
		if ($drop->fb_share_count < $new_count) {
			echo "	Facebook: ".$drop->fb_share_count." -> ".$new_count."\r\n";
			mysql_query("UPDATE newsfeed SET fb_share_count = ".$new_count." WHERE newsfeed_id = ".$drop->newsfeed_id);
		}
	}
	
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$base_url.'/'.$drop->url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec ($ch);
	curl_close ($ch);
	$json = json_decode($data);
	$count = $json[0]->result->metadata->globalCounts->count;
	if ($drop->gplus_share_count < $count) {
		$orig_count = mysql_fetch_object(mysql_query("SELECT COUNT(id) as num FROM newsfeed_shares WHERE api = 'gplus' AND newsfeed_id = ".$drop->newsfeed_id))->num;
		$new_count = $orig_count + min(array(25, $count - $orig_count));
		if ($drop->gplus_share_count < $new_count) {
			echo "	Gplus: ".$drop->gplus_share_count." -> ".$new_count."\r\n";
			mysql_query("UPDATE newsfeed SET gplus_share_count = ".$new_count." WHERE newsfeed_id = ".$drop->newsfeed_id);
		}
	}
	
	/*
	//http://api.pinterest.com/v1/urls/count.json?callback=?&url=https://www.fandrop.com/drop/maya-is-a-technology-design-research-lab-helping-organizations-harvest-value-in-the-age-of-trillions
	$json = @file_get_contents("http://api.pinterest.com/v1/urls/count.json?callback=?&url=".urlencode($base_url.$drop->url));
	$data = json_decode(trim($json, '?()')); //?() - is because pinterest returns jsonp
	if ($drop->pinterest_share_count < @$data->count) {
		$orig_count = mysql_fetch_object(mysql_query("SELECT COUNT(id) as num FROM newsfeed_shares WHERE api = 'pinterest' AND newsfeed_id = ".$drop->newsfeed_id))->num;
		$new_count = $orig_count + min(array(25, $data->count - $orig_count));
		if ($drop->pinterest_share_count < $new_count) {
			echo "	Pinterest: ".$drop->pinterest_share_count." -> ".$new_count."\r\n";
			mysql_query("UPDATE newsfeed SET pinterest_share_count = ".$new_count." WHERE newsfeed_id = ".$drop->newsfeed_id);
		}
	}
	
	$json = file_get_contents("http://www.linkedin.com/countserv/count/share?format=json&url=".$base_url.$drop->url);
	$data = json_decode($json);
	if ($drop->linkedin_share_count < $data->count) {
		$orig_count = mysql_fetch_object(mysql_query("SELECT COUNT(id) as num FROM newsfeed_shares WHERE api = 'linkedin' AND newsfeed_id = ".$drop->newsfeed_id))->num;
		$new_count = $orig_count + min(array(25, $data->count - $orig_count));
		if ($drop->linkedin_share_count < $new_count) {
			echo "	Linkedin: ".$drop->linkedin_share_count." -> ".$new_count."\r\n";
			mysql_query("UPDATE newsfeed SET linkedin_share_count = ".$new_count." WHERE newsfeed_id = ".$drop->newsfeed_id);
		}
	}
	*/
}