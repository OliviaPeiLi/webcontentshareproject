<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_collaborator_emails_table extends CI_Migration {

	public function up()
	{
		/*$this->dbforge->add_column('users', array(
			'role'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
		
		mysql_query("ALTER TABLE  `newsfeed` ADD  `is_ranked` TINYINT UNSIGNED NOT NULL AFTER  `news_rank` ,
					ADD  `ranked_at` DATETIME NOT NULL AFTER  `is_ranked` ,
					ADD INDEX (  `is_ranked` )");
		*/
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
