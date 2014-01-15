<?php

class User_stats_model extends MY_Model
{
    //relation
    protected $belongs_to = array(
                                'user' => array('foreign_column' => 'user_id', 'foreign_model' => 'user')
                            );


    public function _run_after_get($row) {
			if (!parent::_run_after_get($row)) return ;

			if(isset($row->htmls_count) && isset($row->contents_count) && isset($row->embeds_count) && isset($row->texts_count) && isset($row->images_count)){
				$row->_drops_count = $row->htmls_count + $row->contents_count + $row->embeds_count + $row->texts_count + $row->images_count;
			}
			if(isset($row->public_collections_count) && isset($row->private_collections_count)) {
				$row->_collections_count = $row->public_collections_count + $row->private_collections_count;
			}

			if(isset($row->views_count) && isset($row->upvotes_count) && isset($row->comments) && isset($row->ref_count)){
				$row->_total_score = $row->views_count + 
					5*$row->upvotes_count + 
					5*$row->comments + 
					50*$row->ref_count;
			}

		}

		public function _run_after_set($data) {
			$this->_after_set($data);
    	$this->refresh_cache($data);
			return parent::_run_after_set($data);
		}
    
    public function join_users()
    {
        $this->db->join('users', 'users.id = user_stats.user_id');
        return $this;
    }
    
    public function filter_group($group)
    {
        $this->db->where('info', $group);
        return $this;
    }
    
    public function get_rank($stats){
    
    	$group = isset($stats->group) ? $stats->group : null;
    	$count = $this->join_users();
    	if($group){
	    	$count = $count->filter_group($group);
    	}
    	$count = $count->count_by(array('total_score'=>$stats->total_score));
    	if($count > 0){
    		
    		$rank = $this->join_users();
    		if($group){
    			$rank = $rank->filter_group($group);
    		}
    		$rank = $rank->count_by(array('total_score'=>$stats->total_score, 'user_id <'=>$stats->user_id));
    		
	    	$i = $rank+1;
    	}
	    $result = $this->join_users();
	    if($group){
	    	$result = $result->filter_group($group);
	    }
	    $result = $result->count_by(array('total_score >'=>$stats->total_score))+$i;
	    return $result;
    }
    
    protected function _run_before_delete($obj) {
    	$this->refresh_cache($obj);
    	return parent::_run_before_delete($obj);
    }

    /**
     * refresh_cache
     *
     * @param mixed $row
     * @access public
     * @return void
     */
    public function refresh_cache($row)
    {
        // remove home_landing cache
        $ci = get_instance();
        if ( $ci->cache->get('home_landing_popular_users') )
        {
            $ci->cache->delete('home_landing_popular_users');
        }
    }
    
    public function _after_set($data)
    {
	    if(isset($data['views_count']) && isset($data['id'])){
	    	$this->load->model('badge_user_model');
	    	$user_id = $this->get($data['id'])->user_id;
		    if($data['views_count'] == 1000){
			    //dropped it
			    $this->badge_user_model->insert(array('badge_id'=>1,'user_id'=>$user_id));
		    }elseif($data['views_count'] == 10000){
			    //dead drop
			    $this->badge_user_model->insert(array('badge_id'=>2,'user_id'=>$user_id));
		    }elseif($data['views_count'] == 25000){
			    //drop ship
			    $this->badge_user_model->insert(array('badge_id'=>3,'user_id'=>$user_id));
		    }elseif($data['views_count'] == 50000){
			    //paradropper
			    $this->badge_user_model->insert(array('badge_id'=>4,'user_id'=>$user_id));
		    }elseif($data['views_count'] == 100000){
			    //hot dropper
			    $this->badge_user_model->insert(array('badge_id'=>5,'user_id'=>$user_id));
		    }
	    }
	    if(isset($data['id'])){
		    $stats = $this->get($data['id']);
		    
		    $score = $stats->drops_count 
		    		 + $stats->views_count 
		    		 + 5*$stats->upvotes_got_count 
		    		 + 5*$stats->redrops_count
		    		 + 15*$stats->comments_got_count
		    		 + 150*$stats->ref_count;
				   	
			mysql_query("UPDATE user_stats SET total_score = ".$score." WHERE id = ".$stats->id);
	    }
    }

	public function recount($id)
	{
		$this->load->model('newsfeed_model');
		$data['image'] = $this->newsfeed_model->filter_type('images')->count_by(array('user_id_from' => $id));
		$data['screenshot']  = $this->newsfeed_model->filter_type('screenshot')->count_by(array('user_id_from' => $id));
		$data['clip']  = $this->newsfeed_model->filter_type('clip')->count_by(array('user_id_from' => $id));
		$data['video'] = $this->newsfeed_model->filter_type('videos')->count_by(array('user_id_from' => $id));
		$data['text']  = $this->newsfeed_model->filter_type('texts')->count_by(array('user_id_from' => $id));

		$data['drops']  = $data['image'] + $data['screenshot'] + $data['clip'] + $data['video'] + $data['text'];
		$data['id'] = $id;

		return $data;
	}

	public function update_drop_types($id)
	{
		$data = $this->recount($id);
		$this->update_by( $data );
	}
	
	public function order_by($criteria, $order) {
		if ($criteria == 'drops_count') {
			$criteria = '(htmls_count+contents_count+embeds_count+texts_count+images_count)';
		}
		return parent::order_by($criteria, $order);
	}

}
?>
