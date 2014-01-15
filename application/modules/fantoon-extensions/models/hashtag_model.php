<?php

class Hashtag_model extends MY_Model
{
	protected $name_key = 'hashtag';
	public $top_hahtags = array('_hash_Aww','_hash_Entertainment','_hash_Food','_hash_Funny','_hash_Gaming','_hash_Music','_hash_Sports','_hash_Tech','_hash_Travel');
	
	public $null_val  = '- Select one -';
		
	/* =================== Filters ==================== */
	
	public function top_hashtags() {
		$this->db->where_in('hashtag', $this->top_hahtags);
		return $this;
	}
	
	public function not_top_hashtags() {
		$this->db->where_not_in('hashtag', $this->top_hahtags);
		return $this;
	}
	
	public function popular_hashtags($limit=10) {
		return $this->limit($limit)->order_by('count','desc');
	}
	
	public function search($keyword) {
		$keyword = str_replace('#', '', $keyword);
		$keyword = '_hash_'.$keyword;
		$this->db->like('hashtag', $keyword, 'right');
		return $this;
	}
	
	/* ================ EVENTS ====================== */
	
    protected function _run_before_create($data) {
        if (isset($data['hashtag']) && $data['hashtag'] != '') {
        	if(is_numeric(str_replace('_hash_','',$data['hashtag']))) $data['num_only'] = 1;
        }
        return parent::_run_before_create($data);
    }
	
	public function _run_after_get($row=null) {
		if (!parent::_run_after_get($row)) return ;
		if(isset($row->hashtag)){
			$row->_hashtag_url = Url_helper::base_url().'search?q='.str_replace(array('_hash_'),'%23',$row->hashtag);
			$row->_hashtag_name = str_replace("_hash_","#",$row->hashtag);
			$row->_hashtag_collection_url = Url_helper::base_url().'search/collections?q='.str_replace(array('_hash_'),'%23',$row->hashtag);
		}
	}
	
	/* ==================== Selects ==================== */
	
	public function dropdown() {
		$res = parent::dropdown();
		foreach ($res as &$row) {
			$row = str_replace("_hash_","#",$row);
		}
		
		return $res;
	}

	public function popular_top_dropdown()	{

        $hashtags_arr = array(
        	array('id'=>0, 'name' => $this->null_val, 'class'=>'')
        );

        $hashtags = $this->hashtag_model->top_hashtags()->get_all();
        foreach ($hashtags as $hashtag)
        {
			$hashtags_arr[] = array('id'=>$hashtag->id, 'name'=>$hashtag->hashtag_name, 'class'=>' top');
        }
       
        return $hashtags_arr;

	}
	
	public function popular_dropdown() {
        $last_popular = $this->order_by('count','desc')->limit(1,10)->get_by(array());
        if ($last_popular) $last_popular = $last_popular->count; else $last_popular = 0;

        $hashtags_arr = array(
        	array('id'=>0, 'name' => $this->null_val, 'class'=>'')
        );
        $hashtags = $this->hashtag_model->popular_hashtags()->get_all();
        foreach ($hashtags as $hashtag)
        {
			$hashtags_arr[] = array('id'=>$hashtag->id, 'name'=>$hashtag->hashtag_name, 'class'=>$hashtag->count >= $last_popular?' popular':'');
        }
       
        return $hashtags_arr;
	}

}