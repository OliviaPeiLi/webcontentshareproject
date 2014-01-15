<?php

class User_model extends MY_Model
{
	public static $roles = array('Login', 'Editor', 'Admin', 'Featured User', 'Sxsw');
	
	public $name_key = "CONCAT(first_name,' ',last_name) as full_name"; //used for ->dropdown() call by autocomplete
	
	protected $validate = array(
								'email' => array(
									'label' => 'Email Address',
									'rules' => 'required|valid_email|unique_email',
								),
								'uri_name' => array(
									'label' => 'Username',
									'rules' => 'required|min_length[3]|max_length[30]|special_char_space|unique_username'
								),
								'first_name' => array(
									'label' => 'First Name',
									'rules' => 'trim|required|special_char|min_length[1]|max_length[30]'
								),
								'last_name' => array(
									'label' => 'Last Name',
									'rules' => 'trim|special_char|max_length[30]'
								),
								'password' => array('label' => 'Password', 'rules' => 'required|md5'),
								'avatar' => array('label' => 'Avatar', 'rules' => ''),
								'role' => array('label' => 'Role', 'rules' => ''),
								'fb_id' => array('label' => 'Facebook ID', 'rules' => 'numeric'),
								'twitter_id' => array('label' => 'Twitter ID', 'rules' => 'numeric'),
								'gender' => array('label' => 'Gender', 'rules' => ''),
								'birthday' => array('label' => 'Birthday', 'rules' => ''),
						  );

	//Relations
	protected $has_one = array(
		'user_stat' => array('foreign_model' => 'user_stats'),
		'email_setting' => array('foreign_colum' => 'user_id'),
	);
	
	protected $has_many = array(
								'comments' => array('foreign_column' => 'user_id_from'),
								'newsfeeds' => array('foreign_column' => 'user_id_from'),
								'links' => array('foreign_column' => 'user_id_from', 'foreign_model' => 'link'),
								'mentions' => array('foreign_column' => 'user_id_to', 'foreign_model' => 'mention'),
								'user_visits' => array('foreign_column' => 'id','foreign_model' => 'user_visit'),
								'likes',
								'folders',
								'folder_users',
								'folder_contributors',
								'user_schools',
								'user_locations',
								//'pages' => array('foreign_column' => 'owner_id'),
								//'lists' => array('foreign_column' => 'list_maker_id'),
								//'list_users' => array('foreign_column' => 'list_user_id'),
								//'page_users',
								'connections' => array('foreign_column' => 'user1_id'),
								'user_followers' => array(
									'foreign_model' => 'connection',
									'foreign_column' => 'user2_id'
								),
								'user_followings' => array(
									'foreign_model' => 'connection',
									'foreign_column' => 'user1_id'
								),
								'notifications' => array('foreign_column'=>'user_id_to'),
								'badge_users'
						  );

	protected $many_to_many = array(
								  'badges' => array('through' => 'badge_user'),
								  'followings' => array(
									  'through' => 'connections',
									  'self_column' => 'user1_id',
									  'foreign_column' => 'user2_id',
									  'foreign_model' => 'user'
								  ),
								  'followers' => array(
									  'through' => 'connections',
									  'self_column' => 'user2_id',
									  'foreign_column' => 'user1_id',
									  'foreign_model' => 'user'
								  )
							  );
	//Behaviors
	public $behaviors = array(
							'uploadable' => array(
								'avatar' => array(
									'folder' => 'users',
									'default_image' => 'https://s3.amazonaws.com/fantoon-dev/users/default/blue_thumb.png',
									'upload_to_s3' => true,
									'thumbnails' => array(
										'25' => array( //side bar
											'width' => 25, 'height' => 25,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'42' => array( //notifications
											'width' => 42, 'height' => 42,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
										'73' => array( //profile
											'width' => 73, 'height' => 73,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										),
									)
								)
							)
						);
						
	/**
	 * Per item funcs
	 */
	public function icrease_view_count($user) {
		$this->db->query("UPDATE user_stats SET views_count = views_count + 1 WHERE user_id = ".$user->id);
		return $user;
	} 
	
	public function has_fb_collection($user) {
		return (bool) $user->get('folders')->count_by(array('rss_source_id' => 1));
	}
	
	public function has_twitter_collection($user) {
		return (bool) $user->get('folders')->count_by(array('rss_source_id' => 2));
	}
	
	public function is_following($user, $check_user_id) {
		if (is_object($check_user_id)) $check_user_id = $check_user_id->id;
		return $this->connection_model->count_by(array('user1_id'=>$user->id, 'user2_id'=>$check_user_id)) ? true : false;
	}
	
	public function is_follow($user, $user1_id) {
		if (!$user || !$user1_id) return false;
		return (bool) $this->connection_model->count_by(array('user1_id' => $user1_id, 'user2_id' => $user->id));
	}
	
	public function get_feature_drops($user, $limit = 7){
		$feature_drops = $this->newsfeed_model
						->select_fields(array('newsfeed.newsfeed_id','newsfeed.url','activity_user_id','newsfeed.type','newsfeed.complete','link_type','activity_id','user_id_from','newsfeed.folder_id','newsfeed.up_count','comment_count','collect_count','news_rank','newsfeed.hits','orig_user_id','title','description','img','img_width','img_height','newsfeed.hashtag_id','link_url'))
						->limit($limit)
						->order_by('newsfeed_id','DESC')
						->filter_complete()
						->filter_public()
						->get_many_by(array('user_id_from'=>$user->id,'complete'=>true));
						
		return $feature_drops ? $feature_drops : array();
	}
	
	public function get_most_shared_drops($user) {
		foreach($user->folders as $folder){
			$newsfeed = $this->newsfeed_model
					->order_by('share_count','desc')
					->get_by(array('folder_id'=>$folder->folder_id));
			if($newsfeed) {
				$newsfeeds[] = $newsfeed;
			}
		}
		return $newsfeeds;
	}
	//End per item funcs
	
	public function sample() {
		$sample = new Model_Item();
		$sample->_model = $this;
		$sample->id = -1;
		$sample->_avatar_73 = '';
		$sample->full_name = '';
		$sample->url = '';
		$sample->drops_url = '';
		$sample->likes_url = '';
		$sample->user_stat = new Model_Item();
		$sample->user_stat->_model = $this->user_stats_model;
		$sample->user_stat->collections = 0;
		$sample->user_stat->public_collections_count = 0;
		$sample->user_stat->_drops_count = 0;
		$sample->user_stat->upvotes_count = 0;
		return $sample;
	}
	
	public function jsonfy() {
		$res = $this->get_all();
		$me = get_instance()->user;
		foreach ($res as & $row) {
			$row->user_stat = $row->user_stat; //its relation so we get it
			
			unset($row->_model, $row->_avatar_42, $row->_avatar_25,
				$row->user_stat->_model);
			
			$row->is_following = $me && $me->is_following($row->id);
		}
		return $res;
	}

	public function select_newsfeed_fields() {
		return $this->select_fields(array('CONCAT(first_name," ",last_name) as full_name', 'avatar', 'uri_name', 'role'));
	}
	
	public function select_list_fields() {
		return $this->select_fields(array('users.id', 'users.first_name', 'users.last_name', 'users.uri_name', 'users.avatar'));
	}

	public function select_search_fields($keywords) {
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->solr_select = $keywords;
			return $this;
		} else {
			$fields = array('users.id', 'users.first_name', 'users.last_name', 'users.uri_name', 'users.avatar');
			if (strlen($keyword) > 3) {
				$fields[] = "MATCH (first_name, last_name, uri_name, email) AGAINST ('$keyword') as relevance";
			} else {
				$fields[] = "((CASE WHEN `first_name` LIKE '$keyword%' THEN 1 ELSE 0 END) + (CASE WHEN `uri_name` LIKE '$keyword%' THEN 1 ELSE 0 END) + (CASE WHEN `email` LIKE '$keyword%' THEN 1 ELSE 0 END)) AS relevance";
			}
			return $this->select_fields($fields, false);
		}
	}

	/**
	 * Filters
	 */
	public function filter_followings($user_id) {
		$this->db->join('connections', 'connections.user2_id = users.id', 'full');
		$this->_set_where(array(array('connections.user1_id' => $user_id)));
		return $this;
	}
	
	public function filter_editors(){
		$this->db->where('users.role', '1');
		$this->db->where(array('uri_name !='=>'test_user1'));
		return $this;
	}
	
	public function filter_featured(){
		$this->db->where_in('users.role', array(1,2));
		$this->db->where(array('uri_name !='=>'test_user1'));
		return $this;
	}

	public function filter_followers($user_id) {
		$this->db->join('connections', 'connections.user1_id = users.id', 'full');
		$this->_set_where(array(array('connections.user2_id' => $user_id)));
		return $this;
	}

	public function search($keyword) {
		$keyword = mysql_real_escape_string($keyword);
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->db->where(array( 'first_name' => "$keyword*", 'last_name' => "$keyword*", 'uri_name' => "*$keyword*" ));
		} elseif (strlen($keyword) > 3) {
			$this->db->where("MATCH (first_name, last_name, uri_name, email) AGAINST('$keyword*' IN BOOLEAN MODE)", NULL, false);
		} else {
			$this->db->where("(CONCAT(first_name, ' ' ,last_name) LIKE '".$keyword."%' OR email LIKE '".$keyword."%' OR uri_name LIKE '".$keyword."%')", NULL, false);
		}
		return $this;
	}

	public function search_name($keyword)
	{
		$keyword = mysql_real_escape_string($keyword);
		$where = "(CONCAT(first_name, ' ' ,last_name) LIKE '".$keyword."%')";
		$this->db->where($where);
		return $this;
	}

	public function filterout($arr)
	{
		if ($arr) $this->db->where_not_in('users.id', $arr);
		return $this;
	}

	/**
	 * Auth funcs
	 */
	public function set_login_data($user) {
		if (!$user->status) {
			$user->update(array('status'=>1));
		}
		$expire = time()+60*60*24*7*2; // 2 weeks
		setcookie('logged_in', true, $expire, '/'); //Non secured cookie
		
		$this->session->set_userdata(array(
											 'id' => $user->id
										 ));
	}
	
	public function get_current_user() {
		$ci = get_instance();
		if ($ci->session->userdata('id')) {
			$user = $this->get($ci->session->userdata('id'));
			if (!$user || !$user->id) {
				$this->logout();
				return false;
			}
			return $user;
		}
		//Check if user has a "remember cookie"
		if(Cookie_helper::get_cookie('fd_u')) {
			$user = $this->get_by(array('id'=>Cookie_helper::get_cookie('fd_u')));
			if ($user && $user->id && ($user->password == Cookie_helper::get_cookie('u_code') || $user->fb_token == get_cookie('u_code') || $user->twitter_token == get_cookie('u_code') || get_cookie('u_code') == 'fb_token')) {
				$this->set_login_data($user);
				return $user;
			}
		}
		return false;
	}

	public function login($email, $password, $remember = false) {
		$user = $this->get_by(array('email'=>$email));
		if($user && $user->password == md5($password)) {
			if($remember) {
				$this->set_remember($user->id, md5($password));
			}
			$this->set_login_data($user);
			return $user;
		}
		return FALSE;
	}
	
	public function set_remember($id, $code){
		$expire = time()+60*60*24*7*2; // 2 weeks
		Cookie_helper::delete_cookie("fd_u");
		Cookie_helper::delete_cookie("u_code");
		Cookie_helper::set_cookie('fd_u', $id, $expire);
		Cookie_helper::set_cookie('u_code', $code, $expire);
		if (!isset($_SESSION)) session_start();
		
		$_SESSION['uid'] = $id;
		$_SESSION['ucode'] = $code;
	}
	
	public function logout() {
		get_instance()->session->sess_destroy();

		//if the user was logged in no need to show request invite dialog anymore
		Cookie_helper::set_cookie( 'wli', '1', time() + 3600*24*30 ); //*was logged in = 1
		
		Cookie_helper::delete_cookie("fd_u");
		Cookie_helper::delete_cookie("u_code");
		Cookie_helper::delete_cookie("logged_in");
		
		return true;
	}
	
	/**
	 * Currently used only in the api - proably it will be moved or removed
	 */
	public function keep_login($uid, $u_code) {
		if($this->count_by(array('id'=>$uid, 'password'=>$u_code)))
		{
			$user = $this->get($uid);
			$this->session->set_userdata(array(
											 'email' => $user->email,
											 'id' => $user->id,
											 'uri_name' => $user->uri_name,
											 'name' => $user->first_name.' '.$user->last_name,
											 'first_name' => $user->first_name,
											 'status' => 1,
											 'is_logged_in' => true,
											 'role' => $user->role
										 ));
			return $user;
		}
		return FALSE;
	}
	
	/* ======= END AUTH ========== */
	
	/* ================================== EVENTS ========================= */
	
	protected function _run_before_create($data) {

		// echo "<pre>";
		// var_dump($data);
		// echo "</pre>";

		if (!isset($data['newsletter_time'])) $data['newsletter_time'] = date('Y-m-d H:i:s');
		if (!isset($data['avatar']) || !$data['avatar']) $data['avatar'] = Url_helper::s3_url().'users/blue_95.png';
		return parent::_run_before_create($data);
	}

	public function _run_after_create($data) {
		$ci = get_instance();
		
		$this->load->library('parser');
		
		//for user stats
		$this->user_stats_model->insert(array('user_id'=>$data['id']));
		//for user's email settings
		$this->email_setting_model->insert(array('user_id'=>$data['id']),array('connection'=>'0'));
		//for user_visit
		if (get_instance()->is_mod_enabled('signup_hashtag_step')) {
			$this->user_visit_model->insert(array('id'=>$data['id']));
		}
		else {
			$this->user_visit_model->insert(array('id'=>$data['id'], 'preview'=>'0'));
		}
		
		if (isset($data['email'])) {
			if (@$data['info'] == 'catathon') {
				$msg_data = array('user_name'=>$data['first_name']);
				$msg = $this->parser->parse('email_templates/catathon_template', $msg_data, TRUE);
				$subject = 'Catathon Challenge';
				$this->sending_email_model->insert(array('email'=>$data['email'], 'subject'=>$subject, 'message'=>$msg));
			} else {
				$msg_data = array('user_name'=>$data['first_name']);
				$msg = $this->parser->parse('email_templates/welcome_template', $msg_data, TRUE);
				$subject = 'Welcome to Fandrop';
				$this->sending_email_model->insert(array('email'=>$data['email'], 'subject'=>$subject, 'message'=>$msg));
			}
		}

		//RR - @removed - http://dev.fantoon.com:8100/browse/FD-3247 
		//Folllow all users from a group if they register from a group page
		//if(isset($data['info']) && $data['info']!=''){
		//	$friends = $this->user_model->select_fields(array('id'))->get_many_by(array('info'=>$data['info'],'id !='=>$data['id']));
		//	if($friends && !empty($friends)){
		//		foreach($friends as $friend){
		//			$this->connection_model->insert(array('user1_id'=>$data['id'],'user2_id'=>$friend->id));
		//			$this->connection_model->insert(array('user2_id'=>$data['id'],'user1_id'=>$friend->id));
		//		}
		//	}
		//}
		
		if($ci->is_mod_enabled('signup_invite') && isset($data['fb_id']) && $data['fb_id']) {
			$facebook = $ci->load->library('Facebook_driver');
			$friends = $facebook->get_user_friends();
			if (isset($friends->data) && $friends->data) {
				foreach($friends->data as $friend) {
					$users = $this->get_many_by(array('fb_id'=>$friend['id']));
					foreach ($users as $user) {
						$this->connection_model->insert(array(
							'user1_id' => $data['id'],
							'user2_id' => $user->id,
						), true);
					}							
				}
			}
		}		
		
		return parent::_run_after_create($data);
	}
	
	protected function _run_after_set($data) {
		$this->refresh_cache($data);
		return parent::_run_after_set($data);
	}
	
	public function _run_after_get($row=null) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->uri_name)) {
			$row->_url = '/'.$row->uri_name;
			$row->_drops_url = '/drops/'.$row->uri_name;
			$row->_likes_url = '/upvotes/'.$row->uri_name;
			$row->_mentions_url = '/mentions/'.$row->uri_name;
			$row->_comments_url = '/comments/'.$row->uri_name;
			$row->_library_url = '/library/'.$row->uri_name;

			$row->_images_url = '/images/'.$row->uri_name;
			$row->_clips_url = '/clips/'.$row->uri_name;
			$row->_videos_url = '/videos/'.$row->uri_name;
			$row->_screenshots_url = '/screenshots/'.$row->uri_name;
			$row->_texts_url = '/texts/'.$row->uri_name;
		}
		
		if (isset($row->first_name) && isset($row->last_name)) {
			$row->_full_name = $row->first_name.' '.$row->last_name;
		}
		
	}
	

	protected function _run_before_delete($obj) {
		$this->email_setting_model->delete($obj->id);
		$this->refresh_cache($obj);
		return parent::_run_before_delete($obj);
	}

	/**
	 * refresh_cache
	 *	this cache is to keep user model but focussing
	 *	on following/follower list
	 *
	 *	same refresh case should be removed when connection
	 *	table is updated (foreign_column)
	 *
	 * @param mixed $row
	 * @access public
	 * @return void
	 */
	private function refresh_cache($row)
	{
		$arr = array();
		if (!is_array($row)) foreach ($row as $key=>$val) $arr[$key] = $val;
		else $arr = $row;
		$ci = get_instance();
		if (isset($arr['id']) && isset($ci->cache))
		{
			$ci->cache->delete('user_model_'.$arr['id'].'_follow');
		}
	}

	// Quang: there is similar function in newsfeed_model
	// if any change, also change in user_model for consistency
	public function try_solr() {
		$ci = get_instance();
		$solr_conf = $ci->load->config('solr');
		$db = $this->load->database($solr_conf['address'], true);
		if ($db->connected) {
			$this->cached_db = $this->db;
			$this->db = $db;
			$this->_table = 'users';
			return true;
		}
		return false;
	}

	public function get_all($limit = NULL) {
		if ($this->db instanceof CI_DB_solr_driver) {
			$ids = $this->db->get('users')->result_field('id');
			$this->db = $this->cached_db;
			$this->_table = 'users';
			if (!$ids) return array();
			return $this->get_many_by(array('id'=>$ids));
		} else {
			return parent::get_all($limit = NULL);
		}
	}

}

