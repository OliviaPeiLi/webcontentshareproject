<?php
require_once 'search.php';
class Users extends Search {

	/**
	 * Search people
	 * @link /search/people?q=test
	 */
	public function people() {
		$keyword = $this->input->get('q',true);
		$keyword = preg_replace('/[^a-zA-Z0-9\'@#]/','',$keyword);
		if (!$keyword) {
			return Url_helper::redirect('/');
		}
		
		$page = $this->input->get('page', 0)+1;
		$per_page = $this->config->item('people_page_limit');
		

		$this->user_model;
		$user_model = new User_model();
		
		$users = $user_model->select_search_fields($keyword)->search($keyword)
						->paginate($page, $per_page)->order_by('relevance', 'desc');
						
		if ($this->input->is_ajax_request()) {
			echo json_encode($users->jsonfy());
			//$this->load->view('profile/user_items', array('users' => $users));
		} else {
			$users = $users->get_all();
			$url = '/search/people';
			$get = array('q='.$keyword);
			$url .= '?'.implode('&', $get);
			$this->load->view('includes/template', array(
				'main_content' => 'search/search_results',
				'keyword' => $keyword,
				'search_category' => 'people',
				'has_results' => (bool) $users,
				'users' => $users,
				'per_page' => $per_page,
				'url' => $url,
				'header' => 'header',
				'hide_footer' => '1',
				'title' => 'Search results'
			));
	   }
	   
	}

	/**
	 * Used for mentions
	 */
	public function ajax_people() {

		$keyword = $this->input->get('term');
		if ($this->input->get('mentions')) {
			$keyword = substr($keyword, strrpos($keyword, '@')+1);
		}

		if ($this->is_mod_enabled('follow')) {
			$user_id = $this->session->userdata('id');
			$newsfeed_id = $this->input->get('newsfeed_id',true,0);

			//can mention followers
			$user_ids = $this->connection_model->add_users()->dropdown('user2_id', 'user2_id');
			// ->filter_followers($user_id)

			//if mentioning in a drop page - can mention the creator and the commenters
			if($newsfeed_id) {
				$newsfeed = $this->newsfeed_model->get($newsfeed_id);
				$user_ids[$newsfeed->user_id_from] = $newsfeed->user_id_from;
				$user_ids = array_merge($user_ids, $newsfeed->get('comments')->dropdown('user_id_from', 'user_id_from') );
			}
			
			//Cant mention myself
			unset($user_ids[$user_id]);
			
			if( ! $user_ids ) {
				echo json_encode(array());
				return ;
			}
		}
		
		$users = $this->user_model->select_fields(array("CONCAT(id, ':', uri_name) as id","CONCAT(first_name, ' ', last_name) as value"))
							->search($keyword)
							->order_by('value', 'ASC')
							->limit(10);
		
		if ($this->is_mod_enabled('follow')) {
			$users = $users->get_many_by(array('id'=> array_keys($user_ids)));
		}	else {
			$users = $users->get_all();
		}

		//Jsonfy
		foreach ($users as &$user) {
			unset($user->_model);
			$user->value = $user->value . " @" . end(explode(":",$user->id));
		}

		echo json_encode($users);
	}

}
