<div class="width3" style="float:left">
	<h3>Traffic</h3>
	<?=$this->load->view('stats/chart_pie',array('metric'=>'ga:visitors', 'dimension'=>'ga:source'), true)?>
</div>
<div class="width3" style="float:left">
	<h3>Keywords</h3>
	<table class="display stylized">
		<thead>
			<tr>
				<th style="width: 125px; " class="">Keyword</th>
				<th style="width: 137px; " class="">Visits</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($keywords as $key=>$val): ?>
			<tr class="gradeA <?=$key%2 ? 'odd' : 'even'?>">
				<td class="sorting_1"><?=$val[0]?></td>
				<td><?=$val[1]?></td>
			</tr>
			<?php endforeach; ?>
	</table>
</div>
<div>
	<h3>Landing pages</h3>
	<table class="display stylized">
		<thead>
			<tr>
				<th style="width: 125px; " class="">Keyword</th>
				<th style="width: 137px; " class="">Visits</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($landing_pages as $key=>$val): ?>
			<tr class="gradeA <?=$key%2 ? 'odd' : 'even'?>">
				<td class="sorting_1"><?=$val[0]?></td>
				<td><?=$val[1]?></td>
			</tr>
			<?php endforeach; ?>
	</table>
</div> 