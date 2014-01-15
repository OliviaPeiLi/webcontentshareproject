<?php 
$time = microtime(true);
$queries = array(
	"SHOW TABLES FROM `fantoon_ci`", 
	"SELECT * FROM (`migrations`)",
	"SELECT * FROM (`users`) WHERE `id` =  '45'",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'optimized_js' LIMIT 1" ,
	"SELECT * FROM (`modes_config`) WHERE `name` =  'cache' LIMIT 1",
	"SELECT * FROM (`user_visits`) WHERE `id` =  '45'",
	"SELECT * FROM (`user_visits`) WHERE `id` =  '45'",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'landing_ugc' LIMIT 1", 
	"SELECT * FROM (`modes_config`) WHERE `name` =  'new_theme' LIMIT 1",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'undefined_var_checker' LIMIT 1", 
	"SELECT * FROM (`modes_config`) WHERE `name` =  'views_variables' LIMIT 1",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'view_debug' LIMIT 1",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'design_ugc' LIMIT 1",
	"SELECT * FROM (`hashtags`) WHERE `hashtag` IN ('_hash_Aww', '_hash_Celebs', '_hash_Food', '_hash_Funny', '_hash_Gaming', '_hash_Music', '_hash_Sports', '_hash_Tech', '_hash_Travel')",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'list_manager' LIMIT 1",
	"SELECT * FROM (`folder`) WHERE `is_landing` =  1 ORDER BY `updated_at` desc", 
	"SELECT * FROM (`modes_config`) WHERE `name` =  'new_theme' LIMIT 1",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'undefined_var_checker' LIMIT 1",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'views_variables' LIMIT 1", 
	"SELECT * FROM (`modes_config`) WHERE `name` =  'view_debug' LIMIT 1",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '447' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '447' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '447' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1", 
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '447' AND `api` =  'fb'", 
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '447' AND `api` =  'twitter'",
	"SELECT * FROM (`users`) WHERE `id` =  '2' LIMIT 1",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '4' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '4' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '4' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1", 
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '4' AND `api` =  'fb'",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '4' AND `api` =  'twitter' ",
	"SELECT * FROM (`users`) WHERE `id` =  '2' LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '5' AND `user_id` =  '45' ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '5' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '5' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '5' AND `api` =  'fb'",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '5' AND `api` =  'twitter' ",
	"SELECT * FROM (`users`) WHERE `id` =  '3' LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '7' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '7' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '7' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '7' AND `api` =  'fb' ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '7' AND `api` =  'twitter' ",
	"SELECT * FROM (`users`) WHERE `id` =  '30' LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '8' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '8' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '8' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '8' AND `api` =  'fb'",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '8' AND `api` =  'twitter'",
	"SELECT * FROM (`users`) WHERE `id` =  '4' LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '10' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '10' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '10' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '10' AND `api` =  'fb' ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '10' AND `api` =  'twitter' ",
	"SELECT * FROM (`users`) WHERE `id` =  '4' LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`likes`) WHERE `folder_id` =  '11' AND `user_id` =  '45'",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '11' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT SUM(up_count) as upvotes FROM (`newsfeed`) WHERE `folder_id` =  '11' AND `is_deleted` =  0 GROUP BY folder_id LIMIT 1 ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '11' AND `api` =  'fb' ",
	"SELECT COUNT(*) AS `numrows` FROM (`newsfeed_shares`) WHERE `user_id` =  '45' AND `folder_id` =  '11' AND `api` =  'twitter' ",
	"SELECT `users`.`id`, `id` FROM (`users`) WHERE `users`.`role` IN (1, 2) AND `uri_name` != 'test_user1'",
	"SELECT * FROM (`hashtags`) WHERE `hashtag` IN ('_hash_Aww', '_hash_Celebs', '_hash_Food', '_hash_Funny', '_hash_Gaming', '_hash_Music', '_hash_Sports', '_hash_Tech', '_hash_Travel')  ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '10' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '12' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '13' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '14' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '21' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '573' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '603' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`modes_config`) WHERE `name` =  'live_drops' LIMIT 1",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '633' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`folder`) WHERE `user_id` IN ('4', '43', '45', '75', '411', '371681', '369947', '371743', '371739', '371728', '371727', '371718', '371701', '371691', '371680', '371793', '371794', '371795', '371848', '371865', '372112', '372113') AND `hashtag_id` =  '663' AND `private` =  '0' AND `newsfeeds_count` >= 1 ORDER BY `folder_id` DESC LIMIT 4 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625661' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625661' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`contests`) WHERE `id` =  '35' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625387' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625387' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '4' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '624444' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '624444' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '140' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '140' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625659' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625659' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625641' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625641' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625633' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625633' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625631' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` = '625631' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '625569' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '625569' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '139' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '139' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '624424' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '624424' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '4' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '10' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '10' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`users`) WHERE `id` =  '45' LIMIT 1 ",
	"SELECT * FROM (`contests`) WHERE `id` =  '35' LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '625374' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1 ",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '625374' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1",
	"SELECT * FROM (`users`) WHERE `id` =  '4' LIMIT 1",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '11' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1",
	"SELECT * FROM (`newsfeed`) WHERE `folder_id` =  '11' AND `link_type` <> 'text' AND `is_deleted` =  0 ORDER BY `newsfeed_id` desc LIMIT 1", 
);
define("BASEPATH", dirname(__FILE__));
define("ENVIRONMENT", 'development');
include_once 'application/config/database.php';
if (!mysql_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password'])) {
	print_r($db);
	die(print_r(mysql_error()));
}
mysql_select_db($db['default']['database']);

foreach ($queries as $query) {
	$res = mysql_query($query);
	while ($row = mysql_fetch_object($res)) {
		//do nothing;
	}
}
$db_time = microtime(true) - $time;
?>
<!DOCTYPE HTML>
<html xmlns:fb="http://www.facebook.com/2008/fbml" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# theshots: http://ogp.me/ns/fb/theshots#" lang="en">
<head>
	<script type="text/javascript">var NREUMQ=NREUMQ||[];NREUMQ.push(["mark","firstbyte",new Date().getTime()]);</script>
<link rel="stylesheet" href="https://static.fandrop.com/css/MTIyNg==.css?v=1370283014" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTA4MQ==.css?v=1369879519" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTA4NA==.css?v=1371064640" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTEwMQ==.css?v=1371856615" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTEyOA==.css?v=1371856615" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTEyNQ==.css?v=1369311257" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTEwNA==.css?v=1371856615" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTIyNw==.css?v=1370287855" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTEwOQ==.css?v=1371856615" type="text/css"/>	<link rel="stylesheet" href="https://static.fandrop.com/css/MTA5Mg==.css?v=1371856615" type="text/css"/>

	<link rel="icon" type="image/ico" href="/images/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content='The best place to find content on the web.'/>
	<meta name="keywords" content="fan drop, fandrop, fandrop.com, drop it, drop, list, viral">
	<meta property="fb:app_id" content="315227451867064" />
		<title>DB: <?=$db_time?></title>

	
		
		<script type="text/javascript" src="/js/require.js"></script>
	<script type="text/javascript">
		function save_tmp_message(msg) {
			if (!msg) return;
			if (msg.data.indexOf('{') != 0) return;
			data = eval('('+msg.data+')');
			if (!data.fandrop_message) return;
			window.tmp_message = msg;
		}
		
		try {
			if (window.addEventListener) {
				window.addEventListener('message', save_tmp_message );
			} else if (window.attachEvent) {
				window.attachEvent('onmessage', save_tmp_message);
			} else {
				window.onmessage = save_tmp_message;
			}
		} catch(err)	{}
		
		
		var php = {
				'first_visit': '',
				'baseUrl': 'https://www.fandrop.com/',
				'userId': 4,
				'ip': '37.143.252.232',
				'first_name': 'Radil',
				'last_name': 'Radenkov',
				'email': 'radil@fandrop.com',
				'userUrl': 'Radil',
				'redirectUrl': '/',
				'baseCSS': 'https://static.fandrop.com/css/',
				'serverTime' : '2013062509',
				'serverTimestamp' : '1372176464',
				'csrf': {
						'name': 'ci_csrf_token',
						'hash': 'c5f9faa64e6d072db060356dc896d7dd'
					},
				'fb_app_id': '315227451867064',
				'fb_app_namespace': 'fandrop',
				'kissmetrics_key': 'aea0bf276ea776b8349a9c9189f2435184fa98f1',
				's3url': 'https://d17tpoh2r6xvno.cloudfront.net/',
									'landing_ugc': true,
													'design_ugc': true,
								'lang': {
					'error': {}
				}
		};
			
		require.config({
			urlArgs: "v=228952663722",
			baseUrl: "https://static.fandrop.com/js/",
			paths: {
								"jquery": "jquery.min",
				"jquery-ui": "jquery-ui.min"
			},
			waitSeconds: 30
		});
		
		require(["jquery"], function() {
			
			$(document).ready(function() {
				save_tmp_message = function() {};
				if (window.tmp_message) {
					$(window).trigger('message');
				}
			});
			window.onerror = function(errorMsg, file, lineNumber) {
				$.post('/new_relic_error', {
							'errorMsg': errorMsg, 
							'file': file, 
							'lineNumber': lineNumber, 
							'location': window.location.href,
							'agent': navigator.userAgent,
							'ci_csrf_token': 'c5f9faa64e6d072db060356dc896d7dd'
				}, function(data) {
					
				},'json');
			};
		});
		
		require(["MA=="]);
		
		//Define global vars for the APIs
		var _kmq = [];

		var _gaq = _gaq || []; 
			_gaq.push(['_setAccount', 'UA-29771355-1']); /*Fandrop.com*/
			_gaq.push(['_trackPageview']);	
	</script>
	<script type="text/javascript">require(["NDE=","MzI=","MzA=","Mzg=","Mw==","MjU=","Mzk=","MTc=","MTg=","Nzk=","MTA4","ODM=","MzE=","NjE=","NTM="]);</script><script type="text/javascript">
	    window.console = {};
	    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
	    if (typeof Firebug != 'undefined' && typeof Firebug.Console != 'undefined') {
	    	Firebug.Console.logRow = function() {}
	    }
	</script>
</head>
<body class=" ">

	<div id="fb-root" style="display:none"></div>
				    	<div id="preview_popup" class="no-auto-position" style="display:none">
	<div class="modal-body">
		<div class="item_top">
			<div class="stats_container inlinediv">
				<div class="upbox inlinediv">
					<a class="up_button" rel="ajaxButton" href="{up_link}">
						<span class="upvote_wrapper">
							<span class="upvote_contents"></span>
						</span>
					</a>
					<a class="undo_up_button" rel="ajaxButton" href="{undo_up_link}">
						<span class="downvote_wrapper">
							<span class="downvote_contents"></span>
						</span>
					</a>
					<div class="up_count"></div>
				</div>
				<div class="redropbox inlinediv">
					<a href="#collect_popup" class="redrop_button" rel="popup" title="Repost">
						<span class="redrop_icon">
							<span class="redrop_iconContents"></span>
						</span>
					</a>
					<div class="redrop_count"></div>
				</div>
			</div>
			<div class="post_detail inlinediv">
				<div>
					<span class="tl_icon"></span>
					<h2 class="pop_up_title drop-description js-description">{drop_desc}</h2>
				</div>
				<div class="postDetail_drop">Posted in <a href="{folder_url}" class="folder_link">{folder_name}</a></div>
				<div class="postDetail_user">
					By <a href="{user_link}" class="user_link">{user_fullname}</a>
					<span class="roleTitle">Staff Writer</span>
					<span class="topPost_actions">
						<span class="divIder"></span>
						<a href="#newsfeed_popup_edit" class="newsfeed_edit_lnk edit_btn" rel="popup" title="Edit Post">
							<span class="edit_wrapper"><span class="edit_contents"></span></span>
							<span class="actionButton_text">Edit</span>
						</a>
					</span>
				</div>
				<div id="permalinks" class="has-link">
					<a href="javascript:;" target="_blank">
						<img src="" id="link_favicon" alt=""><span class="linktext"></span>
					</a>
									</div>
			</div>
		</div>
				
		<a href="" class="fullscreen_txt">View in Fullscreen</a>
		<a href="" class="close" data-dismiss="modal"></a>
		<div class="preview_popup_main">
			<div class="images_container">
				<img src="" class="thumb-img" alt=""/>
				<img src="" class="full-img" alt=""/>
			</div>
			<iframe src=""></iframe>
		</div><!-- End .left -->
	</div> <!-- End .modal-body -->
	<a class="popup_arrow disabled" id="popup_arrow_left"></a>
	<a class="popup_arrow disabled" id="popup_arrow_right"></a>
	<script type="text/javascript">
		php.fb_id = 1374955153;
		php.twtr_id = 0;
	</script>
	</div><!-- End #preview_popup -->
				
	<!-- email send newsfeed template -->
<div id="share_email_form_wrap" style="display: none;">
	<form  id="share_email_form" rel="ajaxForm" data-required_login="false" method="post" action="/share_email"><div  style="display:none"><input  type="hidden" name="ci_csrf_token" value="c5f9faa64e6d072db060356dc896d7dd"/></div>		<ul style="width:100%">
<!--
			<li class="form_row">
				<div class="text_align_left form_field inlinediv">
					<input id="share_name_to" style="width: 400px; " placeholder="Recipient Name:" name="share_name_to" value="" type="text" data-validate="required" data-error-required="Recipient Name can't be blank">
				</div>
				<div class="error"></div>
			</li>
-->
			<li class="form_row">
				<div class="text_align_left form_field inlinediv addEmailRecipients">
					<input  id="share_email_to" class="tokenInput" allow_insert="true" showDropdown_on_focus="false" data-validate="required|email" data-error-email="Doesn't look like a valid email." data-error-required="An email is required!" hint_text="" searching_text="" no_result_text="" placeholder="Recipient Email:" style="width: 400px" name="share_email_to[]" value="" type="text"/>				</div>
				<span class="hint">Use "Tab" to input multiple email address</span>
				<div class="error"></div>
			</li>
			<li class="form_row">
				<div class="text_align_left form_field inlinediv">
					<textarea placeholder="Message (optional)" rows="5" data-maxlength="250" style="width:400px; height: 70px;" name="share_email_body" id="share_email_body"></textarea>
					<div class="textLimit">250</div>
				</div>
			</li>
			<li>
				<div>
					<input type="hidden" name="newsfeed_id" id="share_email_newsfeed_id" value="" />
					<input  id="share_email_button" class="blue_bg s_button" type="submit" name="submit" value="Send Email"/>				</div>
			</li>
		</ul>
	</form></div>
<div id="share-email-message" class="modal" style="display: none;">
	<div class="success-msg">
		<div class="text_loading">Your message was sent</div>
	</div>
</div>
<!-- end of email send newsfeed template -->	    
	<div id="fb-share-success-popup" class="modal" style="display:none; ">
		<div class="success-msg">Your post was successfully shared on Facebook</div>
	</div>
	
		
			<div id="newsfeed_popup_edit" class="newsfeed_popup_edit newsfeed_edit_popup" style="display:none">
	<div class="modal-body">
		<form  class="edit_post_form" rel="ajaxForm" method="post" action="/newsfeed/edit"><div  style="display:none"><input  type="hidden" name="id" value=""/><input  type="hidden" name="ci_csrf_token" value="c5f9faa64e6d072db060356dc896d7dd"/></div>						<div class="form_row">
				<label>Title</label>
												<textarea  rows="3" cols="67" maxlength="150" class="media_text fd_mentions" data-validate="required|maxlength" data-error-required="The title cannot be blank" name="description"></textarea>				<span class="textLimit">150</span>
				<div class="error" style="display:none;"></div>
			</div>			
			<div class="form_row hashtags">
													<a href="#aww" class="hashtag">#aww</a>
									<a href="#celebs" class="hashtag">#celebs</a>
									<a href="#food" class="hashtag">#food</a>
									<a href="#funny" class="hashtag">#funny</a>
									<a href="#gaming" class="hashtag">#gaming</a>
									<a href="#music" class="hashtag">#music</a>
									<a href="#sports" class="hashtag">#sports</a>
									<a href="#tech" class="hashtag">#tech</a>
									<a href="#travel" class="hashtag">#travel</a>
								<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
				<span class="parenText">(Add if not safe for work)</span>
			</div>
			<div class="form_row">
				<label>Source</label>
				<input  class="media_text source_url" name="link_url" value="" type="text"/>			</div>
			<div class="form_row">
				<a class="done_button blue_bg blueButton" href="">Save</a>
				<a href="#delete_dialog" rel="popup" class="delete_button blue_bg greyButton timeline_delete_btn" data-delurl="">Delete</a>
				<div class="data_status" style="display:none">Saved!</div>
				<input type="submit" style="display:none"/>
			</div>
		</form>	</div>
	</div>
		<div id="delete_dialog" class="delete_post_popup delete_dialog" style="display:none;">
	<div class="delete_dialog_container">
		<span class="warning_message_icon"></span>
		<div class="message">
			<div class="message_main_text"><strong>Are you sure you want to delete this post?</strong></div>
		</div>
		<div class="bottom_row">
			<a href="javascript:;" class="blue_bg greyButton delete_yes" rel="ajaxButton">Delete</a>
			<a href="javascript:;" class="blue_bg blueButton delete_no" data-dismiss="modal">Cancel</a>
		</div>
	</div>
</div> 		<div id="confirm" style="display:none">
			<div class="modal-body">
				<span class="ico"></span>
				<p>Are you sure you want to delete?</p>
				<div class="form_row">
					<a href="javascript:;" rel="ajaxButton" data-dismiss="modal" class="blueButton confirmButton">Delete</a>
					<a href="javascript:;" data-dismiss="modal" class="greyButton cancelButton">Cancel</a>
				</div>
			</div>
		</div>
				
		<div id="header">
		<div class="header_content">
			
			<div class="left">
				<a href="/promoters" class="info-btn ft-dropdown custom-title" rel="about-dropdown">Menu</a>

				<div id="about-dropdown">
				<span class="arrow"></span><!--/.arrow-->
				<ul>
										<li><a href="/promoters">Promoters</a></li>
					<li><a href="/publishers">Publishers</a></li>
									</ul>
				</div><!--/#about-dropdown-->

				<div id="search">
					<form action="/search" method="get">
						<div id="header_search" class="autocomplete_input">
							<button type="submit" id="searchButton" class="search_button"></button>
							<input  id="header_search_box" class="tokenInput" theme="search" data-url="/main_search" hint_text="" bottom_text="click here to see more" bottom_link="/search?q={val}" placeholder="Search" min_Chars="2" alpha_sort="" style="width: 220px" name="q" value="" type="text"/>						</div>                  
					</form>
				</div><!--/#search-->
			</div><!--/..left-->
			
			<!--LOGO--><a href="/" title="" class="header_logo">Fandrop</a>
			
			<div class="right">
												

				
												<a href="/create_list" id="list_link" class="" rel="list_options">
							<span class="listsButton">
								<span class="ico"></span>
							</span>
						</a>
										
					
					
					<a href="/Radil" id="account_link" class="ft-dropdown custom-title" rel="account_options">
						<!--<img  src="https://d17tpoh2r6xvno.cloudfront.net/users/504b8f6faeb26_25.gif"/>-->
						<!--<span class="header_userName">Radil Radenkov</span>-->
					</a>
					<ul id="account_options">
						<li><a href="/manage_lists" title="">Manage Stories</a></li>
						<li><a href="/Radil">Profile</a></li>
												<li><a href="/account_options">Settings</a></li>
						<li><a href="/logout">Logout</a></li>
					</ul>
					
					
							</div>
		</div>
	</div>
	<div id="loading-messages" class="modal" style="display: none">
		<div class="success-msg">
			<img  alt="" src="https://static.fandrop.com/images/OTY5.gif?v=0"/>			<div class="text_loading">Sharing</div> <!-- TODO - make all javascript working with this message -->
		</div>
	</div>	
				
	<div id="landing_page">
	<div id="folder_ugc_top" class="container">
	<div class="row" style="overflow:hidden">
		<div class="span16 bigBox">
			<div class="js_folder folderItem">
						<a href="/mollyfosco/10-signs-that-hipsters-are-slowly-but-surely-taking-over-the-world" data-folder_id="2483324" class="imageContainer  watermarked">
				<img  data-newsfeed_id="25185457" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51c0c6dc24b72_full.jpg"/>			</a>
				<div class="info">
		<a href="/mollyfosco/10-signs-that-hipsters-are-slowly-but-surely-taking-over-the-world" data-folder_id="2483324" class="infoTitle">10 Signs that Hipsters are Slowly but Surely Taking Over the World</a>
		<div class="writtenBy">
							Written By <a href="/mollyfosco">Molly Fosco</a>
					</div>
	</div>
	<div class="actions">
		<a href="/mollyfosco/10-signs-that-hipsters-are-slowly-but-surely-taking-over-the-world" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2483324" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">16</span>
			</a>
			<a href="/rm_like/folder/2483324" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">16</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2483324" data-url="/mollyfosco/10-signs-that-hipsters-are-slowly-but-surely-taking-over-the-world" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/mollyfosco/10-signs-that-hipsters-are-slowly-but-surely-taking-over-the-world" data-text="10 Signs that Hipsters are Slowly but Surely Taking Over the World" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>		</div>
		<div class="span8">
			<div class="mediumBox">
				<div class="js_folder folderItem">
						<a href="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" data-folder_id="2513165" class="imageContainer  watermarked">
				<img  data-newsfeed_id="25185455" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51c0c4de8c4b9_320.jpg"/>			</a>
				<div class="info">
		<a href="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" data-folder_id="2513165" class="infoTitle">Why No One Should Ever Fuck with Miranda Lambert</a>
		<div class="writtenBy">
							Written By <a href="/ninawheeler">Nina Wheeler</a>
					</div>
	</div>
	<div class="actions">
		<a href="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2513165" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">18</span>
			</a>
			<a href="/rm_like/folder/2513165" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">18</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2513165" data-url="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" data-text="Why No One Should Ever Fuck with Miranda Lambert" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>			</div>
			<div class="smallBox_container row">
				<div class="inlinediv span4 smallBox">
					<div class="js_folder folderItem">
						<a href="/amoschina/why-robert-smith-should-be-the-next-doctor-who" data-folder_id="2497805" class="imageContainer  watermarked">
				<img  data-newsfeed_id="25018204" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51b6261f189a2_150.gif"/>			</a>
				<div class="info">
		<a href="/amoschina/why-robert-smith-should-be-the-next-doctor-who" data-folder_id="2497805" class="infoTitle">Why Robert Smith Should be the Next Doctor Who</a>
		<div class="writtenBy">
							Written By <a href="/amoschina">Alex Moschina</a>
					</div>
	</div>
	<div class="actions">
		<a href="/amoschina/why-robert-smith-should-be-the-next-doctor-who" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2497805" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">23</span>
			</a>
			<a href="/rm_like/folder/2497805" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">23</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2497805" data-url="/amoschina/why-robert-smith-should-be-the-next-doctor-who" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/amoschina/why-robert-smith-should-be-the-next-doctor-who" data-text="Why Robert Smith Should be the Next Doctor Who" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>				</div>
				<div class="inlinediv span4 smallBox">
					<div class="js_folder folderItem">
						<a href="/ninawheeler/books-by-lois-lowry-and-judy-blume-that-need-to-become-movies" data-folder_id="2483164" class="imageContainer  watermarked">
				<img  data-newsfeed_id="24841764" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51b0f46c43290_150.png"/>			</a>
				<div class="info">
		<a href="/ninawheeler/books-by-lois-lowry-and-judy-blume-that-need-to-become-movies" data-folder_id="2483164" class="infoTitle">Books by Lois Lowry and Judy Blume that Need to Become Movies</a>
		<div class="writtenBy">
							Written By <a href="/ninawheeler">Nina Wheeler</a>
					</div>
	</div>
	<div class="actions">
		<a href="/ninawheeler/books-by-lois-lowry-and-judy-blume-that-need-to-become-movies" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2483164" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">15</span>
			</a>
			<a href="/rm_like/folder/2483164" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">15</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2483164" data-url="/ninawheeler/books-by-lois-lowry-and-judy-blume-that-need-to-become-movies" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/ninawheeler/books-by-lois-lowry-and-judy-blume-that-need-to-become-movies" data-text="Books by Lois Lowry and Judy Blume that Need to Become Movies" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>				</div>
			</div>
		</div>
	</div>
	<div class="row ugc_top_lowerSection" style="overflow: hidden">
		<div class="span8 mediumBox">
			<div class="js_folder folderItem">
						<a href="/mollyfosco/10-reasons-why-daenerys-targaryen-is-the-most-badass-character-on-game-of-thrones" data-folder_id="2471805" class="imageContainer  watermarked">
				<img  data-newsfeed_id="24727055" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51ad0aff15754_320.jpg"/>			</a>
				<div class="info">
		<a href="/mollyfosco/10-reasons-why-daenerys-targaryen-is-the-most-badass-character-on-game-of-thrones" data-folder_id="2471805" class="infoTitle">10 Reasons Why Daenerys Targaryen is the Most Badass Character on Game of Thrones</a>
		<div class="writtenBy">
							Written By <a href="/mollyfosco">Molly Fosco</a>
					</div>
	</div>
	<div class="actions">
		<a href="/mollyfosco/10-reasons-why-daenerys-targaryen-is-the-most-badass-character-on-game-of-thrones" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2471805" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">13</span>
			</a>
			<a href="/rm_like/folder/2471805" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">13</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2471805" data-url="/mollyfosco/10-reasons-why-daenerys-targaryen-is-the-most-badass-character-on-game-of-thrones" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/mollyfosco/10-reasons-why-daenerys-targaryen-is-the-most-badass-character-on-game-of-thrones" data-text="10 Reasons Why Daenerys Targaryen is the Most Badass Character on Game of Thrones" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>		</div>
		<div class="span8 mediumBox">
			<div class="js_folder folderItem">
						<a href="/ninawheeler/goldie-hawn-is-everything" data-folder_id="2467164" class="imageContainer  watermarked">
				<img  data-newsfeed_id="24684255" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51a7cc58755c9_320.png"/>			</a>
				<div class="info">
		<a href="/ninawheeler/goldie-hawn-is-everything" data-folder_id="2467164" class="infoTitle">Goldie Hawn Is Everything</a>
		<div class="writtenBy">
							Written By <a href="/ninawheeler">Nina Wheeler</a>
					</div>
	</div>
	<div class="actions">
		<a href="/ninawheeler/goldie-hawn-is-everything" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2467164" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">25</span>
			</a>
			<a href="/rm_like/folder/2467164" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">25</span>
			</a>
		</span>
					<a  class=" fb_share_collection" data-folder_id="2467164" data-url="/ninawheeler/goldie-hawn-is-everything" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/ninawheeler/goldie-hawn-is-everything" data-text="Goldie Hawn Is Everything" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>		</div>
		<div class="span8 mediumBox">
			<div class="js_folder folderItem">
						<a href="/jackdevries/15-cartoon-animals-who-hate-you" data-folder_id="2467955" class="imageContainer  watermarked">
				<img  data-newsfeed_id="24690705" onerror="if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); " src="https://d17tpoh2r6xvno.cloudfront.net/links/51a92870e4622_320.gif"/>			</a>
				<div class="info">
		<a href="/jackdevries/15-cartoon-animals-who-hate-you" data-folder_id="2467955" class="infoTitle">15 Cartoon Animals Who Hate You</a>
		<div class="writtenBy">
							Written By <a href="/jackdevries">Jack DeVries</a>
					</div>
	</div>
	<div class="actions">
		<a href="/jackdevries/15-cartoon-animals-who-hate-you" class="more" style="display: none;">More</a>
		<span class="upbox">
						<a href="/add_like/folder/2467955" class="actionButton vote upvote" rel="ajaxButton" style="">
				<span class="ico"></span><span class="num js_upvotes_count">36</span>
			</a>
			<a href="/rm_like/folder/2467955" class="actionButton vote downvote" rel="ajaxButton" style="display:none">
				<span class="ico"></span><span class="num js_upvotes_count">36</span>
			</a>
		</span>
					<a  class=" fb_share_collection disabled_bg" data-folder_id="2467955" data-url="/jackdevries/15-cartoon-animals-who-hate-you" href="https://www.fandrop.com/"><span class="ico"></span></a>			<a  data-url="https://www.fandrop.com/jackdevries/15-cartoon-animals-who-hate-you" data-text="15 Cartoon Animals Who Hate You" data-count="none" class=" share_btn share_twt_app" href="https://www.fandrop.com/"><span class="ico"></span></a>	
			</div>
</div>		</div>		
	</div>
</div>
	
	<div class="container">
	<div class="row">
		<div class="span24 hrule"></div>
	</div>
	<div class="row">
					<div class="hashUnit span8 ">
				<a href="aww" class="hashtag">
					aww				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/jingzhang1253/world_s_10_most_spectacular_swimming_pools" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514b556c2fdb3_thumb.jpg" data-newsfeed_id="21328805"/>
						</a>
						<div class="info">
							<a href="/jingzhang1253/world_s_10_most_spectacular_swimming_pools" class="infoTitle" >World's 10 most spectacular swimming pools</a>
							<div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_world_s_most_romantic_ferris_wheels" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5148e996b72c2_bigsquare.jpg" data-newsfeed_id="21272124"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_world_s_most_romantic_ferris_wheels" class="info">
									The World's Most Romantic Ferris Wheels								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_best_looks_from_paris_fashion_week__spring_2013" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5134eec38925d_bigsquare.jpg" data-newsfeed_id="20844705"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_best_looks_from_paris_fashion_week__spring_2013" class="info">
									The Best Looks from Paris Fashion Week: Spring 2013								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_first_instagram_photos_from_inside_north_korea" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/512f9ec126a75_bigsquare.jpg" data-newsfeed_id="20719604"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_first_instagram_photos_from_inside_north_korea" class="info">
									The First Instagram Photos from Inside North Korea								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="aww">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="celebs" class="hashtag">
					celebs				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/ninawheeler/10-books-baz-luhrmann-needs-to-bring-to-the-big-screen" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51a8fb4bdac51_thumb.jpg" data-newsfeed_id="24686884"/>
						</a>
						<div class="info">
							<a href="/ninawheeler/10-books-baz-luhrmann-needs-to-bring-to-the-big-screen" class="infoTitle" >10 Books Baz Luhrmann Needs to Bring to the Big Screen</a>
							<div class="writtenBy">Written By <a href="/ninawheeler">Nina Wheeler</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_hilarious_photobombs" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514cd5bd3bc9d_bigsquare.jpg" data-newsfeed_id="21362655"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_hilarious_photobombs" class="info">
									The Hilarious Photobombs								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/beyonce___shining_h_m_summer" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514b5eda97d34_bigsquare.png" data-newsfeed_id="21329705"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/beyonce___shining_h_m_summer" class="info">
									Beyonce - shining H&M summer								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_10_greatest_unscripted_movie_scenes" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5148ef6056953_bigsquare.jpg" data-newsfeed_id="21272524"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_10_greatest_unscripted_movie_scenes" class="info">
									The 10 Greatest Unscripted Movie Scenes								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="celebs">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="food" class="hashtag">
					food				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/jingzhang1253/10_most_popular_cocktail_drinks" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514b5c364d6bd_thumb.jpg" data-newsfeed_id="21329564"/>
						</a>
						<div class="info">
							<a href="/jingzhang1253/10_most_popular_cocktail_drinks" class="infoTitle" >10 Most Popular Cocktail Drinks</a>
							<div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/macaron_day_2013_is_coming" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5148e28c346a6_bigsquare.jpg" data-newsfeed_id="21271644"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/macaron_day_2013_is_coming" class="info">
									Macaron Day 2013 is coming								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/sally/17_glorious_green_foods_for_st__patricks" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51411096a431d_bigsquare.png" data-newsfeed_id="21109684"/>
							</a><div class="inlinediv">
								<a href="/sally/17_glorious_green_foods_for_st__patricks" class="info">
									17 Glorious Green Foods for St. Patricks								</a><div class="writtenBy">Written By <a href="/sally">Sally </a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/20_vividly_recipes_for_green_food" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5132a6f9c9e1f_bigsquare.jpg" data-newsfeed_id="20796644"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/20_vividly_recipes_for_green_food" class="info">
									20 Vividly Recipes for Green Food								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="food">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="funny" class="hashtag">
					funny				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51c0c4de8c4b9_thumb.jpg" data-newsfeed_id="25185455"/>
						</a>
						<div class="info">
							<a href="/ninawheeler/why-no-one-should-ever-fuck-with-miranda-lambert" class="infoTitle" >Why No One Should Ever Fuck with Miranda Lambert</a>
							<div class="writtenBy">Written By <a href="/ninawheeler">Nina Wheeler</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/ninawheeler/characters-were-ashamed-we-want-to-bang" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51a84fbf5b6e1_bigsquare.jpg" data-newsfeed_id="24685724"/>
							</a><div class="inlinediv">
								<a href="/ninawheeler/characters-were-ashamed-we-want-to-bang" class="info">
									Characters We're Ashamed We Want To Bang								</a><div class="writtenBy">Written By <a href="/ninawheeler">Nina Wheeler</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/sallychou/10-keep-calm-shirts-you-should-not-wear-by-jack-devries" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/519e6318164ca_bigsquare.jpg" data-newsfeed_id="24668684"/>
							</a><div class="inlinediv">
								<a href="/sallychou/10-keep-calm-shirts-you-should-not-wear-by-jack-devries" class="info">
									10 Keep Calm Shirts You Should Not Wear by Jack DeVries								</a><div class="writtenBy">Written By <a href="/sallychou">Fandrop Writers</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/tumblr-collection" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5167a1c56f71f_bigsquare.jpg" data-newsfeed_id="23229084"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/tumblr-collection" class="info">
									Tumblr Collection								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="funny">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="gaming" class="hashtag">
					gaming				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/sallychou/how-dragons-dogma-made-you-gay-by-jack-devries" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/519e71b61b738_thumb.jpg" data-newsfeed_id="24669355"/>
						</a>
						<div class="info">
							<a href="/sallychou/how-dragons-dogma-made-you-gay-by-jack-devries" class="infoTitle" >How Dragon's Dogma Made You Gay by Jack DeVries</a>
							<div class="writtenBy">Written By <a href="/sallychou">Fandrop Writers</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/new_video_game_releases_for_the_week_of_2_26" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/512d43c5c1891_bigsquare.jpg" data-newsfeed_id="20644604"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/new_video_game_releases_for_the_week_of_2_26" class="info">
									New Video Game Releases For The Week Of 2/26								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/connormcgill/diablo_iii_on_playstation_screenshots" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5128114a7fd4e_bigsquare.jpg" data-newsfeed_id="20509924"/>
							</a><div class="inlinediv">
								<a href="/connormcgill/diablo_iii_on_playstation_screenshots" class="info">
									Diablo III on Playstation Screenshots								</a><div class="writtenBy">Written By <a href="/connormcgill">Connor McGill</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/60_best_free_android_games_2013__season_03" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5126b16308cc9_bigsquare.jpg" data-newsfeed_id="20462255"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/60_best_free_android_games_2013__season_03" class="info">
									60 best free Android games 2013 (Season 03)								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="gaming">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="music" class="hashtag">
					music				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/amoschina/10-examples-of-terrible-parenting-in-music" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51b8d7c23c526_thumb.jpg" data-newsfeed_id="25095764"/>
						</a>
						<div class="info">
							<a href="/amoschina/10-examples-of-terrible-parenting-in-music" class="infoTitle" >10 Examples of Terrible Parenting in Music</a>
							<div class="writtenBy">Written By <a href="/amoschina">Alex Moschina</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/amoschina/why-robert-smith-should-be-the-next-doctor-who" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51b7695ef21ab_bigsquare.gif" data-newsfeed_id="25060305"/>
							</a><div class="inlinediv">
								<a href="/amoschina/why-robert-smith-should-be-the-next-doctor-who" class="info">
									Why Robert Smith Should be the Next Doctor Who								</a><div class="writtenBy">Written By <a href="/amoschina">Alex Moschina</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/top_10_tiesto_songs" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/51560f13a5f21_bigsquare.jpg" data-newsfeed_id="22770124"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/top_10_tiesto_songs" class="info">
									TOP 10 TIESTO SONGS								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/top_10_songs_this_week" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514b64a1df272_bigsquare.jpg" data-newsfeed_id="21330205"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/top_10_songs_this_week" class="info">
									Top 10 songs this week								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="music">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="sports" class="hashtag">
					sports				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/jingzhang1253/5_beautiful_manchester_united_moments_from_the_real_madrid_game" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/512d41d10526d_thumb.gif" data-newsfeed_id="20644305"/>
						</a>
						<div class="info">
							<a href="/jingzhang1253/5_beautiful_manchester_united_moments_from_the_real_madrid_game" class="infoTitle" >5 Beautiful Manchester United moments from the Real Madrid game</a>
							<div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/sally/evolution_of_james_hardens_beard" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/512d36c2a8090_bigsquare.jpg" data-newsfeed_id="20644055"/>
							</a><div class="inlinediv">
								<a href="/sally/evolution_of_james_hardens_beard" class="info">
									Evolution of James Hardens Beard								</a><div class="writtenBy">Written By <a href="/sally">Sally </a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/lebron_james__top_10_dunks_of_all_time" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/512c3f49c4597_bigsquare.jpg" data-newsfeed_id="20612924"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/lebron_james__top_10_dunks_of_all_time" class="info">
									LeBron James: Top 10 Dunks of All Time								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/the_team_with_the_best_looking_car_of_2013" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5127cb8e5dbb9_bigsquare.jpg" data-newsfeed_id="20506205"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/the_team_with_the_best_looking_car_of_2013" class="info">
									The team with the best-looking car of 2013								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="sports">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="tech" class="hashtag">
					tech				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/jingzhang1253/tech_0329" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5155fb7d8a063_thumb.png" data-newsfeed_id="22768805"/>
						</a>
						<div class="info">
							<a href="/jingzhang1253/tech_0329" class="infoTitle" >Tech 0329</a>
							<div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/list_of_10_best_iphone_apps_released_on_march_2013" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514cc698d27a6_bigsquare.jpg" data-newsfeed_id="21361105"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/list_of_10_best_iphone_apps_released_on_march_2013" class="info">
									List of 10 best iPhone apps released on March 2013								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/top_10_upcoming_mobile_phones_can_be_expected_2013" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5148ddc6bfbe0_bigsquare.jpg" data-newsfeed_id="21271105"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/top_10_upcoming_mobile_phones_can_be_expected_2013" class="info">
									Top 10 upcoming mobile phones can be expected 2013								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/know_more_about_htc_one" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/5140b8f7db2e3_bigsquare.jpg" data-newsfeed_id="21106455"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/know_more_about_htc_one" class="info">
									Know More About HTC One								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="tech">See All</a>
					</div>
				</div>
			</div>
					<div class="hashUnit span8 ">
				<a href="travel" class="hashtag">
					travel				</a>
				
								<div class="folder_belowHash">
					<div class="folder first">
						<a href="/jingzhang1253/this_is_yosemite" class="imageContainer">
							<img src="https://d17tpoh2r6xvno.cloudfront.net/links/515608f483297_thumb.jpg" data-newsfeed_id="22769655"/>
						</a>
						<div class="info">
							<a href="/jingzhang1253/this_is_yosemite" class="infoTitle" >This Is Yosemite</a>
							<div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
						</div>
					</div>
					<div class="row">
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/cherry_blossoms_around_the_world" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/514ccf770cd7d_bigsquare.png" data-newsfeed_id="21362205"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/cherry_blossoms_around_the_world" class="info">
									Cherry blossoms around the world								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/jingzhang1253/12_pictures_of_some_well_traveled_cats" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/511b4e7e4bbd3_bigsquare.jpg" data-newsfeed_id="20193205"/>
							</a><div class="inlinediv">
								<a href="/jingzhang1253/12_pictures_of_some_well_traveled_cats" class="info">
									12 Pictures Of Some Well-Traveled Cats								</a><div class="writtenBy">Written By <a href="/jingzhang1253">Jing Zhang</a></div>
							</div>
						</div>
											<div class="folder smallFolder span8">
							<a href="/sally/amazing_places_around_the_world" class="imageContainer">
								<img src="https://d17tpoh2r6xvno.cloudfront.net/links/518bf24689137_bigsquare.jpg" data-newsfeed_id="24262255"/>
							</a><div class="inlinediv">
								<a href="/sally/amazing_places_around_the_world" class="info">
									Amazing Places Around The World								</a><div class="writtenBy">Written By <a href="/sally">Sally </a></div>
							</div>
						</div>
										</div>
					<div class="seeMore">
						<a href="travel">See All</a>
					</div>
				</div>
			</div>
			</div>
</div></div>

	<div class="clear"></div>
<div class="clear"></div>
<script type="text/javascript">
	var bad_drop = "https://d17tpoh2r6xvno.cloudfront.net/links/bad_drop_thumb.png";
	
	var links = document.getElementsByTagName('a');
	for (var i=0; i<links.length; i++) {
		var a = links[i];
		if (a.rel == 'ajaxButton' || a.rel == 'popup' || (a.getAttribute('href') && a.getAttribute('href').indexOf('#') > -1)) {
			a.onclick = function(e) { return false }; 
			//a.addEventListener("click", function(e){ e.preventDefault(); }, false);
		}
	}

	//BP: #FD-2216
	//disable the rel=popup attribute until the code that populates the poup is loaded, it will be re-enabled there
	if ( !window.__ft_drop_preview_loaded ) {
		links = document.querySelectorAll( 'a[href="#preview_popup"][rel="popup"], div[data-url="#preview_popup"][rel="popup"], li[data-url="#preview_popup"][rel="popup"]' );
		for ( var i = links.length - 1; i >= 0; --i ) {
			links[i].setAttribute( 'rel', 'popup-disabled' );
		}
	}
	//end of #FD-2216

	function _set_img_size(img) {
		console.info("EXEC", img);
		if (img.className.indexOf('imgLoaded') == -1) {
			img.className += ' imgLoaded';
		} else {
			return;
		}
		//img.parentNode.offsetWidth
		if (img.width < 576) {
			img.parentNode.parentNode.className = img.parentNode.parentNode.className.replace('watermarked','');
			img.className = img.className.replace('has_zooming','');
		} else {
			img.className = img.className.replace('has_zooming','zooming');
		}
		
		if (img.width < img.parentNode.parentNode.offsetWidth) {
			img.width = Math.min(img.parentNode.parentNode.offsetWidth, img.width*1.2);
		}
		
		//var margin = Math.max(0, (img.parentNode.parentNode.offsetHeight - img.parentNode.offsetHeight)/2);
		//img.parentNode.style.marginTop = margin+'px';
	}

	function exec_img_correction() {
		//RR - IE8 does not support getElementsByClassName. However, it does support querySelectorAll
		var imgs = document.querySelectorAll('#folder_ugc_top .bigBox img, .newsfeed_upperContent .photo-container img, .newsfeed_entry .photo-container img, .tile_new_entry .drop-preview-img, .postcard_entry .drop-preview-img');
		for (var i=0;i<imgs.length;i++) {
			var img = imgs[i];
			img.onload = function() { _set_img_size(this); };
			if (img.width > 20 && img.height > 20) {
				_set_img_size(img);
			}
		}

		/* Center text contents in newsfeed */
		var newsfeed = document.getElementById('list_newsfeed');
		if (newsfeed) {
			var txts = newsfeed.querySelectorAll('.text_wrapper, .textContainer');
			console.info(txts);
			for (var i=0; i < txts.length; i++) {
				txts[i].style['margin-top'] = ((txts[i].parentNode.offsetHeight - txts[i].offsetHeight)/2)+'px';
			}
		}
	}
	exec_img_correction();
</script>	 
<script type="text/javascript">if(!NREUMQ.f){NREUMQ.f=function(){NREUMQ.push(["load",new Date().getTime()]);var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src="https://rpm-images.newrelic.com/42/eum/rum.js";document.body.appendChild(e);if(NREUMQ.a)NREUMQ.a();};NREUMQ.a=window.onload;window.onload=NREUMQ.f;};NREUMQ.push(["nrf2","beacon-4.newrelic.com","5aa36a95dc",1264760,"ZVdSNhBVDUdQABdeWVwdcQEWXQxaHi4CXlgdW14GB0w=",0,1365,new Date().getTime()]);</script>
</body>
</html>