<?php

$route['notification']          = 'notification'; //Defines the default controller

$route['show_all']              = "notification/notification/get_all";
$route['notifications_read']    = "notification/notification/get_read";
$route['mark_as_read']          = "notification/notification/update_mark_read";

$route['system_notification']   = "notification/system_notification/get";
$route['system_notification_close/(:num)'] = "notification/system_notification/remove/$1";
