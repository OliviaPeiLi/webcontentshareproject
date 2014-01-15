<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Ajax extends ADMIN
{
	protected $time_format = 'Y-m-d';
	protected $time_range = array();
	protected $range_arr = array();
	protected $compare = false;

	function __construct()
	{
		$input = $this->input->get();
		if(!isset($input['el'])) continue;
		if(!isset($input['date']))
		{
			list($y,$m,$d) = explode('-', date('Y-n-j', strtotime('-1 month')));
			$start = date('Y-m-d H:i:s', mktime(0, 0, 0, $m, $d, $y));
			list($y,$m,$d) = explode('-', date('Y-n-j', strtotime('now')));
			$end = date('Y-m-d H:i:s', mktime(23, 59, 59, $m, $d, $y));
		}
		else
		{
			$date = $input['date'];
			list($y,$m,$d) = explode('-', date('Y-n-j', strtotime($date[0])));
			$start = date('Y-m-d H:i:s', mktime(0, 0, 0, $m, $d, $y));
			list($y,$m,$d) = explode('-', date('Y-n-j', strtotime($date[1])));
			$end = date('Y-m-d H:i:s', mktime(23, 59, 59, $m, $d, $y));
		}
		$this->time_range = array($start, $end);

		//checking number of lines
		if(isset($input['el2']) && $input['el2'] != '' && $input['el2'] != ' ' && $input['el2'] != 'select')
		{
			$this->compare = true;
		}

		//saving data into session
		$sess_data = array();
		$sess_data['page'] = 'stats';
		$sess_data['date'] = $this->time_range;
		$sess_data['interval'] = $input['interval'];
		$sess_data['el'] = $input['el'];
		if($this->compare == true) $sess_data['el2'] = $input['el2'];

		$this->session->set_userdata($input['page'], $sess_data);

		$start = strtotime($this->time_range[0]);
		$end   = strtotime($this->time_range[1]);
		$diff  = ($end - $start)/86400;

		$dates = array();

		for( $i = 0; $i <= $diff; $i++ )
		{
			$curr = date("Y-m-d", $start);
			$val = ($this->compare == true) ? array(0,0) : array(0);
			$dates[$curr] = $val;
			$start = $start + 86400;
		}
		$this->range_arr = $dates;

		parent::__construct();
	}

	public function index_get()
	{
		$input = $this->input->get();
		$this->load->model('Statsmodel');
		$data = array();

		$data['cols'] = array(array('type' => 'date'), array('label' => ucfirst($input['el']), 'type' => 'number'));
		$func = 'get_'.$input['el'];

		//el preparing
		$stats = $this->Statsmodel-> {$func}($this->time_range);
		foreach($stats as $k=>$r)
		{
			$this->range_arr[date($this->time_format, strtotime($r['time']))][0] = $r['qnty'];
		}

		//el2 preparing
		if($this->compare == true)
		{
			$func = 'get_'.$input['el2'];
			array_push($data['cols'], array('label' => ucfirst($input['el2']), 'type' => 'number'));
			$stats = $this->Statsmodel-> {$func}($this->time_range);

			foreach($stats as $k=>$r)
			{
				$this->range_arr[date($this->time_format, strtotime($r['time']))][1] = $r['qnty'];
			}
		}

		$this->prepare_data();
		foreach($this->range_arr as $k=>$v)
		{
			if($this->compare == true)
			{
				$data['rows'][] = array('c' => array(
											array( 'v' => strtotime($k), 'f' => $this->get_date_value($k) ),
											array( 'v' => $v[0] ),
											array( 'v' => $v[1] )
										),
										'p' => array('lineHidden' => true)
									   );
			}
			else
			{
				$data['rows'][] = array('c' => array(
											array( 'v' => strtotime($k), 'f' => $this->get_date_value($k) ),
											array( 'v' => $v[0] )
										),
										'p' => array('lineHidden' => true)
									   );
			}
		}
		$data['date'] = array(date('d M, Y', strtotime($this->time_range[0])),date('d M, Y', strtotime($this->time_range[1])));
		$data['el'] = $input['el'];
		if($this->compare == true)
		{
			$data['el2'] = $input['el2'];
		}
		echo json_encode($data);
	}

	protected function get_date_value($start)
	{
		$input = $this->input->get();
		if($input['interval'] > 1)
		{
			$end = strtotime($start) + ($input['interval']*86399);
			$diff = $end - strtotime($this->time_range[1]);
			if($diff > 100) $end = strtotime($this->time_range[1]);
			return date('F d, Y', strtotime($start)). ' - ' . date('F d, Y', $end);
		}
		else
			return date('l, F j, Y', strtotime($start));

	}

	protected function prepare_data()
	{
		$input = $this->input->get();
		if(!isset($input['interval'])) $interval = 1;
		else $interval = $input['interval'];

		if($interval > 1)
		{
			$dates = array();
			$i=1;
			foreach($this->range_arr as $k=>$v)
			{
				if(!isset($start) || $i == 1) $start = $k;
				$dates[$start][0] = (isset($dates[$start][0])) ? $dates[$start][0] + $v[0] : $v[0];
				if($this->compare == true)
				{
					$dates[$start][1] = (isset($dates[$start][1])) ? $dates[$start][1] + $v[1] : $v[1];
				}
				if($i == $interval) $i = 1;
				else $i++;
			}
			$this->range_arr = $dates;
		}
	}

}