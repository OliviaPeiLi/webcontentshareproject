<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Users extends ADMIN
{
	protected $list_actions = array('stats'=>'stats','edit'=>'Edit','delete'=>'Delete');
	protected $model = 'user_model';
	protected $list_fields = array(
									 'id'           => 'primary_key',
									 'avatar_73'    => 'image',
									 'full_name'    => 'string',
									 'uri_name'     => 'string',
									 'email'        => 'string',
									 'fb_id'		=> 'string',
									 'twitter_id'   => 'string',
									 'info'         => 'string',
									 'role'         => 'string',
									 'verified'     => 'string'
							 );

	protected $form_fields = array(
								 array(
									 'Edit',
									 'id'           => 'primary_key',
									 'first_name'   => 'string',
									 'last_name'    => 'string',
									 'uri_name'     => 'string',
									 'gender'       => array(
									 					'select',
								 						'options' => array('' => '-select-', 'm' => 'male', 'f' => 'female')
								 					),
									 'birthday'     => 'time',
									 'email'        => 'string',
									 'role'         => array(
									 					'select',
								 						'options' => array()
								 					),
									 'fb_id'		=> 'string',
									 'twitter_id'   => 'string',
									 'info'         => 'string',
									 'verified'     => 'string',
									 'sign_up_date' => 'readonly',
									 'followers'	=> 'token_list',
									 'followings'	=> 'token_list',
								),
								 array(
									'Stats',
									'user_stat->collections_count' => 'readonly',
									'user_stat->contests_count'    => 'readonly',
									'user_stat->htmls_count'       => 'readonly',
									'user_stat->contents_count'    => 'readonly',
									'user_stat->embeds_count'      => 'readonly',
									'user_stat->texts_count'       => 'readonly',
									'user_stat->images_count'      => 'readonly',
									'user_stat->comments_count'   => 'readonly',
									'user_stat->upvotes_count'     => 'readonly',
									'user_stat->followers_count'   => 'readonly',
									'user_stat->followings_count'  => 'readonly',
									'user_stat->views_count'       => 'readonly',
									//'uniqviews' => 'readonly'
								 )
							 );

	protected $filters = array(
									 'id'         => 'primary_key',
									 'uri_name'   => 'string',
									 'email'      => 'string',
									 'first_name' => 'string',
									 'last_name'  => 'string',
									 'fb_id'      => 'string',
									 'twitter_id' => 'string',
									 'info'       => 'string'
							 );
							 
	public function __construct() {
		parent::__construct();
		$this->form_fields[0]['role']['options'] = User_model::$roles;
	}

	public function followers_get($row = false) {
		if ($this->input->is_ajax_request()) {
			$user_model = new User_model();
			//$users = $user_model->search_name($_GET['q'])->dropdown(null, null, true);

			die(json_encode($users));
		} else {
			$id = $row ? $row->id : $_GET['id'];
			if(!$id) return array();

			$user_model = new User_model();
			$users = $user_model->select_list_fields()->filter_followers($id)->dropdown(null, null, true);

			return $users;
		}
	}


	public function followings_get($row = false) {
		if ($this->input->is_ajax_request()) {
			$user_model = new User_model();
			$users = $user_model->search_name($_GET['q'])->dropdown(null, null, true);

			die(json_encode($users));
		} else {
			$id = $row ? $row->id : $_GET['id'];
			if(!$id) return array();

			$user_model = new User_model();
			$users = $user_model->select_list_fields()->filter_followings($id)->dropdown(null, null, true);

			return $users;
		}
	}

	public function item_post() {
		$this->load->model('user_model');
		$this->load->model('connection_model');
		$post = $this->input->post();
		$item = $this->item();


		$postFollowers = is_array($post['followers']) ? $post['followers'] : array();
		if(isset($postFollowers[$item->id])) unset($postFollowers[$item->id]);
		$followers = $this->connection_model->filter_followings($item->id)->get_all();

		if(!(count($followers) == count($postFollowers) && count($followers) == 0)) {
			if(count($postFollowers) == 0 && count($followers) > 0) {
				foreach ($followers as $follower) {
					$follower->delete();
				}
			} else {
				$followers_exist = array();
				if($followers) foreach ($followers as $follower) {
					if(isset($postFollowers[$follower->user1_id])) {
						$followers_exist[] = $follower->user1_id;
					} else {
						$follower->delete();
					}
				}

				if($postFollowers) foreach ($postFollowers as $postFollowerId => $postFollowerName) {
					if($postFollowerId > 0 && !in_array($postFollowerId, $followers_exist)) {
						$this->connection_model->insert(array(
							'user1_id' => $postFollowerId,
							'user2_id' => $item->id
						));
					}
				}
			}
		}

		$postFollowings = is_array($post['followings']) ? $post['followings'] : array();
		if(isset($postFollowings[$item->id])) unset($postFollowings[$item->id]);
		$followings = $this->connection_model->filter_followers($item->id)->get_all();

		if(!(count($followings) == count($postFollowings) && count($followings) == 0)) {
			if(count($postFollowings) == 0 && count($followings) > 0) {
				foreach ($followings as $following) {
					$following->delete();
				}
			} else {
				$followings_exist = array();
				if($followings) foreach ($followings as $following) {
					if(isset($postFollowings[$following->user2_id])) {
						$followings_exist[] = $following->user2_id;
					} else {
						$following->delete();
					}
				}

				if($postFollowings) foreach ($postFollowings as $postFollowingId => $postFollowingName) {
					if($postFollowingId > 0 && !in_array($postFollowingId, $followings_exist)) {
						$this->connection_model->insert(array(
							'user1_id' => $item->id,
							'user2_id' => $postFollowingId
						));
					}
				}
			}
		}

		unset($_POST['followers'], $_POST['followings'], $_POST['user1_id'], $_POST['user2_id']);
		parent::item_post();
	}
}