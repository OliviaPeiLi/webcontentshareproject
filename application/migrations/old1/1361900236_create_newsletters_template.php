<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_newsletters_template extends CI_Migration {

	public function up()
	{
/*		$this->dbforge->add_column('newsletters', array(
			'role'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));*/
		
		mysql_query("ALTER TABLE  `newsletters` ADD  `json_data_tmpl` TEXT AFTER `msg`");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
