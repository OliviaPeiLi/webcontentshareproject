<?php
$route['add_share/(:any)']              = "share/drop/create/$1";

//Backward compatibility
//@todo - cleanup
$route['check_fb_drop']                 = "share/drop/get/fb";
$route['insert_fb_drop']                = "share/drop/create/fb";

$route['share_email']					= "share/share/share_email";
