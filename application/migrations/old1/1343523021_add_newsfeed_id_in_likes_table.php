<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_id_in_likes_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('likes', array(
			'newsfeed_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));

		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}	
		mysql_query("CREATE INDEX newsfeed_id ON likes (newsfeed_id)");
		
		$res = mysql_query("SELECT like_id, photo_id, link_id 
								FROM likes 
								WHERE (photo_id > 0 OR link_id>0)
							");
		while ($row = mysql_fetch_object($res)) {
			if($row->link_id>0){
				mysql_query("UPDATE likes SET newsfeed_id = (SELECT newsfeed_id FROM newsfeed WHERE activity_id = $row->link_id AND type = 'link') WHERE like_id=$row->like_id");
			}elseif($row->photo_id>0){
				mysql_query("UPDATE likes SET newsfeed_id = (SELECT newsfeed_id FROM newsfeed WHERE activity_id = $row->photo_id AND type = 'photo') WHERE like_id=$row->like_id");
			}
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('likes', 'newsfeed_id');
	}
}
