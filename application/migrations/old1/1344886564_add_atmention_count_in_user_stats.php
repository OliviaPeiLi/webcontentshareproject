<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_atmention_count_in_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'mention'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		/*
		$this->dbforge->add_column('comments', array(
			'newsfeed_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		mysql_query("CREATE INDEX newsfeed_id ON comments (newsfeed_id)");
		*/
		mysql_query("UPDATE user_stats SET user_stats.mention = (SELECT COUNT(notifications.id) FROM notifications WHERE notification.user_id_to = user_stats.user_id AND notifications.type = 'at_comm')");
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'user_id_to' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'time'=>array(
				'type' => 'TIMESTAMP'
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id_from');
		$this->dbforge->add_key('user_id_to');
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->create_table('mentions');
		
		
	}

	public function down()
	{
		
		$this->dbforge->drop_column('user_stats', 'mention');
		$this->dbforge->drop_table('mentions');
		//$this->dbforge->drop_column('comments', 'newsfeed_id');
		
	}
}
