<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sxsw_user extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO users (first_name, last_name, uri_name, role) VALUES('SXSW','SXSW', 'winsxsw', 4)");
		$user_id = mysql_insert_id();
		mysql_query("INSERT INTO user_stats (user_id) VALUES ($user_id)");
		
		mysql_query("UPDATE folders SET user_id = $user_id WHERE type = 1");
		mysql_query("UPDATE links SET user_id_from = $user_id WHERE link_id IN (
						SELECT activity_id FROM newsfeed WHERE folder_id IN (
							SELECT folder_id FROM folder WHERE type = 1
						)
					)");
		mysql_query("UPDATE newsfeed SET activity_user_id = $user_id, user_id_from = $user_id, orig_user_id = $user_id
						WHERE folder_id IN (
							SELECT folder_id FROM folder WHERE type = 1
						)");
		
		mysql_query("UPDATE folder SET 
						folder_name = 'SXSW Influencer Award',
						folder_uri_name = 'SXSW_Influencer_Award',
						sort_by=2,
						user_id = $user_id
					WHERE folder_uri_name = 'winsxsw'");
		
		mysql_query("INSERT INTO folder (type, user_id, folder_name, folder_uri_name, sort_by) 
				VALUES (1, $user_id, 'Investor\'s Choice', 'Investors_Choice', 2)"); 
		mysql_query("INSERT INTO folder (type, user_id, folder_name, folder_uri_name, sort_by) 
				VALUES (1, $user_id, 'VentureBeat Favorite', 'VentureBeat_Favorite', 2)"); 
		mysql_query("INSERT INTO folder (type, user_id, folder_name, folder_uri_name, sort_by) 
				VALUES (1, $user_id, 'Most Creative', 'Most_Creative', 2)"); 
		mysql_query("INSERT INTO folder (type, user_id, folder_name, folder_uri_name, sort_by) 
				VALUES (1, $user_id, 'Best Bootstrapped', 'Best_Bootstrapped', 2)"); 
		mysql_query("INSERT INTO folder (type, user_id, folder_name, folder_uri_name, sort_by) 
				VALUES (1, $user_id, 'Best Mobile', 'Best_Mobile', 2)"); 
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
