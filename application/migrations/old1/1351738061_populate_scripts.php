<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Populate_scripts extends CI_Migration {

	public function up()
	{
		  mysql_query("INSERT INTO  `fantoon_ci`.`scripts` (`name` ,`num_instances`)
						VALUES 
							('signup_links',  '1'),
							('newsfeed_ranking.php',  '1'),
							('collection_ranking.php',  '1'),
							('send_newsletter.php',  '1');
		  ");
	}

	public function down()
	{
		  mysql_query("TRUNCATE TABLE  `scripts`");
	}
}
