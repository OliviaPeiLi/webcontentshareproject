<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_index_to_text_for_search extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT (`text`)");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `links` DROP INDEX  `text` ;");
	}
}
