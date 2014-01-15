<?php

$route['main_search']                                 = "search/search/index";

//search
$route['search/test(:any)']                           = "search/search/test$1/$2";

$route['search/collections']                           = "search/search/collections_search";
$route['search']                                      = "search/search/main";
$route['(aww|celebs|food|funny|gaming|music|sports|tech|wtf|travel|entertainment)'] = 'search/search/hashtag/$1';

$route['search/drops']                                = "search/search/drops_search";
$route['search/drops/type/(:any)']                    = "search/search/drops_search/$1";

$route['hashtags']									  = "search/get_hashtags";
$route['post_referrals/(:any)']						  = "search/post_referrals/$1";

$route['source/(:any)']                               = "search/drops_search/source/$1";

$route['search/people']                               = "search/users/people";
$route['search/ajax_people']                          = "search/users/ajax_people";

$route['search/rss_source']                           = "search/collections/rss_source";
