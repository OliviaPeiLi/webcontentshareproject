<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_short_utl extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `short_url` VARCHAR(255) NOT NULL AFTER `url`");
		
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/RentHackr1', link_url = 'http://www.renthackr.com' WHERE sxsw_email = 'zeb@renthackr.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/SportsLab1', link_url = 'http://www.sportslabhq.com' WHERE sxsw_email = 'noman@sportslabinc.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/dealingers1', link_url = 'http://www.dealingers.com' WHERE sxsw_email = 'ilias.pantelakis@gmail.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Turnyp1', link_url = 'http://www.turnyp.com' WHERE sxsw_email = 'ljadavji@turnyp.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Vuact1', link_url = 'http://www.vuact.com' WHERE sxsw_email = 'mikko@vuact.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/HungryGlobetrtr', link_url = 'http://www.HungryGlobetrotter.com' WHERE sxsw_email = 'vijay@hungryglobetrotter.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/FiveRun1', link_url = 'http://www.fiverun.com' WHERE sxsw_email = 'fabian@fiverun.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/MerXZ', link_url = 'http://www.merxz.com' WHERE sxsw_email = 'mike@merxz.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/etceter', link_url = 'http://www.etceter.com' WHERE sxsw_email = 'jon@etceter.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/BlogMutt1', link_url = 'https://www.blogmutt.com' WHERE sxsw_email = 'scott@blogmutt.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Indiloop1', link_url = 'http://www.indiloop.com' WHERE sxsw_email = 'erik@indiloop.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Fleksy1', link_url = 'http://fleksy.com' WHERE sxsw_email = 'ioannis@syntellia.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Fit3D1', link_url = 'http://www.youtube.com/watch?v=TuC7VcfnW4c' WHERE sxsw_email = 'greg.moore@fit3d.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/AHHHA13', link_url = 'http://www.ahhha.com' WHERE sxsw_email = 'matt@ahhha.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/RivalMe', link_url = 'http://www.rivalme.com' WHERE sxsw_email = 'sameer@rivalme.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/EEME11', link_url = 'http://www.eeme.co' WHERE sxsw_email = 'jack.pien@eeme.co' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/GradFly1', link_url = 'http://www.gradf.ly' WHERE sxsw_email = 'oscar@gradf.ly' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Vilynx1', link_url = 'http://www.vilynx.com' WHERE sxsw_email = 'Hendrik@vilynx.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/AbbeyPost1', link_url = 'http://www.abbeypost.com' WHERE sxsw_email = 'cynthia@abbeypost.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/GoodCo13', link_url = 'http://www.good.co' WHERE sxsw_email = 'samar@good.co' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Sherly1', link_url = 'http://www.sher.ly' WHERE sxsw_email = 'blazej@sher.ly' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Relevance1', link_url = 'http://mediarelevance.com' WHERE sxsw_email = 'steven@mediarelevance.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Graphdat', link_url = 'http://www.graphdat.com' WHERE sxsw_email = 'mike@graphdat.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/cloudability', link_url = 'http://www.youtube.com/watch?v=bgFEWpTLuqU' WHERE sxsw_email = 'mat@cloudability.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Z8OOnx', link_url = 'http://www.dishcrawl.com' WHERE sxsw_email = 'tracy@dishcrawl.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/Z8OXHj', link_url = 'http://amap.to' WHERE sxsw_email = 'jack@amap.to' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/10hhF7L', link_url = 'http://www.built.io' WHERE sxsw_email = 'neha@raweng.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/YVdJbO', link_url = 'http://www.spreeify.com' WHERE sxsw_email = 'ruben@spreeify.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/17VvP2t', link_url = 'http://web1.pub.venturocket.com/ ' WHERE sxsw_email = 'marc@venturocket.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/19WY6Cu', link_url = 'http://www.unityhometheater.com' WHERE sxsw_email = 'todd.b@in2Technologies.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/12QkgUC', link_url = 'http://www.Samepage.io' WHERE sxsw_email = 'sschreiman@kerio.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/12Qkpr4', link_url = 'http://www.pogoseat.com' WHERE sxsw_email = 'abel@pogoseat.com' ");
		mysql_query("UPDATE newsfeed SET short_url = '', link_url = 'http://www.TalentXp.com' WHERE sxsw_email = 'mukta@TalentXp.com' ");
		mysql_query("UPDATE newsfeed SET short_url = 'http://fndrs.net/10fURBY', link_url = 'http://www.joinStampede.com' WHERE sxsw_email = 'sdash@joinStampede.com' ");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
