<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/


$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '173.255.253.241';

if(ENVIRONMENT == 'development'){
	$db['default']['hostname'] = 'localhost';
}

//For Alex's local server
if(strpos(BASEPATH, 'fantoon.loc') !== false){
	$db['default']['hostname'] = 'localhost';
}

$db['default']['username'] = 'fantoon_ci';
$db['default']['password'] = 'zVv3ZG5pyAvVQY9P';
$db['default']['database'] = 'fantoon_ci';

if (preg_match("/quangphan/", BASEPATH)){
	// local machine of quang
	$db['default']['hostname'] = 'localhost';
	$db['default']['username'] = 'root';
	$db['default']['password'] = 'quangphan';
}

if(ENVIRONMENT == 'staging'){
	//$db['default']['hostname'] = '10.35.54.182';
	$db['default']['hostname'] = 'localhost';
	$db['default']['username'] = 'fantoon_ci';
    $db['default']['password'] = 'zVv3ZG5pyAvVQY9P';
    $db['default']['database'] = 'fantoon_ci';
}

if(ENVIRONMENT == 'production'){
    //$db['default']['hostname'] = '10.35.54.182:3306';
    $db['default']['hostname'] = 'fandropdb.cqziev1rquow.us-east-1.rds.amazonaws.com';//'192.168.0.2';
    $db['default']['username'] = 'fandrop';//'fantoon_db';
    $db['default']['password'] = 'tuhermana32';//'6axAjXSmcpXpFBVK';
    $db['default']['database'] = 'fandrop_db';
}

$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE; 
