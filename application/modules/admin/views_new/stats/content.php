<div class="">
	<h3>Pageviews</h3>
	<?=$this->load->view('stats/chart_line',array('metric'=>'ga:visitors'), true)?>
</div>
<div>
	<h3>Pages</h3>
	<table class="display stylized">
		<thead>
			<tr>
				<th>Page</th>
				<th>Visits</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($page_views as $key=>$val): ?>
			<tr class="gradeA <?=$key%2 ? 'odd' : 'even'?>">
				<td><?=$val[0]?></td>
				<td class="sorting_1"><?=$val[1]?></td>
			</tr>
			<?php endforeach; ?>
	</table>
</div> 