<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_badge_id_in_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'badge_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		mysql_query("CREATE INDEX badge_id ON users (badge_id)");

	}

	public function down()
	{
		
		$this->dbforge->drop_column('users', 'badge_id');
		
	}
}
