<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * If a folder is open then all users can contribute content inside http://dev.fantoon.com:8100/browse/FD-739
 * @author radilr
 *
 */
class Migration_Add_is_open_to_folders extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'is_open'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'is_open');
	}
}
