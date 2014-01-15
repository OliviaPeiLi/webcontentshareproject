<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_notification_id_to_sending_emails extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sending_emails', array(
			'notification_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('sending_emails', 'notification_id');
	}
}
