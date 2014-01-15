<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_orig_user_id_to_newsfeed_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'orig_user_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		mysql_query("UPDATE newsfeed SET newsfeed.orig_user_id = newsfeed.user_id_from");

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'orig_user_id');
	}
	
	
}
