<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_recent_newsfeeds_to_folders extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'recent_newsfeeds'=>array(
				'type'=>'text',
			)
		));
		$folders = mysql_query("SELECT folder_id FROM folder");
		while ($folder = mysql_fetch_assoc($folders)) {
			$res = mysql_query("
				SELECT newsfeed_id, newsfeed.time, IF (newsfeed.link_type='text', 'text', newsfeed.type) as type, links.link_id as activity_id, IF (newsfeed.link_type='text', links.content, links.title) as title, links.img as img FROM newsfeed 
					JOIN links ON (newsfeed.type='link' AND newsfeed.activity_id = links.link_id)
					WHERE newsfeed.folder_id = {$folder['folder_id']}
				UNION
				SELECT newsfeed_id, newsfeed.time, newsfeed.type, photos.photo_id as activity_id, photos.caption as title, photos.thumbnail as img FROM newsfeed
					JOIN photos ON (newsfeed.type='photo' AND newsfeed.activity_id = photos.photo_id)
					WHERE newsfeed.folder_id = {$folder['folder_id']}
				ORDER BY time DESC LIMIT 6");
			$arr = array();
			if ($res) while ($row = mysql_fetch_assoc($res)) {
				$row['title'] = strip_tags(substr($row['title'], 0, 40));
				$arr[] = $row;
			}
			mysql_query("UPDATE folder SET recent_newsfeeds = '".mysql_escape_string(serialize($arr))."' WHERE folder_id = {$folder['folder_id']}");
		}		
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'recent_newsfeeds');
	}
}
