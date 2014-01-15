<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsletter_time_to_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'newsletter_time'=>array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			),
		));
		$this->dbforge->drop_column('newsletters', 'time');
		$this->dbforge->add_column('newsletters', array(
			'newsletter_time'=>array(
				'type' => 'TIMESTAMP'
			),
		));
		mysql_query("CREATE INDEX newsletter_time ON users (newsletter_time)");
		mysql_query("CREATE INDEX newsletter_time ON newsletters (newsletter_time)");
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'newsletter_time');
	}
}
