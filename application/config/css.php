<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CSS files which are loaded automaticaly.
 * Syntax:
 * $config['css']['module'] = array of files
 * $config['css']['module/view'] = array of files
 * $config['css']['path/view'] = array of files     <- for view outside module
 */

$config['css'] = array(
	'new' => array(
		'fullscraper/full_scraper' => array('NEW/includes/internal_scraper','NEW/fullscraper/full_scraper'),
		'includes/header' => array('NEW/base','NEW/960fluid','NEW/external','NEW/common','NEW/includes/header','NEW/help/help', 'NEW/plugins/bootstrap', 'NEW/plugins/token-list','NEW/popup_info', 'NEW/bookmarklet/info_dialog','NEW/postbox','NEW/link/bookmarklet'),
		'includes/header_landing' => array('NEW/base','NEW/960fluid','NEW/external','NEW/common','NEW/includes/header','NEW/help/help', 'NEW/plugins/bootstrap', 'NEW/plugins/token-list','NEW/popup_info'),
		'includes/header_lean_centered' => array('NEW/base','NEW/960fluid','NEW/common','NEW/includes/header_lean','NEW/style','NEW/external'),
		'about/about' => array('NEW/about/about','NEW/common','NEW/plugins/bootstrap'),
		'landing_page/landing_page_fresh' => array('NEW/landing_page/landing_fresh'),
		'landing_page/landing_fresh_postcard' => array('NEW/landing_page/landing_fresh'),
		'leaderboard/leaderboard_home' => array('NEW/leaderboard/leaderboard'),
		'newsfeed/newsfeed_postcard_list' => array('NEW/newsfeed/newsfeed','NEW/newsfeed/newsfeed_postcard','NEW/plugins/jquery-ui-autocomplete', 'NEW/plugins/fd-scroll'),
		'newsfeed/newsfeed_tile_new_list' => array('NEW/newsfeed/newsfeed','NEW/newsfeed/newsfeed_tile_new','NEW/plugins/jquery-ui-autocomplete'),
		'newsfeed/newsfeed_contest_list' => array('NEW/newsfeed/newsfeed','NEW/newsfeed/newsfeed_contest','NEW/plugins/jquery-ui-autocomplete'),
		'newsfeed/newsfeed_sxsw_list' => array('NEW/newsfeed/newsfeed','NEW/newsfeed/newsfeed_sxsw','NEW/plugins/jquery-ui-autocomplete'),
		'fantoon-extensions/winsxsw_top' => array('NEW/winsxsw_top','NEW/folder/folder_sxsw'),
		'fantoon-extensions/sxsw_dashboard' => array('NEW/sxsw-dashboard/dashboard_sxsw'),
		'fantoon-extensions/sxsw_submission_form' => array('NEW/winsxsw_top'),
		'contest' => array('NEW/plugins/jquery-ui-datepicker'),
		'contest/add_item' => array('NEW/contest/add_item'),
		'contest/create' => array('NEW/contest/create'),
		'contest/preview' => array('NEW/winsxsw_top'),
		'contest/top' => array('NEW/winsxsw_top','NEW/folder/folder_sxsw'),
		'contest/dashboard' => array('NEW/sxsw-dashboard/dashboard_sxsw'),
		'contest/list' => array('NEW/contest/list'),
		'fantoon-extensions/demo_quiz' => array('NEW/hostedContest'),
		'newsfeed/drop_preview_popup' => array('NEW/newsfeed/drop_preview_popup','NEW/comments/comments','NEW/plugins/fd-scroll', 'NEW/plugins/jquery-ui-autocomplete'),
		'newsfeed/drop_page' => array('NEW/newsfeed/newsfeed','NEW/newsfeed/drop_page','NEW/comments/comments','NEW/plugins/jquery-ui-autocomplete-moded','NEW/plugins/fd-scroll'),
		'newsfeed/source' => array('NEW/newsfeed/newsfeed'),
		'newsfeed/coversheet_popup' => array('NEW/newsfeed/coversheet_popup'),
		'includes/internal_scraper' => array('NEW/includes/internal_scraper'),
		'profile' => array('NEW/profile/profile'),
		'profile/profile_top' => array('NEW/newsfeed/newsfeed'),
		'profile/user_info' => array('NEW/profile/user_info'),
		'profile/edit_personal_info' => array('NEW/profile/user_info','NEW/profile/edit_personal_info'),
		'profile/connections_list' => array('NEW/profile/profile'),
		'folder/profile_collection' => array('NEW/folder/folder'),
		'folder/folder_list' => array('NEW/folder/folder'),
		'folder/folder_top' => array('NEW/folder/folder', 'NEW/plugins/fd-scroll'),
		'folder/folder' => array('NEW/folder/folder','NEW/profile/profile'),
		'embed/collection_overview' => array('NEW/embed/embed'),
		'notification/notifications_list' => array('NEW/notification/notification','NEW/newsfeed/newsfeed'),
		'message/threads' => array('NEW/message/message'),
		'message/msgs' => array('NEW/message/message'),
		'invite/invite' => array('NEW/invite/invite'),
		'profile/edit' => array('NEW/profile/account_settings'),
		'bookmarklet/bar' => array('NEW/bookmarklet/bar'),
		'bookmarklet/popup' => array('NEW/bookmarklet/popup','NEW/plugins/token-list','NEW/plugins/jquery-ui-autocomplete', 'NEW/plugins/fd-scroll'),
		'bookmarklet/success' => array('NEW/bookmarklet/success'),
		'bookmarklet/login' => array('NEW/bookmarklet/login'),
		'bookmarklet/walkthrough' => array('NEW/help/walkthrough'),
		'search/search_index_results' => array('NEW/search/search'),
		'search/search_results' => array('NEW/search/search','NEW/newsfeed/newsfeed'),
		//Signup
		'signup/signup_catathon' => array('NEW/plugins/bootstrap'),
		'signup/catathon_intro_popup' => array('NEW/signup/intro_popup'),
		'signup' => array('NEW/signup/signup'),
		'signup/step1_form' => array('NEW/plugins/bootstrap'),
		'signup/login' => array('NEW/common','NEW/signup/login','NEW/signup/signup'),
		'signup/step4_invite' => array('NEW/invite/invite'),
		'signup/forgetpassword' => array('NEW/forget_password/forget_password'),
		'signup/forgetpassword_reset' => array('NEW/forget_password/forget_password'),
		'signup/forgetpassword_reset_ugc' => array('NEW/forget_password/forget_password_ugc'),
		
		//New design
		'includes/header_ugc' => array('NEW/bootgrid','NEW/common_ugc', 'NEW/includes/header_ugc', 'NEW/plugins/token-list-ugc', 'NEW/plugins/fd-scroll','NEW/includes/ugc_trending_bar','NEW/plugins/custom_title'),

		'about/promoters_ugc' => array('NEW/bootgrid', 'NEW/about/about_promoPub'),
		'about/publishers_ugc' => array('NEW/about/about_ugc','NEW/common_ugc','NEW/plugins/bootstrap'),
		'about/about_ugc' => array('NEW/about/about_ugc','NEW/common_ugc','NEW/plugins/bootstrap'),
		'about/about_partners_ugc' => array('NEW/about/about_ugc','NEW/common_ugc','NEW/plugins/bootstrap'),
		'about/about_main_ugc' => array('NEW/about/about_ugc','NEW/common_ugc','NEW/plugins/bootstrap'),

		'bookmarklet/bar_ugc' => array('NEW/bookmarklet/bar_ugc'),
		'bookmarklet/popup_ugc' => array('NEW/bookmarklet/popup_ugc','NEW/plugins/token-list', 'NEW/plugins/fd-scroll'),
		'bookmarklet/login_ugc' => array('NEW/bookmarklet/login_ugc'),
		'bookmarklet/success_ugc' => array('NEW/bookmarklet/success_ugc'),

		'signup/signup_ugc' => array('NEW/signup/logSign_ugc'),
		'landing_page/landing_ugc' => array('NEW/landing_page/landing_ugc','NEW/folder/folder_ugc'),
		'folder/folder_ugc' => array('NEW/folder/folder_ugc'),
		'profile/profile_ugc' => array('NEW/homepage/home_ugc', 'NEW/folder/folder_ugc', 'NEW/profile/profile_ugc'),
		'profile/unsubscribe_ugc' => array('NEW/profile/profile_ugc'),
		'homepage/home_main_ugc' => array('NEW/homepage/home_ugc'),
		'search/search_ugc' => array('NEW/search/search_ugc', 'NEW/homepage/home_ugc'),
		'lists/container_ugc' => array('NEW/profile/listmanager/listmanager_ugc'),
		'lists/create' => array('NEW/plugins/jquery-ui-autocomplete'),
		'invite/invite_ugc' => array('NEW/invite/invite_ugc'),
		'newsfeed/drop_preview_popup_ugc' => array('NEW/newsfeed/drop_preview_popup_ugc'),
		'notification/notifications_list_ugc' => array('NEW/notification/notification_ugc'),
	),
	'default' => array(
		'includes/header' => array('base','960fluid','includes/header','style','common','external','bookmarklet/info_dialog','help/help','plugins/web_scraper_bar','jquery-ui-1.8.14.custom', 'NEW/plugins/bootstrap','popup_info'),
		'includes/info_dialog' => array('internal_scraper','bookmarklet/info_dialog','postbox','link/bookmarklet'/*,'plugins/web_scraper'*/),
		'includes/internal_scraper' => array('includes/internal_scraper'),
		'about/about' => array('about/about','common','NEW/plugins/bootstrap'),
		'home' => array('home','home/home','newsfeed/newsfeed', 'newsfeed/newsfeed_timeline_new','internal_scraper','newsfeed/newsfeed_ticker'),
		'landing_page/landing_page_new' => array('home','home/home','signup/signup','newsfeed/newsfeed', 'newsfeed/newsfeed_tile','newsfeed/newsfeed_limited_tile','newsfeed/newsfeed_timeline_new'),
		'landing_page/landing_page' => array('home','home/home','signup/signup','newsfeed/newsfeed', 'newsfeed/newsfeed_timeline_new'),
		'landing_page/landing_page_top' => array('signup/signup'),
		'landing_page/landing_page_top_new' => array('signup/signup'),
		'landing_page/landing_page_fresh' => array('landing_page/landing_fresh'),
		'signup/request_invite' => array('signup/request_invite'),
		'newsfeed/source' => array('home/home'),
		'home/home_main' => array('newsfeed/newsfeed', 'newsfeed_timeline_new','newsfeed/newsfeed_popup'),
		'folder/folder' => array('home/home'), //home is added because of the newsfeed view btns
		'folder/folder_main' => array('folder/folder','home/home'),
		'bookmarklet/popup' => array('external'),
		'bookmarklet/embed_popup' => array('external'),
		'bookmarklet/bar' => array('plugins/web_scraper_bar'),
		'signup/grabbed_info' => array('signup/grabbed_info'),
		'signup/grabbed_info_basic' => array('signup/grabbed_info'),
		'signup/create_collections' => array('signup/grabbed_info'),
		'list/list_view' => array('list/circle','list/friend','list/tooltip'),
		'profile' => array('profile/profile','newsfeed/newsfeed_ticker','newsfeed/newsfeed_timeline_new', 'newsfeed/newsfeed_popup', 'newsfeed/newsfeed_edit_popup','folder/folder', 'folder/drop_into_folder_popup'),
		'folder/profile_collection' => array('folder/folder', 'folder/drop_into_folder_popup'),
		'profile/profile_drops' => array('folder/folder','home/home','newsfeed/newsfeed_timeline_new', 'newsfeed/newsfeed_popup'),
		'profile/profile_types' => array('home/home','folder/folder'),
		'profile/account' => array('profile/account_settings'),
		'profile/user_info' => array('profile/user_info'),
		'profile/edit_personal_info' => array('profile/user_info','profile/edit_personal_info'),
		'profile/my_interests_edit' => array('profile/my_interests_edit'),
		'profile/view_interests' => array('profile/view_interests','profile/user_info'),
		'invite/invite_page' => array('invite/invite'),
		'profile/unsubscribe' => array('common'),
		'interests/page' => array('interests/page','internal_scraper', 'folder/folder', 'home/home'), //home/home is for newsfeed
		'interests/page_info' => array('interests/page_info','interests/page'),
		'interests/edit_page_info' => array('interests/page_info','interests/page','interests/page_edit'),
		'interests/feature_page_edit' => array('interests/page_info','interests/page','interests/page_edit'),
		'interests/community_edit' => array('interests/page','interests/page_edit'),
		'interests/page_wall' => array('interests/page','interests/page_edit'),
		'interests/page_wizard' => array('interests/page_wizard'),
		'discover_interests/discover_interests' => array('discover_interests/discover_interests'),
		'message/msg_inbox' => array('message/message'),
		'message/msg_thread' => array('message/message'),
		'message/msg_inbox_entry' => array('message/message'),
		'message/msg_thread_entry' => array('message/message'),
		'notification/notifications_list' => array('notification/notification'),
		'search/search_index_results' => array('search/search'),
		'search/search_results' => array('search/search'),
		'recommendations/gen_similarity_finish' => array('search/search'),
		'search/search_collections_results' => array('folder/folder'),
		'search/search_drops_results' => array('newsfeed/newsfeed_tile','newsfeed/newsfeed_popup'),
		'signup/forgetpassword_view' => array('common','forget_password/forget_password'),
		'signup/forgetpassword_step2' => array('common','forget_password/forget_password'),
		'signup/resetpassword_view' => array('common','forget_password/forget_password'),
		'signup/basic_info' => array('signup/signup','common'),
		'signup/register_form' => array('signup/signup','common'),
		'signup/twitter_form' => array('signup/signup','common'), //adding home just to test deployment
		'signup/more_info' => array('signup/signup'),
		'signup/category' => array('signup/signup'),
	/*	'user_location/links_details' => array('user_location/'),*/
		'user_location/links_update' => array('user_location/user_location'),
		'user_location/place_detail' => array('user_location/user_location'),
		'user_location/place_update' => array('user_location/user_location'),
		'graph/graphy' => array('graph/graphy'),
		'newsfeed/activity' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup'),
		'newsfeed/newsfeed' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup'),
		'newsfeed/newsfeed_grid' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup'),
		'newsfeed/newsfeed_home_interests' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup'),
		'newsfeed/drop_page' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup'),
		'newsfeed/newsfeed_full_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_full_list','newsfeed/newsfeed_popup', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_tile_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_tile','newsfeed/newsfeed_popup', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_timeline_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_popup', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_basic_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_basic', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_timeline_new_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_timeline_new', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_card_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_card', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_limited_montage_list' => array('newsfeed/newsfeed','newsfeed/newsfeed_montage', 'newsfeed/newsfeed_edit_popup'),
		'newsfeed/newsfeed_postcard_list' => array('NEW/newsfeed/newsfeed','newsfeed/newsfeed_popup','NEW/newsfeed/newsfeed_postcard'),
		
		'help/walkthrough' => array('help/walkthrough'),
		'bookmarklet/walkthrough' => array('help/walkthrough'),
	
		'winsxsw' => array('sxsw/sxsw'),
	
		'includes/header_external' => array('plugins/web_scraper'),
		'includes/header_lean_centered' => array('base','960fluid','includes/header_lean','style','external','signup/signup'),
		'signup/login' => array('common','signup/login'),
		'signup/requires_invite' => array('signup/request_invite'),
		'signup/signup_error' => array('common'),
		'signup/email_form' => array('NEW/plugins/bootstrap','signup/signup','common'),
		'invite/invite_gmail' => array('profile/invite','profile/fb_friends_list','invite/invite'),
		'profile/follow_power_users' => array('profile/follow_power_users'),

	)
);

//bobef: #FD-2116
//some MS specific css styles, so only load them if we are not using IE10
//disabled because there could be cache problems if we have different css
//for different browsers
/*
$iepos = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE ');
if ( $iepos >= 0 ) {
	if (substr($_SERVER['HTTP_USER_AGENT'], $iepos + 5, 3) != '10.' ) {
		foreach ( $config['css'] as $k => $v ) {
			foreach ( $v as $k1 => $v1 ) {
				if ( is_array($v1) && in_array('NEW/plugins/bootstrap', $v1) ) {
					$config['css'][$k][$k1][] = 'NEW/plugins/bootstrap.ielt10';
				}
			}
		}
	}
	unset($iepos);
}
//*/
//end of #FD-2116

?>
