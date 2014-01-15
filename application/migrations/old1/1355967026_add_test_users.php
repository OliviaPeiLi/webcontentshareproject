<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_test_users extends CI_Migration {

	public function up()
	{
		$res = mysql_query("SELECT id FROM users WHERE email = 'test.user1@example.com'");
		if ($res && $user_id = mysql_fetch_object($res)) {
			$user_id->id;
		} 
		if (!$user_id) {
			mysql_query("INSERT INTO users (first_name, last_name, uri_name, email, password) 
				VALUES ('Test', 'User 1', 'test_user1', 'test.user1@example.com', MD5('lFDvlksDF'))");
			$user_id = mysql_insert_id();
		}
		
		mysql_query("INSERT INTO user_stats (user_id) VALUES ($user_id)");
		mysql_query("INSERT INTO email_settings (user_id, connection) VALUES ($user_id, '0')");
		mysql_query("INSERT INTO user_visits (id, home, profile, interest, preview) VALUES ($user_id, '0', '0', '0', '0')");
		mysql_query("INSERT INTO loops (user_id, loop_name) VALUES($user_id, 'Friends')");
		
		
		
		$res = mysql_query("SELECT id FROM users WHERE email = 'test.user2@example.com'");
		if ($res && $user_id2 = mysql_fetch_object($res)) {
			$user_id2->id;
		}
		if (!$user_id2) {
			mysql_query("INSERT INTO users (first_name, last_name, uri_name, email, password) 
				VALUES ('Test', 'User 1', 'test_user2', 'test.user2@example.com', MD5('lkfdEFdxcXV'))");
			$user_id2 = mysql_insert_id();
		}
		mysql_query("INSERT INTO user_stats (user_id) VALUES ($user_id2)");
		mysql_query("INSERT INTO email_settings (user_id, connection) VALUES ($user_id2, '0')");
		mysql_query("INSERT INTO user_visits (id, home, profile, interest, preview) VALUES ($user_id2, '0', '0', '0', '0')");
		mysql_query("INSERT INTO loops (user_id, loop_name) VALUES($user_id2, 'Friends')");
		
		//Followers
		$power_user = mysql_query("SELECT id FROM users ORDER BY follower DESC LIMIt 1");
		$power_user = mysql_fetch_object($power_user)->id;
		mysql_query("INSERT INTO connections (user1_id, user2_id) VALUES($user_id, $user_id2)");
		mysql_query("INSERT INTO connections (user1_id, user2_id) VALUES($user_id, $power_user)");
		mysql_query("INSERT INTO connections (user1_id, user2_id) VALUES($user_id2, $user_id)");
		mysql_query("INSERT INTO connections (user1_id, user2_id) VALUES($user_id2, $power_user)");
		
	}

	public function down()
	{
		$res = mysql_query("SELECT id FROM users WHERE email = 'test.user1@example.com'");
		if (!$res) {
			exit("User not found");
		}
		$user_id = mysql_fetch_object($res)->id;
		
		mysql_query("DELETE FROM loops WHERE user_id = $user_id");
		mysql_query("DELETE FROM user_visits WHERE id = $user_id");
		mysql_query("DELETE FROM email_settings WHERE user_id = $user_id");
		mysql_query("DELETE FROM user_stats WHERE user_id = $user_id");
		mysql_query("DELETE FROM users WHERE id = $user_id");
		mysql_query("DELETE FROM connections WHERE user1_id = $user_id OR user2_id = $user_id");
		
		$res = mysql_query("SELECT id FROM users WHERE email = 'test.user2@example.com'");
		if (!$res) {
			exit("User not found");
		}
		$user_id = mysql_fetch_object($res)->id;
		
		mysql_query("DELETE FROM loops WHERE user_id = $user_id");
		mysql_query("DELETE FROM user_visits WHERE id = $user_id");
		mysql_query("DELETE FROM email_settings WHERE user_id = $user_id");
		mysql_query("DELETE FROM user_stats WHERE user_id = $user_id");
		mysql_query("DELETE FROM users WHERE id = $user_id");
		mysql_query("DELETE FROM connections WHERE user1_id = $user_id OR user2_id = $user_id");
		
		
	}
}
