<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Dashboard extends ADMIN
{

 public function index_get()
	{
		//$this->load->library('google/analytics');
		$this->load->model('Statsmodel');

		$data = array();
		$data['view'] = 'dashboard';

		if ($this->user->role == 2)
		{
			$data = $this->get_superadmin_stats($data);
		}
		elseif ($this->user->role == 1)
		{
			$data = $this->get_limitedadmin_stats($data);
		}

		$this->load->view('admin/layout', $data);
	}

	private function get_superadmin_stats($data)
	{
		$start = time();
		//Get ACTIVE Users
		$act_users = 0;
//		$act_users = $this->Statsmodel->get_active_users_num(); //Returns a number of users who have at least 1 share for last month
		$step1 = time();
		$data['data']['num_users'] = $this->db->count_all('users'); //Returns a number of users
		$step2 = time();
		$data['data']['act_users'] = count($act_users); //Returns a number of users who have at least 1 share for last month
		$step3 = time();

		//Social connections
		$data['data']['fb'] = $this->Statsmodel->get_fb_users_num(); //Returns a number of users connected via Facebook.
		$step4 = time();
		$data['data']['tw'] = $this->Statsmodel->get_tw_users_num(); //Returns a number of users connected via Twitter.
		$step5 = time();
		$data['data']['shared_links'] = $this->db->count_all('links'); //Returns a number of shared links
		$step6 = time();

		//Unique domains
//		$data['data']['uniq_domains'] = $this->Statsmodel->get_unique_domains(); //Returns a list of most unique domains
		$step7 = time();

		//Folders
		$data['data']['folders_count'] = $this->db->count_all_results('folder'); //Returns a number of folders
		$step8 = time();
		$data['data']['private_count'] = $this->db->from('folder')->where('private', '1')->count_all_results(); //Returns a number of private folders
		$step9 = time();
		$data['data']['public_count'] = $this->db->from('folder')->where('private', '0')->count_all_results(); //Returns a number of public folders
		$step10 = time();

		$avg_links = $this->Statsmodel->get_avg_links_per_folder();
		$data['data']['avg_links'] = round($avg_links[0]['avarage']);//Returns number of avaragelinks per folder
		$step11 = time();

		$data['data']['most_links'] = $this->Statsmodel->get_most_collections_links(); //Returns a list of most collections links
		$step12 = time();
		$data['data']['users_shares'] = $this->Statsmodel->get_users_with_most_shares(); //Returns a list of users with most shares
		$step13 = time();

		$this->load->model('comment_model');
		$this->load->model('like_model');
		$data['data']['comments_count'] = $this->comment_model->count_all();
		$data['data']['likes_count'] = $this->like_model->count_all();
		$data['data']['redrop_count'] = $this->newsfeed_model->count_by('parent_id > 0');

		//different type of newsfeeds
		$this->load->model('newsfeed_model');
		$data['data']['image_count'] = $this->newsfeed_model->filter_type('images')->count_by();
		$data['data']['clip_count'] = $this->newsfeed_model->filter_type('clips')->count_by();
		$data['data']['screenshot_count'] = $this->newsfeed_model->filter_type('screenshots')->count_by();
		$data['data']['video_count'] = $this->newsfeed_model->filter_type('videos')->count_by();
		$data['data']['text_count'] = $this->newsfeed_model->filter_type('texts')->count_by();

		return $data;
	}

	private function get_limitedadmin_stats($data)
	{
		//Get ACTIVE Users

		//Social connections
		$data['data']['fb'] = $this->user->fb_id; //Returns a number of users connected via Facebook.
		$data['data']['tw'] = $this->user->twitter_id; //Returns a number of users connected via Twitter.
		$data['data']['shared_links'] = $this->db->where('user_id_to', $this->user->id)->count_all('links'); //Returns a number of shared links

		//Unique domains
		//$data['data']['uniq_domains'] = $this->Statsmodel->get_unique_domains(); //Returns a list of most unique domains

		//Folders
		$data['data']['folders_count'] = $this->db->where('user_id', $this->user->id)->count_all_results('folder'); //Returns a number of folders
		$data['data']['private_count'] = $this->db->where('user_id', $this->user->id)->where('private', '1')->count_all_results('folder'); //Returns a number of private folders
		$data['data']['public_count'] = $this->db->where('user_id', $this->user->id)->where('private', '0')->count_all_results('folder'); //Returns a number of public folders

		return $data;
	}
}