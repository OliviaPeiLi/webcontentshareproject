<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Stats extends ADMIN
{

	public function index_get($page='general')
	{
		$data = array('page'=>$page);
		if ($this->user->role == 2)
		{
			$data['view'] = 'stats/index';
		}
		elseif ($this->user->role == 1)
		{
			$data['view'] = 'stats';
			$data['url'] = $this->user->url;
		}
		$this->load->view('admin/layout', $data);
	}

	public function general_get()
	{
		$this->load->view('stats/general');
	}
	
	public function newsfeeds_get()
	{
		$data['starttime'] = date('Y-m-d' , mktime(0,0,0,date('n', strtotime('-3 months')),date('j', strtotime('-3 months')),date('Y', strtotime('-3 months'))));
		$data['endtime'] = date('Y-m-d');
		$this->load->view('stats/newsfeeds', $data);
	}

	public function sources_get()
	{
		$this->load->library('google/analytics');
		$this->load->view('stats/sources', array(
							  'keywords' => $this->analytics->get_page_graph(null, null, null, 'ga:visits', 'ga:keyword', 10),
							  'landing_pages' => $this->analytics->get_page_graph(null, null, null, 'ga:visits', 'ga:landingPagePath', 10)
						  ));
	}

	public function content_get()
	{
		$this->load->library('google/analytics');
		$this->load->view('stats/content', array(
							  'page_views' => $this->analytics->get_page_graph(null, null, null, 'ga:visits', 'ga:pagePath', 20)
						  ));
	}

	public function users_get()
	{
		if (!$this->input->is_ajax_request()) return $this->index_get('users');
		$users = array(
					 'total' => $this->db->from('users')->count_all_results(),
					 'male' => $this->db->from('users')->where('gender', 'm')->count_all_results(),
					 'female' => $this->db->from('users')->where('gender', 'f')->count_all_results(),
					 'fb_id' => $this->db->from('users')->where('fb_id > 0')->count_all_results(),
					 'twitter_id' => $this->db->from('users')->where('twitter_id > 0')->count_all_results()
				 );

		$this->load->view('stats/users', array(
							  'gender'=>array(
								  array('label'=>'male', 'data' => $users['male']),
								  array('label'=>'female', 'data' => $users['female']),
							  ),
							  'linked' => array(
								  array(
									  'label' => 'Linked',
									  'data' => array(
										  array(0, round($users['fb_id']/$users['total']*100), 'twitter ('.(round($users['fb_id']/$users['total']*100)).'%)'),
										  array(2, round($users['twitter_id']/$users['total']*100), 'facebook ('.(round($users['twitter_id']/$users['total']*100)).'%)')
									  )
								  )
							  )
						  ));
	}

	public function newsfeeds_data_get()
	{
		$get = $this->input->get();
		$starttime = strtotime($get['from']) > 0 ? strtotime($get['from']) : mktime(0,0,0,date('n', strtotime('-3 months')),date('j', strtotime('-3 months')),date('Y', strtotime('-3 months')));
		$endtime = strtotime($get['to']) ? strtotime($get['to']) : strtotime('now');
		$data = array();
		$data[] = array(
					  'label' => 'Newsfeeds added',
					  'data' => $this->db
					  					->select('UNIX_TIMESTAMP(time)*1000 as `0`, COUNT(newsfeed_id) as `1`')
										->where("UNIX_TIMESTAMP(time) >= {$starttime}")
										->where("UNIX_TIMESTAMP(time) <= {$endtime}")
										->group_by('YEAR(time), MONTH(time), DAY(time)')
										->order_by('time ASC')
										->get('newsfeed')
										->result_array()
				  );
				  //die($this->db->last_query());
		die(json_encode($data));
	}
	
	public function data_registered_get()
	{
		$data = array();
		$data[] = array(
					  'label' => 'Registered users',
					  'data' => $this->db->select('UNIX_TIMESTAMP(sign_up_date)*1000 as `0`, COUNT(id) as `1`')->group_by('DATE(sign_up_date)')->get('users')->result_array()
				  );
		die(json_encode($data));
	}

	public function links_get()
	{
		if (!$this->input->is_ajax_request()) return $this->index_get('links');
		$types = $this->db->select('link_type as `label`, COUNT(newsfeed_id) as `data`')->group_by('link_type')->get('newsfeed')->result_array();
		foreach ($types as &$type) $type['data'] = (int)$type['data'];
		$sources = $this->db->select(array("SUBSTRING(`link` FROM 1 FOR LOCATE('/',link,10)-1) as `label`", "COUNT(link_id) as `data`"))
				   ->group_by('label')->order_by('data','desc')->get('links')->result_array();
		foreach ($sources as &$source) $source['data'] = (int)$source['data'];
		$this->load->view('stats/links', array(
							  'types' => $types,
							  'sources' => $sources
						  ));
	}

	public function data_links_get()
	{
		$data = array();
		$data[] = array(
					  'label' => 'Shared links',
					  'data' => $this->db->select('UNIX_TIMESTAMP(time)*1000 as `0`, COUNT(link_id) as `1`')->group_by('DATE(time)')->get('links')->result_array()
				  );
		die(json_encode($data));
	}
	/*
		function ajax_get()
		{
			$this->load->model('user_model');
			$this->load->model('newsfeed_model');

			$data['male_cnt'] = $this->user_model->get_male();
			$data['female_cnt'] = $this->user_model->get_female();

			$data['drop_total'] = $data['drop_image'] = $this->newsfeed_model->get_drop('images');
			$data['drop_total'] += $data['drop_clip'] = $this->newsfeed_model->get_drop('clips');
			$data['drop_total'] += $data['drop_screenshot'] = $this->newsfeed_model->get_drop('screenshots');
			$data['drop_total'] += $data['drop_video'] = $this->newsfeed_model->get_drop('videos');
			$data['drop_total'] += $data['drop_text'] = $this->newsfeed_model->get_drop('texts');

			echo json_encode($data);
			exit();
		}*/


}
