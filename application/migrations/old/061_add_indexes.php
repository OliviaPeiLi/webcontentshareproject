<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_indexes extends CI_Migration {

	public function up()
	{	
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}	
		//activities table
		mysql_query("CREATE INDEX user_id_from ON activities (user_id_from)");
		mysql_query("CREATE INDEX user_id_to ON activities (user_id_to)");
		mysql_query("CREATE INDEX page_id_to ON activities (page_id_to)");
		mysql_query("CREATE INDEX page_id_from ON activities (page_id_from)");
		mysql_query("CREATE INDEX user_id_from ON activities (user_id_to)");
		mysql_query("CREATE INDEX folder_id ON activities (folder_id)");
		mysql_query("CREATE INDEX activity_id ON activities (activity_id)");
		
		//comments table
		mysql_query("CREATE INDEX parent_id ON comments (parent_id)");
		mysql_query("CREATE INDEX user_id_from ON comments (user_id_from)");
		mysql_query("CREATE INDEX user_id_to ON comments (user_id_to)");
		mysql_query("CREATE INDEX page_id_from ON comments (page_id_from)");
		mysql_query("CREATE INDEX page_id_to ON comments (page_id_to)");
		mysql_query("CREATE INDEX post_id ON comments (post_id)");
		mysql_query("CREATE INDEX photo_id ON comments (photo_id)");
		mysql_query("CREATE INDEX event_id ON comments (event_id)");
		mysql_query("CREATE INDEX pr_id ON comments (pr_id)");
		mysql_query("CREATE INDEX link_id ON comments (link_id)");
		
		//comments_children table
		mysql_query("CREATE INDEX children_id ON comments_children (children_id)");
		
		//fb_friends table
		mysql_query("CREATE INDEX friend_id ON fb_friends (friend_id)");
		
		//fb_links table
		mysql_query("CREATE INDEX u_id ON fb_links (u_id)");
		mysql_query("CREATE INDEX fb_id ON fb_links (fb_id)");
		mysql_query("CREATE INDEX twitter_id ON fb_links (twitter_id)");
		
		//folder table
		mysql_query("CREATE INDEX folder_name ON folder (folder_name)");
		
		//folder_user table
		mysql_query("ALTER TABLE `".$database."`.`folder_user` DROP INDEX `folder_id`");
		mysql_query("CREATE INDEX folder_id ON folder_user (folder_id)");
		mysql_query("CREATE INDEX user_id ON folder_user (user_id)");
		
		//folder_content table
		mysql_query("CREATE INDEX link_id ON folder_content (link_id)");
		mysql_query("CREATE INDEX photo_id ON folder_content (photo_id)");
		
		//folder_contributors table
		mysql_query("CREATE INDEX folder_id ON folder_contributors (folder_id)");
		mysql_query("CREATE INDEX user_id ON folder_contributors (user_id)");
		
		//likes table
		mysql_query("CREATE INDEX user_id ON likes (user_id)");
		mysql_query("CREATE INDEX page_id ON likes (page_id)");
		mysql_query("CREATE INDEX post_id ON likes (post_id)");
		mysql_query("CREATE INDEX photo_id ON likes (photo_id)");
		mysql_query("CREATE INDEX event_id ON likes (event_id)");
		mysql_query("CREATE INDEX pr_id ON likes (pr_id)");
		mysql_query("CREATE INDEX link_id ON likes (link_id)");
		mysql_query("CREATE INDEX comment_id ON likes (comment_id)");
		
		//links table
		mysql_query("CREATE INDEX user_id_from ON links (user_id_from)");
		mysql_query("CREATE INDEX user_id_to ON links (user_id_to)");
		mysql_query("CREATE INDEX page_id_from ON links (page_id_from)");
		mysql_query("CREATE INDEX page_id_to ON links (page_id_to)");
		
		//link_collects table
		mysql_query("CREATE INDEX photo_id ON link_collects (photo_id)");
		
		//msg_content table
		mysql_query("CREATE INDEX thread_id ON msg_content (thread_id)");
		
		//msg_info table
		mysql_query("CREATE INDEX msg_id ON msg_info (msg_id)");
		mysql_query("CREATE INDEX thread_id ON msg_info (thread_id)");
		mysql_query("CREATE INDEX from ON msg_info (from)");
		mysql_query("CREATE INDEX to ON msg_info (to)");
		mysql_query("CREATE INDEX erase_tyoe ON msg_info (erase_tyoe)");
		mysql_query("CREATE INDEX display_status ON msg_info (display_status)");
		mysql_query("CREATE INDEX number_read ON msg_info (number_read)");
		
		//newsfeed table
		mysql_query("CREATE INDEX activity_user_id ON newsfeed (activity_user_id)");
		mysql_query("CREATE INDEX link_type ON newsfeed (link_type)");
		mysql_query("CREATE INDEX user_id_from ON newsfeed (user_id_from)");
		mysql_query("CREATE INDEX user_id_to ON newsfeed (user_id_to)");
		mysql_query("CREATE INDEX page_id_from ON newsfeed (page_id_from)");
		mysql_query("CREATE INDEX page_id_to ON newsfeed (page_id_to)");
		mysql_query("CREATE INDEX folder_id ON newsfeed (folder_id)");
		mysql_query("CREATE INDEX time ON newsfeed (time)");
		
		//notifications table
		mysql_query("CREATE INDEX type ON notifications (type)");
		mysql_query("CREATE INDEX a_id ON notifications (a_id)");
		mysql_query("CREATE INDEX m_id ON notifications (m_id)");
		
		//photos table
		mysql_query("CREATE INDEX user_id_from ON photos (user_id_from)");
		mysql_query("CREATE INDEX user_id_to ON photos (user_id_to)");
		mysql_query("CREATE INDEX page_id_from ON photos (page_id_from)");
		mysql_query("CREATE INDEX page_id_to ON photos (page_id_to)");
		mysql_query("CREATE INDEX folder_id ON photos (folder_id)");
		
		//user_stats table
		mysql_query("CREATE INDEX user_id ON user_stats (user_id)");
	}

	public function down()
	{
		if(ENVIRONMENT == 'production'){
			$database = 'fandrop_db';
		}else{
			$database = 'fantoon_ci';
		}
		//activities table
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `user_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `user_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `page_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `page_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `folder_id`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `type`");
		mysql_query("ALTER TABLE `".$database."`.`activities` DROP INDEX `activity_id`");
		
		//comments table
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `parent_id`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `user_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `user_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `page_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `page_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `post_id`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `photo_id`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `event_id`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `pr_id`");
		mysql_query("ALTER TABLE `".$database."`.`comments` DROP INDEX `link_id`");
		
		//comments_children table
		mysql_query("ALTER TABLE `".$database."`.`comments_children` DROP INDEX `children_id`");
		
		//fb_friends table
		mysql_query("ALTER TABLE `".$database."`.`fb_friends` DROP INDEX `friend_id`");
		
		//fb_links table
		mysql_query("ALTER TABLE `".$database."`.`fb_links` DROP INDEX `u_id`");
		mysql_query("ALTER TABLE `".$database."`.`fb_links` DROP INDEX `fb_id`");
		mysql_query("ALTER TABLE `".$database."`.`fb_links` DROP INDEX `twitter_id`");
		
		//folder table
		mysql_query("ALTER TABLE `".$database."`.`folder` DROP INDEX `folder_name`");
		
		//folder_content table
		mysql_query("ALTER TABLE `".$database."`.`folder_content` DROP INDEX `link_id`");
		mysql_query("ALTER TABLE `".$database."`.`folder_content` DROP INDEX `photo_id`");
		
		//folder_contributors table
		mysql_query("ALTER TABLE `".$database."`.`folder_contributors` DROP INDEX `folder_id`");
		mysql_query("ALTER TABLE `".$database."`.`folder_contributors` DROP INDEX `user_id`");
		
		//likes table
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `user_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `page_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `post_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `photo_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `event_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `pr_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `link_id`");
		mysql_query("ALTER TABLE `".$database."`.`likes` DROP INDEX `comment_id`");
		
		//links table
		mysql_query("ALTER TABLE `".$database."`.`links` DROP INDEX `user_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`links` DROP INDEX `user_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`links` DROP INDEX `page_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`links` DROP INDEX `page_id_to`");
		
		//link_collects table
		mysql_query("ALTER TABLE `".$database."`.`link_collects` DROP INDEX `photo_id`");
		
		//msg_content table
		mysql_query("ALTER TABLE `".$database."`.`msg_content` DROP INDEX `thread_id`");
		
		//msg_info table
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `msg_id`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `thread_id`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `from`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `to`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `erase_tyoe`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `display_status`");
		mysql_query("ALTER TABLE `".$database."`.`msg_info` DROP INDEX `number_read`");
		
		//newsfeed table
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `activity_user_id`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `link_type`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `user_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `user_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `page_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `page_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`newsfeed` DROP INDEX `folder_id`");
		
		//notifications table
		mysql_query("ALTER TABLE `".$database."`.`notifications` DROP INDEX `type`");
		mysql_query("ALTER TABLE `".$database."`.`notifications` DROP INDEX `a_id`");
		mysql_query("ALTER TABLE `".$database."`.`notifications` DROP INDEX `m_id`");
		
		//photos table
		mysql_query("ALTER TABLE `".$database."`.`photos` DROP INDEX `user_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`photos` DROP INDEX `user_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`photos` DROP INDEX `page_id_from`");
		mysql_query("ALTER TABLE `".$database."`.`photos` DROP INDEX `page_id_to`");
		mysql_query("ALTER TABLE `".$database."`.`photos` DROP INDEX `folder_id`");
		
		//user_stats table
		mysql_query("ALTER TABLE `".$database."`.`user_stats` DROP INDEX `user_id`");
	}
}
