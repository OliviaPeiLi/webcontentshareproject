<div>
	<h3>Shared links</h3>
	<?=$this->load->view('stats/chart_line',array('data_url'=>'stats/data_links'), true)?>
</div>
<div class="width3" style="float:left">
	<h3>Link types</h3>
	<?=$this->load->view('stats/chart_pie',array('data'=>$types), true)?>
</div>
<div class="width3" style="float:left">
	<h3>Link sources</h3>
	<?=$this->load->view('stats/chart_pie',array('data'=>$sources), true)?>
</div>
<div>
	<h3>Link sources</h3>
	<table class="display stylized">
		<thead>
			<tr>
				<th style="width: 125px; " class="">Keyword</th>
				<th style="width: 137px; " class="">Visits</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sources as $key=>$val): if ($key>50) break; ?>
			<tr class="gradeA <?=$key%2 ? 'odd' : 'even'?>">
				<td class="sorting_1"><?=$val['label']?></td>
				<td><?=$val['data']?></td>
			</tr>
			<?php endforeach; ?>
	</table>
</div> 