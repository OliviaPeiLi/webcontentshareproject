<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_uri_name_to_topics extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('topics', array(
			'uri_name'=>array(
				'type'=>'VARCHAR',
				'constraint'=>'100',
				'default'=>''
			),
		));
		
		$query = mysql_query("SELECT * FROM topics");
		while($topic = mysql_fetch_array($query)){

			$url_str = str_replace (' ', '_', $topic['topic_name']); 
			$url_str = str_replace ('_(', '_', $url_str);
			$url_str = str_replace ('(', '_', $url_str);
			$url_str = str_replace (':', '_', $url_str);
			$url_str = str_replace ('_&_', '-', $url_str);
			$url_str = str_replace ('&', '-', $url_str);
			$url_str = str_replace ('?', '', $url_str);
			$url_str = str_replace ('/', '_', $url_str);
			$url_str = str_replace (')', '_', $url_str);
			$url_name = str_replace ('%_', '', $url_str);
			$url_name = rtrim($url_name, '_');
			$uri_name = preg_replace_callback('/[^a-z0-9-_\/\.:%=&\?]+/i', create_function('$matches', 'return "";'), $url_name);
			
			mysql_query("UPDATE topics SET uri_name = '".$uri_name."' WHERE topic_id = '".$topic['topic_id']."'");
			//echo mysql_error();
		}
	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('topics', 'uri_name');
	}
	
	
	
}
