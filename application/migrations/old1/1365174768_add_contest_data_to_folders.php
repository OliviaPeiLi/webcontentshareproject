<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contest_data_to_folders extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` ADD  `ends_at` DATETIME NOT NULL,
					ADD  `info` TEXT NOT NULL");
		mysql_query("UPDATE folder SET ends_at = (SELECT MAX(ends_at) FROM newsfeed WHERE newsfeed.folder_id = folder.folder_id) WHERE `folder`.`type` > 0");
		mysql_query("ALTER TABLE `newsfeed` DROP `ends_at`");
		
		$content = '<h3>Prizes:</h3>
		<span class="prizeItem"><strong>1st prize:</strong> the Standing Cat Cup </span>
		<span class="prizeItem"><strong>2nd prize:</strong> Selection of x4 meme posters</span>
		<span class="prizeItem"><strong>3rd prize:</strong> Selection of x4 meme pin badges</span>';
		
		mysql_query("UPDATE folder SET ends_at = '2013-04-08 23:59:59', info = '$content' WHERE folder_uri_name = 'kittencamp'");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
