<?php

//For Ray's server config(Remote server)
if (strpos(BASEPATH, 'Ray') !== false) {
    $config['base_url'] = 'http://ray.fantoon.com';
	$config['pre_login'] = array(
		'user' => 'sava',
		'password' => 'i6QFslRheXNR'
	);
}
//For Petru's server config
elseif(strpos(BASEPATH, 'Petru') !== false){

}
//For Alex's server config(Remote Server), Alex, you can change your config to your localhost if you want
elseif(strpos(BASEPATH, 'endway') !== false){
    $config['base_url'] = 'http://endway.fantoon.com/';
}elseif(strpos(BASEPATH, 'alexandr') !== false){
    $config['base_url'] = 'http://fandrop.loc/';
}elseif(strpos(BASEPATH, 'test.fandrop') !== false){
	$config['base_url'] = 'http://test.fandrop.com/';
	$config['pre_login'] = array(
		'user' => 'test',
		'password' => 'EhPyRco0pGaqWW4'
	);
} else {
	$config['base_url'] = 'http://www.fandrop.com/';
}

$config['login'] = array(
	'id' => '45',
	'email' => 'radilr@yahoo.com',
	'password' => '851221',
	'fist_name' => 'Radil',
	'last_name' => 'Radenkov'
);

$config['test_page'] = array(
    'page_id' => '2',
    'page_name' => 'microsoft',
	'official_url' => 'microsoft'
);

$config['test_user'] = array(
	'user_id' => '7',
	'first_name' => 'Derek',
	'last_name' => 'Shepherd'
);

$config['api_data'] = array(
	'users' => array(
		'test.user1@example.com',
		'test.user2@example.com'
	)
);

$config['objects'] = array(
	'users' => array(
		'first_name' => 'First%i',
		'last_name' => 'Last%i',
		'email' => 'test_user_%i@test.com'
	),
	'pages' => array(
		'page_name' => 'Interest %i',
		'uri_name' => 'Interest_%i',
		'interest_id' => 12,
		'sign_up_date' => '2012-01-24 21:47:48'
	),
	'custom_tabs' => array(
		'tab_name' => 'Tab %i',
		'activated' => true
	),
	'lists' => array(
		'list_maker_id' => $config['login']['id'],
		'list_name' => 'List %i',
		'description' => 'List Description %i'
	),
	'connections' => array(
		'user2_id' => '1',
		'user1_id' => $config['login']['id'],
	),
	'newsfeed_activity'=>array(
	    'newsfeed_id'=>'0',
	    'folder_id'=>'0',
	    'loop_id'=>'0',
	    'activity_user_id'=>'4',
	    'activity_user_type'=>'users',
	    'activity_id'=>'0',
	    'reply_user_id'=>'0',
	    'type'=>'link',
	    'a_data'=>'1',
	    'user'=>'',
	    'time'=>'2012-01-24 21:47:48'
	),
	'newsfeed'=> array(
	    'thread_id' => '0',
	    'loop_id' => '0',
	    'activity_user_id' => $config['login']['id'],
	    'type' => 'link',
	    'link_type' => 'text',
	    'activity_id' => '0',
	    'data'=>'a:26:{s:7:"link_id";s:3:"294";s:9:"thread_id";s:1:"1";s:12:"user_id_from";s:1:"1";s:10:"user_id_to";s:1:"0";s:12:"page_id_from";s:1:"0";s:10:"page_id_to";s:1:"2";s:8:"link_url";s:70:"http://www.imdb.com/oscars/galleries/rto2012-sag2012-press-rm226799872";s:8:"link_img";s:104:"http://ia.media-imdb.com/images/M/MV5BMTM4NjA4NjA0OV5BMl5BanBnXkFtZTcwNTg5NTMzNw@@._V1._SX640_SY471_.jpg";s:6:"s3_img";s:64:"https://s3.amazonaws.com/fantoon-dev/links/293-4f2af69dc1809.jpg";s:8:"s3_thumb";s:70:"https://s3.amazonaws.com/fantoon-dev/links/293-4f2af69dc1809_thumb.jpg";s:5:"title";s:80:"18th Annual Screen Actors Guild Awards - Backstage, Audience, and Press Gallery ";s:7:"content";s:69:"IMDb: The biggest, best, most award-winning movie site on the planet.";s:4:"text";s:0:"";s:5:"ptime";s:19:"2012-02-02 12:48:29";s:5:"media";s:0:"";s:4:"link";s:44:"<a href="/profile/Alexi-Ned/1">Alexi Ned</a>";s:3:"url";s:19:"profile/Alexi-Ned/1";s:9:"thumbnail";s:66:"https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg";s:13:"user_uri_name";s:9:"Alexi-Ned";s:9:"page_link";s:34:"<a href="/microsoft">microsoft</a>";s:8:"page_url";s:9:"microsoft";s:10:"page_thumb";s:66:"https://s3.amazonaws.com/fantoon-dev/pages/2/pics/thumbs/thumb.jpg";s:16:"page_category_id";s:2:"20";s:5:"point";s:3:"152";s:5:"likes";a:1:{i:0;a:8:{s:7:"like_id";s:2:"38";s:7:"user_id";s:1:"1";s:7:"page_id";s:1:"0";s:9:"like_time";s:19:"2012-02-02 13:29:46";s:4:"link";s:44:"<a href="/profile/Alexi-Ned/1">Alexi Ned</a>";s:3:"url";s:19:"profile/Alexi-Ned/1";s:9:"thumbnail";s:66:"https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg";s:4:"type";s:4:"user";}}s:8:"comments";a:1:{i:113;a:19:{s:10:"comment_id";s:3:"113";s:9:"parent_id";s:1:"0";s:7:"post_id";s:1:"0";s:8:"photo_id";s:1:"0";s:8:"event_id";s:1:"0";s:13:"reply_user_id";s:1:"0";s:13:"reply_page_id";s:1:"0";s:12:"user_id_from";s:1:"1";s:10:"user_id_to";s:1:"0";s:12:"page_id_from";s:1:"0";s:10:"page_id_to";s:1:"2";s:7:"comment";s:6:"where?";s:5:"ctime";s:19:"2012-02-02 13:29:39";s:4:"link";s:44:"<a href="/profile/Alexi-Ned/1">Alexi Ned</a>";s:3:"url";s:19:"profile/Alexi-Ned/1";s:9:"thumbnail";s:66:"https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg";s:5:"point";s:3:"152";s:8:"children";a:0:{}s:5:"likes";a:0:{}}}}',
	    'time'=>'2012-01-24 21:47:48',
	    'page_type'=>'page',
	    'user_id_from'=>'1',
	    'user_id_to'=>'0',
	    'page_id_from'=>'0',
	    'page_id_to'=>'2',
	    'location'=>null,
	    'latitude'=>null,
	    'longitude'=>null,
	    'city'=>null,
	    'province'=>null,
	    'country'=>null,
	    'folder_id'=>'12',
	    'up_count'=>'0',
	    'comment_count'=>'0',
	    'collect_count'=>'0'
	),
	'topics' => array(
		'avatar'=>'',
		'thumbnail'=>'',
		'topic_name'=>'Topic %i',
		'user_hits'=>'0',
		'hits'=>'0',
		'merge'=>'0'
	),
	'topic_aliases' => array(
		'topic_id' => '0',
		'aliases' => 'test_alias_%i'
	),
	'comments' =>array(
	    'parent_id' => 0,
	    'reply_user_id' => 0,
	    'reply_page_id' => 0,
	    'user_id_from' => 0,
	    'user_id_to' => 0,
	    'page_id_from' => 0,
	    'page_id_to' => 0,
	    'post_id' => 0,
	    'photo_id' => 0,
	    'event_id' => 0,
	    'pr_id' => 0,
	    'link_id' => 1,
	    'comment' => 'test comment %i'
	),
	'test_profile' => array(
		'id' => 396,
		'fist_name' => 'Stefani',
		'last_name' => 'Germanotta',
		'uri_name' => 'Stefani-Germanotta',
		'email' => 'g@g.com'
	),
	'test_folder' => array(
		'folder_id' => 70,
		'folder_name' => 'Music',
		'user_id' => 396,
		'private' => 1,
		'fist_name' => 'Stefani',
		'last_name' => 'Germanotta',
		'uri-name' => 'Stefani-Germanotta'
	),
	'likes' => array(
		'user_id'=>0,
		'page_id'=>0,
		'post_id'=>0,
		'photo_id'=>0,
		'event_id'=>0,
		'pr_id'=>0,
		'link_id'=>0,
		'comment_id'=>0
	),
	'links' =>array(
		'thread_id' => 1,
	    'user_id_from' => 1,
	    'user_id_to' => 1,
	    'page_id_from' => 0,
	    'page_id_to' => 0,
	    'link' => 'http://www.cnn.com',
	    'img' => '',
	    'media' => '',
	    'title' => 'test title',
	    'content' => 'test content',
	    'text' =>'',
	    'time' => '2012-02-27 21:16:20'
	)
);