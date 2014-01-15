<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_fb_share_count extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'fb_share_count'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		$this->dbforge->add_column('folder', array(
			'fb_share_count'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		mysql_query("UPDATE folder SET folder.fb_share_count =  (SELECT COUNT(folder_id) FROM fb_drops WHERE fb_drops.folder_id = folder.folder_id)");
		
		mysql_query("UPDATE newsfeed SET newsfeed.fb_share_count =  (SELECT COUNT(newsfeed_id) FROM fb_drops WHERE fb_drops.newsfeed_id = newsfeed.newsfeed_id)");

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'fb_share_count');
		$this->dbforge->drop_column('collection', 'fb_share_count');
	}
	
	
}
