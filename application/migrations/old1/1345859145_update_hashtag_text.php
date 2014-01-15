<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_hashtag_text extends CI_Migration {

	public function up()
	{
		
		mysql_query("UPDATE comments SET comment = REPLACE(comment,'#','_hash_')");
		mysql_query("UPDATE photos SET caption = REPLACE(caption,'#','_hash_')");
		mysql_query("UPDATE links SET text = REPLACE(text,'#','_hash_')");
		
	}

	public function down()
	{
		mysql_query("UPDATE comments SET comment = REPLACE(comment,'_hash_','#')");
		mysql_query("UPDATE photos SET caption = REPLACE(caption,'_hash_','#')");
		mysql_query("UPDATE links SET text = REPLACE(text,'_hash_','#')");
	}
}
