<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Folders extends ADMIN
{

	protected $model = 'folder_model';

	protected $export_csv = true;
	protected $list_actions = array('stats'=>'stats','edit'=>'Edit','delete'=>'Delete');
	protected $list_collection_actions = array(
										 		'merge'      => 'Merge Collections',
										 		'delete'     => 'Delete Selected',
											  );
	
	protected $list_fields = array(
								 'folder_id'       => 'primary_key',
								 'folder_name'     => 'string',
								 'user'            => 'belongs_to',
								 'rss_source'      => 'belongs_to',
								 'newsfeeds_count' => 'number',
								 'followers_count' => 'number',
								 'hits'			   => 'number',
								 
							 );
	protected $form_fields = array(
								 array(
									'Edit',
									'folder_id'	      => 'primary_key',
									'folder_name' 	  => 'string',
									'user_id'	  	  => 'string',
									'private' 	  	  => 'string',
									//'editable'      => 'string',
									'newsfeeds_count' => 'readonly',
									'followers_count' => 'readonly',
									'type'            => 'string',
									'sort_by'		  => 'string',
								 	'filters'         => 'token_list',
								 ),
								 array(
									 'Stats',
									 'links' 				=> 'function',
									 'likes' 				=> 'function',
									 'followers_count'		=> 'readonly',
									 'newsfeeds_count' 		=> 'readonly',
									 'views' 				=> 'function',
									 'uniqviews' 			=> 'function',
									 'timeonpage' 			=> 'function',
									 'avgtimeonpage' 		=> 'function',
									 'entrances' 			=> 'function',
									 'entrancerate' 		=> 'function',
									 'bouncerate'			=> 'function',
									 'exits' 				=> 'function',
									 'views_graph' 			=> 'function',
									 'uniqviews_graph' 		=> 'function',
									 'avgtimeonpage_graph' 	=> 'function',
									 'entrances_graph' 		=> 'function',
									 'exits_graph' 			=> 'function',
									 'sources' 				=> 'function'
								 ),
							 );

	protected $filters = array(
							 'folder_id'       => 'primary_key',
							 'folder_uri_name' => 'string',
							 'folder_name'     => 'string',
							 'user_id'	       => 'number',
							 'rss_source_id'   => 'number',
						 );

	function index_post_merge() {
		$items = $this->input->post('items');
		
		if(count($items)<2) {
			die(json_encode(array('status'=>false, 'error'=>'<p>Please select an action</p>')));
		}
		
		$rows = $this->folder_model->get_many($items);

		$folders = array();
		$user_id = $rows[0]->user_id;
		foreach($rows as $folder) {
			$folders[$folder->folder_id] = $folder->folder_name ." - ". $folder->folder_id;
			if($user_id!=$folder->user_id) {
				die(json_encode(array('status'=>false, 'error'=>'You cannot merge stories belong to different owners')));
			}
		}

		$data['popup'] = $this->load->view('admin/folders/select_folder_view',array('folders'=>$folders), TRUE);

		echo json_encode($data);
	}
	
	
	function merge_post()
	{

		$main_folder_id = $this->input->post('main_folder_id');
		$folders = $this->input->post('folders');

		$this->load->model('folder_model');
		$this->folder_model->merge_folders($main_folder_id, $folders);

		Url_helper::redirect($_SERVER['HTTP_REFERER']);

	}
	
	function get_owner($folder){
		return $folder->user->full_name;
	}
	
	function get_folder_link($folder){
		return $folder->get_folder_url();
	}

	function get_sources($folder)
	{
		$this->load->library('google/analytics');
		return $this->load->view('links/sources', array(
									 'sources' => $this->analytics->get_page_graph(
										 //'/',
										 str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
										 '2012-01-01',
										 date('Y-m-d', time()+60*60*24),
										 'ga:visits',
										 'ga:source'
									 )
								 ), true);
	}

	function get_links($folder)
	{
		return $this->db->from('activities')->where('folder_id', $folder->folder_id)->where('type', 'link')->count_all_results();
	}

	function get_likes($folder)
	{
		return $this->db->from('activities')->where('folder_id', $folder->folder_id)->where('type', 'like')->count_all_results();
	}

	function get_views($folder)
	{
		$this->load->library('google/analytics');
		return $folder->get_total_hits();
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:pageviews');
	}

	function get_uniqviews($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:uniquePageviews');
	}

	function get_bouncerate($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:visitBounceRate');
	}

	function get_timeonpage($folder)
	{
		$this->load->library('google/analytics');
		$time = $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:timeOnPage');
		return(gmdate("H:i:s", $time));
	}

	function get_avgtimeonpage($folder)
	{
		$this->load->library('google/analytics');
		$time = $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:avgTimeOnPage');
		return(gmdate("H:i:s", $time));
	}

	function get_entrances($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:entrances');
	}

	function get_entrancerate($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:entranceRate').'%';
	}

	function get_visits($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:visits');
	}

	function get_exits($folder)
	{
		$this->load->library('google/analytics');
		return $this->analytics->get_number(str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()), 'ga:exits');
	}

	function get_views_graph($folder)
	{
		$url = str_replace(Url_helper::base_url(), '/', $folder->get_folder_url());
		return $this->load->view('stats/chart_line',array(
									 'url' => str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
									 'title' => 'Collection '.$folder->folder_name,
									 'data_url' => 'google_charts/page'
								 ),true);
	}

	function get_uniqviews_graph($folder)
	{
		$url = str_replace(Url_helper::base_url(), '/', $folder->get_folder_url());
		return $this->load->view('stats/chart_line',array(
									 'url' => str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
									 'title' => 'Collection '.$folder->folder_name,
									 'data_url' => 'google_charts/uniqviews'
								 ),true);
	}

	function get_avgtimeonpage_graph($folder)
	{
		$url = str_replace(Url_helper::base_url(), '/', $folder->get_folder_url());
		return $this->load->view('stats/chart_line',array(
									 'url' => str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
									 'title' => 'Collection '.$folder->folder_name,
									 'data_url' => 'google_charts/avgtimeonpage',
									 'unit' => 'seconds'
								 ),true);
	}

	function get_entrances_graph($folder)
	{
		$url = str_replace(Url_helper::base_url(), '/', $folder->get_folder_url());
		return $this->load->view('stats/chart_line',array(
									 'url' => str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
									 'title' => 'Collection '.$folder->folder_name,
									 'data_url' => 'google_charts/entrances'
								 ),true);
	}

	function get_exits_graph($folder)
	{
		$url = str_replace(Url_helper::base_url(), '/', $folder->get_folder_url());
		return $this->load->view('stats/chart_line',array(
									 'url' => str_replace(Url_helper::base_url(), '/', $folder->get_folder_url()),
									 'title' => 'Collection '.$folder->folder_name,
									 'data_url' => 'google_charts/exits'
								 ),true);
	}

	protected function filter($items, $filter = false)
	{
		if ($this->user->role != 2)
		{
			$items->_set_where(array("(user_id = {$this->user->id} OR folder_id IN (SELECT folder_id FROM folder_contributors WHERE user_id = {$this->user->id}))"));
		}
		return parent::filter($items, $filter);
	}

	protected function check_access($item)
	{
		if ($this->user->role != 2)
		{
			if ($item->user_id != $this->user->id) return false;
		}
		return parent::check_access($item);
	}

}
