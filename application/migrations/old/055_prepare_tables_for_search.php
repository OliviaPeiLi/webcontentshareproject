<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_prepare_tables_for_search extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `links` CHANGE  `content`  `content` LONGTEXT NULL DEFAULT NULL");
		mysql_query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT (`content`)");
		mysql_query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT (`title`)");
		mysql_query("ALTER TABLE  `fantoon_ci`.`photos` ADD FULLTEXT (`caption`)");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `links` CHANGE  `content`  `content` LONGBLOB NULL DEFAULT NULL");
		mysql_query("ALTER TABLE  `links` DROP INDEX  `content` ;");
		mysql_query("ALTER TABLE  `links` DROP INDEX  `title` ;");
		mysql_query("ALTER TABLE  `photos` DROP INDEX  `caption` ;");
	}
}
