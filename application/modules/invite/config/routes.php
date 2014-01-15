<?php
$route['invites']                                     = "invite/invite/index";
$route['invites/(:any)']                              = "invite/invite/index/$1";
$route['invite_facebook']                             = "invite/invite/invite_facebook";
$route['invite_email']                                = "invite/invite/invite_email";
$route['invite_gmail']     						      = "invite/invite/invite_gmail";
$route['invite_yahoo']     						      = "invite/invite/invite_yahoo";

//Called via ajax on facebook invite success
$route['invited_users']                               = "invite/invite/invited_users";

//called with ajax on info dialog open to clear the "invite_more" session var
$route['info_dialog_opened']                          = "invite/invite/info_dialog_opened";

//Auth
$route['gmail_auth']								  = "invite/auth_api/gmail";
$route['yahoo_email_auth']							  = "invite/auth_api/yahoo";

//Old
//$route['fb_invite_list']							  = "invite/facebook_invite/fb_invite_list";
//$route['fb_friends_list']							  = "invite/facebook_invite/fb_friends_list";

