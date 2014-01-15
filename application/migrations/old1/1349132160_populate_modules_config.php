<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Populate_modules_config extends CI_Migration {

	public function up()
	{
		  mysql_query("INSERT INTO  `fantoon_ci`.`modes_config` (`name` ,`development` ,`staging` ,`production`)
						VALUES 
							('sxsw',  '0',  '0',  '0'),
							('timeline_view',  '0',  '0',  '0'),
							('tile_view',  '0',  '0',  '0'),
							('list_view',  '1',  '1',  '1'),
							('card_view',  '1',  '0',  '0'),
							('timeline_view_new',  '1',  '1',  '1'),
							('montage_view',  '0',  '0',  '0'),
							('topics',  '1',  '1',  '1'),
							('topics_add_new',  '1',  '1',  '1'),
							('folders',  '1',  '1',  '1'),
							('folders_privacy',  '1',  '0',  '0'),
							('folders_contributors',  '1',  '0',  '0'),
							('interests',  '1',  '1',  '0'),
							('internal_scraper',  '1',  '1',  '1'),
							('internal_scraper_new',  '1',  '0',  '0'),
							('new_landing_page',  '1',  '1',  '1'),
							('landing_page_2col',  '0',  '0',  '0'),
							('landing_page_type',  '1',  '1',  '1'),
							('speed_popup',  '1',  '1',  '1'),
							('deactivate_account',  '1',  '0',  '0'),
							('new_signup',  '1',  '1',  '1'),
							('drop_signup',  '0',  '0',  '0'),
							('regular_signup',  '1',  '1',  '1'),
							('new_server',  '1',  '1',  '1'),
							('optimized_js',  '0',  '1',  '1'),
							('social_share',  '1',  '1',  '1'),
							('redrop_comment_social_share',  '0',  '1',  '1'),
							('embed_collection',  '1',  '1',  '1'),
							('embed_bookmarklet',  '1',  '0',  '0'),
							('bookmark_html_page',  '1',  '1',  '1'),
							('new_invite',  '1',  '1',  '1'),
							('cache',  '1',  '0',  '0'),
							('yahoo_invite',  '1',  '1',  '1'),
							('open_signup',  '1',  '1',  '1'),
							('transfer_links',  '0',  '0',  '0'),
							('education_settings',  '1',  '0',  '0'),
							('location_links_settings',  '1',  '0',  '0'),
							('folder_filter',  '1',  '1',  '1'),
							('loops',  '0',  '0',  '0'),
							('follow_from_invite',  '1',  '1',  '1'),
							('atmention_notification',  '1',  '1',  '1'),
							('fb_comment_action',  '0',  '1',  '1'),
							('fb_view_action',  '0',  '0',  '0'),
							('fb_drop_object',  '0',  '0',  '0'),
							('email_signup',  '1',  '1',  '1'),
							('mentions_page',  '1',  '1',  '1'),
							('fixed_tooltip',  '1',  '0',  '0'),
							('replacable_thumbnails',  '1',  '1',  '1'),
							('views_variables',  '1',  '0',  '0'),
							('undefined_var_checker',  '1',  '0',  '0'),
							('exclusive_content',  '1',  '1',  '1'),
							('fresh_landing_page',  '1',  '1',  '1'),
							('view_debug',  '1',  '0',  '0'),
							('password_reminder',  '0',  '0',  '0'),
							('category_power_users',  '1',  '1',  '1'),
							('skip_preview_info',  '1',  '1',  '1'),
							('system_notification',  '1',  '1',  '1'),
							('general_power_users',  '0',  '0',  '0');
		  ");
	}

	public function down()
	{
		  mysql_query("TRUNCATE TABLE  `modes_config`");
	}
}
