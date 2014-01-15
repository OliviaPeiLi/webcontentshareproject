<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_num_only_in_hashtags extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('hashtags', array(
			'num_only'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
		
		$res = mysql_query("SELECT id, hashtag 
							FROM hashtags
							ORDER BY id ASC	 
						   ");
		while ($row = mysql_fetch_object($res)) {
			if(is_numeric(str_replace('_hash_','',$row->hashtag))){
				mysql_query("UPDATE hashtags SET num_only = 1 WHERE id=$row->id");
			}
		}
	}

	public function down()
	{
		$this->dbforge->drop_column('hashtags', 'num_only');
	}
}
