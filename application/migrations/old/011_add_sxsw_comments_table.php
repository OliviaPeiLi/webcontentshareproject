<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_sxsw_comments_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'link_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'comment' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_comments');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('sxsw_comments');
	}
	
	
}
