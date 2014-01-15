<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_dimensions_to_database extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('pages', array(
			'avatar_width' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'avatar_height' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
		$this->dbforge->add_column('users', array(
			'avatar_width' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'avatar_height' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
		$this->dbforge->add_column('links', array(
			'img_width' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'img_height' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
		$this->dbforge->add_column('photos', array(
			'full_img_width' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'full_img_height' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('pages', 'avatar_width');
		$this->dbforge->drop_column('pages', 'avatar_height');
		
		$this->dbforge->drop_column('users', 'avatar_width');
		$this->dbforge->drop_column('users', 'avatar_height');
		
		$this->dbforge->drop_column('links', 'img_width');
		$this->dbforge->drop_column('links', 'img_height');
		
		$this->dbforge->drop_column('links', 'full_img_width');
		$this->dbforge->drop_column('links', 'full_img_height');
	}
}
