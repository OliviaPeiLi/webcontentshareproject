<?php
class Google_charts extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		//TO-DO:  ? has admin priv
		$this->load->library('google/analytics');
		/*
		die(json_encode(array(
			array(			 //Series 1
				'label' => 'Data',
				'data' => array(
					array(1267873200 * 1000, 1),
					array(1267959600  * 1000, 2),
					array(1268046000  * 1000, 5),
					array(1268132400  * 1000, 1)
				)
			)
		)));
		*/
	}
	public function page()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_page_timeline(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to')
								)
							)
						)));
	}
	public function uniqviews()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_uniqviews_timeline(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to')
								)
							)
						)));
	}
	public function avgtimeonpage()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_avgtimeonpage_timeline(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to')
								)
							)
						)));
	}
	public function entrances()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_entrances_timeline(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to')
								)
							)
						)));
	}
	public function exits()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_exits_timeline(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to')
								)
							)
						)));
	}
	public function source()
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_page_graph(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to'),
									'ga:source',
									'ga:visits'
								)
							)
						)));
	}
	public function graph($metric=null, $dimension=null)
	{
		die(json_encode(array(
							array(  //Series 1
								'label' => $this->input->get('page'),
								'data' => $this->analytics->get_page_graph(
									$this->input->get('page'),
									$this->input->get('from'),
									$this->input->get('to'),
									$this->input->get('metric'),
									$this->input->get('dimension')
								)
							)
						)));
	}

	public function pie($metric=null, $dimension=null)
	{
		$data = $this->analytics->get_page_graph(
					$this->input->get('page'),
					$this->input->get('from'),
					$this->input->get('to'),
					$this->input->get('metric'),
					$this->input->get('dimension'),
					$this->input->get('max-results')
				);
		$rows = array();
		foreach ($data as $row)
		{
			$rows[] = array(
						  'label' => $row[0],
						  'data' => (Int) $row[1]
					  );
		}
		die(json_encode($rows));
	}
}