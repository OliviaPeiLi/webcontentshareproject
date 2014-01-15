<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Db_clean extends CI_Migration {

	public function up()
	{
		 $this->dbforge->drop_column('users', 'full_name');
		 $this->dbforge->drop_column('users', 'thumbnail');
	}

	public function down()
	{
		
	}
}
