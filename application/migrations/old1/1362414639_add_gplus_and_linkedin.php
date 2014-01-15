<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_gplus_and_linkedin extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `gplus_share_count` INT UNSIGNED NOT NULL AFTER  `twitter_share_count` ,
					ADD  `linkedin_share_count`INT UNSIGNED NOT NULL AFTER `twitter_share_count`");
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'gplus_share_count');
		$this->dbforge->drop_column('newsfeed', 'linkedin_share_count');
	}
}
