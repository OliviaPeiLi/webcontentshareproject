<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_for_main_hashtags extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'hashtag_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		$main_hashtags = array('_hash_Aww','_hash_Celebs','_hash_Food','_hash_Funny','_hash_Gaming','_hash_Hot','_hash_Music','_hash_Sports','_hash_Tech','_hash_WTF');
		foreach($main_hashtags as $hashtag){
			
			//check hashtag
		    $hashtag_query = mysql_query("SELECT id FROM hashtags WHERE hashtag='".$hashtag."'");
		    $hashtag_data = mysql_fetch_object($hashtag_query);
		    if(!$hashtag_data){
		    	mysql_query("INSERT into hashtags (hashtag, count) VALUES ('".$hashtag."', 0)");
		    }
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'hashtag_id');
	}
}
