<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_full_name_in_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'full_name'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
				'after' => 'last_name'
			),
		));
		mysql_query("ADD FULLTEXT `full_name` (  `full_name` )");
		mysql_query("UPDATE users SET full_name = CONCAT(first_name, ' ' ,last_name)");
	}

	public function down()
	{
		
		mysql_query("ALTER TABLE `users` DROP `full_name`");
		
	}
}
