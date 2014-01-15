<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_img_in_topics extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE topics SET img = 'art.png' WHERE topic_name='Art'");
		mysql_query("UPDATE topics SET img = 'book.png' WHERE topic_name='Book'");
		mysql_query("UPDATE topics SET img = 'business.png' WHERE topic_name='Business'");
		mysql_query("UPDATE topics SET img = 'charity.png' WHERE topic_name='Charity'");
		mysql_query("UPDATE topics SET img = 'entertainment.png' WHERE topic_name='Entertainment'");
		mysql_query("UPDATE topics SET img = 'family.png' WHERE topic_name='Family'");
		mysql_query("UPDATE topics SET img = 'fashion.png' WHERE topic_name='Fashion'");
		mysql_query("UPDATE topics SET img = 'food.png' WHERE topic_name='Food'");
		mysql_query("UPDATE topics SET img = 'funny.png' WHERE topic_name='Funny'");
		mysql_query("UPDATE topics SET img = 'government.png' WHERE topic_name='Government'");
		mysql_query("UPDATE topics SET img = 'health.png' WHERE topic_name='Health'");
		mysql_query("UPDATE topics SET img = 'music.png' WHERE topic_name='Music'");
		mysql_query("UPDATE topics SET img = 'news.png' WHERE topic_name='News'");
		mysql_query("UPDATE topics SET img = 'science.png' WHERE topic_name='Science'");
		mysql_query("UPDATE topics SET img = 'sports.png' WHERE topic_name='Sports'");
		mysql_query("UPDATE topics SET img = 'tech.png' WHERE topic_name='Tech'");
		mysql_query("UPDATE topics SET img = 'movie.png' WHERE topic_name='Movie'");
		mysql_query("UPDATE topics SET img = 'TV.png' WHERE topic_name='TV'");
		mysql_query("UPDATE topics SET img = 'local.png' WHERE topic_name='Local'");
		mysql_query("UPDATE topics SET img = 'community.png' WHERE topic_name='Community'");
		mysql_query("UPDATE topics SET img = 'activity.png' WHERE topic_name='Activity'");
		mysql_query("UPDATE topics SET img = 'travel.png' WHERE topic_name='Travel'");
		mysql_query("UPDATE topics SET img = 'localBiz.png' WHERE topic_name='Local Business or Place'");
		mysql_query("UPDATE topics SET img = 'organization.png' WHERE topic_name='Organization or Company'");
		mysql_query("UPDATE topics SET img = 'brand.png' WHERE topic_name='Brand or Product'");
		mysql_query("UPDATE topics SET img = 'publicFigure.png' WHERE topic_name='Public Figure'");

	}

	public function down()
	{

	}
}
