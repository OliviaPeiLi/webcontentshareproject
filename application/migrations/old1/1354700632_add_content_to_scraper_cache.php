<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_content_to_scraper_cache extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `scraper_cache` ADD  `content` LONGTEXT NOT NULL AFTER  `data`");
	}

	public function down()
	{
		mysql_query("ALTER TABLE `scraper_cache` DROP `content`");
	}
}
