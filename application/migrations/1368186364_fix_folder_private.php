<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_folder_private extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` CHANGE `private` `private` TINYINT UNSIGNED NOT NULL,
					ADD INDEX ( `private` )");
		mysql_query("UPDATE folder SET private = private - 1 WHERE private > 0");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
