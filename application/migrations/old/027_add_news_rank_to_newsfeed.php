<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_news_rank_to_newsfeed extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('newsfeed', array(
			'news_rank' => array(
				'type' => 'VARCHAR',
				'constraint' => '20',
				'default'=>1
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'news_rank');
	}
}
