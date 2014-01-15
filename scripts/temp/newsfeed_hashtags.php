<?php 
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
}else{
   	define('ENVIRONMENT', 'development');
}

if(ENVIRONMENT == 'staging'){
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(ENVIRONMENT == 'production'){
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
	define('BASEPATH', __DIR__.'/../../system/');
}
include(BASEPATH.'../scripts/config.php');
include(BASEPATH.'../application/config/config.php');
include_once BASEPATH.'../scripts/db.php';

function get_hashtag_id($hashtag=null){
    
    //check hashtag
    $hashtag_query = mysql_pquery("SELECT id FROM hashtags WHERE hashtag='".$hashtag."'");
    
    $hashtag_data = mysql_fetch_object($hashtag_query);
    if($hashtag_data){
	    $hashtag_id = $hashtag_data->id;		
	    return $hashtag_id;
    }else{
    	return false;
    }
}

function get_newsfeed_hashtag($newsfeed_id=0, $hashtag_id=0){
    
    //check hashtag
    $hashtag_query = mysql_pquery("SELECT id FROM newsfeed_hashtags WHERE newsfeed_id=".$newsfeed_id." AND hashtag_id=".$hashtag_id);
    
    $hashtag_data = mysql_fetch_object($hashtag_query);
    if($hashtag_data){		
	    return TRUE;
    }else{
    	return false;
    }
}



while (1) {

	$res = mysql_query("SELECT newsfeed_id, description FROM newsfeed WHERE thumb_generated=0 ORDER BY newsfeed_id DESC LIMIT 1000");
	if (!$res) break;
	while ($newsfeed = mysql_fetch_assoc($res)) {
		
		if(!$newsfeed) break;
		//if (!isset($newsfeed['description'])) continue; //some places we don't always select comment text or caption
		$orig_text = $newsfeed['description'];
	    $newsfeed_id = $newsfeed['newsfeed_id'];

        $hashtag = "/_hash_[a-zA-Z0-9\-\.]+(\/\S*)?/";
        $match_hash = preg_match_all($hashtag, $orig_text, $hash_token);
        if($match_hash)
        {
            foreach($hash_token[0] as $token)
            {
            	$hashtag_id = get_hashtag_id($token);
            	if($hashtag_id > 0){
	            	if(get_newsfeed_hashtag($newsfeed_id, $hashtag_id)){
		            	//do nothing
	            	}else{
		            	mysql_pquery("UPDATE hashtags SET count = count+1 WHERE id=".$hashtag_id);
		            	mysql_pquery("INSERT into newsfeed_hashtags (newsfeed_id, hashtag_id) VALUES (".$newsfeed_id.", ".$hashtag_id.")");
	            	}
            	}else{
            		mysql_pquery("INSERT into hashtags (hashtag, count) VALUES ('".$token."', 1)");
            		$hashtag_id = mysql_insert_id();
	            	mysql_pquery("INSERT into newsfeed_hashtags (newsfeed_id, hashtag_id) VALUES (".$newsfeed_id.", ".$hashtag_id.")");
            	}
            }
        }
        
        mysql_query("UPDATE newsfeed SET thumb_generated = 1 WHERE newsfeed_id = '".$newsfeed_id."'");
	}
	//die('1000 done');
	echo 'one load is done';
	
	
}

