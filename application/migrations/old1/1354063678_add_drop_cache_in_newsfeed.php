<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_drop_cache_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'user_drops'=>array(
				'type'=>'TEXT',
				'default'=>''
			)
		));
		$this->dbforge->add_column('newsfeed', array(
			'source_drops'=>array(
				'type'=>'TEXT',
				'default'=>''
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('newsfeed', 'user_drops');
		$this->dbforge->drop_column('newsfeed', 'source_drops');

	}
}
