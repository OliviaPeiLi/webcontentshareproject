<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_update_modes_config extends CI_Migration {

	public function up()
	{
			
		mysql_query("ALTER TABLE  `modes_config` ADD  `updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL");
		
	}

	public function down()
	{
				 
		  mysql_query("ALTER TABLE `modes_config` DROP `updated`");
	
	}
}
