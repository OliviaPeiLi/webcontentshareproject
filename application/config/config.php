<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['default_newsfeed_view'] = 'timeline_new';

$config['google_api_key'] = 'ABQIAAAA03fm_TWiFxW8uhHS9yZ26RSxCJiJLoB1IR156elRCYMnpY12pRS8yfSIQIrAQutLhV5Qdfc3_G7M5A';

$config['leaderboard_info'] = array('test','stanford','erlibird','growthathon','catathon','team','superbowl','mashable','valentines');

//$config['avatar_color'] = array('blue','green','indigo','orange','red','violet','yellow');
$config['avatar_color'] = array('blue');

//main hashtags
if(ENVIRONMENT == 'production'){
	$config['km_key'] = 'aea0bf276ea776b8349a9c9189f2435184fa98f1';
	$config['hashtag_power_users'] = array('_hash_Aww'=>array(23964, 4402), //#Aww - Sally, Victor
											'_hash_Celebs'=>array(12644, 23964),	//#Celebs - Liz, Sally
											'_hash_Food'=>array(12644, 50205),	//#Food -Dmitry, Liz
											'_hash_Funny'=>array(49964, 23964, 24305),	//#Funny -Alexi, Torrance, Sally
											'_hash_Gaming'=>array(50324, 332),	//#Gaming - Joel, Rob
											'_hash_Hot'=>array(332, 307),	//#Hot - Joel, Clayton
											'_hash_Music'=>array(50324, 50004),	//#Music - Alvin, Rob
											'_hash_Sports'=>array(50004, 24305, 50255),	//#Sports - Alvin, Ray, Torrance
											'_hash_Tech'=>array(50255, 50205, 1462, 14),	//#Tech -Ray, Vi, Dmitry, Kenzi
											'_hash_WTF'=>array(1462, 14, 307));	//#WTF - Vi, Clayton, Kenzi
}elseif(ENVIRONMENT == 'staging'){
	$config['km_key'] = 'd652d7a9c10a1aaf218eb24e26e6b6c437fe139f';
	$config['hashtag_power_users'] = array('_hash_Aww'=>array(1),
											'_hash_Celebs'=>array(1),
											'_hash_Food'=>array(1),
											'_hash_Funny'=>array(1),
											'_hash_Gaming'=>array(1),
											'_hash_Hot'=>array(1),
											'_hash_Music'=>array(1),
											'_hash_Sports'=>array(1),
											'_hash_Tech'=>array(1),
											'_hash_WTF'=>array(1));
}else{
	$config['km_key'] = '777d28b4065850508bcbe1f7f6a3287e6129e672';
	$config['hashtag_power_users'] = array('_hash_Aww'=>array(43),
											'_hash_Celebs'=>array(43),
											'_hash_Food'=>array(43),
											'_hash_Funny'=>array(43),
											'_hash_Gaming'=>array(43),
											'_hash_Hot'=>array(43),
											'_hash_Music'=>array(43),
											'_hash_Sports'=>array(43),
											'_hash_Tech'=>array(43),
											'_hash_WTF'=>array(43));
}

if(ENVIRONMENT == 'production' || ENVIRONMENT == 'staging'){
	$config['power_users'] = array('102','99','2','14','27');
	
}else{
	$config['power_users'] = array('4','43');
	
}

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
//The check is performed because of the unittests
$config['base_url']	= '';

$config['upload_path'] = substr(BASEPATH, 0, strrpos(BASEPATH, '/', -2)).'/uploads/';
$config['upload_url'] = '/uploads/';

/*
|--------------------------------------------------------------------------
| Twitter API config
|--------------------------------------------------------------------------
*/
//$config['twtr_api_key']	= "cNaILSWUyxJRXNHmmMYw";
//$config['twtr_api_secret']	= "pjVb4dX2AwsWHsogUiZl5ZNFCMLLqYGQYJLa9HqnL3s";

if(isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'fantoon.com') > 0) {
	//fantoon
	$config['twtr_api_key']	= "EaqkUTdSnAyWJKydWQ24Bw";
	$config['twtr_api_secret']	= "KNBKJqN24S5G4GXV7ymXGovGmZAtIsKyp4bq9yK03o";
} else {
	//fandrop
	$config['twtr_api_key']	= "j76HDN45ZVs0T2a6HIBg";
	$config['twtr_api_secret'] = "Ix9Ss0ueYNbqbKbjYd2Lm9n00KCQpGaG3bPJ4l5tY";
}

/*
|--------------------------------------------------------------------------
| Facebook API config
|--------------------------------------------------------------------------
*/
//$config['fb_api']	= "3e779b641bc57f7b0c029dcda75a0a1a";
if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'fantoon.local' || $_SERVER['HTTP_HOST'] == 'localhost') ) {
	$config['fb_app_key']	= "172910572812315";
	$config['fb_app_secret']	= "9f40fcdb569b3153260d1066d5c22724";
	$config['fb_app_namespace']	= "fantoon_local";
} elseif(isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'fantoon.com') > 0) {
	//fantoon
	$config['fb_app_key']	= "115671641849040";
	$config['fb_app_secret']	= "cab2200bdbf1a5a2e4df0bd6a1bdd9e0";
	$config['fb_app_namespace']	= "fantoon";
	//$config['fb_app_key']	= "315227451867064";
	//$config['fb_app_secret']	= "d13a9a8692fed202cf1d0d5388edae67";
	//$config['fb_app_namespace']	= "theshots";
	$config['access_token'] = "115671641849040|M52NVrABH5b-ntwvJg34X9vkd7U";
} else {
	//for fandrop
	$config['fb_app_key']	= "315227451867064";
	$config['fb_app_secret'] = "d13a9a8692fed202cf1d0d5388edae67";
	$config['fb_app_namespace']	= "fandrop";
	$config['access_token'] = "315227451867064|7sF1pdE5Hga889CedeUdhWxv0Yo";
	//get access token from here: https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id=315227451867064&client_secret=d13a9a8692fed202cf1d0d5388edae67
}

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';
$config['cache_expire'] = 60*60*2;  //2 hours

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'vyZKpEkRsiHTXR8k7hvoTKVt856yvFoV';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 0;
$config['sess_expire_on_close']	= TRUE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= TRUE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= @$_SERVER['HTTPS'] == 'on';

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = TRUE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'ci_csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 60*60*24; //1 day

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';

/**
 * Modules config
 */
/* 
 $config['modules_locations'] = array(
        APPPATH.'modules/' => '../modules/', //Modules
    );
 */

$config['default_url'] = 'profile/main/profile';
//'profile' has be the last one, because we enable the username for url

$config['undefined_var_log'] = str_replace('system/', '', BASEPATH) . "view_variables" . DIRECTORY_SEPARATOR . "undefined_var_log.csv";
$config['undefined_var_serialize'] = false;

/* End of file config.php */
/* Location: ./application/config/config.php */

//Move to s3.php

if(ENVIRONMENT == 'production'){
    $config['s3_bucket']	= "fantoon";
    $config['s3_url']	= "https://d17tpoh2r6xvno.cloudfront.net";
}else{
    $config['s3_bucket']	= "fantoon-dev";
    $config['s3_url']	= "https://s3.amazonaws.com/fantoon-dev";
}

?>
