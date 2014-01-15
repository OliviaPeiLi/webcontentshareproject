<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_over_views_count_in_user_stats_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'views_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		mysql_query("UPDATE user_stats SET user_stats.views_count =  ((SELECT SUM(newsfeed.hits) as n_hits FROM newsfeed where newsfeed.user_id_from=user_stats.user_id AND newsfeed.hits > 0)+(SELECT SUM(folder.hits) as f_hits FROM folder where folder.user_id=user_stats.user_id AND folder.hits > 0))");
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'views_count');
	}
}
