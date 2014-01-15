<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_drop_old_photos_table extends CI_Migration {

	public function up()
	{
        $this->dbforge->drop_table('photos');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'photo_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'thread_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'album_id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'user_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'page_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'photo_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => 0,
			),
			'full_url' => array(
				'type' => 'TEXT',
			),
			'thumb_url' => array(
				'type' => 'TEXT',
			),
			'photo_caption' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'friends_tags' => array(
				'type' => 'VARCHAR',
				'constraint' => '1024',
			),
			'pages_tags' => array(
				'type' => 'VARCHAR',
				'constraint' => '1024',
			),
			'tags' => array(
				'type' => 'TEXT',
			),
			'wiki_photo_url' => array(
				'type' => 'BLOB',
			),
			'ptime'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_key('photo_id', TRUE);
		$this->dbforge->create_table('photos');
	}
}
