<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Writes serialized images and video thumbnails to be used by the internal scraper
 * @author radilr
 *
 */
class Migration_Scraper_cache extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'link' => array(
				'type' => 'VARCHAR',
				'constraint' => '2048',
				'default' => 0,
			),
			'data' => array(
				'type' => 'TEXT',
			),
			'updated_at' => array(
				'type' => 'DATETIME'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('link');
		$this->dbforge->create_table('scraper_cache');
	}

	public function down()
	{
		$this->dbforge->drop_table('scraper_cache');
	}
}
