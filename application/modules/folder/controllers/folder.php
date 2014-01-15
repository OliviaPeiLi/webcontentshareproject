<?php
class Folder extends MX_Controller {
	protected $default_sort = 'folder.position asc, folder.folder_name asc';
	protected $default_view = 'list';
	protected $per_page = 0;
	
	public function __construct() {
		parent::__construct();
		$this->load->config('folder/config');
	}
	
	/* =================================== GET ==================================== */
	
	/**
	 * Get a collection. Newsfeeds inside are loaded via modules::run(folder_newsfeed)
	 * upvote collection, then redirect to it (applied for upvote in landing page for not login-user)
	 * 
	 * @link - fandrop.com/collection/{user_name}/{folder_name}/{folder_id}
	 * @link - fandrop.com/collection/{user_name}/{folder_name}/{action}/{folder_id}
	 */
	public function get($user_name, $folder_name, $folder_id = null, $filter=null) {

		$error_template = $this->is_mod_enabled('design_ugc') ? 'profile/errors_404_ugc' : 'profile/errors_404';
		if ($folder_id && $folder_id != 'false') {
			$folder = $this->folder_model->get($folder_id);
			$user = $folder->user;
		} else {
			$user = $this->user_model->get_by(array('uri_name' => $user_name));
			if (!$user) { //Get contest
				$contest = $this->contest_model->get_by(array('url'=>$user_name));
				$folder = $this->folder_model->get_by(array('contest_id'=>$contest->id, 'folder_uri_name' => $folder_name));
				$user = $folder->user;
			} else {
				$folder = $this->folder_model->get_by(array('user_id'=>$user->id, 'folder_uri_name' => $folder_name));
			}
			if (!$user->id) {
				return parent::template($error_template);
			}
		}

		if ( $folder->private == 1 && $this->session->userdata('id') != $folder->user_id )	{
			return parent::template($error_template);
		}

		if($user->id != $this->session->userdata('id') && $user->status == '0') {
			return parent::template($error_template);
		}
		
		// increase hits count
		$this->folder_model->increase_folder_hits($folder);
		
		if (!$folder->folder_id || !$folder->can_view($this->session->userdata('id'))) {
			return parent::template($error_template);
		}

		$user->icrease_view_count();

		if ($folder->user_id == $this->session->userdata('id')) {   //user account is activated. if not then activate it.
			if (!$folder->owner_visited) {
				$folder->update(array('owner_visited' => 1));
			}
		}
		
		$folders = $this->folder_model->filter_user($user->id)->order_by('folder.folder_name', 'ASC')
							->group_by('folder.folder_id')->get_all();
		
		$template = 'folder/folder';
		if ($this->is_mod_enabled('design_ugc') && !isset($contest)) $template .= '_ugc';
		
		return parent::template($template, array(
			'collection_dropdown' => $folders,
			'folder' => $folder,
			'contest' => $folder->type == 2 ? $folder->contest : null,
			'filter' => $filter,
			'hide_header' => $folder->type >= 1 ? '1' : '0',
			'head_content' => Html_helper::og_meta($folder),
			'hide_footer'=>true
		), 'Fandrop - '. $folder->folder_name);
	
	}
	
	public function embed($folder_id) {
    	$folder = $this->folder_model->get($folder_id);
        if(! $folder) die('List doesnt exists');  

        $this->load->view('folder/embed', array(
        	'folder' => $folder,
        	'title' => 'Fandrop',
        ));
    }

    public function create_collection()	{

    	$folder_name = $this->input->post("folder_name");

    	if (!$folder_name)	{
    		die(json_encode(array("status"=>false)));
    	}

		$folder_id = $this->folder_model->insert(array(
			 'user_id' => $this->session->userdata('id'),
			 'folder_name' => $folder_name,
			 'folder_uri_name'=>Url_helper::url_title($this->input->post('folder_name')),
		 ));

		$folder = $this->folder_model->get($folder_id);
		
		die(json_encode(array("status"=>true,"data"=>$folder)));

    }
    
	/**
	 * Called via ajax
	 * if folder_id != null (rename collection) - check if there is a duplicate name
	 * if folder_id == nyll (create collection) - check if there is a duplicate name
	 * @param int $folder_id
	 */
	public function validate_collection($folder_id=null){
		$check_array = array(
			'folder_uri_name' => Url_helper::url_title($this->input->post('folder_name')),
			'user_id' => $this->session->userdata('id'),
		);
		if ($this->input->post('contest_id')) {
			$check_array['contest_id'] = $this->input->post('contest_id'); 
		}
		if($folder_id) {
			$check_array['folder_id !='] = $folder_id;
		}
		echo json_encode(array('status'=>(bool) $this->folder_model->count_by($check_array)));
		return ;
	}
	
	/* ================================== Listing ================================= */
	
	protected function get_model($per_page=0, $join_users=true) {
		$this->folder_model;
		$folder_model = new Folder_model();
		
		//Join
		if ($join_users) {
			$folder_model = $folder_model->with('user');
		}
		
		//Select
		$folder_model = $folder_model->select_list_fields();
		
		//Sort
		$this->default_sort = str_replace(':', ' ', $this->default_sort);
		$this->default_sort = str_replace('time', 'folder_id', $this->default_sort);
				
		if ($per_page) {
			$this->per_page = $per_page;
			$page = $this->input->get('page', true, 0)+1;
			$folder_model = $folder_model->paginate($page, $per_page);
		}
		
		$folder_model = $folder_model->order_by($this->default_sort, ' ');
		
		return $folder_model;
	}

	protected function output($folders, $url, $get = array(), $is_profile = false) {

		$page = $this->input->get('page', true, 0)+1;
		$drop_type = $this->input->get('type', true);

	 	if ( $page > 1 ) {
	 		header( 'Content-Type: application/json' );
	 		if(isset($this->cache_name)) {
	 			if(!$cache = $this->cache->get($this->cache_name)) {
	 				$cache = json_encode( $this->folder_model->jsonfy( $folders ) );
	 				$this->cache->save($this->cache_name, $cache);
	 			}
	 			print $cache;
	 		} else echo json_encode( $this->folder_model->jsonfy( $folders ) );
		} else {
			$sort_by = $this->input->get('sort_by', true, $this->default_sort);
			
			if ($sort_by != $this->default_sort) $get[] = 'sort_by='.$sort_by; 
			if ($drop_type) $get[] = 'type='.$drop_type;
			if ($get) $url .= '?'.implode('&', $get);
			$this->load->view('folder/folder_'.$this->default_view, array(
				'url' 		 => $url,
				'per_page'   => $this->per_page,
				'folders' 	 => $folders,
				'is_profile' => $is_profile,
				'cache_name' => isset($this->cache_name) ? $this->cache_name : false
			));
		}
	
	}
	
	/* ========================================= UPDATE ================================= */

	public function update() {
		
		//@todo - move to contest_folder
		if (@$_FILES['temp_logo']) { //Upload Contest Logo
			$config = $this->contest_model->behaviors['uploadable'];
			$_FILES['logo'] = $_FILES['temp_logo'];
			$responce = Uploadable_Behavior::do_upload(array(), $config);
			if (!$responce) {
				die(json_encode(array('status'=>false, 'error'=>'Could not upload file.')));
			} else {
				$filename = substr($responce->logo, strrpos($responce->logo, '/')+1);
				die(json_encode(array('status'=>true, 'filename'=>$filename, 'thumb'=>$responce->logo_thumb)));
			}
		}//end todo
		
		$post = $this->input->post(); 
	  
		if ( ! $data = $this->folder_model->validate($post)) {
	   		die(json_encode(array('status'=>false, 'error'=>Form_Helper::validation_errors())));
		}
		
		//@todo - move to contest_folder
		if (@$post['contest_id']) {
			$contest = $this->contest_model->get($post['contest_id']);
			if (!$contest || (!in_array($this->user->role, array('2')) && $contest->user_id != $this->user->id)) {
				die(json_encode(array('status'=>false,'error'=>'You cannot add stories to this contest')));
			}
			if ($post['logo']) {
				$contest_model = $this->contest_model;
				$contest_model = new Contest_model();
				unset($contest_model->behaviors['uploadable']['logo']);
				$contest_model->update($post['contest_id'], array('logo'=>$post['logo']));
			}
			if ($contest->is_simple) {
				$contest_data = array('url'=>$post['folder_name']);
				$this->session->set_userdata('contest_id', $post['contest_id']);
				if (!$this->contest_model->validate($contest_data)) {
					die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
				}
				$contest->update($contest_data);
			}
		} //End todo
				
		$folder_id = $this->folder_model->update_or_insert($data);
		
		//@todo - use model relation methods
		if (isset($post['folder_contributors'])) {
			$this->folder_contributor_model->delete_by(array('folder_id'=>$folder_id));
			if($post['folder_contributors']) {
				// add again all contributors
				foreach($post['folder_contributors'] as $user_id => $name) {
					$check_data = array('folder_id'=>$folder_id, 'user_id'=>$user_id);
					if( ! $this->folder_contributor_model->count_by($check_data)) {
						$this->folder_contributor_model->insert($check_data);
					}
				}
			}
		} //End todo

		$folder = $this->folder_model->get($folder_id);

		header( 'Content-Type: application/json' );
		echo json_encode( array(
			'status' => true,
			'data' => end($this->folder_model->jsonfy( array($folder) ))
		) );
		return ;
	}
	
	public function redrop() {

        $newsfeed_id = $this->input->post('newsfeed_id');
        $folder = $this->input->post('folder_id');

        //Original newsfeed
        $newsfeed = $this->newsfeed_model->get($newsfeed_id);
        if( ! $newsfeed) {
            die(json_encode(array('status'=>false, 'error'=>'drop not found: '.$newsfeed_id)));
        }
    	
		//@todo - move to validation
    	if (isset($folder[0])) {

			$folder_name = $folder[0][0];

			if(!$folder_name || $folder_name == 'Click to Add'){	
				die(json_encode(array('status'=>false,'error'=>'please create a list')));
			}

			if ($this->folder_model->count_by(array('folder_name'=>$folder_name,'user_id'=>$this->session->userdata('id')))) {
				die(json_encode(array('status'=>false,'error'=>'list already exists')));
			}

			$_POST['folder_id'] = 0;
			
			$folder_id = $this->folder_model->insert(array(
									 'user_id' => $this->session->userdata('id'),
									 'folder_name' => $folder_name,
								 ));

		} else {
			$folder_name = reset($folder);
			$folder_id = key($folder);
		}
		
		$new_newsfeed_id = $newsfeed->redrop($this->session->userdata('id'), $folder_id,array("description"=>$this->input->post("description")),array(),true);
		$new_feed = $this->newsfeed_model->get($new_newsfeed_id);

        if ($this->is_mod_enabled('kissmetrics')) {
	        //kissmetrics
			$this->load->library('KISSmetrics/km');
			$this->km->init($this->config->item('km_key'));
			$this->km->identify($this->user->uri_name);
			$this->km->record('did a redrop');
        }

        //share on fb
        if($this->user->id && $this->user->fb_id>0 && $this->user->fb_activity=='1') {
                $this->fb_activity_model->insert(array('fb_id'=>$this->user->fb_id,
                                                       'action'=>'redrop',
                                                       'link_url'=>Url_helper::base_url().'drop/'.$new_feed->url,
                                                       'newsfeed_id'=>$new_feed->newsfeed_id,
                                                       'object' => 'drop'));
        }

		$latest_drops = $this->user_model->get_feature_drops($this->user, 1);

        die(json_encode(array(
                            'status'=>true,
                            ($newsfeed->type).'_id'=>$newsfeed->activity_id,
                            'newsfeed_id'=>$new_newsfeed_id,
                            'folder' => array(
                            	'folder_id' => $new_feed->folder->folder_id,
                            	'folder_name' => $new_feed->folder->folder_name,
                            	'_folder_url' => $new_feed->folder->folder_url,
                            	'_display_name' => $new_feed->folder->display_name,
                            ),

							 // to add recent drop
                             'profile_top_element' => $latest_drops ? $this->load->view('profile/profile_top_element', array('newsfeed' => $latest_drops[0]), true) : ''
                         )));
                                                  
    }
    
    public function set_landing($folder_id) {
    	if ($this->user->role != 2) die(json_encode(array('status'=>false,'error'=>'no permission')));
    	$folder = $this->folder_model->get($folder_id);
    	if (!$folder) die(json_encode(array('status'=>false,'error'=>'folder not found')));
    	
    	$folder->update(array('is_landing'=>1,'updated_at'=>date('Y-m-d H:i:s')));
    	//mysql_query("UPDATE folder SET is_landing = 0 WHERE is_landing = 1 ORDER BY updated_at ASC LIMIT 1");
    	
    	echo json_encode(array('status'=>true));
    	return ;
    } 
	
	/* ====================================== DELETE =================================== */
	
	public function delete($id) {
		$folder = $this->folder_model->get($id);
		if (!$folder->can_edit($this->session->userdata('id'))) {
			die(json_encode(array('status'=>false,'error'=>'You cant delete this folder')));
		}
		
		$folder->delete($id);
		$this->notification_model->delete_by(array("folder_id",$id));
		
		die(json_encode(array('status'=>true)));
	}
	
    public function rem_landing($folder_id) {
    	if ($this->user->role != 2) die(json_encode(array('status'=>false,'error'=>'no permission')));
    	$folder = $this->folder_model->get($folder_id);
    	if (!$folder) die(json_encode(array('status'=>false,'error'=>'folder not found')));
    	
    	$folder->update(array('is_landing'=>0));
    	mysql_query("UPDATE folder SET is_landing = 1 WHERE is_landing = 0 ORDER BY ranking DESC LIMIT 1");
    	echo json_encode(array('status'=>true));
    	return ;
    }
     
}
