<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_user_stats_data extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE user_stats SET user_stats.mention = (SELECT COUNT(mentions.id) FROM mentions WHERE mentions.user_id_to = user_stats.user_id)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
