<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_default_topics_again extends CI_Migration {

	public function up()
	{
		$default_topics = array('Art','Book','Business','Charity','Entertainment','Family','Fashion','Food','Funny','Government','Health','Music','News','Science','Sports','Tech','Movie','TV','Local','Community','Activity','Travel','Local Business or Place','Organization or Company','Brand or Product','Public Figure');
		foreach($default_topics as $topic){
			
			$url_str = str_replace (' ', '_', $topic); 
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
			
			mysql_query("INSERT INTO topics (topic_name, editable, uri_name) VALUES ('".$topic."', '0', '".$uri_name."')");
			echo mysql_error();
		}
		
	}
	
	
	public function down()
	{
		$default_topics = array('Art','Book','Business','Charity','Entertainment','Family','Fashion','Food','Funny','Government','Health','Music','News','Science','Sports','Tech','Movie','TV','Local','Community','Activity','Travel','Local Business or Place','Organization or Company','Brand or Product','Public Figure');
		foreach($topics as $topic){
			mysql_query("DELETE FROM topics WHERE topic_name='".$topic."'");
		}
	}
	
	
}
