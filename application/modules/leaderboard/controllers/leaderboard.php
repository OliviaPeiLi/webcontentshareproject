<?php

class Leaderboard extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->lang->load('leaderboard/leaderboard', LANGUAGE);
	}
	
	function home($group=null){
		$my_stats = $this->user->info==$group ? $this->user->user_stat : null;
		
		if($group){
			$top_stats_users = $this->user_stats_model->select_fields(array('user_stats.id'))->join_users()->filter_group($group)->order_by('total_score','desc')->limit(10)->get_all();
		}else{
			$top_stats_users = $this->user_stats_model->select_fields(array('id'))->order_by('total_score','desc')->limit(30)->get_all();
		}
		$top_user_ids = array();
		foreach($top_stats_users as $user){
			$top_user_ids[] = $user->id;
		}
		
		if(isset($top_user_ids) && !empty($top_user_ids)){
			$top_stats = $this->user_stats_model;
			if(in_array($this->input->get('order'),array('asc','desc')) && in_array($this->input->get('orderby'),array('views_count','upvotes_got_count','comments_got_count','redrops_count','ref_count','total_score'))){
				$order = $this->input->get('order');
				$orderby = $this->input->get('orderby');
				$top_stats = $top_stats->order_by($orderby,$order);
			}else{
				$top_stats = $top_stats->order_by('total_score','desc');
			}
			$top_stats = $top_stats->order_by('user_id','ASC')->get_many($top_user_ids);
		}

		$this->load->view('includes/template', array(
			'header' => 'header',
			'footer' => 'footer',
			'title' => $this->lang->line('leader_board_title'),
			'main_content' => 'leaderboard_home',
			'my_stats' => $my_stats,
			'top_stats' => $top_stats,
			'board_name' => $group ? ucfirst($group.' ') : '',
			'order' => $this->input->get('order')=='desc' ? 'asc' : 'desc',
			'group' => $group
		));
	}

}

?>
