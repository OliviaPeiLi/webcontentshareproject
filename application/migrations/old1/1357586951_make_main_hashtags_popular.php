<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Make_main_hashtags_popular extends CI_Migration {

	public function up()
	{		
		$main_hashtags = array('_hash_Aww','_hash_Celebs','_hash_Food','_hash_Funny','_hash_Gaming','_hash_Hot','_hash_Music','_hash_Sports','_hash_Tech','_hash_WTF');
		foreach($main_hashtags as $hashtag){
			
			//check hashtag
		    $hashtag_query = mysql_query("SELECT id FROM hashtags WHERE hashtag='".$hashtag."'");
		    $hashtag_data = mysql_fetch_object($hashtag_query);
		    if($hashtag_data){
		    	mysql_query("UPDATE  hashtags SET count=300 WHERE id=".$hashtag_data->id);
		    }else{
			    mysql_query("INSERT into hashtags (hashtag, count) VALUES ('".$hashtag."', 300)");
		    }
		}
	}

	public function down()
	{

	}
}
