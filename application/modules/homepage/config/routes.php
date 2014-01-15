<?php

$route['signup_thanks']                               = "homepage/main/index";
$route['signup_thanks/:any']                          = "homepage/main/index";

$route['i/(:any)']                                    = "homepage/main/index";

$route['recent']                                      = "homepage/main/index/recent";
$route['people-you-follow']                           = "homepage/main/index/recent";
$route['people-you-follow/(:any)/type/(:any)']        = "homepage/main/index/recent/$1/$2";
$route['people-you-follow/(:any)']                    = "homepage/main/index/recent/$1";

$route['drops-by-everyone']                           = "homepage/main/index/popular";
$route['drops-by-everyone/(:any)/type/(:any)']        = "homepage/main/index/popular/$1/$2";
$route['drops-by-everyone/(:any)']                    = "homepage/main/index/popular/$1";

$route['my-feed'] = 'homepage/main/my_feed';

//These routes are the optimized ones used by autoscroll
$route['homepage/newsfeed/(:any)/(:any)']             = 'homepage/home_newsfeed/$1/$2'; //category_type(recent_popular)
$route['homepage/newsfeed/(:any)']                    = 'homepage/home_newsfeed/$1';    //category type (recent|popular)
$route['home/folders/(:any)']                         = 'homepage/home_folders/$1';

$route['popular_collections']                  		  = "homepage/home_folders/popular_folders";
//$route['homepage/home_folders/popular_folders']       = "homepage/home_folders/popular_folders";

/** Internal scraper **/
$route['internal_scraper']                        = "homepage/internal_scraper/index";
$route['internal_scraper/get_content']            = "homepage/internal_scraper/get_content";
$route['internal_scraper/get_cached_content']     = "homepage/internal_scraper/get_content/true";

//??
$route['oauth2callback']                          = "homepage/main/index";

//$route['category/(:any)']                             = "homepage/main/landing_page/popular/$1/category";
//$route['(screenshots|videos|pictures|texts|clips)']   = "homepage/main/index/popular";

//$route['newsfeed/get_limited']                        = 'homepage/main/get_newsfeed_limited';
//$route['newsfeed/get_limited/(:any)']                 = 'homepage/main/get_newsfeed_limited/$1';

//$route['homepage/main/get_newsfeed_new/(:any)']                  = 'homepage/main/get_newsfeed_new/$1';
//$route['homepage/main/get_newsfeed_new/(:any)/(:any)']           = 'homepage/main/get_newsfeed_new/$1/$2';
//$route['homepage/main/get_newsfeed_new/(:any)/(:any)/(:any)']    = 'homepage/main/get_newsfeed_new/$1/$2/$3';
//$route['homepage/main/cache_newsfeed']                           = 'homepage/main/cache_newsfeed';

//Three lines above are the same as 3 lines below, have to choose between 2 options or come up with other URLs
//$route['newsfeed/home/(:any)']                        = 'homepage/main/get_newsfeed_new/$1';
//$route['newsfeed/home/(:any)/(:any)']                 = 'homepage/main/get_newsfeed_new/$1/$2';
//$route['newsfeed/home/(:any)/(:any)/(:any)']          = 'homepage/main/get_newsfeed_new/$1/$2/$3';

