<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//user_module
///require_once('application/controllers/operation_code/profile_operation.php');
//include('application/controllers/business_code/admin.php');

class Profile extends MX_Controller {
	
	protected $default_sort = 'users.id';
	protected $per_page = 0;

	function __construct() {
		parent::__construct();
		$this->load->helper('typography');
		
		if($this->input->get('ref') == 'notif'){
			$graph_url = "https://graph.facebook.com/".$this->input->get('request_ids')."?access_token=" . $this->config->item('access_token');
			
			$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_URL, $graph_url);
			$response = curl_exec($c);
			$err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
			curl_close($c);
		
			$decoded_response = json_decode($response);
			Url_helper::redirect('/signup?a=fb&b=b87jgzfke5&ref_id='.$decoded_response->from->id);
		}
	}
	
	/* =============================================== GET ITEM =========================================== */
	
	/**
	 * Get user profile
	 */
	public function get($type, $name, $filter=null) {

		$profile_user = $this->user_model->get_by(array('uri_name'=>$name));

		if ( !isset($profile_user->id) || ! $profile_user->id) {
			//RR - both contests and profiles are first level so trying to find a contest
			$contest = $this->contest_model->get_by(array('url'=>$name));
			if ($contest) {
				if ($contest->is_simple) {
					return Modules::run('folder/folder/get', $name, $name);
				} else {
					return Modules::run('fantoon-extensions/contest/get', $name);
				}
			} else {
				$template = $this->is_mod_enabled('design_ugc') ? 'profile/errors_404_ugc' : 'profile/errors_404';
				return parent::template($template, array(), 'Fandrop');
			}
		}
		
		if ($this->input->is_ajax_request()) {
			if ($type == 'collections') {
				return Modules::run('profile/profile_folder/'.$type, $profile_user->id);
			} elseif (in_array($type, array('drops', 'upvotes', 'mentions'))) {
				return Modules::run('profile/profile_folder/'.$type, $profile_user->id);
				// return Modules::run('profile/profile_newsfeed/'.$type, $profile_user->id, $filter);
			} elseif (in_array($type, array('followings', 'followers'))) {
				return Modules::run('profile/profile_connection/'.$type, $profile_user->id);
			} elseif (in_array($type, array('settings'))) {
				return Modules::run('profile/profile/edit');
			} elseif (in_array($type, array('info'))) {
				return Modules::run('profile/profile/user_info', $profile_user->id);
			} else {
				die(json_encode(array('status'=>false,'error'=>"Profile sub page not found: $type")));
			}
		}

		if($profile_user->id != $this->session->userdata('id') && $profile_user->status === '0') {
			return parent::template('profile/errors_inactive');
		}

		//check if first time visit profile
		$first_visit = @$this->user_visit_model->get($this->session->userdata('id'))->profile;
		if($first_visit == '1') {
			$this->user_visit_model->update($this->session->userdata('id'), array('profile'=>'0'));
		}

		$connection_id_array = $this->connection_model->get_many_by(array('user1_id'=>$this->session->userdata('id')));
		$followings = array();
		foreach($connection_id_array as $k=>$v)
		{
			$followings[] = array('id'=>@$v->user2->id, 'name'=>@$v->user2->full_name, 'thumb'=>@$v->user2->avatar_73, 'link'=>@$v->user2->url);
		}
		
		$title = $profile_user->role == 4 ? 'WinSXSW' : 'Fandrop - '.$profile_user->full_name;
		if($type == 'followings'){
			$title = 'Fandrop - '.$profile_user->full_name."'s followings";
		}elseif($type == 'followers'){
			$title = 'Fandrop - '.$profile_user->full_name."'s followers";
		}

		if ($this->is_mod_enabled('design_ugc')) { 
			
			$total_upvotes = $this->folder_model->get_total_folder_upvotes($profile_user->id);
			$public_mentions = $this->folder_model->get_total_public_mentions($profile_user->id);
			
			return parent::template('profile/profile_ugc', array(
				'public_mentions'=>$public_mentions,
				'total_upvotes'=>$total_upvotes,
				'profile_user' => $profile_user,
				'type' => $type,
				'followings' => $followings,
				'filter' => $filter,
				'first_visit' => $first_visit,
				'hide_header' => $profile_user->role == 4 ? '1' : false,
				'hide_footer'=>true
			), $title);

		} else {
			
			$folders = $this->folder_model->filter_user($profile_user->id)->order_by('folder.folder_name', 'ASC')
							->group_by('folder.folder_id')->get_all();
							
			return parent::template('profile/profile', array(
				'profile_user' => $profile_user,
				'collection_dropdown' => $folders,
				'type' => $type,
				'followings' => $followings,
				'filter' => $filter,
				'first_visit' => $first_visit,
				'hide_header' => $profile_user->role == 4 ? '1' : false,
			), $title);
		}
	}
	
	/**
	 * Get user info
	 * @link /get_info/45
	 * @since 8/1/2012 RR - Remove all includes
	 */
	public function user_info($user_id) {
		$user = $this->user_model->get($user_id);

		$this->load->view('profile/user_info', array(
			'user' => $user,
		));
	}
	
	/**
	 * get the badge for the user or page
	 */
	function get_badge($type, $id, $link_disable = NULL)
	{
		$this->load->model('connection_model');
		$user = $this->user_model->get($id);

		$user_id = $this->user ? $this->user->id : 0;

		$this->load->view('badge',array(
			'type' => $type,
			'id' => $id,
			'link_disable' => $link_disable,
			'check_friends' => $this->connection_model->count_by(array('user1_id'=>$user_id, 'user2_id'=>$id)),
			'user' => $user,
			'collections_number' => $user->user_stat->{$id == $user_id ? 'collections_count' : 'public_collections_count'},
			'drops_count' => $user->user_stat->drops_count,
			'upvotes_count' => $user->user_stat->upvotes_count,
		));
	}
	
	/* ============================================= End get funcs =============================================== */
	
	/* ================================================= UPDATE ============================================== */
	
	/**
	 * Edit user profile
	 * @link /account_options
	 */
	public function update() {

		$profile_user = $this->user_model->get($this->user->id);
		$post = $this->input->post();

		if (isset($post['email_setting']) || (isset($post['section'])  && $post['section'] == 'email') )	{

			if (!isset($post['email_setting']))	{
				$post['email_setting'] = array();
			}

			$settings = array(
				"message"=>0,
				"comment"=>0,
				"up_link"=>0,
				"up_comment"=>0,
				"connection"=>0,
				"follow_folder"=>0,
				"newsletter"=>0,
				"folder_like"=>0
			);

			$post['email_setting'] = array_merge( $settings, $post['email_setting'] );
			// FD-5137
			$post['email_setting']['connection'] = 0;
		
		}

		if ( $post ) {

			unset($post['id']);

			if (isset($post['new_pass'])) {
				if ( $this->user->password != "" && $this->user->password != md5($post['old_pass'])) {
					echo json_encode(array('status'=>false,'error'=>'The old password field doent match your password'));
					return ;
				} else {
					$post['password'] = $post['new_pass'];
					unset($post['new_pass'], $post['old_pass']);
				}
			}

			if (isset($post['email_setting'])) {
				if ($data = $this->email_setting_model->validate($post['email_setting'])) {
					$this->user->email_setting->update($data);
					echo json_encode(array('status'=>true));
					return ;
				} else {
					echo json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors()));
					return ;
				}				
				//unset($post['email_setting']);
			}
			
			if ($data = $this->user_model->validate($post)) {

				if ($this->input->post("gender"))	{
					$day = $this->input->post("day");
					$month = $this->input->post("month");
					$year = $this->input->post("year");
					$data['birthday'] = $year ."-". $month ."-". $day;
					$data['about'] = $this->input->post("about");
				}

				$this->user->update($data);

				if (isset($post['uri_name']) && $post['uri_name'])	{
					$this->notification_model->on_user_change();
				}

				echo json_encode(array('status'=>true));
				return ;
			} else {
				echo json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors()));
				return ;
			}
			
		}
		$template = $this->is_mod_enabled('design_ugc') ? 'profile/profile_ugc' : 'profile/profile';
		return parent::template($template, array(
			'public_mentions'=>$profile_user->user_stat->mentions_count,
			'total_upvotes'=>$this->folder_model->get_total_folder_upvotes($profile_user->id),
			'profile_user' => $profile_user,
			'type' => 'settings',
		), 'Fandrop - '.$profile_user->full_name);
	}
	
	public function unsubscribe_email() {

		$user_id = $this->input->get('u');
		$email_setting_id = $this->input->get('e');

		if (!$user_id || !$email_setting_id) {
			return show_404();
		}
		
		$email_setting = $this->email_setting_model->get_by(array('id'=>$email_setting_id,'user_id'=>$user_id));
		if (!$email_setting) {
			return show_404();
		}
				
		$email_setting->update(array(
									'message'=>'0',
									'comment'=>'0',
									'up_link'=>'0',
									'reply'=>'0',
									'up_comment'=>'0',
									'connection'=>'0',
									'follow_folder'=>'0',
									'follow_list'=>'0',
									'collaboration'=>'0'
								));

		return $this->is_mod_enabled('design_ugc') ? parent::template('profile/unsubscribe_ugc') : parent::template('profile/unsubscribe');
	}
	
	/**
	 * Display the user account form.
	 * Its loaded as a module inside profile/profile.php/get()
	 * @link /account_options 
	 * @since 8/1/2012 RR - remove profile_user include 
	 */
	function edit() {
		if( ! $this->session->userdata('id')) {
			Url_helper::redirect('/signin?redirect_url=/account_options');
		}
		$template = $this->is_mod_enabled('design_ugc') ? 'profile/edit_ugc' : 'profile/edit';
		$this->load->view($template);
	}
	
	/* ================================ End Update funcs ====================================== */
	
	/* ====================================== LISTING ============================================ */
	
	/**
	 * Listing helpers
	 */
	protected function get_model($per_page=0) {
		$this->user_model;
		$user_model = new User_model();
		
		//Select
		$user_model = $user_model->select_list_fields();
		
		//Sort
		$user_model = $user_model->order_by($this->default_sort, 'DESC');
		
		if ($per_page) {
			$this->per_page = $per_page;
			$page = $this->input->get('page', true, 0)+1;
			$user_model = $user_model->paginate($page, $per_page);
		}
		
		return $user_model;
	}
	
	protected function output($users, $url, $get = array()) {
		$page = $this->input->get('page', true, 0)+1;
		
	 	if ($page > 1 ) {
			header( 'Content-Type: application/json' );
			echo json_encode( $users->jsonfy() );
		} else {
			$sort_by = $this->input->get('sort_by', true, $this->default_sort);
			
			if ($sort_by != $this->default_sort) $get[] = 'sort_by='.$sort_by; 
			if ($get) $url .= '?'.implode('&', $get);
			
			$this->load->view('profile/user_list', array(
				'url' => $url,
				'per_page' => $this->per_page,
				'users' => $users->get_all()
			));
		}
	}

	//Change profile picture popup
	public function edit_picture() {
		if ($this->input->post()) {
			if ($this->input->post('src_img_url')) { //Upload by url
				$config = $this->user_model->behaviors['uploadable'];
				$config['avatar']['upload_to_s3'] = true;
				$responce = Uploadable_Behavior::do_upload(array('avatar' => $this->input->post('src_img_url')), $config);
				die(json_encode(array('status'=>true, 'thumb'=>$responce->avatar_73)));
			}
			else if ($this->input->post('src_img')) { //Save clicked
				$src = str_replace(Url_helper::s3_url().'users/', '', $this->input->post('src_img'));
				$this->user_model = $this->user_model;
				$model = new User_model();
				unset($model->behaviors['uploadable']);
				$responce = $model->update($this->user->id, array('avatar' => $src));
				$this->user = $this->user_model->get($this->session->userdata('id'));
				die(json_encode(array('status'=>true, 'img' => $this->user->avatar_73)));
			}
			else {                                    //Upload file (ajax)
				$config = $this->user_model->behaviors['uploadable'];
				$config['avatar']['upload_to_s3'] = true;
				$responce = Uploadable_Behavior::do_upload(array(), $config);
				die(json_encode(array('status'=>true, 'thumb'=>$responce->avatar)));
			}
		}
		$this->load->view('edit_picture', array('user' => $this->user));
	}

	function crop() {
		//$this->user - refers to logged in user defined in system/Controller.php
		if (! $this->user->avatar) {
			die(json_encode(array('status'=>false, 'error' =>'Upload a photo, please.')));
		}
		$this->load->library('image_lib');
		$post = $this->input->post();
		$tmp = $this->input->post('img_path');

		//Init
		$filename = substr($this->user->avatar, strrpos($this->user->avatar, '/')+1);
		$upload_cfg = $this->config->item('uploads');
		file_put_contents($upload_cfg['path'].'tmp/'.$filename, file_get_contents($this->user->avatar));
		
		$config = array(
			'image_library' => 'gd2',
			'source_image' => $upload_cfg['path'].'tmp/'.$filename,
			'maintain_ratio' => false,
			'y_axis' => 0
		);
		if($max_width != null) $config['width'] = $post['w'];
		if($max_height != null) $config['height'] = $post['h'];
		if($x_axis != null) $config['x_axis'] = $post['x'];
		if($y_axis != null) $config['y_axis'] = $post['y'];

		$obj = new CI_Image_lib();
		$obj->initialize($config);
		
		if ( ! $obj->crop()) {
			echo $obj->display_errors();
			return ;
		}

		$this->user->update(array('avatar'=>$upload_cfg['url'].'tmp/'.$filename));
		$this->user = $this->user_model->get($this->session->userdata('id'));

		die(json_encode(array('status'=>true, 'original'=>$this->user->avatar, 'thumb' =>$this->user->avatar_73, 'update'=>'header')));
	}


}
?>
