<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_system_notification extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `system_notifications` ADD `template` TINYINT UNSIGNED NOT NULL AFTER `title`");
		mysql_query("ALTER TABLE  `users` ADD `system_notification` INT UNSIGNED NOT NULL AFTER `verified`");
		mysql_query("INSERT INTO system_notifications(id, template) VALUES (1, 1), (2, 2), (3, 3)");
		
		mysql_query("UPDATE users SET system_notification = 3");
		
		$res = mysql_query("SELECT id FROM users WHERE default_avatar_msg = 1");
		while ($row = mysql_fetch_object($res)) {
			mysql_query("UPDATE users SET system_notification = 0 WHERE id = ".$row->id);
		}
		$res = mysql_query("SELECT id FROM users WHERE friend_msg = 1");
		while ($row = mysql_fetch_object($res)) {
			mysql_query("UPDATE users SET system_notification = 1 WHERE id = ".$row->id);
		}
		$res = mysql_query("SELECT id FROM users WHERE password_msg = 1");
		while ($row = mysql_fetch_object($res)) {
			mysql_query("UPDATE users SET system_notification = 2 WHERE id = ".$row->id);
		}
		
		$this->dbforge->drop_column('users', 'default_avatar_msg');
		$this->dbforge->drop_column('users', 'friend_msg');
		$this->dbforge->drop_column('users', 'friends_array');
		$this->dbforge->drop_column('users', 'password_msg');
		$this->dbforge->drop_column('users', 'notification_time');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
