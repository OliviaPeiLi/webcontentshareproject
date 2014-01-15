<?php

$route['signup']                                  = "signup";
$route['(growthathon|catathon|stanford|erlibird|team|superbowl|mashable|valentines|techcrunch)'] = "signup/signup/index/$1";

$route['request_invite']			                = "signup/request_invite";
$route['request_invite_fb']			              = "signup/request_invite/fb_index";
$route['validate_invited_email']			        = "signup/request_invite/validate_invited_email";

//Step 1
$route['signup/form']							  = "signup/step1";
$route['signup_error']                            = "signup/signup_error";
$route['validate_email']                          = "signup/validate_email";
$route['validate_username']                       = "signup/validate_username";
$route['validate_contest']                        = "signup/validate_contest";
$route['twitter_after']                           = "signup/twitter_afterlogin";
$route['facebook_after']                          = "signup/facebook_afterlogin";

//Step 2
$route['choose_category']						  = "signup/step2";

//Step 3
$route['signup_walkthrough']                      = "signup/step3";

//Step 4
$route['signup_invite']                           = "signup/step4";

///Login
$route['signin']								  = "signup/login";
$route['get_csrf']								  = "signup/login/get_csrf_token";
$route['login/twitter_after']                     = "signup/login/twitter_afterlogin";
$route['login/facbook_after']                     = "signup/login/facebook_afterlogin";
$route['fb_login']                                = "signup/login/facebook_afterlogin";
$route['set_fb_data']                             = "signup/login/set_fb_data";
$route['set_fb_data/(:any)']                      = "signup/login/set_fb_data/$1";
$route['twitter']                                 = "signup/login/twitter_login";
$route['twitter/(:any)']                          = "signup/login/twitter_login/$1";
$route['twitter_connected']                       = "signup/login/twitter_connected";
$route['tweet']                                   = "signup/login/tweet";

$route['signup/edit_picture']					  = "signup/signup/edit_picture";

$route['logout']                                  = "signup/login/logout";


//forget password
$route['forgotpassword']                          = "signup/forgetpassword/index";
//the link from the email
$route['resetpassword/(:any)/(:any)']             = "signup/forgetpassword/reset/$1/$2";

//checker
$route['checker/(:any)']                          = 'signup/checker_controller/$1';


//////////////////////////////////////////////////
/*
//signup controller
$route['twitter_callback']                            = "signup/signup/callback";
$route['twitter_redirect']                            = "signup/signup/redirect";
$route['fandrop_signup']                              = "signup/signup/fandrop_signup";
$route['fandrop_signup/i/(:any)']                     = "signup/signup/fandrop_signup";

//signup update controller
$route['signup_landing']                              = "signup/signup_update/insert_email";
$route['signup_save_email']                           = "signup/signup_update/save_email";
$route['signup_save_email/i/(:any)']                  = "signup/signup_update/save_email/i/$1";


//login controller
$route['login']                                       = "signup/login/validate_credentials";
$route['signin']									  = "signup/login/signin";

//register
$route['getting_started']                             = "signup/register/add_interests";
$route['validate_username/(:any)']                    = "signup/register/validate_username/$1";
$route['validate_dob']                                = "signup/register/validate_dob";
$route['save_more_info']                              = "signup/register/save_more_info";
$route['choose_category']							  = "signup/register/show_category";
$route['signup/edit_picture']						  = "signup/register/edit_picture";

// register update controller
$route['twitter_after/:any']                          = "signup/register/twitter_afterlogin";
$route['signup/:any']                                 = "signup/register_update/basic_info";
$route['create_user']                                 = "signup/register_update/save_basic_info";
$route['show_more_info']                              = "signup/register_update/show_more_info";
$route['show_more_info/:any']                         = "signup/register_update/show_more_info";
$route['preview_info']                                = "signup/register_update/preview_info";
$route['save_more_links']                             = "signup/register_update/save_more_links";
$route['fb_signup'] 								  = "signup/register_update/fb_signup";
$route['default_collections']						  = "signup/register_update/default_collections";

//get csrf token

$route['signuptest'] = "signup/signuptest/index";
*/
