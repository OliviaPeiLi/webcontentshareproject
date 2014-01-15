<div class="width3" style="float:left">
	<h3>Language</h3>
	<?=$this->load->view('stats/chart_pie',array('metric'=>'ga:visitors', 'dimension'=>'ga:language'), true)?>
</div>

<div class="width3" style="float:left">
	<h3>Browser</h3>
	<?=$this->load->view('stats/chart_pie',array('metric'=>'ga:visitors', 'dimension'=>'ga:browser'), true)?>
</div>

<div class="width3" style="float:left">
	<h3>Operating system</h3>
	<?=$this->load->view('stats/chart_pie',array('metric'=>'ga:visitors', 'dimension'=>'ga:operatingSystem'), true)?>
</div>

<div class="width3" style="float:left">
	<h3>Screen Resolution</h3>
	<?=$this->load->view('stats/chart_pie',array('metric'=>'ga:visitors', 'dimension'=>'ga:screenResolution'), true)?>
</div> 