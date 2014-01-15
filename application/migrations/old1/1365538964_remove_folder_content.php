<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_folder_content extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('folder_content');
	}

	public function down()
	{

	}
}
