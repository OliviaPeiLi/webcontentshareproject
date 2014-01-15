<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//invitees
$config['invitees'] = array('fb','test','stanford','erlibird','growthathon','team','access','superbowl','mashable','valentines', 'techcrunch');
$config['invitee_code'] = 'b87jgzfke5';

if(ENVIRONMENT == 'production' || ENVIRONMENT == 'staging'){
	$config['power_users'] = array('102','99','2','14','27');
	$config['top_topics'] = array('392','395','396','399','402','403','404','405','408');
	
	/* Query to get top active users for each topic
	SELECT DISTINCT(folder.user_id), folder.folder_id, folder.hits
	FROM `topic_folders` 
	JOIN `folder` ON folder.folder_id=topic_folders.folder_id
	JOIN `newsfeed` ON newsfeed.folder_id=topic_folders.folder_id 
	WHERE newsfeed.time >= '2012-08-27 00:00:00'
	AND topic_id=392 
	ORDER BY folder.hits DESC 
	LIMIT 30
	*/
	
	/*
	Activities ->kenzi, alexi, joel, sally, vi
	Entertainment ->kenzi, alexi, joel, sally, vi
	Food ->kenzi, alexi, joel, sally, vi
	Funny ->kenzi, alexi, joel, sally, vi
	Movies ->kenzi, alexi, joel, sally, vi
	Music -> kenzi, alexi, joel, sally, vi
	Sports ->kenzi, alexi, joel, sally, vi
	Startups ->kenzi, alexi, joel, sally, vi
	Tech -> kenzi, alexi, joel, sally, vi
	*/
	
	$config['topic_power_users'] = array(
										'388'=>array('2','14','332','23964','1462'), 
										'389'=>array('102','99','2','14','27'),	
										'390'=>array('102','99','2','14','27'),	
										'391'=>array('102','99','2','14','27'),	
										'392'=>array('2','14','332','23964','1462'), 
										'393'=>array('102','99','2','14','27'),	
										'394'=>array('102','99','2','14','27'),
										'395'=>array('2','14','332','23964','1462'), 
										'396'=>array('2','14','332','23964','1462'), 
										'397'=>array('102','99','2','14','27'),	
										'398'=>array('102','99','2','14','27'),
										'399'=>array('2','14','332','23964','1462'), 
										'400'=>array('102','99','2','14','27'),
										'401'=>array('102','99','2','14','27'),
										'402'=>array('2','14','332','23964','1462'), 
										'403'=>array('2','14','332','23964','1462'), 
										'404'=>array('2','14','332','23964','1462'), 
										'405'=>array('2','14','332','23964','1462'), 
										'406'=>array('102','99','2','14','27'), 
										'407'=>array('102','99','2','14','27'),
										'408'=>array('102','99','630','305','605'), //
										'409'=>array('102','630','650','310','1453'), //
										'410'=>array('102','99','2','14','27'), 
										'411'=>array('102','99','2','14','27'),	
										'412'=>array('102','99','2','14','27'),	
										'413'=>array('102','99','2','14','27'),
										'414'=>array('102','99','2','14','27'),
										'415'=>array('102','99','2','14','27'),
										'416'=>array('102','99','2','14','27')
										);
}else{
	$config['power_users'] = array('4','43');
	$config['top_topics'] = array('1','2','3','4','5','6','7','8','9','10');
	$config['topic_power_users'] = array(
										'0'=>array('43','6'), 
										'1'=>array('43','6'), 
										'2'=>array('43','6'), 
										'3'=>array('43','6'), 
										'4'=>array('43','6'), 
										'5'=>array('43','6'), 
										'6'=>array('43','6'), 
										'7'=>array('43','6'), 
										'8'=>array('43','6'), 
										'9'=>array('43','6'),
										'10'=>array('43','6'),
										'11'=>array('43','6'), 
										'12'=>array('43','6'), 
										'13'=>array('43','6'), 
										'14'=>array('43','6'), 
										'15'=>array('43','6'), 
										'16'=>array('43','6'), 
										'17'=>array('43','6'), 
										'18'=>array('43','6'), 
										'19'=>array('43','6'),
										'20'=>array('43','6'), 
										'21'=>array('43','6'), 
										'22'=>array('43','6'), 
										'23'=>array('43','6'), 
										'24'=>array('43','6'), 
										'25'=>array('43','6'), 
										'26'=>array('43','6'),
										'27'=>array('43','6'),
										'28'=>array('43','6'),
										'29'=>array('43','6')
										);
}
