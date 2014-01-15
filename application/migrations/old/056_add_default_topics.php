<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_default_topics extends CI_Migration {

	public function up()
	{
		$default_topics = array('Art','Book','Business','Charity','Entertainment','Family','Fashion','Food','Funny','Government','Health','Music','News','Science','Sports','Tech','Movie','TV','Local','Community','Activity','Travel','Local Business or Place','Organization or Company','Brand or Product','Public Figure');
		foreach($default_topics as $topic){
			mysql_query("INSERT INTO topics (topic_name, editable) VALUES ('".$topic."', '0')");
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
