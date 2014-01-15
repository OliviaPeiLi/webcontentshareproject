<?php
//Up/unup comment
$route['add_like/comment/(:num)']                   = "like/comment/create/$1";
$route['rm_like/comment/(:num)']                    = "like/comment/remove/$1";

// Up/unup drop
$route['add_like/drop/(:num)']                      = "like/drop/create/$1";
$route['rm_like/drop/(:num)']                       = "like/drop/remove/$1";

// Up/unup collection
$route['add_like/folder/(:num)']                    = "like/folder/create/$1";
$route['rm_like/folder/(:num)']                     = "like/folder/remove/$1";

//Backward compatibility
$route['add_like/link/(:num)']                      = "like/link/create/$1";
$route['rm_like/link/(:num)']                       = "like/link/remove/$1";