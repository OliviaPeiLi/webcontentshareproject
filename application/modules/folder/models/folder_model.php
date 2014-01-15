<?php

class Folder_model extends MY_Model
{
	public $name_key = 'folder_name';
	protected $_table = 'folder';
	protected $primary_key = 'folder_id';

	//Relations
	protected $has_many = array(
							  'newsfeeds' => array(
								  'foreign_model' => 'newsfeed',
								  'foreign_column' => 'folder_id'
							  ),
							  'folder_hashtags',
							  'folder_users',
							  'folder_contributors',
							  'likes',
							  'activities' => array(
								  'foreign_model' => 'activity',
								  'foreign_column' => 'folder_id'
							  )
						  );
	protected $many_to_many = array('hashtags');
	protected $belongs_to = array(
								'user', 'rss_source', 'hashtag', 'contest'
							);
							
	public $behaviors = array(
								'countable' => array(
	                            	array(
										'table' => 'user_stats',
										'relation' => array('user_id' => 'user_id'),
										'fields' => array(
											'public_collections_count'=>'private!=1 && type==0',
	                            			'private_collections_count'=>'private==1 && type==0',
	                            		),
									)
	                            ),
							);
							
	protected $validate = array(						
					'folder_id'     => array( 'label' => 'Folder id',    'rules' => 'numeric' ),
					'folder_name'   => array( 'label' => 'List name',    'rules' => 'required|trim|folder_name|max_length[150]'),   // folder_name_chars_limit
					'rss_source_id' => array( 'label' => 'Rss Source',   'rules' => 'rss_source'),
					'hashtag_id'    => array( 'label' => 'Hashtag',      'rules' => 'hashtag'),
					'exclusive'     => array( 'label' => 'exclusive',    'rules' => 'checkbox' ),
					'is_open'       => array( 'label' => 'is_open',      'rules' => 'checkbox' ),
					'admin_social'  => array( 'label' => 'admin_social', 'rules' => 'checkbox' ),
					'sort_by'       => array( 'label' => 'sort_by',      'rules' => 'radio' ),
					'private'       => array( 'label' => 'Private',      'rules' => '' ),
					//For contest update
					'type'          => array( 'label' => 'type',         'rules' => 'trim' ),
					'ends_at'       => array( 'label' => 'ends_at',      'rules' => 'datetime' ),
					'info'          => array( 'label' => 'info',         'rules' => 'trim' ),
					'contest_id'    => array( 'label' => 'contest_id',   'rules' => 'trim' ),
				);
							
	public $types = array('default', 'sxsw', 'contest');   

	//Per item funcs
	public function is_owned($folder, $user_id) {
		return $folder->user_id == $user_id;
	}
	
	public function get_folder_url($folder=null) {
		if ($folder == null) return null;
		if (!isset($folder->_folder_url)) {
			if (!isset($folder->user_id) || ! @$folder->user) {
				$folder->_folder_url = '';
			} elseif(isset($folder->folder_uri_name)) {
				if (isset($folder->type) && $folder->type == 2) {
					$folder->_folder_url = '/'.$folder->contest->url.($folder->folder_uri_name != $folder->contest->url ? '/'.$folder->folder_uri_name : '');
				} else {
					$folder->_folder_url = '/'.$folder->user->uri_name.'/'.$folder->folder_uri_name;
				}
			} else {
				$folder->_folder_url = '/'.$folder->user->uri_name.'/'.Url_helper::url_title($folder->folder_name);
			}
		}
		return $folder->_folder_url;
	}
	
	public function get_share_count($folder) {
		if ($folder->type==1) { //sxsw
			$number = get_instance()->newsfeed_model->select_fields(array("(SELECT SUM('.{$this->newsfeed_model->share_count}.')
				FROM newsfeed as n1
				WHERE n1.user_id_from = newsfeed.user_id_from
				AND n1.title = newsfeed.title)
				as share_count"))
			->get_many_by('folder_id', $folder->folder_id);
			
			if ( ! $number) {
				return 0;
			}
			$count = 0;
			foreach($number as $item){
				$count += $item->share_count;
			}
			return $count;
		} else {
			return (int) $folder->get('newsfeeds')->select_fields('SUM('.$this->newsfeed_model->share_count.') as share_count')->get_by(array())->share_count;
		}
	}
	
	public function get_points($folder) {
		return (int) mysql_fetch_object(mysql_query("
			SELECT SUM(views) as num FROM newsfeed_referrals WHERE newsfeed_id IN (
				SELECT newsfeed_id FROM newsfeed WHERE folder_id = $folder->folder_id
			)
		"))->num
		+ (int) mysql_fetch_object(mysql_query("
			SELECT uniqview+(twitter_share_count*10) as num FROM newsfeed WHERE folder_id = $folder->folder_id
		"))->num;
	}
	
	public function get_ga_views($folder) {
		return (int) mysql_fetch_object(mysql_query("
			SELECT SUM(views) as num FROM newsfeed_referrals WHERE newsfeed_id IN (
				SELECT newsfeed_id FROM newsfeed WHERE folder_id = $folder->folder_id
			)
		"))->num;
	}
	
	public function get_collaborators_json($folder) {

		$collaborators = $folder->get('folder_contributors')->get_all();
		$short_data = array();

		foreach ($collaborators as $k=>$v) {
			# code...
			$short_data[] = (object)array(
				"id"=>$v->user->id,
				"url"=>$v->user->_url,
				"img"=>$v->user->_avatar_small,
				"name"=>$v->user->first_name . " " .$v->user->last_name,
			);
		}

		return json_encode($short_data);
	}
	
	public function get_most_shared_drops($folder, $limit=7) {
		return $folder->get('newsfeeds')->order_by('share_count','desc')->get_all($limit);
	}
	
	public function is_liked($folder, $user) {
		$user_id = is_object($user) ? $user->id : $user;
		if (!isset($folder->_is_liked)) {
			$folder->_is_liked = (bool) $this->like_model->count_by(array('folder_id'=>$folder->folder_id, 'user_id' => $user_id));
	  	}
	  	return $folder->_is_liked;
	}
	
	public function is_in_progress($folder) {
		return $folder->rss_source_id > 0 && $folder->updated_at == '0000-00-00 00:00:00';
	}
	
	public function has_contributors($folder, $user) {
		$user_id = is_object($user) ? $user->id : $user;
		return (bool) $folder->get('folder_contributors')->count_by(array('user_id'=>$user_id));
	}
	
	public function can_edit($folder, $user_id) {
		if (is_object($user_id)) $user_id = $user_id->id;
		return $folder->editable && ($this->is_owned($folder, $user_id) || $this->has_contributors($folder, $user_id) || ($this->user && in_array($this->user->role,array('2'))));
	}
	
	public function can_add($folder, $user_id) {
		if (is_object($user_id)) $user_id = $user_id->id;
		return $folder->is_open || $this->is_owned($folder, $user_id) || $this->has_contributors($folder, $user_id) || ($this->user && in_array($this->user->role,array('2')));
	}

	public function can_view($folder, $user_id) {
		if (is_object($user_id)) $user_id = $user_id->id;
		return !$folder->private || $folder->user_id = $user_id;
	}

	public function is_shared($folder, $user, $api='fb') {
		
		$user_id = is_object($user) ? $user->id : $user;

		return (bool) $this->newsfeed_share_model->count_by(array(
			'user_id' => $user_id,
			'folder_id' => $folder->folder_id,
			'api' => $api,
		));
	}

	public function increase_folder_hits($folder)	{
		$this->db->update("folder", array("hits"=> $folder->hits + 1), array("folder_id"=>$folder->folder_id) );
	}
	
	public function get_total_hits($folder) {
		if (! $folder->folder_id) return 0;
		if (!isset($folder->_total_hits)) {
			$drops_data = $this->db->query("SELECT SUM(hits) as hits FROM newsfeed where folder_id=".$folder->folder_id." AND hits > 0");
			$drops_hits = $drops_data->first_row()->hits;
			$folder->_total_hits = $folder->hits + $drops_hits;
		}
		return $folder->_total_hits;
	}
	
	public function get_total_upvotes($folder) {

		return $folder->upvotes_count 
			+ @$folder->get('newsfeeds')
				->select_fields("SUM(up_count) as upvotes", false)
				->group_by('folder_id')
				->get_by(array())->upvotes;
	}

	public function upvotes()	{
		$this->db->where("upvotes_count >","0");
	return $this;
	}

	public function get_total_folder_upvotes($user_id)	{

		$query = $this->db->query("SELECT 
									SUM(f.`upvotes_count`) as uc
									FROM `folder` as f
									WHERE f.user_id = {$user_id} AND f.private = 0
									");
		$uc = $query->first_row()->uc;

		$ids = '';
		$query = $this->db->query("SELECT folder_id FROM folder WHERE user_id ={$user_id} AND private =0");
		if($query->num_rows() > 0) foreach($query->result() as $key => $row) {
			$ids.= ($key!=0 ? ', ' : false) . $row->folder_id;
		}

		$query = $this->db->query("SELECT SUM( up_count ) as nuc FROM newsfeed WHERE is_deleted = 0 AND folder_id IN ( {$ids} )");
		$nuc = $query->first_row()->nuc;

	return $uc + $nuc;
	}

	public function get_total_public_mentions($user_id)	{

		$query = $this->db->query("SELECT 
									COUNT(*) as mcnt
									FROM `mentions` as m
									INNER JOIN `folder` as f ON m.`folder_id` = f.`folder_id`
									WHERE 
										m.`user_id_to` = {$user_id} AND f.`private` = 0 AND m.`newsfeed_id` = 0
									");

	return $query->first_row()->mcnt;
	}

	public function by_mentions()	{
		$this->db->join('mentions', 'mentions.folder_id = folder.folder_id');
		$this->db->where('mentions.newsfeed_id',0);
		$this->db->group_by('folder.folder_id');
	return $this;
	}
	
	public function is_followed($folder, $user) {
		$user_id = is_object($user) ? $user->id : $user;
		return (bool) $this->folder_user_model->count_by(array('folder_id'=>$folder->folder_id, 'user_id'=>$user_id));
	}
	
	/**
	 * Returns the thumbnail of the first newsfeed
	 */
	public function get_thumbnail($folder) {
		$rows = $folder->get('newsfeeds')->with_thumbnail()->order_by('newsfeed_id','desc')->limit(1)->get_all();
		if ($rows) {
			return $rows[0]->img_thumb;
		}
		return false;
	}
	
	public function get_latest_drop($folder) {
		return $folder->get('newsfeeds')->order_by('newsfeed_id','desc')->get_by(array('link_type <>'=> 'text'));
	}
		
	//End item funcs
	
	public function sample() {
		$sample = new Model_Item();
		$sample->_model = $this;
		$sample->folder_id = -1;
		$sample->folder_name = 0;
		$sample->private = 0;
		$sample->editable = 0;
		$sample->upvotes_count = 0;
		$sample->newsfeeds_count = 0;
		$sample->hashtag_id = 0;
		$sample->recent_newsfeeds = array();
		$sample->folder_uri_name = '';
		$sample->is_owner = false;
		$sample->exclusive = false;
		$sample->is_open = false;
		$sample->is_liked = false;
		$sample->_total_hits = 0;
		$sample->user_id = 0;
		$sample->rss_source_id = 0;
		$sample->following = false;
		$sample->sort_by = false;
		$sample->updated_at = null;
		$sample->created_at = null;
		$sample->type = 0;
		
		//Contest folders
		$sample->ends_at = '0000-00-00 00:00:00';
		$sample->info = '';
		
		$sample->folder_hashtags = array(
			(Object) array(
				'hashtag' => (Object) array(
					'hashtag_url' => '',
					'hashtag_name' => '',
				)
			)
		);
		
		$sample->user = new Model_Item();
		$sample->user->_model = $this->user_model;
		$sample->user->url = '';
		$sample->user->full_name = '';
		$sample->user->role = 0;
		$sample->user->uri_name = '';
		$sample->user->avatar_42 = '';
		
		$sample->rss_source = new Model_Item();
		$sample->rss_source->_model = $this->rss_source_model;
		$sample->rss_source->source = '';
		
		return $sample;
	}
	
	public function join_contributor() {
		$this->db->join('folder_contributors', 'folder_contributors.folder_id = folder.folder_id', 'left');
		return $this;
	}

	public function join_hashtag() {
		$this->db->join('hashtags', 'folder.hashtag_id = hashtags.id', 'left');
		return $this;
	}
	
	public function join_users() {
		if ($this->db instanceof CI_DB_solr_driver) {
		} else {
			$this->db->join('users', 'users.id = folder.user_id');
		}
		return $this;
	}
	
	/* ======================================== Filters ============================ */

	public function search($keyword) {
		 $keyword = mysql_real_escape_string($keyword);
		if ($this->db instanceof CI_DB_solr_driver) {
			$this->db->where(array('folder_name' => "*$keyword*"));
		} else {
			if(preg_match('/^_hash_/i', $keyword)) {
				$this->db->join("folder_hashtags","folder.folder_id = folder_hashtags.folder_id");
				$this->db->join("hashtags","folder_hashtags.hashtag_id = hashtags.id AND hashtags.hashtag = '$keyword'");
				// $this->db->join('hashtags', "folder.hashtag_id = hashtags.id AND hashtags.hashtag = '$keyword'");
			} else {
				$this->db->like('folder_name', $keyword);
			}
			$this->db->where('private', '0');
		}
		return $this;
	}
	
	public function filter_user($user_id) {
		//$this->db->where( "(folder.user_id=$user_id OR folder.folder_id IN (SELECT folder_contributors.folder_id FROM folder_contributors WHERE user_id = $user_id))" );
		$this->db->where('folder.user_id', $user_id); //RR - the collaborators are disabled in the new design
		$this->db->where( "folder.type < 2");
		return $this;
	}
	
	public function filter_contest($contest_id) {
		$this->db->where(array('contest_id'=>$contest_id, 'folder.type'=>2));
		return $this;
	}
	
	public function filter_staff() {
		$this->db->join('users', 'users.id = folder.user_id');
		$this->db->where_in('role',array('1'));
		return $this;
	}
	
	public function filter_has_contributors() {
		$this->db->join('folder_contributors', 'folder.folder_id = folder_contributors.folder_id');
		return $this;
	}
	
	public function filter_out($folder_ids) {
		$this->db->where_not_in('folder_id',$folder_ids);
		return $this;
	}
	
	public function filter_editors() {
		$this->db->join('users', 'users.id = folder.user_id');
		$this->db->where('users.role', '1','4');
		$this->db->group_by('users.id');
		return $this;
	}
	
	public function has_newsfeeds() {
		if ($this->db instanceof CI_DB_solr_driver) {
		} else {
			$this->db->where("EXISTS(SELECT 1 FROM newsfeed WHERE newsfeed.folder_id = folder.folder_id)");
		}
		return $this;
	}
	
	public function has_followers() {
		$this->db->where("EXISTS(SELECT 1 FROM folder_user WHERE folder_user.folder_id = folder.folder_id)");
		return $this;
	}
	
	public function not_followed($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("NOT EXISTS(SELECT 1 FROM folder_user WHERE folder_user.folder_id = folder.folder_id AND folder_user.user_id = $user_id)", null, false);
		return $this;
	}
	
	public function filter_followed($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->join('folder_user',"folder_user.folder_id = folder.folder_id AND folder_user.user_id = $user_id");
		return $this;
	}
		
	public function has_likes() {
		$this->db->where("EXISTS(SELECT 1 FROM likes WHERE likes.folder_id = folder.folder_id)");
		return $this;
	}
	
	public function not_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->where("NOT EXISTS(SELECT 1 FROM likes WHERE likes.folder_id = folder.folder_id AND user_id = $user_id)", null, false);
		return $this;
	}
	
	public function filter_liked($user) {
		$user_id = is_object($user) ? $user->id : $user;
		$this->db->join('likes',"likes.folder_id = folder.folder_id AND likes.user_id = $user_id");
		return $this;
	}
	
	public function drops_more_than($num) {
		$this->db->where('newsfeeds_count >',$num);
		return $this;
	}
	
	public function user_id_in($arr) {
		$this->db->where_in('user_id', $arr);
		return $this;
	}

	public function for_user($user_id) {
		$this->db->where('user_id', $user_id);
		return $this;
	}
	
	/* ======================================= EVENTS ================================= */
	
	protected function _run_before_create($data) {
		if (!isset($data['user_id'])) $data['user_id'] = get_instance()->user->id;
		if (!isset($data['created_at'])) $data['created_at'] = date("Y-m-d H:i:s");
		return parent::_run_before_create($data);
	}
	
	protected function _run_after_create($data) {

		if(!isset($data['private']) || $data['private'] != '1') {
			mysql_query("INSERT IGNORE INTO folder_user (user_id, folder_id) SELECT user1_id, '".$data['folder_id']."' FROM connections WHERE user2_id = '".$data['user_id']."'");
		}
		
		if (isset($data['rss_source_id'])) {
			$source = $this->rss_source_model->get($data['rss_source_id']);
			if ($source && $source->update_on > 0) {
				$copy_from = $this->folder_model->get_by(array('rss_source_id' => $source->id));
				$user_id = get_instance()->session->userdata('id');
				$recent = $copy_from->get('newsfeeds')->order_by('newsfeed_id','desc')->limit(20)->get_all();
				foreach ($recent as $newsfeed) {
					$new_feed_id = $newsfeed->redrop($user_id, $data['folder_id']);
				}
				if ($recent) {
					$this->db->query("UPDATE folder SET updated_at = NOW() WHERE folder_id = ".$data['folder_id']);
		   		}
			}
		}
		return parent::_run_after_create($data);
	}
	
	protected function _run_before_set($data) {

		if (isset($data['folder_name'])) {
			$data['folder_uri_name'] = Url_helper::url_title($data['folder_name']);
		}
		if (isset($data['contest_id']) && !isset($data['sort_by'])) {
			$data['sort_by'] = 2;
		}
		$data['is_ranked'] = '0';
		return parent::_run_before_set($data);
	}
	
	protected function _run_after_set($data) {
		$this->notification_model->on_folder_name_change( $this->folder_model->get( $data['folder_id'] ) );
		$this->refresh_cache($data);
		return parent::_run_after_set($data);
	}
	
	public function _run_after_get($row=null) {

		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->folder_name)) {
			$row->_display_name = $row->folder_name;
			if(isset($row->private) && $row->private == '1') $row->_display_name = $row->folder_name.' (Draft)';
		}
		
		if (isset($row->ends_at)) {
			$row->_ends_at_formatted  = date('l n-j-y H:i:sa', strtotime($row->ends_at));
		}
		
		if (isset($row->filters)) $row->filters = json_decode($row->filters);
		
		if (isset($row->recent_newsfeeds)) {
			$row->_recent_newsfeeds = $row->recent_newsfeeds = (Array) @json_decode($row->recent_newsfeeds);
			foreach ($row->recent_newsfeeds as $key => &$item) {
				if (!$item) unset($row->recent_newsfeeds[$key]); 
				$_item = new Model_Item();
				$_item->description_plain = '';
				if (count(@$item) > 0) : 
					foreach (@$item as $var=>$val) $_item->$var = $val;
				endif;
				$item = $_item;
				$this->newsfeed_model->_run_after_get($item);
			}
			$row->_newsfeed_top = @$row->recent_newsfeeds[0];
			$row->_recent_newsfeeds_notop = array_slice($row->recent_newsfeeds, 1);
		}
	
	}
	
	protected function _run_before_delete($obj) {
	   	$this->refresh_cache($obj);
	   	return parent::_run_before_delete($obj);
	}
	
	protected function _run_after_delete($obj) {
		if ($obj->contest_id && !$this->count_by(array('contest_id'=>$obj->contest_id))) {
			$obj->contest->delete();
		}
		return parent::_run_after_delete($obj);
	}
	
	/* =========================================== Selects ========================== */
	
	public function jsonfy( $folders ) {
		
		$user_id = $this->session->userdata('id');
		$users = array();	
		
		foreach ( $folders as &$folder ) {
			
			$folder->updated_at = date('F d', strtotime($folder->updated_at));
			$folder->created_at = date('F d', strtotime($folder->created_at));
			
			$folder->is_followed = $this->is_followed( $folder, $user_id );
			$folder->is_liked = $folder->is_liked( $user_id );
			$folder->is_owned = $folder->is_owned( $user_id );
			$folder->is_shared_fb = $folder->is_shared($user_id, 'fb');
			$folder->is_shared_twitter = $folder->is_shared($user_id, 'twitter');
			$folder->can_edit = $folder->can_edit( $user_id );
			
			//$folder->_collaborators_json = $folder->collaborators_json;
			//$folder->collaborators = $folder->collaborators_json;

			$folder->total_hits = $folder->get_total_hits( $folder );
			$folder->total_upvotes = $folder->get_total_upvotes( $folder ); 

			
			$folder->user = (object)array( 
				'uri_name' => $folder->user->uri_name,
				'full_name' => $folder->user->full_name,
				'avatar' => $folder->user->avatar_42,
			);

			$folder->folder_url = $this->get_folder_url( $folder );
			$folder->rss_source = $folder->rss_source;
			$folder->is_in_progress = $folder->is_in_progress();
			$folder->pinterest_url = '';//pinterest share is disabled

			$folder->newsfeeds_count = Text_Helper::restyle_text($folder->newsfeeds_count);
			$folder->hits = Text_Helper::restyle_text($folder->hits);
			$folder->total_hits = Text_Helper::restyle_text($folder->total_hits);
			$folder->share_count = $folder->share_count;
			//$folder->hashtag = $folder->hashtag;
			
			$folder->hashtags = $folder->get('folder_hashtags')->with('hashtag')->dropdown('id','hashtag');
			
			foreach ($folder->recent_newsfeeds as &$recent) {
				unset($recent->_model);
			}
			
			unset(
				$folder->_is_liked, $folder->_folder_uri,
				$folder->_display_name, $folder->_total_hits, $folder->rss_source->_model,
				$folder->_model_rss_source, $folder->_model, $folder->_model_newsfeeds,
				$folder->_folder_contributors, $folder->_model_folder_contributors, $folder->_model_hashtag,  $folder->_hashtag,
				$folder->_model_user, $folder->user->_model, $folder->_user, $folder->_model_collaborators_json, 
				$folder->_model_folder_hashtags, $folder->_model_share_count
			);
		
		}
		
		return $folders;
	}
	
	public function select_list_fields($fields=array()) {
		$fields = array_merge(array(
			'folder.folder_id','user_id', 'folder_name', 'upvotes_count','newsfeeds_count','recent_newsfeeds', 'folder.private',
			'folder.exclusive', 'folder.is_open', 'folder.hits', 'folder.editable', 'folder_uri_name', 'rss_source_id',
			'folder.sort_by', 'folder.updated_at', 'folder.created_at', 'folder.type', 'folder.contest_id', 'folder.ends_at', 'folder.info'
		), $fields);
		foreach ($this->db->ar_join as $join) {
			if (strpos($join, "JOIN `users`") !== false) {
				$fields = array_merge($fields, array(
					'users.id as `users:id`', 'users.first_name as `users:first_name`', 'users.last_name as `users:last_name`', 'users.uri_name as `users:uri_name`', 'users.role as `users:role`', 'users.avatar as `users:avatar`',
					"CONCAT('/',users.uri_name,'/',folder.folder_uri_name) as _folder_url"
				));
			}
		}
		return $this->select_fields($fields);
	}

	public function select_featured_fields($fields=array()) {
		$this->select_fields(array_merge($fields, array(
			'folder_id','hits','folder_uri_name','rss_source_id','user_id', 'upvotes_count','folder_name',
			'hashtag_id','recent_newsfeeds', 'folder.private', 'folder.updated_at','newsfeeds_count',
			'users.id', 'users.first_name', 'users.last_name', 'users.uri_name', 'users.role','type',
			"CONCAT('/collection/',users.uri_name,'/',folder.folder_uri_name) as folder_uri",
		)));
		$this->select_fields("IF (users.role > 0, 1, 0) as users_ranking", false);
		return $this;
	}
	
	public function select_newsfeed_fields() {
		return $this->select_fields(array(
			"folder.folder_name", "folder.private", "folder.type", "folder.folder_uri_name", "folder.contest_id",
			"CONCAT('/collection/',users.uri_name,'/',folder.folder_uri_name) as folder_url"
		));
	}

	public function select_list($user_id, $convert = true, $limit = null) {

		$folders_arr = array();
		$last_popular = $this->filter_user($user_id)->order_by('newsfeeds_count','desc')->limit(1,15)->get_by(array());
		if ($last_popular) $last_popular = $last_popular->newsfeeds_count; else $last_popular = 0;

		// ignore rss folders - 05-03-2013 - http://dev.fantoon.com:8100/browse/FD-3626 by Geno
		$this->db->where("rss_source_id","0");

		$folders = $this->folder_model->order_by('folder.folder_name', 'ASC')->group_by('folder.folder_id')->filter_user($user_id)->get_all();
		foreach ($folders as $id=>$folder) {
			$is_shared = $this->folder_contributor_model->count_by(array('folder_id'=>$folder->folder_id));
			if ($convert) {
				if ($limit && strlen($folder->folder_name) > $limit) {
					$name = substr($folder->folder_name, 0, $limit).'&hellip;';
				}
				else {
					$name = $folder->folder_name;
				}
				$folders_arr[$folder->folder_id] = array('id'=>(string)$folder->folder_id, 'name'=>$name, 'class'=>($is_shared?'shared':'').($folder->newsfeeds_count>=$last_popular?' popular':''));
			}
			else {
				$folders_arr[$folder->folder_id] = $folder;
			}
		}
		
		return array_values($folders_arr);
	}
	
	/* ============================================== Others ============================== */

	/**
	 * param int $main_folder_id
	 * param array - folders ids to be merged
	 */
	public function merge_folders($main_folder_id, $merged_folders) {


		//update folder_contributors
		$this->db->set('folder_id', $main_folder_id)
		->where_in('folder_id', $merged_folders)
		->update('folder_contributors');

		//calculate followers count on merged folder
		$followers = $this->db->select("SUM('id') AS num", FALSE)
					 ->where('folder_id', $main_folder_id)
					 ->get('folder_contributors')
					 ->row()->num;

		//update folder_user (shares)
		$this->db->set('folder_id', $main_folder_id)
		->where_in('folder_id', $merged_folders)
		->update('folder_user');

		//calculate shares count
		$shares = $this->db->select("SUM('id') AS num", FALSE)
				  ->where('folder_id', $main_folder_id)
				  ->get('folder_user')
				  ->row()->num;

		//calculate hits and fb_share_count
		$query = $this->db->select("SUM(hits) AS num_hits, SUM(fb_share_count) AS num_fb_share_count", TRUE)
				 ->where('folder_id', $main_folder_id)
				 ->where('hits >0')
				 ->get('newsfeed');
		$result = $query->row();
		$hits = $result->num_hits;
		$fb_share_count = $result->num_fb_share_count;

		//calculate recent_newsfeeds
		$this->db->select('recent_newsfeeds')->where_in('folder_id', $merged_folders);
		$folders = $this->db->get('folder')->result();

		$recent_newsfeeds = array();
		foreach($folders as $folder) {
			$newsfeeds = @json_decode($folder->recent_newsfeeds);
			if(is_array($newsfeeds)) {
				foreach($newsfeeds as $newsfeed) {
					$recent_newsfeeds[strtotime($newsfeed->time)] = $newsfeed;
				}
			}
		}
		//sort newsfeed by time desc
		krsort($recent_newsfeeds, SORT_NUMERIC);

		//extract last 6 recent newsfeeds
		$recent_newsfeeds = array_slice($recent_newsfeeds, 0, 6);

		//encode results
		$recent_newsfeeds = json_encode($recent_newsfeeds);

		//update folder totals (shares, followers, hits, fb_share_count, recent_newsfeeds)
		$fields = array(
					  'followers' => $followers,
					  'drops' => $shares,
					  'hits' => $hits,
					  'fb_share_count' => $fb_share_count,
					  'recent_newsfeeds' => $recent_newsfeeds
				  );
		$this->db->set($fields)->where('folder_id',$main_folder_id)->update('folder');

		//delete merged folders
		if(in_array($main_folder_id, $merged_folders)) {
			$merged_folders = array_diff($merged_folders, array($main_folder_id));
		}
		
		$this->db->where_in('folder_id', $merged_folders)->delete('folder');

	}

	private function refresh_cache($row) {

		$arr = array();
		if (!is_array($row)) foreach ($row as $key=>$val) $arr[$key] = $val;
		else $arr = $row;
		$ci = get_instance();
		if (isset($arr['user_id']))
		{
			//remove collection dropdown cache files
			$ci->cache->delete("folders_dropdown_{$arr['user_id']}_nolimit");
			$ci->cache->delete("folders_dropdown_{$arr['user_id']}_25");
			$ci->cache->delete("folders_dropdown_{$arr['user_id']}_false");
		}
		if(isset($arr['user_id']))
		{
			//remove user collection cache files
			for ($i=1;$i<=5;$i++) {
				$ci->cache->delete("collections_{$arr['user_id']}_{$i}");
			}
		}
		if (isset($arr['folder_id']))
		{
			$ci->cache->delete("folder_newsfeed_data_{$arr['folder_id']}");
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
			$this->_table = 'folder';
			return true;
		}
		return false;
	}

	public function get_all($limit = NULL, $fields = NULL) {

		if ($this->db instanceof CI_DB_solr_driver) {
			$ids = $this->db->get('folder')->result_field('folder_id');
			$this->db = $this->cached_db;
			$this->_table = 'folder';
			if (!$ids) return array();
			
			return $this->select_fields($fields ? $fields : '*')->get_many_by(array('folder_id'=>$ids));
		} else {
			return parent::get_all($limit = NULL);
		}
	}

	public function select_search_fields($keyword) {

		$fields = array('folder.folder_name', 'folder.user_id');

		if ($this->db instanceof CI_DB_solr_driver) {
			$this->solr_select = $fields;
			return $this;
		} else {
			return $this->select_fields($fields);
		}
	}

}

?>
