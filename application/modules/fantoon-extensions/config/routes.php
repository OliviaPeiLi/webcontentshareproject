<?php
//contest
$route['winsxsw/(:any)/dashboard'] = "fantoon-extensions/contest/sxsw_dashboard/$1";
$route['(:any)/(:any)/dashboard'] = "fantoon-extensions/contest/dashboard/$1/$2";
$route['(:any)/(:any)/submit']    = "fantoon-extensions/contest/add_item/$1/$2";

//simple contest
$route['(:any)/dashboard'] = "fantoon-extensions/contest/dashboard/$1/$1";
$route['(:any)/submit']    = "fantoon-extensions/contest/add_item/$1/$1";

$route['contest/save']             = "fantoon-extensions/contest/save";
$route['contest/create']           = "fantoon-extensions/contest/create";
$route['contest/edit']             = "fantoon-extensions/contest/update";
$route['contest/(:any)']           = "fantoon-extensions/contest/create";

$route['time/set']           = "fantoon-extensions/time/set_client_time";

//winsxsw
$route['winsxsw/(:any)/dashboard'] = "fantoon-extensions/contest_dashboard/sxsw_dashboard/$1";

//$route['winsxsw/self_submission/form'] = "fantoon-extensions/contest/form";
//$route['winsxsw/self_submission/save'] = "fantoon-extensions/contest/sxsw_save";

$route['demo/quiz'] = "fantoon-extensions/demo/quiz";
