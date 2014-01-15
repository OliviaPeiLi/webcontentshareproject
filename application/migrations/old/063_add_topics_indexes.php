<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_topics_indexes extends CI_Migration {

	public function up()
	{	
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}	
		//activities table
		mysql_query("CREATE INDEX editable ON topics (editable)");

	}

	public function down()
	{
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}
		//activities table
		mysql_query("ALTER TABLE `".$database."`.`topics` DROP INDEX `editable`");

	}
}
