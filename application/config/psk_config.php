<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// weight of factors
$config['age_weight']	= 0.10;
$config['work_weight']	= 0.20;
$config['location_weight']	= 0.15;
$config['page_weight']	= 0.45;
$config['topic_weight']	= 0.05;

// (point=25) <= 5 < (point=20) <= 10 < (point=15) <= 15 < (point=10) <= 20 < (point=5) <= 25 < (point=0)
$config['age_level_1']	= 5;
$config['age_level_2']	= 10;
$config['age_level_3']	= 15;
$config['age_level_4']	= 20;
$config['age_level_5']	= 25;

$config['age_point_1']	= 25;
$config['age_point_2']	= 20;
$config['age_point_3']	= 15;
$config['age_point_4']	= 10;
$config['age_point_5']	= 5;

// point when having same company
$config['work_point']	= 20;

// point when having same place of current/travel
$config['place_point']	= 20;

// (point=25) <= 5 < (point=20) <= 10 < (point=15) <= 15 < (point=10) <= 20 < (point=5) <= 25 < (point=0)
// unit = kilometer
$config['distance_level_1']	= 5;
$config['distance_level_2']	= 10;
$config['distance_level_3']	= 15;
$config['distance_level_4']	= 20;
$config['distance_level_5']	= 25;

$config['distance_point_1']	= 25;
$config['distance_point_2']	= 20;
$config['distance_point_3']	= 15;
$config['distance_point_4']	= 10;
$config['distance_point_5']	= 5;

/* End of file myconfig.php */
/* Location: ./application/config/myconfig.php */
