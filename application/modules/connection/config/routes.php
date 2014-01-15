<?php
$route['ac_get_connections']                          = "connection/connection/index";

$route['follow_user/(:num)']                          = "connection/connection/create/$1";
$route['unfollow_user/(:num)']                        = "connection/connection/delete/$1";
//connect controller
