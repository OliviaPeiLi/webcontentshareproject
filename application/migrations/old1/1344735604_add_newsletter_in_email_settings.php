<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsletter_in_email_settings extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('email_settings', array(
			'newsletter'=>array(
				'type' => 'ENUM',
				'constraint' => "'1','0'"
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('email_settings', 'newsletter');
	}
}
