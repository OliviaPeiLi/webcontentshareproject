<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_fix_public_collection_counts extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE user_stats SET user_stats.public_collections =  (SELECT COUNT(folder_id) FROM folder WHERE folder.user_id = user_stats.user_id AND folder.private = '0') + (SELECT COUNT(folder_id) FROM folder_contributors WHERE folder_contributors.user_id = user_stats.user_id)");

	}
	
	
	public function down()
	{

		
	}
	
	
}
