<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_description_to_modules extends CI_Migration {

	public function up()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` IN ('timeline_view','tile_view','list_view','card_view',
						'timeline_view_new','montage_view','internal_scraper','speed_popup','new_signup','drop_signup',
						'regular_signup','new_server','transfer_links','folder_filter','password_reminder','skip_preview_info',
						'general_power_users','popular_collections','solr_search','new_search_page')");
		
		mysql_query("UPDATE modes_config SET description = 'If users can set a folder to private' WHERE name = 'folders_privacy'");
		mysql_query("UPDATE modes_config SET description = 'If users can add another users to contribute to his folder' WHERE name = 'folders_contributors'");
		mysql_query("UPDATE modes_config SET description = 'The internal scraper in homepage' WHERE name = 'internal_scraper_new'");
		mysql_query("UPDATE modes_config SET description = 'If the user can deactivate their account' WHERE name = 'deactivate_account'");
		mysql_query("UPDATE modes_config SET description = 'JS/CSS grouping' WHERE name = 'optimized_js'");
		mysql_query("UPDATE modes_config SET description = 'Embed bookmarklet button in websites' WHERE name = 'embed_bookmarklet'");
		mysql_query("UPDATE modes_config SET description = 'Bookmark page from the bookmarklet' WHERE name = 'bookmark_html_page'");
		mysql_query("UPDATE modes_config SET description = 'Is Memcache enabled' WHERE name = 'cache'");
		mysql_query("UPDATE modes_config SET description = 'Can a user register without an invitation' WHERE name = 'open_signup'");
		mysql_query("UPDATE modes_config SET description = 'Can a user add his education data in settings' WHERE name = 'education_settings'");
		mysql_query("UPDATE modes_config SET description = 'Can a user add his location data in settings' WHERE name = 'location_links_settings'");
		mysql_query("UPDATE modes_config SET description = 'Can a user register with email not just FB/Twitter' WHERE name = 'email_signup'");
		mysql_query("UPDATE modes_config SET description = 'Debugging for messy code in Dev environment' WHERE name = 'views_variables'");
		mysql_query("UPDATE modes_config SET description = 'Debugging for messy code in Dev environment' WHERE name = 'undefined_var_checker'");
		mysql_query("UPDATE modes_config SET description = 'Debugging for messy code in Dev environment' WHERE name = 'view_debug'");
		mysql_query("UPDATE modes_config SET description = 'I have no idea what this is' WHERE name = 'exclusive_content'");
		mysql_query("UPDATE modes_config SET description = 'Signup step - select power users' WHERE name = 'category_power_users'");
		mysql_query("UPDATE modes_config SET description = 'General notifications like /upload avatar/ or /set your pass/' WHERE name = 'system_notification'");
		mysql_query("UPDATE modes_config SET description = 'Uses views_new and css_new paths' WHERE name = 'new_theme'");
		mysql_query("UPDATE modes_config SET description = 'If enabled will automaticaly invite the user who requested invitation' WHERE name = 'signup_wait_list'");
		mysql_query("UPDATE modes_config SET description = 'If enabled user will automaticaly send an invitation to his fb friends on signup (if connected to fb)' WHERE name = 'signup_invite'");
		mysql_query("UPDATE modes_config SET description = 'No idea' WHERE name = 'fb_invite_request'");
		mysql_query("UPDATE modes_config SET description = 'Is user able to change the drop thumbnail with a custom one' WHERE name = 'coversheet'");
		mysql_query("UPDATE modes_config SET description = 'Newsfeed ordered by hashtags in landing page instead of just popularity' WHERE name = 'hashtag_landingpage_newsfeed'");
		mysql_query("UPDATE modes_config SET description = 'Choose some hashtags on signup and follow the powerusers of these hashtags' WHERE name = 'signup_hashtag_step'");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
