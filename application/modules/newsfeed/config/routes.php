<?php

$route['newsfeed']                                    = 'newsfeed'; //Defines the default controller

//The new CRUD structure - GET
$route['drop/(:any)']                                 = "newsfeed/newsfeed/get/$1";
$route['popup-right/(:num)']                          = 'newsfeed/newsfeed/popup_right/$1';
$route['popup-info/(:any)/extended']                  = "newsfeed/newsfeed/get_post_details/$1/extended";
$route['popup-info/(:any)']                   		  = "newsfeed/newsfeed/get_post_details/$1";

//UPDATE
$route['newsfeed/edit']                               = 'newsfeed/newsfeed/update';
$route['add_referral']                                = 'newsfeed/newsfeed_referrals/create';

//LIST
$route['newsfeed/source/(:any)']                      = 'newsfeed/newsfeed/source/$1';
$route['newsfeed/group/(:any)']                       = "newsfeed/newsfeed/group/$1";

//DELETE
$route['del_link/(:num)']                             = "newsfeed/newsfeed/delete/$1";

//Used by the email templates
$route['newsfeed/thumb/(:num)']                       = 'newsfeed/newsfeed/thumb/$1';

//End new structure

//$route['newsfeed/(likes|upvotes|mentions|comments|images|clips|screenshots|videos|texts|source)/(:any)'] = "newsfeed/newsfeed_controller/get_library_newsfeed/$2/$1";

//$route['newsfeed/library /(:any)']                     = "newsfeed/newsfeed_controller/get_library_newsfeed/$1";

//$route['newsfeed/drops']                              = "newsfeed/newsfeed_controller/get_library_newsfeed";

//$route['newsfeed/drops/(:any)']                       = "newsfeed/newsfeed_controller/get_library_newsfeed/$1/drops";

// newsfeed controler
//$route['newsfeed/library']                            = "newsfeed/newsfeed_controller/get_library_newsfeed";

//$route['newsfeed/drops']                            = "newsfeed/newsfeed_controller/get_newsfeed";
//$route['newsfeed/likes']                            = "newsfeed/newsfeed_controller/get_newsfeed_likes";

//$route['newsfeed/activities/profile/(:any)']          = "newsfeed/newsfeed_controller/get_activity_feed/$1";
//$route['newsfeed/set_height/(:num)/(:num)']           = "newsfeed/newsfeed_controller/set_height/$1/$2";

// Newsfeed controller
//$route['drop-edit-thumb']                             = "newsfeed/newsfeed_update/edit";
