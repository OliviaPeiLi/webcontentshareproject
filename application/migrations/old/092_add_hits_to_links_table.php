<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_hits_to_links_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('links', array(
			'hits'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		mysql_query("UPDATE links SET links.hits =  (SELECT newsfeed.hits FROM newsfeed WHERE newsfeed.activity_id = links.link_id AND newsfeed.type = 'link')");

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('links', 'hits');
	}
	
	
}
