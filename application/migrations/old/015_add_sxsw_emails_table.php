<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_sxsw_emails_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'code' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_emails');
		
	}
	
	
	public function down()
	{
	    $this->dbforge->drop_table('sxsw_emails');
	}
	
	
}




