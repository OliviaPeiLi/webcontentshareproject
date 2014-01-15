<?php

// bookmarklet controller
//For scripts used externally
$route['load_web_scraper/bookmarklet.js']    = "bookmarklet/scripts/js"; //get the latest version
$route['load_web_scraper/external.js']       = "bookmarklet/scripts/external"; //loads the bookmarklet
$route['load_web_scraper/maintenance.js']    = "bookmarklet/scripts/maintenance"; //loads the bookmarklet
$route['bookmarklet_embed.js']       		 = "bookmarklet/scripts/embed_js";
$route['get_embed_count.js']	       		 = "bookmarklet/scripts/get_embed_count";
$route['bookmarklet/pdf2html']               = 'bookmarklet/scripts/bookmarklet/pdf2html';
$route['bookmarklet/update_cache']           = 'bookmarklet/scripts/update_cache';
$route['bookmarklet/watchdog']               = "bookmarklet/scripts/watchdog";
//sent from the scripts server
$route['refresh_cache/(:num)']               = 'bookmarklet/scripts/refresh_cache/$1';

//Bookmarklet - GET
$route['bookmarklet']                 = "bookmarklet/bookmarklet/index";
$route['bookmarklet/bar']             = "bookmarklet/bookmarklet/index";
$route['bookmarklet/external_login']  = "bookmarklet/bookmarklet/external_login";
$route['bookmarklet/popup']           = "bookmarklet/bookmarklet/popup";
$route['bookmarklet/success']         = "bookmarklet/bookmarklet/success";

//Bookmarklet - CREATE
//$route['bookmarklet/screenshot']                      = 'bookmarklet/bookmarklet_update/screenshot';
$route['bookmarklet/add_image']                       = 'bookmarklet/bookmarklet/create';
$route['bookmarklet/add_image_after/(:num)']          = 'bookmarklet/bookmarklet/add_image_after/$1';
$route['bookmarklet/add_html_after/(:num)']           = 'bookmarklet/bookmarklet/add_html_after/$1';
$route['bookmarklet/add_page_after/(:num)']           = 'bookmarklet/bookmarklet/add_page_after/$1';

//Bookmarkket - UPDATE @deprecated
//$route['bookmarklet/edit']                            = 'bookmarklet/bookmarklet_update/edit';
//$route['bookmarklet/settings']                        = 'bookmarklet/bookmarklet_update/settings';
//$route['turn_off_bookmarklet_noti']                   = 'bookmarklet/bookmarklet_update/turn_off_bookmarklet_noti';

//Bookmarklet - Delete @deprecated
//$route['bookmarklet/delete']                          = 'bookmarklet/bookmarklet_update/delete';


//Internal
$route['bookmarklet/snapshot_preview/(:num)'] = "bookmarklet/internal/snapshot_preview/$1";
