<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_twitter_id_to_fb_links extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_links', array(
			'twitter_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('fb_links', 'twitter_id');
	}
}
