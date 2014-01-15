<?php
/*
$route['profile']                                     = 'profile'; //Defines the default controller
*/

//GET - Profile subpages
$route['(drops|upvotes|mentions)/(:any)/type/(:any)'] = "profile/profile/get/$1/$2/$3";
$route['(followings|followers|info|collections|drops|upvotes|mentions|contests)/(:any)'] = "profile/profile/get/$1/$2";

$route['badge/(:any)/(:num)']                  = "profile/profile/get_badge/$1/$2";
$route['badge/(:any)/(:num)/(:any)']           = "profile/profile/get_badge/$1/$2/$3";

$route['manage_lists']                         = "profile/lists/index";

//Update
$route['account_options']                      = "profile/profile/update";
$route['unsubscribe_email']					   = "profile/profile/unsubscribe_email";
$route['profile/edit_picture']                 = "profile/profile/edit_picture";
$route['profile/crop']                         = "profile/profile/crop";

$route['create_list']                          = "profile/lists/create";
$route['set_as_cover/(:any)']                  = "profile/lists/set_as_cover/$1";
$route['manage_lists/resort_folders']          = "profile/lists/resort";
$route['manage_lists/(:any)/delete']           = "profile/lists/delete/$1";
$route['manage_lists/(:any)/edit']             = "profile/lists/create/$1";
$route['manage_lists/(:any)/publish']          = "profile/lists/publish/$1";
$route['manage_lists/(:any)/unpublish']        = "profile/lists/unpublish/$1";
$route['manage_lists/(:any)/add_posts']        = "profile/lists_posts/update/$1";
$route['manage_lists/(:any)/resort_posts']     = "profile/lists_posts/resort/$1";
$route['manage_lists/(:any)/edit_post/(:any)'] = "profile/lists_posts/update/$1/$2";
$route['manage_lists/(:any)']                  = "profile/lists/update/$1";

//Loaded via modules:run and on autoscroll
$route['profile_folder/(:any)']                = 'profile/profile_folder/collections/$1'; //lists autoscroll
$route['activity/(:any)']                      = "profile/activity/index/$1"; //for activity autoscroll

$route['get_feature_drops']                    = "profile/profile_newsfeed/feature_drops";

//$route['connect_fb']                         = "profile/profile_social/connect_fb"; //this is in signup
$route['disconnect_fb']                        = "profile/profile_social/disconnect_fb";
$route['enable_fb_activity']                   = "profile/profile_social/enable_fb_activity";
$route['disable_fb_activity']                  = "profile/profile_social/disable_fb_activity";

$route['enable_twitter_activity']              = "profile/profile_social/enable_twitter_activity";
$route['disable_twitter_activity']             = "profile/profile_social/disable_twitter_activity";
$route['disconnect_twitter']                   = "profile/profile_social/disconnect_twitter";

//RR - Currently these are moved to last position by MX/Modules->list_modules() but this is confusing 
$route['([a-zA-Z0-9_-]+)']                              = "profile/profile/get/collections/$1";
$route['([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)']             = "folder/folder/get/$1/$2";
$route['([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/type/(:any)'] = "folder/folder/get/$1/$2/false/$3";
