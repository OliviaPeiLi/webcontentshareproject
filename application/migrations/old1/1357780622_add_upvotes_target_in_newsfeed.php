<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_upvotes_target_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'up_target'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		mysql_query("ALTER TABLE  `newsfeed` ADD INDEX (  `up_count` )");
		mysql_query("ALTER TABLE  `newsfeed` ADD INDEX (  `up_target` )");
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'up_target');
	}
}
