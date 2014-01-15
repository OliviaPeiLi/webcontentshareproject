<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_id_in_comments extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('comments', array(
			'newsfeed_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		mysql_query("CREATE INDEX newsfeed_id ON comments (newsfeed_id)");
		mysql_query("UPDATE comments SET comments.newsfeed_id = (SELECT newsfeed.newsfeed_id FROM newsfeed WHERE newsfeed.activity_id = comments.photo_id AND newsfeed.type = 'photo') WHERE comments.photo_id>0");
		mysql_query("UPDATE comments SET comments.newsfeed_id = (SELECT newsfeed.newsfeed_id FROM newsfeed WHERE newsfeed.activity_id = comments.link_id AND newsfeed.type = 'link') WHERE comments.link_id>0");
		
	}

	public function down()
	{
		$this->dbforge->drop_column('comments', 'newsfeed_id');
		
	}
}
