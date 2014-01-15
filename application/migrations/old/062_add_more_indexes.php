<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_more_indexes extends CI_Migration {

	public function up()
	{	
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}	
		//activities table
		mysql_query("CREATE INDEX type ON activities (type)");
		mysql_query("CREATE INDEX time ON activities (time)");

	}

	public function down()
	{
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}
		//activities table
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `type`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `time`");

	}
}
