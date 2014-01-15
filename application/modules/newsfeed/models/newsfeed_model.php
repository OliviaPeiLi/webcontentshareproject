<?php
class Newsfeed_model extends MY_Model
{
	public $name_key = 'title';
	protected $_table = 'newsfeed';		 //Table name should be 'newsfeeeds'
	protected $primary_key = 'newsfeed_id'; //Primary key should be 'id'
	
	//relations
	protected $belongs_to = array(
								'folder',
								'user_from' => array('foreign_column'=>'user_id_from', 'foreign_model'=>'user'),
								'orig_user' => array('foreign_column'=>'orig_user_id', 'foreign_model'=>'user'),
							);
	protected $polymorphic_belongs_to = array(
											'activity' => array(
													'model_column' => 'type',
													'on_delete_cascade' => true
											)
										);
	protected $has_many = array(
							  //'newsfeed_activities',
							  'newsfeed_hashtags', 'likes', 'comments', 'newsfeed_shares', 'mentions', 'newsfeed_referrals'
						  );
						  
	//Behaviors
	public $behaviors = array(
							'uploadable' => array(
								'img' => array(
									'folder' => 'links',
									'no_enlarge' => true,
									'rename_original' => true,
									'default_image' => array(
										'link_type<>content'=>'loading_icons/bigLoading.gif',
										'link_type==content'=>'loading_icons/bigLoading.gif',
									),
									'upload_to_s3' => true,
									'save_size' => true,
									'thumbnails' => array(
										'full' => array(
											'create_thumb' => true,
											'transform' => array(
												'watermark' => array(
													array(
														'wm_overlay_path' => '{images_path}/backgrounds/watermark.png',
														'wm_vrt_alignment' => 'below'
													),
													array(
														'wm_text' => 'fandrop.com',
														'wm_font_color' => '333333'
													),
													array(
														'wm_text' => '                            /drop/{newsfeed_id}',
														'wm_font_color' => 'D1D3D4',
													),
												)
											)
										),
										'thumb' => array( // timeline
											'width' => 500,
											'height' => 999999999,//2000,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array(
												'resize',
												'watermark' => array(
													array(
														'wm_overlay_path' => '{images_path}/backgrounds/watermark.png',
														'wm_vrt_alignment' => 'below'
													),
													array(
														'wm_text' => 'fandrop.com',
														'wm_font_color' => '333333'
													),
													array(
														'wm_text' => '                            /drop/{newsfeed_id}',
														'wm_font_color' => 'D1D3D4',
													),
												)
											)
										),
										'tile' => array( //tileview
											'width' => 200,
											'height' => 999999999,//1000,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array(
												'resize',
												'watermark' => array(
													array(
														'wm_overlay_path' => '{images_path}/backgrounds/watermark.png',
														'wm_vrt_alignment' => 'below'
													),
													array(
														'wm_text' => 'fandrop.com',
														'wm_font_color' => '333333'
													),
													array(
														'wm_text' => '                            /drop/{newsfeed_id}',
														'wm_font_color' => 'D1D3D4',
													),
												)
											)
										),
										'small' => array( // for grabbed data from fb/twtr to display in a signup popup
											'width' => 50,
											'height' => 999999999,//200,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize')//, 'crop')
										),
										'square' => array( // for link popup right side "Originally dropped by", "Dropped via"
											'width' => 50,
											'height' => 50,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'bigsquare' => array( // for link popup right side "Originally dropped by", "Dropped via"
											'width' => 112,
											'height' => 112,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										//New design
										'22' => array(
											'width' => 22,
											'height' => 22,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'45' => array(
											'width' => 45,
											'height' => 45,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'52' => array(
											'width' => 52,
											'height' => 52,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'75' => array(
											'width' => 75,
											'height' => 75,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'83' => array(
											'width' => 83,
											'height' => 61,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'150' => array(
											'width' => 150,
											'height' => 100,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'229' => array(
											'width' => 229,
											'height' => 182,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'320' => array(
											'width' => 320,
											'height' => 270,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'576' => array(
											'width' => 576,
											'height' => 999999999,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array(
												'resize',
												'watermark' => array(
													array(
														'wm_overlay_path' => '{images_path}/backgrounds/watermark.png',
														'wm_vrt_alignment' => 'below'
													),
													array(
														'wm_text' => 'fandrop.com',
														'wm_font_color' => '333333'
													),
													array(
														'wm_text' => '                            /drop/{newsfeed_id}',
														'wm_font_color' => 'D1D3D4',
													),
												)
											)
										),
										
									)
								)
							),
							'mentionable' => array(
								'description' => array(
									'mention'=>TRUE,
									'hashtag'=>TRUE,
									'link'=>TRUE,
									'hashtag_id' => TRUE, //update hashtag_id column
									'model' => 'newsfeed',
									'has_many' => 'newsfeed_hashtags'
								)
							),
							'countable' => array(
								array(
									'table' => 'user_stats',
									'relation' => array('user_id_from' => 'user_id'),
									'fields' => array(
										'htmls_count' => 'link_type_id==1',
										'contents_count' => 'link_type_id==2',
										'embeds_count' => 'link_type_id==3',
										'texts_count' => 'link_type_id==4',
										'images_count' => 'link_type_id==5',
									)
								),
								array(
									'table' => 'user_stats',
									'relation' => array('orig_user_id' => 'user_id'),
									'fields' => array('redrops_count'),
								),
								array(
									'table' => 'folder',
									'relation' => array('folder_id' => 'folder_id'),
									'fields' => array('newsfeeds_count'),
								),
								array(
									'table' => 'newsfeed',
									'relation' => array('parent_id' => 'newsfeed_id'),
									'fields' => array('collect_count'),
								),
							),
							'cachable' => array(
								array(
									'_model' => 'newsfeed',
									'update' => array('folder'=>'recent_newsfeeds'),
									'relation' => array('folder_id'=>'folder_id'),
									'max' => 4, 'skip_first' => true,
									'data' => array('link_type', 'description_plain', 'img', 'text')
								)
							),
							'active' => array(
    							'primary_key' => 'activity_id',
    							'user_from_field' => 'user_id_from',
								'folder_id' => 'folder_id',
    							'type' => array('type'),
    						),
							'notify' => array(
								'user_to_field' => 'orig_user_id',
								'primary_key' => 'newsfeed_id',
								'type' => array(
									'redrop' => 'parent_id > 0',
								)
							),
							'paranoid' => array(
								'table' => 'newsfeed',
								'primary_key' => 'newsfeed_id'
							)
	);
	
	public $validate = array(
				'newsfeed_id'  => array( 'label' => 'ID',          'rules' => '' ),
				'parent_id'    => array( 'label' => 'Parent ',     'rules' => '' ),
				'link_type'    => array( 'label' => 'Type',        'rules' => 'required|newsfeed_type' ),
				'activity'     => array( 'label' => 'Activity',    'rules' => 'required|newsfeed_activity' ),
				'user_id_from' => array( 'label' => 'User',        'rules' => 'required' ),
				'folder_id'    => array( 'label' => 'Folder',      'rules' => 'required|folder_id' ),
				'title'        => array( 'label' => 'Title',       'rules' => '' ),
				'description'  => array( 'label' => 'Description', 'rules' => 'required|max_lengh[150]' ),                   // description_chars_limit
				'img'          => array( 'label' => 'Image',       'rules' => 'required' ),
				'link_url'     => array( 'label' => 'Source Link', 'rules' => '' ),
				//Contests
				'top_prize'    => array( 'label' => 'Prize',       'rules' => '' ),
				'share_goal'   => array( 'label' => 'Share Goal',  'rules' => '' ),
				'sub_type'     => array( 'label' => 'sub_type',  'rules' => '' ),
	);
	
	public $link_types = array('','html','content','embed','text','image');
	public $share_count = 'fb_share_count+twitter_share_count+pinterest_share_count+gplus_share_count+linkedin_share_count+email_share_count'; 
	
	private $is_redrop = false;

	/* ======================== PER ITEM FUNCS ============================= */
	
	public function get_share_count($newsfeed) {
		return $newsfeed->fb_share_count
			  +$newsfeed->twitter_share_count
			  +$newsfeed->pinterest_share_count
			  +$newsfeed->gplus_share_count
			  +$newsfeed->linkedin_share_count
			  +$newsfeed->email_share_count;
	}
	
	public function get_points($newsfeed) {
		return 
			(int) mysql_fetch_object(mysql_query("SELECT SUM(views) as num FROM newsfeed_referrals WHERE newsfeed_id = ".$newsfeed->newsfeed_id))->num
			+ $newsfeed->uniqview+($newsfeed->twitter_share_count*10)/* + ($newsfeed->share_count*10)*/ /*+ $newsfeed->hits*/;
	}
	
	/*
	public function get_sxsw_share_count($newsfeed) {
		return $this->newsfeed_model->select_fields(array("SUM(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count) as share_count"))
					->get_by(array('user_id_from'=>$newsfeed->user_id_from, 'title' => $newsfeed->title))->share_count;
	}*/
	
	public function icrease_view_count($newsfeed) {
		mysql_query("UPDATE newsfeed SET hits = hits + 1 WHERE newsfeed_id = ".$newsfeed->newsfeed_id);
		return $this;
	}
	
	public function can_edit($newsfeed, $user) {
		if (!$user || !is_object($user)) return false;
		return $newsfeed->user_id_from == $user->id || $user->role == 1 || $user->role == 2;
	}
	
	public function is_liked($newsfeed, $user=null) {
		if (!$user || !isset($user->id)) return false;
		if (is_object($user)) $user_id = $user->id;
		return $this->db->where("newsfeed_id = '{$newsfeed->newsfeed_id}' AND user_id = {$user_id}")->count_all_results('likes');
	}
	
	public function get_previous($newsfeed)	{
		return $this->order_by('newsfeed_id','asc')->get_by(array('newsfeed_id >'=>$newsfeed->newsfeed_id, 'folder_id'=>$newsfeed->folder_id));
	}

	public function get_next($newsfeed)	{
		return $this->order_by('newsfeed_id','desc')->get_by(array('newsfeed_id <'=>$newsfeed->newsfeed_id, 'folder_id'=>$newsfeed->folder_id));
	}
	
	public function get_text($newsfeed) {
		if ($newsfeed->link_type != 'text') return null;
		return $newsfeed->activity->content;
	}
	
	public function is_shared($newsfeed, $user, $api='fb') {
		if (!$user || !isset($user->id)) return false;
		if (is_object($user)) $user_id = $user->id;
		return (bool) $this->newsfeed_share_model->count_by(array(
			'user_id' => $user_id,
			'newsfeed_id' => $newsfeed->newsfeed_id,
			'api' => $api,
		));
	}
	
	public function get_shares($newsfeed, $from=null, $to=null) {
		if (!$from || $from <= 0) $from = time()-60*60*24*7;
		if (!$to) $to = time();
		$interval = 60*60*24; //12 hours
		//was used in sxsw
		//$newsfeeds = $this->newsfeed_model->_set_where(array(array('newsfeed_id'=> $newsfeed->title, 'user_id_from'=>$newsfeed->user_id_from)))->dropdown('newsfeed_id','title');
		$res = $this->newsfeed_share_model
					->_set_where(array(array('newsfeed_id'=> $newsfeed->newsfeed_id)))
					->_set_where(array('created_at >= ', date('Y-m-d H:i:s', $from)))
					->order_by('created_at','asc')->group_by('FLOOR(UNIX_TIMESTAMP(created_at) / '.$interval.')')
					->dropdown('FLOOR(UNIX_TIMESTAMP(created_at) / '.$interval.')', 'COUNT(id)');
		$ret = array();
		$num = 0;
		for ($i=floor($from/$interval);$i < floor($to/$interval); $i++) {
			$res[$i] = isset($res[$i]) ? $res[$i] : 0;
			$num += (int) $res[$i];
			$ret[] = $num;
			if (isset($res[$i+1])) {
				$num += rand($num, $res[$i+1]);
				$ret[] = $num;
				$num += rand($num, $res[$i+1]);
				$ret[] = $num;
				$num += rand($num, $res[$i+1]);
				$ret[] = $num;
	   		}
		}
		return $ret;
	}
	
	public function is_youtube($newsfeed) {
		return $newsfeed->type === 'link' && $newsfeed->link_type === 'embed' && (
					strpos($newsfeed->link_url, 'youtube.com/v/') !== false || strpos($newsfeed->link_url, 'youtube.com/watch') !== false 
				);
	}
	
	public function last_redrops($newsfeed, $redrop_limit = 4) {
		return $this->newsfeed_model->with('folder')->select_fields(array(
			'*', 'folder.folder_id as `folder:folder_id`', 'folder_name as `folder:folder_name`', 'private as `folder:private`',
			'folder.user_id as `folder:user_id`', 'folder.folder_uri_name as `folder:folder_uri_name`'))
			->limit($redrop_limit)
			->get_many_by(array('private'=>'0', 'parent_id' => $newsfeed->newsfeed_id));
	}
	
	public function redrop($newsfeed, $user_id, $folder_id, $overwrite=array()) {

		$this->is_redrop = true;

		$new_feed = $newsfeed->as_array();
		$link = $newsfeed->activity->as_array();
		unset($link['link_id']);
		$link['user_id_from'] = $user_id;
		$content = $link['content']; unset($link['content']);
		$new_feed['activity_id'] = $this->link_model->insert($link);
		
		$new_feed['activity_user_id'] = $user_id;
		$new_feed['user_id_from'] = $user_id;
		$new_feed['folder_id'] = $folder_id;
		$new_feed['parent_id'] = $newsfeed->newsfeed_id;

		unset($new_feed['newsfeed_id'], $new_feed['up_count'], $new_feed['comment_count'], $new_feed['collect_count'],
				$new_feed['hits'], $new_feed['fb_share_count'], $new_feed['url']
			);
		
		$this->newsfeed_model = $this->newsfeed_model;
		$newsfeed_model = new Newsfeed_model();
		unset($newsfeed_model->behaviors['uploadable']);
		$new_feed = array_merge($new_feed, $overwrite);
		$new_feed_id = $newsfeed_model->insert($new_feed);
		
		if ($new_feed['link_type'] == 'content') {
			get_instance()->load->library('s3');
			@S3::copyObject(
					   Url_helper::s3_bucket(), 'uploads/screenshots/drop-'.$newsfeed->newsfeed_id.'/index.php', 
					   Url_helper::s3_bucket(), 'uploads/screenshots/drop-'.$new_feed_id.'/index.php',
					   S3::ACL_PUBLIC_READ
		 	);
		} elseif (isset($content)) {
			mysql_query("UPDATE links SET content = '".mysql_real_escape_string($content)."' WHERE link_id = ".$new_feed['activity_id']);
		}
		
		mysql_query("UPDATE folder SET is_ranked = 0 WHERE folder_id = ".$folder_id);
		
		return $new_feed_id;
	}
	//End per item funcs
	
	public function sample() {
		$sample = new Model_Item();
		$sample->_model = $this;
		$sample->newsfeed_id = -1;
		$sample->title = '';
		$sample->time = date('Y-m-d H:i:s');
		$sample->description = '';
		$sample->url = '';
		$sample->short_url = '';
		$sample->link_type = '';
		$sample->type = 'link';
		$sample->sub_type = '';
		$sample->coversheet_updated = false;
		$sample->img_width = 0;
		$sample->img_height = 0;
		$sample->up_count = 0;
		$sample->collect_count = 0;
		$sample->uniqview = 0;
		$sample->fb_share_count = 0;
		$sample->pinterest_share_count = 0;
		$sample->twitter_share_count = 0;
		$sample->gplus_share_count = 0;
		$sample->share_count = 0;
		$sample->linkedin_share_count = 0;
		$sample->complete = 0;
		$sample->link_type_class = '-1';
		$sample->user_id_from = 0;
		$sample->img_full = '';
		$sample->img_tile = '';
		$sample->img_thumb = '';
		$sample->img_bigsquare = '';
		$sample->link_url = '';
		$sample->source = '-1';
		$sample->comment_count = 0;
		$sample->share_goal = 0;
		$sample->top_prize = '';
		$sample->hits = '';
		
		$sample->folder_id = 0;
		$sample->folder = new Model_Item();
		$sample->folder->_model = $this->folder_model;
		$sample->folder->folder_id = -1;
		$sample->folder->folder_name = '';
		$sample->folder->folder_uri_name = '';
		$sample->folder->type = 0;
		$sample->folder->contest_id = 0;
		$sample->user_from = new Model_Item();
		$sample->user_from->_model = $this->user_model;
		$sample->user_from->id = -1;
		$sample->user_from->url = '';
		$sample->user_from->avatar_42 = '';
		$sample->user_from->full_name = '';
		$sample->user_from->role = 0;
		
		return $sample;
	}
	
	/* ========================================= SELECTS ====================================== */
	
	public function select_list_fields() {
		$fields = array(
			'newsfeed.newsfeed_id', 'newsfeed.title', 'newsfeed.description', 'newsfeed.url', 'newsfeed.link_type', 'newsfeed.type', 'newsfeed.sub_type', 'newsfeed.coversheet_updated',
			'newsfeed.img', 'newsfeed.img_width', 'newsfeed.img_height', 'newsfeed.link_url',
			'newsfeed.up_count', 'newsfeed.collect_count', 'newsfeed.comment_count', 'newsfeed.time',
			'newsfeed.fb_share_count','newsfeed.pinterest_share_count','newsfeed.twitter_share_count','newsfeed.gplus_share_count','newsfeed.linkedin_share_count','newsfeed.email_share_count',
			'(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count+email_share_count) as share_count',
			'share_goal', 'top_prize', 'hits', 'uniqview', 'short_url', 'sxsw_email',
			'newsfeed.folder_id', 'newsfeed.user_id_from', 'newsfeed.activity_id', 'newsfeed.complete'
		);
		
		foreach ($this->db->ar_join as $join) {
			if (strpos($join, "JOIN `folder`") !== false) {
				$fields = array_merge($fields, array(
					'folder.folder_uri_name as `folder:folder_uri_name`', 'folder.folder_name as `folder:folder_name`', 'folder.private as `folder:private`',
					'folder.user_id as `folder:user_id`','folder.contest_id as `folder:contest_id`',
				));
			} elseif (strpos($join, "JOIN `users`") !== false) {
				$fields = array_merge($fields, array(
					'users.id as `users:id`', 'users.uri_name as `users:uri_name`', 'users.avatar as `users:avatar`', 'users.first_name as `users:first_name`', 'users.last_name as `users:last_name`',
					'users.first_name as `users:first_name`',
					'users.last_name as `users:last_name`',
					'users.role as `users:role`'
				));
			}
		}
		
		return $this->select_fields($fields);		
	}
	
	public function select_dashboard_fields() {
		$fields = array(
						"newsfeed_id", "title", "user_id_from", "img",
						"(SELECT SUM(fb_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as total_fb_share_count",
						"(SELECT SUM(pinterest_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as total_pinterest_share_count",
						"(SELECT SUM(twitter_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as total_twitter_share_count",
						"(SELECT SUM(gplus_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as total_gplus_share_count",
						"(SELECT SUM(linkedin_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as total_linkedin_share_count",
						"(SELECT SUM(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count)
							FROM newsfeed as n1 
							WHERE n1.user_id_from = newsfeed.user_id_from
								AND n1.title = newsfeed.title) 
							as share_count"
					);
		$this->select_fields($fields);
		return $this;
	}
	
	public function select_search_fields($keyword) {

		$keyword = mysql_real_escape_string($keyword);

		$fields =  array('newsfeed.newsfeed_id','newsfeed.title', 'newsfeed.description', 'newsfeed.url', 'newsfeed.link_type', 'newsfeed.type', 'newsfeed.coversheet_updated',
			'newsfeed.img', 'newsfeed.img_width', 'newsfeed.img_height', 'newsfeed.link_url',
			'newsfeed.up_count', 'newsfeed.collect_count', 'newsfeed.comment_count', 'newsfeed.fb_share_count','newsfeed.pinterest_share_count','newsfeed.twitter_share_count','newsfeed.gplus_share_count','newsfeed.linkedin_share_count','newsfeed.email_share_count',
			'newsfeed.folder_id', 'newsfeed.user_id_from', 'newsfeed.activity_id', 'newsfeed.complete'
		);
		
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->solr_select = $fields;
			return $this;
		} else {
			$this->select_fields($fields);
			if (strlen($keyword) > 3) {
				return $this->select_fields("MATCH(description, link_url) AGAINST ('$keyword') as relevance", false);
			} else {
				return $this->select_fields("((CASE WHEN `description` LIKE '%$keyword%' THEN 1 ELSE 0 END) + (CASE WHEN `link_url` LIKE '%$keyword%' THEN 1 ELSE 0 END)) AS relevance", false);
		   	}
		}
	}
	
	public function jsonfy($limit = NULL) {

		if (is_array($limit)) {
			$res = $limit;
		} else {
			$res = $this->get_all($limit);
		}
		$is_contest = false;
		if (isset($res[0]) && $res[0]->folder->type) {
			$is_contest = true;
		}

		foreach ($res as &$newsfeed) {
			$newsfeed->is_shared = $newsfeed->is_shared($this->user);
			$newsfeed->is_liked = $newsfeed->is_liked($this->user);
			$newsfeed->can_edit = $newsfeed->can_edit($this->user);
			$newsfeed->share_count = $newsfeed->share_count;
			$newsfeed->styled_time =  date("M d",strtotime($newsfeed->time));
			
			$newsfeed->folder = $newsfeed->get('folder')->with('user')->select_newsfeed_fields()->get_by(array());
			$newsfeed->user_from = $newsfeed->get('user_from')->select_newsfeed_fields()->get_by(array());
			
			if ($newsfeed->link_type == 'text') {
				$newsfeed->content = $newsfeed->get('activity')->select_fields(array('content','link_id'))->get_by(array())->content;
			}
			
			foreach ($this->behaviors['uploadable']['img']['thumbnails'] as $thumb=>$thumb_data) {
				if (isset($newsfeed->{'_img_'.$thumb})) {
					$newsfeed->{'_img_'.$thumb} = Html_helper::img_src($newsfeed->{'_img_'.$thumb});
				}
			}
			
			$newsfeed->img = Html_helper::img_src($newsfeed->img);
			
			$newsfeed->twt_shared = $newsfeed->is_shared( $this->user, 'twitter' );
			$newsfeed->fb_shared =  $newsfeed->is_shared( $this->user, 'fb' );
			$newsfeed->pin_shared = $newsfeed->is_shared( $this->user, 'pinterest' );
			
			if ($is_contest) {
				$newsfeed->share_btns = Html_helper::twitter_btn($newsfeed);
				if (!in_array($res[0]->folder->contest->url, array('fndemo','crowdfunderio'))) {
					$newsfeed->share_btns .= Html_helper::fb_share_btn($newsfeed);
					$newsfeed->share_btns .= Html_helper::gplus($newsfeed);
					if (!in_array($res[0]->folder->contest->url, array('cite'))) {
						$newsfeed->share_btns .= Html_helper::pinterest_btn($newsfeed); 
						$newsfeed->share_btns .= Html_helper::likedin($newsfeed); 
					}
				}
				$newsfeed->points = (int) $newsfeed->points;
			}
			
			unset($newsfeed->_model, $newsfeed->folder->_model, $newsfeed->_model_folder,
					$newsfeed->user_from->_model, $newsfeed->_model_user_from);
		}
		
		return $res;
	}
	
	/* ================================== FILTERS ========================================= */
		
	public function has_likes() {
		$this->db->where("EXISTS (SELECT 1 FROM likes WHERE likes.newsfeed_id = newsfeed.newsfeed_id)", null, false);
		return $this;
	}
	
	public function not_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("NOT EXISTS (SELECT 1 FROM likes WHERE likes.newsfeed_id = newsfeed.newsfeed_id AND user_id = $user_id)", null, false);
		return $this;
	}
	
	public function filter_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("EXISTS (SELECT 1 FROM likes WHERE likes.newsfeed_id = newsfeed.newsfeed_id AND user_id = $user_id)", null, false);
		return $this;
	}
	
	public function has_comments() {
		$this->db->where("EXISTS (SELECT 1 FROM comments WHERE comments.newsfeed_id = newsfeed.newsfeed_id)", null, false);
		return $this;
	}
	
	public function filter_user_group($group) {
		$this->db->join('users', "users.id = newsfeed.user_id_from AND users.info = '$group'");
		return $this;
	}
	
	public function filter_hashtag($hashtag) {
		$hashtag_id = is_object($hashtag) ? $hashtag->id : $hashtag;
		$this->db->where('hashtag_id', $hashtag_id);
		return $this;
	}

	// Quang: there is similar function in user_model
	// if any change, also change in user_model for consistency
	public function try_solr() {
		$ci = get_instance();
		$solr_conf = $ci->load->config('solr');
		$db = $this->load->database($solr_conf['address'], true);
		if ($db->connected) {
			$this->cached_db = $this->db;
			$this->db = $db;
			$this->_table = 'newsfeed';
			return true;
		}
		return false;
	}
	
	public function search($keyword) {
		// don't care +, someone use it for a MUST character
		$keyword = str_replace('+', '', $keyword);
		$keyword = mysql_real_escape_string($keyword);
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->db->where(array( 'title' => $keyword, 'description' => $keyword, 'content_plain' => $keyword ));
		} elseif (strlen($keyword) > 3) {
			$this->db->where("MATCH (description, link_url) AGAINST('$keyword*' IN BOOLEAN MODE)", NULL, false);
		} else {
			$this->db->where("(title LIKE '%$keyword%' OR description LIKE '%$keyword%')", NULL, false);
		}

		return $this;
	}
	
	public function with_thumbnail() {
		$this->db->where("link_type != 'text'");
		return $this;
	} 
	
	public function filter_user($user_id) {
		$this->_set_where(array(array("user_id_from" => $user_id)));
		return $this;
	}
	
	public function filter_user_likes($user_id) {
		$this->db->join('likes', 'likes.newsfeed_id = newsfeed.newsfeed_id AND likes.user_id = '.$user_id);
		return $this;
	}
	
	public function filter_user_mentions($user_id) {
		$this->db->join('mentions', 'mentions.newsfeed_id = newsfeed.newsfeed_id AND mentions.user_id_to = '.$user_id);
		return $this;
	}

	public function recent() {
		$this->db->where("newsfeed.time > '".date('Y-m-d H:i:s', time()-5*60)."'")
				->order_by('newsfeed.time DESC')
				->limit(7);
		return $this;
	}

	public function filter_complete() {
		$this->db->where_in('complete',array('','1'));
		$this->db->where(array('img !='=>'bad_drop.png'));
		return $this;
	}
	
	public function filter_source($source) {
		$source = mysql_real_escape_string($source);
		$source_obj = $this->db->query("SELECT id FROM sources WHERE source = '$source'")->row();
		$this->db->where("source_id", @$source_obj->id);
		return $this;
	}
	
	public function folder_id_in($arr) {
		if(empty($arr)) {
			$arr = array('0');
		}
		$this->db->where_in('folder_id', $arr);
		return $this;
	}

	public function filter_page_by_user_and_only_draft( $logged_in_user = false)	{
		if ($logged_in_user)	{
			$this->db->where( "folder.private != 1", NULL, FALSE );
			// $this->db->where( "folder.user_id != " . $logged_in_user, NULL, FALSE );
		}
	}
	
	public function filter_public(){
		$this->db->join('folder', 'folder.folder_id = newsfeed.folder_id');
		$this->db->where("folder.private!='1'", NULL, FALSE);
		return $this;
	}
	
	public function filter_type($type) {
		if ($this->db instanceof CI_DB_solr_driver) {
			if($type == 'clips') {
				$this->db->where(array('link_type' => 'html'));
				//$this->db->where(array('link_type' => 'html content'));
			} elseif($type == 'videos') {
				$this->db->where(array('link_type' => 'embed'));
			} elseif($type == 'texts') {
				$this->db->where(array('link_type' => 'text'));
			} elseif ($type == 'pictures') {
				$this->db->where(array('link_type' => 'image'));
			} elseif ($type == 'live_drops') {
				$this->db->where(array('link_type' => 'content'));
			}
		} else {
			if($type == 'clips') {
				$this->db->where("newsfeed.link_type_id", 1);
			} elseif($type == 'videos') {
				$this->db->where("newsfeed.link_type_id", 3);
			} elseif($type == 'texts') {
				$this->db->where("newsfeed.link_type_id", 4);
			} elseif ($type == 'pictures') {
				$this->db->where("newsfeed.link_type_id", 5);
			} elseif ($type == 'live_drops') {
				$this->db->where(array('link_type_id' => 2));
			}
	   	}
		return $this;
	}
	
	
	/* =========================================== EVENTS ===================================== */
	
	protected function _run_before_create($data) {
		if (!isset($data['time'])) {
			$data['time'] = date('Y-m-d H:i:s');
		}
		if (!isset($data['user_id_from'])) {
			$data['user_id_from'] = get_instance()->user->id;
		}
		
		if(isset($data['link_type']) && $data['link_type'] == 'html'){
			$data['complete'] = '0';
		}
		if(!isset($data['orig_user_id']) || $data['orig_user_id'] == 0){
			$data['orig_user_id'] = $data['user_id_from'];
		}
		
		if($this->user && in_array($this->user->role, array(1,2))){
			//for staff editors prompt their content by adding more upvotes
			$data['up_target'] = rand(5,15);
		}
		
		if(isset($data['link_type'])){
			foreach ($this->link_types as $link_type_id=>$link_type) if ($link_type == $data['link_type']) {
				$data['link_type_id'] = $link_type_id;
			}
		}
		return parent::_run_before_create($data);
	}
	
	/**
	 * @since 8/16/2012 RR - removed bigimg. no need to write all the static data in the databse we need just the image filename
	 */
	protected function _run_after_create($data) {
	
		if($data['folder_id'] > 0) {
			$contributors = $this->folder_contributor_model->filter_folder($data['folder_id'])->dropdown('user_id','user_id');
			foreach($contributors as $folder_contributor) {
				if ($folder_contributor == $data['user_id_from']) continue;
				$activity_id = $this->activity_model->insert(array(
										'user_id_from' => $data['user_id_from'],
										'user_id_to' => $folder_contributor,
										'folder_id' => $data['folder_id'],
										'activity_id' => $data['newsfeed_id'],
										'type' => 'newsfeed'
								));

				
				$notification_id = $this->notification_model->insert(array(
										'user_id_from' => $data['user_id_from'],
										'user_id_to' => $folder_contributor,
										'item_id' => $data['newsfeed_id'],
										'type' => "newsfeed",
										'a_id' => $activity_id
								));
			}
		}
		
		$ci = get_instance();
		if ($ci->is_mod_enabled('kissmetrics') && $ci->user) { //RR - $ci->user check is for the scripts
			//kissmetrics
			$ci->load->library('KISSmetrics/km');
			$ci->km->init($ci->config->item('km_key'));
			$ci->km->identify($ci->user->uri_name);
			$ci->km->record('made a '.$data['link_type'].' drop');
		}

		//upvote newsfeed by owner
		$this->like_model->insert(array('user_id'=>$data['user_id_from'], 'user_id_to'=>$data['user_id_from'], 'newsfeed_id'=>$data['newsfeed_id']));
		
		return parent::_run_after_create($data);
	}
	
	protected function _run_before_set($data) {				
		if (isset($data['description']) || isset($data['title'])) {
			if ( ! isset($data['url'])) {
				$url = $url_base = Url_helper::url_title($data['description'] ? $data['description'] : $data['title'], 100);
				$i = 0;
				while (mysql_fetch_object(mysql_query("SELECT 1 FROM newsfeed WHERE url = '$url' AND newsfeed_id <> ".(@$data['newsfeed_id'] ? $data['newsfeed_id'] : 0)))) {
					$url = $url_base.'-'.$i; $i++;
				}
				$data['url'] = $url;
			}
		}
		
		if (isset($data['link_url']) && $data['link_url']) {
			$source = parse_url($data['link_url']);
			$source = str_replace('www.', '', @$source['host']);
			if ($source) {
				$source_obj = $this->source_model->get_by('source', $source);
				if ( ! $source_obj) {
					$data['source_id'] = $this->source_model->insert(array('source'=> $source));
				} else {
					$data['source_id'] = $source_obj->id;
				}
		   	}
		   	$data['link_url'] = Url_helper::valid_url($data['link_url']);
		}
		
		return parent::_run_before_set($data);
	}
	
	protected function _run_after_set($data) {
		if (isset($data['img'])) {
			$default_image = Uploadable_Behavior::get_default_image($data, @$this->behaviors['uploadable']['img']['default_image']); 
			if (@$data['link_type'] == 'image' && $data['img'] != $default_image) {
  		  		$this->db->query("UPDATE newsfeed SET complete = 1 WHERE newsfeed_id = ".$data['newsfeed_id']);
		  	}
		}

		$this->notification_model->on_newsfeed_name_change( $this->newsfeed_model->get($data['newsfeed_id']) );

		return parent::_run_after_set($data);
	}
	
	public function _run_after_get($row) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->description)) $row->_description_plain = strip_tags($row->description);
		
		if (isset($row->link_type)) {
			switch ($row->link_type) {
				case 'html':
					$row->_link_type_class = 'tl_HTML';
					break;
				case 'photo':
				case 'image':
					$row->_link_type_class = 'tl_image';
					break;
				case 'embed':
					$row->_link_type_class = 'tl_video';
					break;
				case 'content':
					$row->_link_type_class = get_instance()->is_mod_enabled('live_drops') ? 'tl_RSS' : 'tl_HTML';
					break;
				default:
					$row->_link_type_class = 'tl_text';
			}
		}
		
		if (isset($row->link_url)) {
			if (!$row->link_url) {
				$row->_source = '';
			} else {
				$row->link_url = Url_helper::valid_url($row->link_url);
				$parsed = parse_url($row->link_url);
				$row->_source = str_replace('www.', '', $parsed['host']);
			}
		}
	}
	
	protected function _run_before_delete($obj) {
		$this->refresh_cache($obj);
		return parent::_run_before_delete($obj);
	}
	
	
	/**
	 * Model funcs
	 */
	public function refresh_cache($row) {
		$ci =& get_instance();
		$arr = array();
		if (!is_array($row)) foreach ($row as $key=>$val) $arr[$key] = $val;
		else $arr = $row;
		//delete all user newsfeeds cache files
		if (isset($arr['user_id_from']))
		{
			for ($i=0;$i<=5;$i++) {
				$ci->cache->delete("library_newsfeed_{$arr['user_id_from']}_folders_{$i}");
			}
			foreach (array('likes','mentions','comments','source') as $type) {
				for ($i=1;$i<=5;$i++) {
					$ci->cache->delete("library_newsfeed_{$arr['user_id_from']}_{$type}_{$i}_pictures");
					$ci->cache->delete("library_newsfeed_{$arr['user_id_from']}_{$type}_{$i}_clips");
					$ci->cache->delete("library_newsfeed_{$arr['user_id_from']}_{$type}_{$i}_videos");
					$ci->cache->delete("library_newsfeed_{$arr['user_id_from']}_{$type}_{$i}_texts");
				}
			}
			if (isset($arr['folder_id'])) {
				for ($i=1;$i<=5;$i++) {
					$ci->cache->delete("collection_newsfeed_data_{$arr['user_id_from']}_{$arr['folder_id']}_{$i}_pictures");
					$ci->cache->delete("collection_newsfeed_data_{$arr['user_id_from']}_{$arr['folder_id']}_{$i}_clips");
					$ci->cache->delete("collection_newsfeed_data_{$arr['user_id_from']}_{$arr['folder_id']}_{$i}_videos");
					$ci->cache->delete("collection_newsfeed_data_{$arr['user_id_from']}_{$arr['folder_id']}_{$i}_texts");
				}
			}
		}
		
	}
	
	/**
	 * Hack to change the field name for solr (should be removed when the solr config is updated for consistency)
	 */
	public function order_by($field, $order = 'ASC') {
		if ($this->db instanceof CI_DB_solr_driver && $field == 'relevance') {
			$field = 'news_rank'; 
		} elseif ($field == 'share_count') {
			$field = '('.$this->share_count.')';
		}
		return parent::order_by($field, $order);
	}
	
	public function get_all($limit = NULL) {
		if ($this->db instanceof CI_DB_solr_driver) {
			$ids = $this->db->get('newsfeed')->result_field('newsfeed_id');
			$this->db = $this->cached_db;
			if (!$ids) return array();
			return $this->get_many_by(array('newsfeed_id'=>$ids));
		} else {
			return parent::get_all($limit = NULL);
		}
	}

}
