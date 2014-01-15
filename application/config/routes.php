<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller']                        = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/
$route['404_override']                               = '404';
/*
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|

*/



$route['default_controller']                          = "homepage/main/index";
$route['/']                                           = "homepage/main/index";
$route['404_override']                                = '';

// this is common route because there is no module
//recommendations controller
/*$route['gen_similarity/:any']                         = "recommendations/gen_similarity/index";
$route['page_suggestion']                             = "recommendations/recommendations/index";
$route['getPagesSimilarity']                          = "recommendations/recommendations/getPagesSimilarity";
$route['getUserSimilarity']                           = "recommendations/recommendations/getUserSimilarity";
$route['buildPageSimDataset']                         = "recommendations/recommendations/buildPageSimDataset";
$route['buildUserSimDataset']                         = "recommendations/recommendations/buildUserSimDataset";
$route['getUserPreferences']                          = "recommendations/recommendations/getUserPreferences";
$route['getUserSimRecs']                              = "recommendations/recommendations/getUserSimRecs";
$route['getUserFriendsRecs']                          = "recommendations/recommendations/getUserFriendsRecs";
$route['getRecommendations']                          = "recommendations/recommendations/getRecommendations";

//custom_tab controller
$route['activate/:any']                               = "custom_tab/custom_tab/activate";
$route['dectivate/:any']                              = "custom_tab/custom_tab/dectivate";
$route['del_tab/:any']                                = "custom_tab/custom_tab/delete_tab";
$route['del_component/:any']                          = "custom_tab/custom_tab/delete_component";
$route['sort_components/:any']                        = "custom_tab/custom_tab/sort_components";
$route['tab_name/:any']                               = "custom_tab/custom_tab/edit_name";
$route['add_text/:any']                               = "custom_tab/custom_tab/add_text";
$route['component/:any']                              = "custom_tab/custom_tab/edit_component";
$route['new_tab/:any']                                = "custom_tab/custom_tab/new_tab";

//$route['create_loop']                               = "loop/loops_controller/create_loop";
$route['new_loop']                                    = "loop/loops_controller/new_loop";
$route['edit_loop/:num']                              = "loop/loops_controller/edit_loop";
$route['update_loop']                                 = "loop/loops_controller/update_loop";
$route['rm_loop/:num']                                = "loop/loops_controller/rm_loop";
$route['loop/:any']                                   = "loop/loops_controller/loop_page";
$route['get_loops_for_post']                          = "loop/loops_controller/get_loops";
$route['loops_user_is_in']                            = "loop/loops_controller/get_user_loops";
//$route['build_loop']                                = "loop/loops_controller/build_mainloop";


//photo controller
$route['photo_albums/:any']                           = "photos/photos_controller/get_albums";
$route['get_album_cover/:any']                        = "photos/photos_controller/get_album_cover";
$route['view_photos/:any']                            = "photos/photos_controller/get_photos";
$route['view_profile_photos/:any']                    = "photos/photos_controller/get_profile_photos";
$route['my_photos']                                   = "photos/photos_controller/my_photos";
$route['show_photo/:any']                             = "photos/photos_controller/show_photo";
$route['get_photo_info/:any']                         = "photos/photos_controller/get_photo_info";
//tags_controller
$route['new_tags']                                    = "photos/tags_controller/post_tags";
$route['get_tags/:any']                               = "photos/tags_controller/get_tags";
$route['get_all_tags/:any']                           = "photos/tags_controller/get_all_tags";
$route['get_interests_tags/:any']                     = "photos/tags_controller/get_item_tags";
$route['post_tags/:any']                              = "photos/tags_controller/post_tags";
$route['post_interests_tags/:any']                    = "photos/tags_controller/post_item_tags";
$route['post_all_tags/:any']                          = "photos/tags_controller/post_all_tags";


//privacy controller
$route['privacy']                                     = "privacy/privacy_controller/privacy_page";
*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */
