<?php

$route['folder']                       = 'folder_controller'; //Defines the default controller

//GET
$route['collection/(:any)/(:any)/(:any)']   = "folder/folder/get/$1/$2/$3"; //This is shown in the redrop success popup - the folder uri there is not 100% accurate so we need to use the id
$route['collection/(:any)/(:any)']          = "folder/folder/get/$1/$2";
$route['collection/:any']                   = "folder/folder/get";
$route['embed/collection/(:any)']           = "folder/folder/embed/$1";
$route['validate_collection/(:any)']        = "folder/folder/validate_collection/$1";
$route['validate_collection']               = "folder/folder/validate_collection";

//UPDATE
$route['folder_edit_basic']                 = "folder/folder/update";
$route['collect_into_folder']               = "folder/folder/redrop";
$route['set_landing_folder/(:num)']         = "folder/folder/set_landing/$1";

//DELETE
$route['delete_folder/(:any)']              = "folder/folder/delete/$1";
$route['rem_landing_folder/(:num)']         = "folder/folder/rem_landing/$1";

//OLD
$route['newsfeed/collection/(:any)']        = 'folder/folder_newsfeed/index/$1';

$route['follow_folder/(:any)']              = "folder/folder_follow/create/$1";
$route['unfollow_folder/(:any)']            = "folder/folder_follow/delete/$1";
$route['create_collection']            		= "folder/folder/create_collection";

