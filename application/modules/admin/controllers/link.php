<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Link extends ADMIN
{
	protected $model = 'link_model';
	protected $list_actions = array('stats'=>'stats','edit'=>'Edit','delete'=>'Delete');

	protected $list_fields = array(
								 'link_id'			=> 'primary_key',
								 'title'			=> 'string_link',
								 'text'				=> 'hidden',
								 'link'				=> 'link',
								 'img'				=> 'hidden',
								 'time'				=> 'time',
								 'user_id_from'		=>'string',
								 'owner'	 		=> 'string_unsortable',
								 'fb_share_count'	=> 'number',
								 'title_link' 		=> 'hidden',
								 'hits'  			=> 'number'
							 );
	protected $form_fields = array(
								 array(
									 'Edit',
									 'link_id' 		=> 'primary_key',
									 'title'   		=> 'string',
									 'text'			=> 'string',
									 'link'			=> 'string',
									 'img'	  		=> 'string',
									 'time'			=> 'time'
								 ),
								 array(
									 'Stats',
									 'comments' 	=> 'function',
									 'likes' 		=> 'function',
									 'views'   		=> 'function',
									 'uniqviews' 	=> 'function',
									 'timeonpage'	=> 'function',
									 'avgtimeonpage'=> 'function',
									 'entrances' 	=> 'function',
									 'entrancerate' => 'function',
									 'bouncerate'	=>'function',
									 'exits' 		=> 'function',
									 'views_graph' 	=> 'function',
									 'uniqviews_graph'		=> 'function',
									 'avgtimeonpage_graph' 	=> 'function',
									 'entrances_graph' 		=> 'function',
									 'exits_graph' 	=> 'function',
									 'sources'		=> 'function'
								 )
							 );
	protected $filters = array(
							 'link_id' 				=> 'primary_key',
							 'user_id_from' 		=> 'number',
							 'source'				=> 'string'
						 );
						 
	public function index_post()
	{
		//print_r($this->input->post());
		//die();
		$this->load->model('link_model');
		$this->load->model('newsfeed_model');
		if($this->input->post('delete')){
			$newsfeeds = $this->newsfeed_model->select_fields('newsfeed_id','source')->get_many_by(array('source'=>$this->input->post('source',true)));
			foreach($newsfeeds as $newsfeed){
				$newsfeed_id[] = $newsfeed->newsfeed_id;
			}
			$this->newsfeed_model->delete_many($newsfeed_id);
		}
		Url_helper::redirect('/admin/link');
	}
	
	public function index_get($child_items = NULL)
	{
		if (! $this->model())
		{
			return $this->load->view('layout', array('view'=>'home'));
		}
		$post = $this->input->post();
		$model = $this->model();
		$this->load->model($model);
		$model_name = $this->model();

		if (strpos($this->model(), '/') > 0)
		{
			$model_info = explode('/', $this->model);
			$model_name = $model_info[count($model_info)-1];
		}
		$items = $child_items ? $child_items : $this-> {$model_name};
		if(empty($post))
		{
			$items = $items->paginate($this->input->get('page'), 20);
			$data['pagination'] = $items->pagination->create_links();
		}
		$items = $this->filter($items);
		$items = $this->sort($items);
		$data['rows'] = $items->get_all();
		
		$this->response($data, 'list');
	}

	function get_views($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:pageviews');
	}

	function get_uniqviews($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:uniquePageviews');
	}

	function get_bouncerate($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:visitBounceRate').'%';
	}

	function get_timeonpage($link)
	{
		$this->load->library('google/analytics');
		$time = $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:timeOnPage');
		return(gmdate("H:i:s", $time));
	}

	function get_avgtimeonpage($link)
	{
		$this->load->library('google/analytics');
		$time = $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:avgTimeOnPage');
		return(gmdate("H:i:s", $time));
	}

	function get_entrances($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:entrances');
	}

	function get_entrancerate($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:entranceRate').'%';
	}

	function get_visits($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:visits');
	}

	function get_exits($link)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number('/drop/'.$link->newsfeed->newsfeed_id, 'ga:exits');
	}

	function get_sources($link)
	{
		$this->load->library('google/analytics');
		return $this->load->view('links/sources', array(
									 'sources' => $this->analytics->get_page_graph(
										 //'/',
										 '/drop/'.$link->newsfeed->newsfeed_id,
										 '2012-01-01',
										 date('Y-m-d', time()+60*60*24),
										 'ga:visits',
										 'ga:source'
									 )
								 ), true);
	}

	function get_graph($link)
	{
		$this->load->library('google/analytics');
		return $this->load->view('stats',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
								 ),true);
	}

	function get_stats($link)
	{
		return $this->load->view('stats',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title
								 ),true);
	}

	public function get_comments($link)
	{
		return $this->db->from('comments')->where('link_id', $link->link_id)->count_all_results();
	}

	public function get_likes($link)
	{
		return $this->db->from('likes')->where('link_id', $link->link_id)->count_all_results();
	}

	protected function filter($items)
	{
		if ($this->user->role != 2)
		{
			$items->_set_where(array("(user_id_from = {$this->user->id})"));
		}
		return parent::filter($items);
	}

	protected function check_access($item)
	{
		if ($this->user->role != 2)
		{
			if ($item->user_id_from != $this->user->id) return false;
		}
		return parent::check_access($item);
	}

	function get_views_graph($link)
	{

		return $this->load->view('stats/chart_line',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
									 'data_url' => 'google_charts/page'
								 ),true);
	}

	function get_uniqviews_graph($link)
	{

		return $this->load->view('stats/chart_line',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
									 'data_url' => 'google_charts/uniqviews'
								 ),true);
	}

	function get_avgtimeonpage_graph($link)
	{

		return $this->load->view('stats/chart_line',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
									 'data_url' => 'google_charts/avgtimeonpage',
									 'unit' => 'seconds'
								 ),true);
	}

	function get_entrances_graph($link)
	{

		return $this->load->view('stats/chart_line',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
									 'data_url' => 'google_charts/entrances'
								 ),true);
	}

	function get_exits_graph($link)
	{

		return $this->load->view('stats/chart_line',array(
									 'url' => '/drop/'.$link->newsfeed->newsfeed_id,
									 'title' => 'fandrop: '.$link->title,
									 'data_url' => 'google_charts/exits'
								 ),true);
	}

}