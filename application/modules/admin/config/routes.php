<?php

$route['admin']                                       = 'admin/dashboard'; //Defines the default controller

$route['alpha']                                       = "admin/alpha/index";
$route['send_alpha_email']                            = "admin/alpha_user/send_alpha_email";
$route['send_user_email']                             = "admin/users/send_user_email";

//for admin controller
//$route['admin']                                       = "admin/admin/index";
//$route['admin/:any']                                = "admin/admin/index";
$route['admin_logout']                                = "admin/admin/logout";
$route['admin_page']                                  = "admin/admin/manage_pages";
$route['make_official/:any']                          = "admin/admin/make_official";
$route['page_aliases']                                = "admin/admin/page_aliases";
$route['proc_alias/:any']                             = "admin/admin/proc_aliases";
$route['peopleshouldknow/:any']                       = "admin/admin/peopleshouldknow";
$route['merge_page']                                  = "admin/admin/merge_interests";
$route['manage_favorite']                             = "admin/admin/manage_questions";
$route['question_display/:any']                       = "admin/admin/question_display";
$route['question_rm/:any']                            = "admin/admin/question_rm";
$route['edit_question/:any']                          = "admin/admin/edit_question";
$route['save_question']                               = "admin/admin/save_question";
$route['delete_page']                                 = "admin/admin/delete_page";

//for transfer_controller
$route['transfer_page']                               = "admin/transfer_controller/transfer_data";
$route['transfer_img']                                = "admin/transfer_controller/upload_wiki_photo";
$route['transfer_img/:any']                           = "admin/transfer_controller/upload_wiki_photo";
$route['update_abstract']                             = "admin/transfer_controller/update_abstract";
$route['fix_categories']                              = "admin/transfer_controller/fix_categories";
$route['t_fb_pageids']                                = "admin/transfer_controller/transfer_fb_pageids";
$route['transfer_twitter_id']                         = "admin/transfer_controller/transfer_twitter_id";
$route['update_empty_image']                          = "admin/transfer_controller/update_empty_img";
$route['show_update_empty_image_progress/:any']       = "admin/transfer_controller/show_update_empty_img_progress";

$route['admin_stats']                                 = 'admin/stats/ajax';

$route['admin/(:any)']                                = 'admin/$1';
$route['admin/(:any)/(:any)']                         = 'admin/$1/$2';
$route['admin/(:any)/(:any)/(:any)']                  = 'admin/$1/$2/$3';
$route['admin/(:any)/(:any)/(:any)/(:any)']           = 'admin/$1/$2/$3/$4';
$route['admin/(:any)/(:any)/(:any)/(:any)/(:any)']    = 'admin/$1/$2/$3/$4/$5';
