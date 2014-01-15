<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_followers_to_folder extends CI_Migration {

	public function up()
	{		
		$this->dbforge->add_column('folder', array(
			'followers' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		
		mysql_query("UPDATE folder SET folder.followers =  (SELECT COUNT(user_id) FROM folder_user WHERE folder_user.folder_id = folder.folder_id)");
		
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'followers');
	}
}
