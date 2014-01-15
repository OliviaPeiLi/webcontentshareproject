<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Change_sxsw_folder_name extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE folder SET folder_uri_name='winsxsw', folder_name='WINSXSW' WHERE type=1");
	}

	public function down()
	{
		mysql_query("UPDATE folder SET folder_uri_name='sxsw', folder_name='SXSW' WHERE type=1");
	}
}
