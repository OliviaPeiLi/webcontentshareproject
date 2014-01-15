<div>
	<h3>Newsfeeds</h3>
	<?=$this->load->view('stats/chart_line', array('data_url' => 'stats/newsfeeds_data', 'starttime' => $starttime, 'endtime' => $endtime), true)?>
</div>