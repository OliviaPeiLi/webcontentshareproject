<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_parent_id_to_newsfeeds extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `parent_id` INT UNSIGNED NOT NULL AFTER  `newsfeed_id`,
					ADD INDEX (  `parent_id` )");
		$res = mysql_query("SELECT * FROM link_collects");
		while ($row = mysql_fetch_object($res)) {
			$parent = mysql_fetch_object(mysql_query("SELECT newsfeed_id FROM newsfeed WHERE activity_id = ".($row->link_id ? $row->link_id : $row->photo_id)));
			if (!$parent) continue;
			$newsfeed = mysql_fetch_object(mysql_query("SELECT newsfeed_id FROM newsfeed WHERE activity_id = ".$row->new_id));
			if (!$newsfeed) continue;
			mysql_query("UPDATE newsfeed SET parent_id = $parent->newsfeed_id WHERE newsfeed_id = $newsfeed->newsfeed_id");
		}
		$this->dbforge->drop_table('link_collects');
	}

	public function down()
	{
		  $this->dbforge->drop_column('newsfeed', 'parent_id');
	}
}
