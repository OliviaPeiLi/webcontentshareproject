<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_type_counts_to_user_stats extends CI_Migration {

	public function up()
	{		
		$this->dbforge->add_column('user_stats', array(
			'image' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'text' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'screenshot' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'video' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'clip' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
		//for clip
		mysql_query("UPDATE user_stats SET user_stats.clip =  (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.user_id_from = user_stats.user_id AND newsfeed.link_type = 'html')");
		//for screenshot
		mysql_query("UPDATE user_stats SET user_stats.screenshot =  (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.user_id_from = user_stats.user_id AND (newsfeed.link_type = 'link' OR newsfeed.link_type = 'content'))");
		//for video
		mysql_query("UPDATE user_stats SET user_stats.video =  (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.user_id_from = user_stats.user_id AND (newsfeed.link_type = 'embed' OR newsfeed.link_type = 'media_link'))");
		//for image
		mysql_query("UPDATE user_stats SET user_stats.image =  (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.user_id_from = user_stats.user_id AND (newsfeed.link_type = 'image' OR newsfeed.type = 'photo'))");
		//for text
		mysql_query("UPDATE user_stats SET user_stats.text =  (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.user_id_from = user_stats.user_id AND newsfeed.link_type = 'screen')");
		
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'image');
		$this->dbforge->drop_column('user_stats', 'text');
		$this->dbforge->drop_column('user_stats', 'screenshot');
		$this->dbforge->drop_column('user_stats', 'video');
		$this->dbforge->drop_column('user_stats', 'clip');
	}
}
