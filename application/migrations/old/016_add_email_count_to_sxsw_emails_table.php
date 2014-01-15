<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_email_count_to_sxsw_emails_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_emails', array(
			'email_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_emails', 'email_count');
	}
}
