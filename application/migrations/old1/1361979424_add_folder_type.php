<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folder_type extends CI_Migration {

	public function up() {
		mysql_query("ALTER TABLE  `folder` ADD  `type` TINYINT UNSIGNED NOT NULL AFTER  `folder_name`");
		$user_id = mysql_fetch_object(mysql_query("SELECT id FROM users WHERE uri_name = 'Alexi'"))->id;
		mysql_query("INSERT INTO `folder` (folder_name, folder_uri_name, type, user_id, drops) 
					VALUES ('SXSW', 'sxsw', 1, $user_id, 10)");
		$folder_id = mysql_insert_id();
		
		$drop_default = array(
				'activity_user_id' => $user_id,
				'type' => 'link',
				'link_type' => 'html',
				'user_id_from' => $user_id,
				'folder_id' => $folder_id,
				'complete' => true,
				'orig_user_id' => $user_id,
				'link' => array(
					'user_id_from' => $user_id,
					'link_type' => 'html',
					'time' => date('Y-m-d H:i:s'),
				)
			);
		$drops = array(
			array_merge($drop_default, array(
				'title' => 'Virool',
				'description' => 'Promote your YouTube videos. Easy, fast, powerful platform trusted by the biggest brands',
				'link_url' => 'http://vine.co/',
				'img' => 'http://onlinemarketing.de/wp-content/uploads/2013/02/virool_1.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Vine',
				'description' => 'Vine is the best way to see and share life in motion. Create short, beautiful, looping videos in a simple and fun way for your friends and family to see',
				'link_url' => 'http://www.virool.com/',
				'img' => 'http://www.moborobo.com/uploads/allimg/c130220/136132Y9B0160-21K55.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Intro',
				'description' => 'Creates a customized and ranked list of the most relevant business people nearby now',
				'link_url' => 'https://getintro.net/',
				'img' => 'https://getintro.net/img/logo.png',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Relevance',
				'description' => 'Monetization made simple for Mobile & Online Video',
				'link_url' => 'http://mediarelevance.com/',
				'img' => 'https://secure-b.vimeocdn.com/ts/401/511/401511957_295.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Clarity',
				'description' => 'Connecting entrepreneurs over the phone to move their dreams and goals forward',
				'link_url' => 'http://clarity.fm/home',
				'img' => 'http://realventures.com/en/files/2012/12/logo-whiteonblue.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Udemy',
				'description' => 'The world`s largest destination for online courses',
				'link_url' => 'https://www.udemy.com/',
				'img' => 'https://photos-3.dropbox.com/t/0/AABcFtdPDuciOUHKN1eya-J4QE5zzxnmQOVmZUaQcFwGbg/10/2976608/jpeg/32x32/2/1361973600/0/2/udemy_logo_600_w_background.jpg/6jAMBtD3_eaUQ0DR6YVxLdXWGLOX1Zs_0JKQdS_Nehw?size=1024x768&size_mode=2',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Duolingo',
				'description' => 'A free service that helps you learn languages with your friends',
				'link_url' => 'http://duolingo.com/',
				'img' => 'http://duolingo.com/images/about/resources/duolingo-owl.png',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Treehouse',
				'description' => 'Treehouse brings affordable Technology education to people everywhere, in order to help them achieve their dreams and change the world',
				'link_url' => 'http://teamtreehouse.com/',
				'img' => 'http://skogmo.me/wp-content/uploads/2013/02/treehouse-logo3.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Gumroad',
				'description' => 'Enables every creative to sell directly to their audience',
				'link_url' => 'https://gumroad.com/',
				'img' => 'https://gumroad.s3.amazonaws.com/assets/brand/g-white-512-74f60d789e86b38f40c7be5ea7a27d56.png',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
			array_merge($drop_default, array(
				'title' => 'Pocket',
				'description' => 'When you find something you want to view later, put it in Pocket',
				'link_url' => 'http://getpocket.com/',
				'img' => 'http://img4-3.realsimple.timeinc.net/images/1210new/pocket-logo-ictcrop_300.jpg',
				'link' => array_merge($drop_default['link'], array(
					'content' => ''
				))
			)),
		);
		$drops = array_reverse($drops);
		foreach ($drops as $drop) {
			foreach ($drop['link'] as $key=> &$val) $val = mysql_real_escape_string($val);
			mysql_query("INSERT INTO links (".implode(', ', array_keys($drop['link'])).") VALUES('".implode("', '", $drop['link'])."')");
			$drop['activity_id'] = mysql_insert_id();
			unset($drop['link']);
			
			foreach ($drop as $key=> &$val) $val = mysql_real_escape_string($val);
			mysql_query("INSERT INTO newsfeed (".implode(', ', array_keys($drop)).") VALUES('".implode("', '", $drop)."')");
		}
	}

	public function down()
	{
		  $this->dbforge->drop_column('folder', 'type');
	}
}
