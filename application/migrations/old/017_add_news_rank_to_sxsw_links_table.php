<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_news_rank_to_sxsw_links_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_links', array(
			'news_rank'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>1
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_links', 'news_rank');
	}
}
