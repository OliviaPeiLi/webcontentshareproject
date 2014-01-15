<?php
/**
 * Base file for executing qunit tests
 * @link http://tools.fantoon.com/dokuwiki/doku.php?id=automated_tests
 */

$testUsers = array(
	array( 'user' => 'test.user1@example.com', 'pass' => 'lFDvlksDF' ),
	array( 'user' => 'test.user2@example.com', 'pass' => 'lkfdEFdxcXV' )
);

define('qunit_user', $testUsers[0]['user']);
define('qunit_pass', $testUsers[0]['pass']);

if (strpos(PHP_OS, 'WIN') !== false) {
	define('OS', 'windows');
} elseif (strpos(PHP_OS, 'Linux') !== false) {
	define('OS', 'linux');
} elseif (strpos(PHP_OS, 'Darwin') !== false) {
	define('OS', 'mac');
} else {
	echo 'Operating system not recognized';
	exit();
}

$tests = array();
$args = $_SERVER['argv'];
unset($args[0]);
foreach ($args as $arg) {
	if (substr($arg, 0,2) == '--') { //is setting
		list($arg, $val) = explode('=', $arg, 2);
		switch ($arg) {
			case '--host': define('HOST', $val);
		}
	} else {
		$tests = array($arg);
	}
}

if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
	define('BASEPATH', '/home/fandrop/current/system/');
	define("HOST", "https://www.fandrop.com");
	require_once 'application/config/database.php';
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
	define('BASEPATH', '/home/test.fandrop/current/system/');
	define("HOST", "https://test.fandrop.com");
	require_once 'application/config/database.php';
}else{
   	define('ENVIRONMENT', 'development');
	define('BASEPATH', __DIR__.'/../system/');
	require_once 'application/config/database.php';
	if (!defined('HOST')) {
		if (strpos(__DIR__, 'ray') !== false) {
			define("HOST", "http://ray.fantoon.com");
		} else if (strpos(__DIR__, 'dmitry') !== false) {
			define("HOST", "http://dmitry.fantoon.com");
		} else {
			define("HOST", "http://ft");
		}
	}
	if (strpos(HOST, 'ray.fantoon.com') !== false || strpos(HOST, 'dmitry.fantoon.com') !== false) {
		$db['default']['hostname'] = '173.255.253.241';
	}
}

if (!$tests) {
	$tests = get_all_tests();
}

init_db();
run($tests);

/**
 * The tests
 */

function test_landing() {
	echo "\r\n------Running Landing page test-------\r\n";
	return run_test('',false);
}

function test_home($module) {
	$follow_mod = mysql_fetch_object(mysql_query("SELECT * FROM modes_config WHERE name = 'follow'"))->{ENVIRONMENT};
	if (!$follow_mod) return ;
	echo "\r\n------Running Home page test-------\r\n";
	//Reset
	$user = get_user();
	mysql_query("DELETE FROM likes WHERE user_id = ".$user->id);
	
	mysql_query("UPDATE `users` SET `role` = '1', `system_notification` = 0, avatar='' WHERE `id` = '{$user->id}'");
	
	if ( $module ) {
		return run_test('/my-feed', true, $module);
	} else {
		return run_test('/my-feed', true);
	}
}

function test_profile($module) {
	
	echo "\r\n------Running Profile test -------\r\n";
	
	global $testUsers;
	$user = get_user();
	$user2 = get_user($testUsers[1]);
	
	//Reset
	mysql_query("DELETE FROM folder_user WHERE user_id = ".$user->id);
	
	$res = mysql_query("SELECT COUNT(*) as cnt FROM folder WHERE user_id = {$user->id}");
	$row = mysql_fetch_assoc($res);
	
	
	// create minimum 16 collection to test the scroll
	if ( $row['cnt'] < 16 )	{
	
		$to_populate = 16 - $row['cnt'];
		
		for ( $i = 0; $i < $to_populate; $i++ )	{
			$name = "Quint test - folder #{$i}";
			// add new folder to database
			get_user_folder( $user->id, $name);
		}
	
	}
	$follow_mod = mysql_fetch_object(mysql_query("SELECT * FROM modes_config WHERE name = 'follow'"))->{ENVIRONMENT};
	
	if ($follow_mod) {
		$is_follow = mysql_fetch_object(mysql_query("SELECT COUNT(id) as is_follow FROM connections WHERE user1_id = {$user->id} AND user2_id = {$user2->id}"))->is_follow;
		if (!$is_follow) {
			mysql_query("INSERT INTO connections (user1_id, user2_id) VALUES({$user->id}, {$user2->id})");
		}
	}
	
	if ($module == 'follow') {
		return run_test("/".$user2->uri_name, true, $module);
	} elseif ($module) {
		mysql_query("DELETE FROM folder WHERE user_id = {$user->id} AND folder_name = 'qunit test collection 2'");
		$ret =run_test("/".$user->uri_name, true, $module);
	} else {
		mysql_query("DELETE FROM folder WHERE user_id = {$user->id} AND folder_name = 'qunit test collection 2'");
		if ($ret =run_test("/".$user->uri_name, true)) return $ret;
		if ($follow_mod) {
			if ($ret = run_test("/".$user2->uri_name, true, 'follow')) return $ret;
		}
	}
}


function test_manage_lists() {
	echo "\r\n------Running Mange List - folders test-------\r\n";
	return run_test('/manage_lists', true);
}

function test_manage_list($module) {
	echo "\r\n------Running Mange List - posts test-------\r\n";
	$collection = mysql_fetch_object(mysql_query("SELECT folder_id FROM folder WHERE user_id = ".get_user()->id." AND folder_uri_name =  'bookmarklet_folder'"));
	return run_test('/manage_lists/'.$collection->folder_id, true, $module);
}

function test_manage_list_folder() {
	echo "\r\n------Running Mange List - edit folder test-------\r\n";
	$collection = mysql_fetch_object(mysql_query("SELECT folder_id FROM folder WHERE user_id = ".get_user()->id." AND folder_uri_name =  'bookmarklet_folder'"));
	return run_test('/manage_lists/'.$collection->folder_id.'/edit', true);
}

function test_search() {
	
	echo "\r\n------Running search page test-------\r\n";
	if (run_test('/search?q=test', true)) return 1;
	if (run_test('/search/food', true)) return 1;
	// if (run_test('/search/people?q=test', true)) return 1;
	// if (run_test('/source/hbo.com', true)) return 1;
	
	// $folder_info = get_user_folder();
	//if (run_test('/search?q=%23hash&action=created_collection&folder_id=' . $folder_info->folder_id . "&name=" . $folder_info->folder_name , true)) return 1;
	
	//echo "\r\n------Running search landing page test-------\r\n";
	//run_test('/search?q=test');
	return 0;
}


function test_account ( $module ) {
	echo "\r\n------Running account page test-------\r\n";
	$user = get_user();
	
	return run_test( '/account_options', true, $module );
}

/*
function test_drop($module) {
	$newsfeed_id = get_user_drop();
	echo "\r\n------Running Drop test ($newsfeed_id)-------\r\n";
	
	//Reset
	$user = get_user();
	mysql_query("DELETE FROM likes WHERE user_id = ".$user->id);
	
	return run_test('/drop/'.$newsfeed_id, true, $module);
}
*/

function test_collection($module) {
	$user = get_user();
	$collection = mysql_fetch_object(mysql_query("SELECT folder_id, folder_uri_name FROM folder WHERE user_id = ".$user->id." AND folder_uri_name =  'bookmarklet_folder'"));
	$newsfeed_id = get_user_drop();
	//reset
	mysql_query("DELETE FROM likes WHERE newsfeed_id = ".$newsfeed_id);
	mysql_query("UPDATE folder SET hashtag_id = 0 WHERE folder_id = ".$collection->folder_id);
	mysql_query("DELETE FROM folder WHERE user_id = '{$user->id}' AND folder_name = 'test collection 3'");
	mysql_query("DELETE FROM folder WHERE folder_name = 'TEST1' AND user_id = ".$user->id);

	echo "\r\n------Running Collection test (collection/{$user->uri_name}/{$collection->folder_uri_name}) $collection->folder_id -------\r\n";
	$ret = run_test("/{$user->uri_name}/{$collection->folder_uri_name}", true, $module);

	//reset
	mysql_query("UPDATE folder SET folder_name = 'Bookmarklet folder', folder_uri_name = 'bookmarklet_folder' WHERE folder_id = ".$collection->folder_id);
	
	return $ret;
}
/*
NO TEST FOUND FOR: C:/xampp/htdocs/fantoon.loc/system/../application/modules/pro
file/js/tests/followings_test_user1_ugc.js
*/
function test_profile_followings($module)	{
	$follow_mod = mysql_fetch_object(mysql_query("SELECT * FROM modes_config WHERE name = 'follow'"))->{ENVIRONMENT};
	if (!$follow_mod) return ;
	
	echo "\r\n------Running Profile Followings page test -------\r\n";

	global $testUsers;
	$user = get_user();
	$user2 = get_user($testUsers[1]);
	
	return run_test("/followings/".$user->uri_name, true, $module);	
}

/*
NO TEST FOUND FOR: C:/xampp/htdocs/fantoon.loc/system/../application/modules/pro
file/js/tests/followers_test_user1_ugc.js

function test_profile_followers($module)	{
	echo "\r\n------Running Profile Followers page test -------\r\n";

	global $testUsers;
	$user = get_user();
	$user2 = get_user($testUsers[1]);

	return run_test("/followers/".$user->uri_name, true, $module);	
}
*/
/*
NO TEST FOUND FOR: C:/xampp/htdocs/fantoon.loc/system/../application/modules/pro
file/js/tests/upvotes_test_user2_ugc.js
function test_profile_upvote($module)	{
	echo "\r\n------Running Profile Upvotes page test -------\r\n";

	global $testUsers;
	$user = get_user();
	$user2 = get_user($testUsers[1]);

	return run_test("/upvotes/".$user2->uri_name, true, $module);
}
*/

/*
function test_invites() {
	echo "\r\n------Running Invites page test-------\r\n";
	//here the localhost is ovverriden because google and yahoo dont accept any other local url except "localhost"
	return run_test( (HOST == 'http://ft' ? 'http://localhost' : HOST).'/invites',true);
}
*/

/*
/*
 ** [out :: 54.243.131.84] Validate on submit
 ** [out :: 54.243.131.84] validate [object HTMLTextAreaElement] submit
 ** [out :: 54.243.131.84] Field not valid:  [object HTMLDivElement]
 ** [out :: 54.243.131.84] === Wait timeout ===> .err_msg_receiver
 ** [out :: 54.243.131.84] === Unitest failed ===> Validation receiver
 ** [out :: 54.243.131.84] Assertion Failed: New message: Validation receiver
 ** [out :: 54.243.131.84] Failed assertion: Validation receiver
 ** [out :: 54.243.131.84] Took 10989ms to run 4 tests. 3 passed, 1 failed.
 
function test_messages($module=null) {
	global $testUsers;
	echo "\r\n------Running Messages page test-------\r\n";
	if ($module) {
		$user = $module=='list' ? true : $testUsers[1];
		return run_test('/messages', $user, $module);
	} else {
		if ($ret = run_test('/messages', true, 'list')) return $ret;
		if ($ret = run_test('/messages', $testUsers[1], 'delete')) return $ret;
	}
}

*/

function test_winsxsw() {
	echo "\r\n------Running Winsxsw test-------\r\n";
	if (run_test('/winsxsw', true)) return 1;
	if (run_test('/winsxsw/SXSW_Influencer_Award', true)) return 1;
}
function test_demo() {
	echo "\r\n------Running Demo contest test-------\r\n";
	if (run_test('/demo', true)) return 1;
	if (run_test('/demo/windemomobile-influencer', true)) return 1;
}
function test_fndemo() {
	echo "\r\n------Running FNDemo contest test-------\r\n";
	if (run_test('/fndemo', true)) return 1;
}
/**
 * @to-do We can check for the added data instead of just deleting it.
 * @since temporary disabled it returns on test server: Unable to post message to http://test.fandrop.com. Recipient has origin http://www.youtube.com.
 */
/*
function test_bookmarklet() {
	$folder = get_user_folder(null, "Bookmarklet folder");
	return run_test('http://www.youtube.com/watch?v=rTgA4tHOp2Y', array('bookmarklet'=>true));
	
	mysql_query("DELETE FROM newsfeed WHERE folder_id = '{$folder->folder_id}'");
}
*/

/**
 * ========= Helper functions ============
 */

/**
 * Creates the db connection
 */
function init_db() {
	global $db;
	global $active_group;
	$link = mysql_connect($db[$active_group]['hostname'], $db[$active_group]['username'], $db[$active_group]['password']);
	if (!$link) {
	    die('Not connected : ' . mysql_error());
	}
	// make foo the current db
	$db_selected = mysql_select_db($db[$active_group]['database'], $link);
	if (!$db_selected) {
	    die ('Can\'t select db : ' . mysql_error());
	}
	get_user();
}

/**
 * Gets the testing user db record
 * @param $user - To override default user use: array('user'=>'some_user@email.com', 'pass'=>'123456')
 */
function get_user($user=null) {
	$username = isset($user['user']) ? $user['user'] : qunit_user;
	$pass = isset($user['pass']) ? $user['pass'] : qunit_pass;
	
	if ($username == 'test.user2@example.com') { //quick fix bc of the bad migration
		mysql_query("UPDATE users SET last_name = 'User 2', uri_name = 'test_user2' WHERE email = '$username'");
	} else {
		mysql_query("UPDATE users SET uri_name = 'test_user1' WHERE email = '$username'");
	}
	
	$res = mysql_query("SELECT * FROM users WHERE email = '$username' AND password = '".md5($pass)."'");
	if (!$res) {
		exit("Testing user not found");
	}
	$row = mysql_fetch_object($res);
	return $row;
}

function get_user_folder($user_id=null, $title='QUnit test folder') {
	$user_id = $user_id ? $user_id : get_user()->id;
	$res = mysql_query("SELECT * FROM folder WHERE user_id = $user_id AND folder_name = '$title'");
	if ($res && $row = mysql_fetch_object($res)) { //Get user collection
		echo "Folder found {$row->folder_id} \r\n";
		return $row;
	} else { //Create testing collection
		echo "Creating test folder: ";
		$uri = url_title($title);
		mysql_query("INSERT INTO folder (folder_name, user_id, private, folder_uri_name) 
		                    VALUES ('$title', $user_id, 1, '$uri')");
		$folder_id = mysql_insert_id();
		echo $folder_id."\r\n";
		return (object) array(
			'folder_id' => $folder_id,
			'folder_name' => $title,
			'folder_uri_name' => $uri,
		);
	}
}

function url_title($str, $limit=null) {
	if ($limit) {
		$str = Text_Helper::character_limiter($str, $limit, '');
	}
	$replace	= '-';

	$trans = array(
					'&\#\d+?;'				=> '',
					'&\S+?;'				=> '',
					'\s+'					=> $replace,
					'[^a-z0-9\-_]'			=> '',
					$replace.'+'			=> $replace,
					$replace.'$'			=> $replace,
					'^'.$replace			=> $replace,
					'\.+$'					=> ''
				);

	$str = strip_tags($str);

	foreach ($trans as $key => $val) {
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	return trim(stripslashes(strtolower($str)));
}

function get_user_drop($user_id=null, $folder_id=null) {
	$user_id = $user_id ? $user_id : get_user()->id;
	$folder_id = $folder_id ? $folder_id : get_user_folder($user_id)->folder_id;
	$res = mysql_query("SELECT newsfeed_id FROM newsfeed WHERE activity_user_id = $user_id AND folder_id = $folder_id LIMIT 1");
	if ($res && $row = mysql_fetch_object($res)) { //Get a testing drop
		echo "Drop found: {$row->newsfeed_id} \r\n";
		return $row->newsfeed_id;
	} else { //Create a testing drop
		echo "Creating test drop: ";
		$title = 'qunit test drop _hash_test_hash';
		$url = str_replace(' ', '-', $title);
		$content = 'qunit test content';
		mysql_query("INSERT INTO links (user_id_from, link_type, title, content, content_plain) 
					            VALUES($user_id, 'text', '$title', '$content', '$content')");
		$link_id = mysql_insert_id();
		mysql_query("INSERT INTO newsfeed (activity_user_id, type, 	link_type, activity_id, time, user_id_from, folder_id, orig_user_id, description, url)
		                           VALUES ($user_id, 'link', 'text', $link_id, NOW(), $user_id, $folder_id, $user_id, '$title', '$url')");
		$newsfeed_id = mysql_insert_id();
		mysql_query("UPDATE folder SET drops = drops + 1 WHERE folder_id = ".$folder_id);
		echo $newsfeed_id."\r\n";
		return $newsfeed_id;
	}
}

/**
 * Executes a qunit test
 * @param string $url - relative test url 
 * @param $logged_in - can be boolean (true or false) it uses the default login user. Also it can be overwritten
 *                     array('user'=>'some_user@email.com', 'pass'=>'123456')
 */
function run_test($url, $logged_in=true, $module=null) {
	if (is_bool($logged_in) && $logged_in == true) {
		$login_params = '&qu_user='.qunit_user.'&qu_pass='.qunit_pass;
	} elseif (is_array($logged_in) && isset($logged_in['user']) && isset($logged_in['pass'])) {
		$login_params = '&qu_user='.$logged_in['user'].'&qu_pass='.$logged_in['pass'];
	} elseif (is_array($logged_in) && isset($logged_in['bookmarklet'])) {
		$login_params = '&bookmarklet='.HOST;
	} else {
		$login_params = '';
	}
	
	$phantom_js = 'js/tests/'.OS.'/phantomjs --web-security=no';
	if (OS == 'windows') $phantom_js = str_replace('/', "\\", $phantom_js);
	
	$url .= (strpos($url, '?') !== false ? '&' : '?').'qunit_tests=true';
	$url .= $login_params;
	$url .= $module ? '&qunit_module='.$module : '';
	if (strpos($url, 'http://') === false) {
		$url = HOST.$url;
	}
	
	$cmd = $phantom_js.' js/tests/runner.js "'.$url.'"';
	echo $cmd."\r\n";
	system($cmd, $rtn);
	
	return $rtn;
}

/**
 * Gets all defined tests
 * @return array('home', 'landing', 'drop', .....)
 */
function get_all_tests() {
	$ret = array();
	$funcs = get_defined_functions();
	foreach ($funcs['user'] as $func) {
		if (strpos($func, 'test_') === 0) {
			$ret[] = str_replace('test_', '', $func);
		}
	}
	return $ret;
}

/**
 *  Run the tests
 */
function run($tests) {
	foreach ($tests as $test) {
		if (strpos($test, '/') !== false) {
			list($test, $module) = explode('/', $test, 2);
		} else {
			$module = null;
		}
		if (function_exists('test_'.$test)) {
			$ret = call_user_func('test_'.$test, $module);
		} else {
			echo "Avaliable tests are:\r\n";
			$tests = get_all_tests();
			foreach ($tests as $_test) {
				echo "    ".$_test."\r\n";
			}
			exit("Test ".$test." doesnt exists");
		}
		if ($ret) {
			echo "Test failed. Returned: ";
			var_dump($ret);
			exit($ret);
		}
	}	
}
