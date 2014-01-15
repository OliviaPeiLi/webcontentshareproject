<?php
/**
 *  Page controller class
 */
require_once 'api.php';

class Notification extends API
{
	
	public function index_get() {
		$_GET['filter']['user_id_to'] = $this->user->id;
		return parent::index_get();
	}
	
	public function grouped_get()
	{
		$this->load->model($this->model());
		$model = $this->model();
		$items = $this-> {$this->model()};
		$items = $this->filter($items);
		$items = $items->filter_home();
		if($this->input->get('page_limit') > 0)
		{
			$page_limit = $this->input->get('page_limit');
		}
		else
		{
			$page_limit = 10;
		}
		$items = $items->paginate($this->input->get('page'), $page_limit);
		$primary_key = $this->load->model($model)->primary_key();

		if($this->input->get('since_id') > 0)
		{
			$items = $items->order_by($primary_key, 'ASC')->get_many_by(array($primary_key.' >='=>$this->input->get('since_id')));
		}
		elseif($this->input->get('max_id') > 0)
		{
			$items = $items->order_by($primary_key, 'DESC')->get_many_by(array($primary_key.' <='=>$this->input->get('max_id')));
		}
		else
		{
			$items = $items->get_all();
		}
		$c=0;
		foreach ($items as $row) {
			//$ret[date('Y-m-d', strtotime($row->time))][] = $row;
			$time = date('Y-m-d', strtotime($row->time));
			if ($time === date('Y-m-d')) {
				$ret[$time]['day'] = 'Today';
				$ret[$time]['data'][] = $row;
			} else if ($time === date("Y-m-d", strtotime("yesterday"))) {
				$ret[$time]['day'] = 'Yesterday';
				$ret[$time]['data'][] = $row;
			} else {
				$ret[$time]['day'] = date('F d, Y', strtotime($time));
				$ret[$time]['data'][] = $row;
			} 
			$c++;
		}
		$this->response(array('results'=>$ret), 200); // 200 being the HTTP response code
		
	}
	
}