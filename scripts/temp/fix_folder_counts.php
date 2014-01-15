<?php
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
   	define('ENVIRONMENT', 'development');
	define('BASEPATH', __DIR__.'/../../system/');
}
include_once BASEPATH.'../scripts/db.php';

/*

UPDATE user_stats SET 
	public_collections_count = (SELECT COUNT(folder_id) FROM folder WHERE type = 0 AND private = 0 AND folder.user_id = user_stats.user_id ),
	private_collections_count = (SELECT COUNT(folder_id) FROM folder WHERE type = 0 AND private = 1 AND folder.user_id = user_stats.user_id )

 */

while (1) {
	$has_results = false;
	$res = mysql_query("SELECT folder_id FROM folder WHERE flag = 0 LIMIT 1000");
	while ($row = mysql_fetch_object($res)) {
		$has_results = true;
		echo "Folder: ".$row->folder_id."\n";
		mysql_query("UPDATE folder SET
						flag = 1, 
						comments_count = (SELECT COUNT(comment_id) FROM comments WHERE comments.folder_id = folder.folder_id)
					WHERE folder_id = {$row->folder_id}
					");
	}
	if (!$has_results) break;
}
echo "DONE";