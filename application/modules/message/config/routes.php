<?php
//Thread
$route['messages']                  = "message/thread/index"; //User inbox - list of threads
$route['view_msg/(:any)']           = "message/thread/get/$1"; //List a thread with messages
$route['del_thread/(:num)']         = "message/thread/delete/$1"; //Delete a thread
$route['send_msg']                  = "message/thread/create";

//Message
$route['reply_msg']                 = "message/message/create";
$route['del_msg/(:num)']	        = "message/message/delete/$1";

