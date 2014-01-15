<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_olympics_img_in_topics extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE topics SET img = 'olympics.png' WHERE topic_name='Olympics'");
	}

	public function down()
	{

	}
}
