<?php
include_once dirname(__FILE__).'/config.php';
include_once dirname(__FILE__).'/db.php';

function get_latest_version() {
	return filemtime(__FILE__);
}

$latest = get_latest_version();
while (1) {
	if (get_latest_version() != $latest) {
		die("Restarting script to new version");
	}
	
	$newsletter = mysql_fetch_object(mysql_pquery("SELECT * FROM newsletters ORDER BY id DESC LIMIT 1"));
	
	while (1) {
		$res = mysql_pquery("SELECT users.id, first_name, last_name, email, newsletter
								FROM users 
								LEFT JOIN email_settings ON users.id=email_settings.user_id
								WHERE newsletter_time < '{$newsletter->newsletter_time}' AND newsletter = '1'	
								ORDER BY users.id ASC
								LIMIT 20
							");
		$has_users = false;
		while ($row = mysql_fetch_object($res)) {
			$has_users = true;
			echo "Seding to: ".$row->email."\n";
			Email_helper::SendEmail($row->email, $newsletter->subject, $newsletter->msg);
			mysql_pquery("UPDATE users SET newsletter_time = '{$newsletter->newsletter_time}' WHERE id = {$row->id}");
		}
		if (!$has_users) break;
		echo "watiing 20secs before the next users \n";
		sleep(20);
	}
	echo "Waiting for new newsletter \n";
	sleep(300);
}