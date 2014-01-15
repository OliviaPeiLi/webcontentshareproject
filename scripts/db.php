<?php

if ( ! function_exists('connect')) {
	function connect() {
		echo "LOG: Connecting to db";
		include(BASEPATH.'../application/config/database.php');
		$link = mysql_connect($db[$active_group]['hostname'], $db[$active_group]['username'], $db[$active_group]['password']);
		if (!$link) {
		    die('Not connected : ' . mysql_error());
		}
		// make foo the current db
		$db_selected = mysql_select_db($db[$active_group]['database'], $link);
		if (!$db_selected) {
		    die ('Can\'t select db : ' . mysql_error());
		}
		echo "LOG: Connected to db";
	}
}

if ( ! function_exists('mysql_pquery')) {
	function mysql_pquery($sql) {
		if (!$res = mysql_query($sql)) {
			echo mysql_error();
			if (mysql_errno() == 2006) {
				connect();
				if (!$res = mysql_query($sql)) {
					echo mysql_error();
					return false;
				}
			}
		}
		return $res;
	}
}

connect();