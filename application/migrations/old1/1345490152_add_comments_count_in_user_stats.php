<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_comments_count_in_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'comments'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));

		mysql_query("UPDATE user_stats SET user_stats.comments = (SELECT COUNT(comments.comment_id) FROM comments WHERE comments.user_id_from = user_stats.user_id)");
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'comments');
	}
}
