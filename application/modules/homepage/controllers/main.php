<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		
		//@todo - not for here
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
	
	/**
	 * function to load home page
	 * if there is session->userdata('id') then load home page for user, display newsfeed that this logged in user's following folders, and the tickers that other user's follow activity to this user
	 * else load landing page
	 */
	public function index($category_type = 'hashtag', $sort_by='time', $filter=null) {

		if (@$this->input->get('analytics_login')) {
			$this->load->library('google/analytics');
			$this->analytics->login(null,true);
		}
		
		//check if first time visit home
		$first_visit = $this->user_visit_model->get($this->session->userdata('id'))->home;
		if($first_visit == '1') {
			$this->user_visit_model->update($this->session->userdata('id'), array('home'=>'0'));
		}
		
		return parent::template($this->is_mod_enabled('landing_ugc') ? 'landing_page/landing_ugc' : 'landing_page/landing_fresh_postcard', array(
			'category_type' => $category_type,
			'sort_by' => $sort_by,
			'filter' => $filter,
		), 'Fandrop', $this->is_mod_enabled('landing_ugc') ? 'header_ugc' : 'header_landing');
		
	}
	
	public function my_feed($category_type = 'hashtag', $sort_by='time', $filter=null) {
		return parent::template($this->is_mod_enabled('design_ugc') ? 'home_main_ugc' : 'home_main', array(
			'category_type' => $category_type,
			'sort_by' => $sort_by,
			'filter' => $filter,
			'first_visit' => $this->user_visit_model->get($this->session->userdata('id'))->home,
		),'Fandrop - discovery hub of the web');
	}
	
	public function landing_page($category_type='hashtag', $sort_by = 'time', $filter = null) {

		if($this->input->is_ajax_request()) {
			echo '<script>parent.window.location.reload(true);</script>';
			die();
		}
		$this->router->set_method('landing_page'); //used for js files grouping

		if ($this->is_mod_enabled('landing_ugc')) {
			$main_content = 'landing_page/landing_ugc';
			$header = 'header_ugc';
		} else {
			$main_content = 'landing_page/landing_fresh_postcard';
			$header = 'header_landing';
		}
		
		return parent::template($main_content, array(
			'category_type' => $category_type,
			'sort_by' => $sort_by,
			'filter' => $filter,
		), 'Fandrop', $header);
	}

	public function trending_bar() {
		return ; //http://dev.fantoon.com:8100/browse/FD-5117
		$top_hashtags = $this->hashtag_model->top_hashtags()->get_all();

		$trending_hashtags = $this->hashtag_model->order_by('count','DESC')
								->not_top_hashtags()
								->order_by('id', 'RAND()')
								->limit(5)
								->get_many_by(array('num_only'=>0));
	   
		if ($this->is_mod_enabled('invite5') && $this->session->userdata('id')) {
			$num_invited_users = $this->alpha_user_model->count_by(array('user_id'=>$this->session->userdata('id')));
			if ($num_invited_users < 5) {
				$this->session->set_userdata('invite_more', true);
			}
		} else {
			$num_invited_users = 0;
		}
		
		$this->load->view('includes/trending_categories_bar', array(
			'top_hashtags' => $top_hashtags,
			'trending_hashtags' => $trending_hashtags,
			'num_invited_users' => $num_invited_users
		));

		return;
	}

}
