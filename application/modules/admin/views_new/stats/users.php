<div>
	<h3>Registered</h3>
	<?=$this->load->view('stats/chart_line',array('data_url'=>'stats/data_registered'), true)?>
</div>
<div class="width3" style="float:left">
	<h3>Gender</h3>
	<?=$this->load->view('stats/chart_pie',array('data'=>$gender), true)?>
</div>

<div class="width3" style="float:left">
	<h3>Linked</h3>
	<?=$this->load->view('stats/chart_bar',array('data'=>$linked), true)?>
</div> 