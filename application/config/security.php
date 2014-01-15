<?php
/**
 * General security config file. All controllers need logged in user by default.
 * format:
 * module/controller/method => vaule,
 * module/controller => value
 * module => value
 * 
 * Value can be:
 * (empty) - default, logged in user
 * public -  full access
 * friends - collaborators list
 * custom_func  - any other values will be called as a functions which should be added in the security helper
 */
$config['security'] = array(
	'homepage/main/landing_page' => 'public',
	'homepage/internal_scraper/get_content' => 'public',
	'homepage/home_newsfeed' => 'public', //Landing page autoscroll
	'homepage/home_newsfeed/hashtag' => 'public', //Landing page autoscroll
	'homepage/home_newsfeed/popular' => 'public', //Landing page autoscroll

	'homepage/home_folders/popular_folders' => 'public', //popular collections
	'homepage/home_folders/popular_ugc' => 'public', //popular collections

	'newsfeed/newsfeed/popup_right' => 'public', //Landing page popup comments
	'newsfeed/newsfeed/source' => 'public', //source page
	'newsfeed/newsfeed/get' => 'public', //drop
	'homepage/home_newsfeed/get_activity_feed' => 'public',
	'newsfeed/newsfeed/get_post_details' => 'public',
	'newsfeed/newsfeed/thumb' => 'public',
	'newsfeed/newsfeed_referrals' => 'public',

	'folder/folder/get' => 'public', //collection page
	'folder/folder/embed' => 'public',
	'folder/folder/get_special' => 'public', //collection page
	'folder/folder_newsfeed/index' => 'public', //collection autoscroll

	'profile/profile/get' => 'public', //profile
	'profile/profile/unsubscribe_email' => 'public', //unsubscribe
	'profile/profile_newsfeed' => 'public', //profile subpages
	'profile/profile_connection' => 'public', //followers, followings
	'profile/profile_folder/collections' => 'public',
	'profile/user/top_power_users' => 'public',
	'profile/profile/user_info' => 'public',
	'profile/profile/get_badge' => 'public',
	'profile/account_controller/validate_username' => 'public', //validate username

	'share' => 'public',
	'about' => 'public',

	'signup' => 'public',
	'login' => 'public',
	'leaderboard' => 'public',
	'signup/forgotpassword' => 'public',
	'connection/connect_update/follow_user' => 'public',
	'folder/folder_update_controller/follow_folder' => 'public',
	
	//search
	'search' => 'public',
	'search/search' => 'public',
	'search/hashtag' => 'public',
	'search/users' => 'public',
	'search/drops_search' => 'public',
	'search/collections_search' => 'public',

	'api' => 'public', //API has its own security engine

	//contests
	'contest/dashboard' => 'public',
	'contest/sxsw_dashboard' => 'public',
	'contest/create' => 'public',
	'contest/save' => 'public',
	'contest/add_item' => 'public',
	'demo/quiz' => 'public',

	//Bookmarklet
	'bookmarklet' => 'bookmarklet',
	'bookmarklet/bookmarklet/external_login' => 'public',
	'bookmarklet/bookmarklet/add_image_after' => 'public', //for failsafe script to fix images
	'bookmarklet/scripts/js' => 'public',
	'bookmarklet/scripts/maintenance' => 'public',
	'bookmarklet/scripts/external' => 'public',
	'bookmarklet/scripts/embed_js' => 'public',
	'bookmarklet/scripts/get_embed_count' => 'public',
	'bookmarklet/internal/snapshot_preview' => 'public',
	//CLI
	'cronjob' => 'cli',
	'js_packer' => 'cli',
	'migrate' => 'cli',
	//Core
	'js_packer/js_packer/set_js_files' => 'public',
);