<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_frist_visit extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_visits', array(
			'preview'=>array(
				'type'=>'ENUM',
				'constraint'=>"'2','1','0'"
			),
		));
		
		mysql_query("UPDATE user_visits SET preview = '0'");
	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('user_visits', 'preview');
		
	}
	
	
}
