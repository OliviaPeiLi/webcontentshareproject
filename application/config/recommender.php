<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
| weights used to calculate similarity between diferent items; all weights must be between 0 and 1
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| page similarity weights
*/
$config['wtPageTopics']	= 0.3;
$config['wtPageUserRatings'] = 0.7;

/* 
| user similarity weights
*/
$config['wtUserAge'] = 0.2;
$config['wtUserGender'] = 0.1;
$config['wtUserExperience'] = 0.7;

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
| weights used to calculate recommendations
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
*/
$config['wtRecUserPreferences'] = 0.5;
$config['wtRecSimilarUsers'] = 0.15;
$config['wtRecUsersFriends'] = 0.15;
$config['wtRecAdmin'] = 0.2;

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
| max count of friends and similars used in recommendations calculus; max number of recommendations returned
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
*/
$config['maxFriends'] = 5;
$config['maxSimilars'] = 5;
$config['maxRecommendations'] = 3;

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
| execution timeout; keep in mind that building similarity datasets could take a while!
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
*/
$config['executionTimeout'] = 1800;

