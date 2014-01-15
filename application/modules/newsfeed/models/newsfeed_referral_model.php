<?php

class newsfeed_referral_model extends MY_Model {
	
	protected $belongs_to = array('newsfeed');
	
	/** PER ITEM FUNCS **/
	
	public function icrease_view_count($referral) {
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$ip = explode('.', $ip);
		$ip_int = $ip[0]+(255*$ip[1])+(255*255*$ip[2])+(255*255*255*$ip[3]);
		mysql_query("INSERT INTO newsfeed_referrals_views (newsfeed_referrals_id, ip) VALUES ({$referral->id}, {$ip_int}) ON DUPLICATE KEY UPDATE views=views+1");
	}
	
	
	/** END PER ITEM FUNCS **/
	
	public function _run_after_get($row) {
		if (!parent::_run_after_get($row)) return ;
		
		if (isset($row->email)) {
			list($name, $addr) = explode('@', $row->email);
			$row->_name = $name;
		}
	}
	
	public function _run_before_create($data) {
		$i=0;
		list($name, $dummy) = explode('@', $data['email']);
		$_name = $name;
		while($this->count_by(array('newsfeed_id'=>$data['newsfeed_id'],'name'=>$name))) {
			$name = $_name.$i; $i++;
		}
		$data['name'] = $name;
		
		$this->load->library('bitly');
		$newsfeed = $this->newsfeed_model->get($data['newsfeed_id']);
		$data['url'] = Url_helper::base_url('/drop/'.$newsfeed->url.'/'.$data['name']);
		$ret = $this->bitly->shorten('/drop/'.$newsfeed->url.'/'.$data['name']);
		if ($ret->data && $ret->data->url) {
			$data['url'] = $ret->data->url;
		}
		
		return parent::_run_before_create($data);
	}
	
	public function search($keyword) {
		$this->db->like('name', $keyword);
		return $this;
	}
	
	public function update_url($ref) {
		if ($ref->url) return;
		$this->load->library('bitly');
		$url = Url_helper::base_url('/drop/'.$ref->newsfeed->url.'/'.$ref->name);
		$ret = $this->bitly->shorten('/drop/'.$ref->newsfeed->url.'/'.$ref->name);
		if ($ret->data && $ret->data->url) {
			$url = $ret->data->url;
		}
		$ref->update(array('url'=>$url));
		return $url;
	}
	    
}