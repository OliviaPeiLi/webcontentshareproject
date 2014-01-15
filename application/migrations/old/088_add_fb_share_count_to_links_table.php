<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_fb_share_count_to_links_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('links', array(
			'fb_share_count'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		mysql_query("UPDATE links SET links.fb_share_count =  (SELECT newsfeed.fb_share_count FROM newsfeed WHERE newsfeed.activity_id = links.link_id AND newsfeed.type = 'link')");

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('links', 'fb_share_count');
	}
	
	
}
