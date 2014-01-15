<table class="display stylized" id="example">
	<thead>
		<tr>
				<th>Source</th>
				<th>Num visits</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($sources as $source): ?>
			<tr>
				<td><?=$source[0]?></td>
				<td><?=$source[1]?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td></td>
		</tr>
	</tfoot>
</table> 