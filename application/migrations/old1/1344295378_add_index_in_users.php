<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_index_in_users extends CI_Migration {

	public function up()
	{
		$this->db->query("ALTER TABLE  `users` ADD FULLTEXT  `name` (`first_name` ,`last_name` ,`uri_name` ,`email`)");
	}

	public function down()
	{
		$this->db->query("ALTER TABLE `users` DROP INDEX `name`");
	}
}
