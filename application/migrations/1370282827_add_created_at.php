<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_created_at extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` ADD  `created_at` DATETIME NOT NULL AFTER  `updated_at`");
		mysql_query("UPDATE folder SET created_at = updated_at");
	}

	public function down()
	{
	}
}
