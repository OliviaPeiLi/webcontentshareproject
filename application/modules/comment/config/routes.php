<?php

//List
$route['comments/(:num)']                             = "comment/comment/index/$1";
$route['comments/folder/(:num)']                      = "comment/comment/folder_comments/$1"; //for the autoscroll (not used)

//Create
$route['comment']                                     = "comment/comment/create";

//Remove
$route['del_comm/(:any)']		                      = "comment/comment/remove/$1";


//RR - doesnt seem to be used
//$route['get_up_data/(:num)/(:any)']                   = "comment/comment/get_up_data/$1/$2";
//$route['get_up_data']                                 = "comment/comment/get_up_data";
