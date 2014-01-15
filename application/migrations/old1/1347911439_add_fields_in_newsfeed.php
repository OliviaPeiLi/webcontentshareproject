<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_fields_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'title'=>array(
				'type'=>'VARCHAR',
				'constraint'=>200,
				'default'=>''
			)
		));
		
		$this->dbforge->add_column('newsfeed', array(
			'description'=>array(
				'type'=>'TEXT',
				'default'=>''
			)
		));
		
		$this->dbforge->add_column('newsfeed', array(
			'img'=>array(
				'type'=>'VARCHAR',
				'constraint'=>200,
				'default'=>''
			)
		));
		
		$this->dbforge->add_column('newsfeed', array(
			'comments_cache'=>array(
				'type'=>'TEXT',
				'default'=>''
			)
		));
		
		$this->dbforge->add_column('newsfeed', array(
			'likes_cache'=>array(
				'type'=>'TEXT',
				'default'=>''
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('newsfeed', 'title');
		$this->dbforge->drop_column('newsfeed', 'description');
		$this->dbforge->drop_column('newsfeed', 'img');
		$this->dbforge->drop_column('newsfeed', 'comments_cache');
		$this->dbforge->drop_column('newsfeed', 'likes_cache');

	}
}
