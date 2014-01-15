<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_beanstalk_jobs_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'job_id' => array(
				'type' => 'BIGINT',
				'constraint' => '20',
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '20',
			),
			'data' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
			'created_at' => array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			),
			'started_at' => array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			),
			'finished_at' => array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('job_id');
		$this->dbforge->add_key('type');
		$this->dbforge->create_table('beanstalk_jobs');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('beanstalk_jobs');
	}
	
	
}
