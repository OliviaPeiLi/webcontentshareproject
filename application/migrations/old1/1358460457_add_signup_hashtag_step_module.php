<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_signup_hashtag_step_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('signup_hashtag_step',  '0',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='signup_hashtag_step'");
	}
}
