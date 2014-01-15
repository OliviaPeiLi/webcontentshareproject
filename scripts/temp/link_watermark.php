<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";

$newsfeeds = mysql_query("SELECT newsfeed_id, img FROM  `newsfeed` WHERE newsfeed_id > 23084005 AND link_type_id = 5 AND img_width >= 500 AND img_height >= 500 ORDER BY newsfeed_id ASC");
$has_more = false;
while($row = mysql_fetch_object($newsfeeds)) {
	echo "updating: ".$row->newsfeed_id."\n";
	if (strpos($row->img, '.gif') !== false) continue;
	get_instance()->newsfeed_model->update($row->newsfeed_id, array('img' => "https://d17tpoh2r6xvno.cloudfront.net/links/".str_replace('.', '_original.', $row->img)));
}
echo "Done \n";
