<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_gplus_and_linkedin1 extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed_shares` CHANGE  `api`  `api` ENUM(  'fb',  'twitter',  'pinterest',  'gplus',  'linkedin' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `newsfeed_shares` CHANGE  `api`  `api` ENUM(  'fb',  'twitter',  'pinterest' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}
	
}
