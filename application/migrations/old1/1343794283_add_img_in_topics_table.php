<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_img_in_topics_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('topics', array(
			'img'=>array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'default' => ''
			),
		));
		
		mysql_query("UPDATE topics SET img = 'Art' WHERE topic_name='art.png'");
		mysql_query("UPDATE topics SET img = 'Book' WHERE topic_name='book.png'");
		mysql_query("UPDATE topics SET img = 'Business' WHERE topic_name='business.png'");
		mysql_query("UPDATE topics SET img = 'Charity' WHERE topic_name='charity.png'");
		mysql_query("UPDATE topics SET img = 'Entertainment' WHERE topic_name='entertainment.png'");
		mysql_query("UPDATE topics SET img = 'Family' WHERE topic_name='family.png'");
		mysql_query("UPDATE topics SET img = 'Fashion' WHERE topic_name='fashion.png'");
		mysql_query("UPDATE topics SET img = 'Food' WHERE topic_name='food.png'");
		mysql_query("UPDATE topics SET img = 'Funny' WHERE topic_name='funny.png'");
		mysql_query("UPDATE topics SET img = 'Government' WHERE topic_name='government.png'");
		mysql_query("UPDATE topics SET img = 'Health' WHERE topic_name='health.png'");
		mysql_query("UPDATE topics SET img = 'Music' WHERE topic_name='music.png'");
		mysql_query("UPDATE topics SET img = 'News' WHERE topic_name='news.png'");
		mysql_query("UPDATE topics SET img = 'Science' WHERE topic_name='science.png'");
		mysql_query("UPDATE topics SET img = 'Sports' WHERE topic_name='sports.png'");
		mysql_query("UPDATE topics SET img = 'Tech' WHERE topic_name='tech.png'");
		mysql_query("UPDATE topics SET img = 'Movie' WHERE topic_name='movie.png'");
		mysql_query("UPDATE topics SET img = 'TV' WHERE topic_name='TV.png'");
		mysql_query("UPDATE topics SET img = 'Local' WHERE topic_name='local.png'");
		mysql_query("UPDATE topics SET img = 'Community' WHERE topic_name='community.png'");
		mysql_query("UPDATE topics SET img = 'Activity' WHERE topic_name='activity.png'");
		mysql_query("UPDATE topics SET img = 'Travel' WHERE topic_name='travel.png'");
		mysql_query("UPDATE topics SET img = 'Local Business or Place' WHERE topic_name='localBiz.png'");
		mysql_query("UPDATE topics SET img = 'Organization or Company' WHERE topic_name='organization.png'");
		mysql_query("UPDATE topics SET img = 'Brand or Product' WHERE topic_name='brand.png'");
		mysql_query("UPDATE topics SET img = 'Public Figure' WHERE topic_name='publicFigure.png'");
		
	}

	public function down()
	{
		$this->dbforge->drop_column('topics', 'img');
	}
}
