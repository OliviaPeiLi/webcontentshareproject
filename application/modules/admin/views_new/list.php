<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<!-- Left column/section -->
		<section class="column width10 first">
			<?=$this->load->view('filters',$filters,true)?>
			<h3><?=ucwords(str_replace('_', ' ', $this->router->class));?> <a href="<?=$this->router->class?>/create" class="btn btn-blue">Create</a></h3>
			<div id="message"></div>
			<? if(!empty($filter_tabs)){ ?>
				Filter by:
				
				<? foreach($filter_tabs as $tab=>$filter){ ?>
					<? foreach ($filter as $filter_by=>$filter_token){ 
						
					}?>
					<a href="?filter_by=<?=$filter_by?>&filter_token=<?=$filter_token?>"><?=' ('.$tab.') '?></a>
				<? } ?>
				<a href="/admin/<?=$this->uri->segment(2)?>">(See ALL)</a>
			<? } ?>
			<? if($export_csv) { ?>
				<?=Form_Helper::open('', array('id' => 'export_csv_form', 'method' => 'POST'))?>
					<?=Form_Helper::form_hidden('list_action', 'export_csv');?>
					<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
						<?=Form_Helper::form_hidden('fields['.$field.']', 1);?>
					<? } ?>
					<?
						$options = array();
						foreach ($_GET as $key => $value) {
							if(in_array($key, array('cond', 'search')) || in_array($key, array_keys($filters))){
								$options[$key] = $value;
							}
						}
						foreach ($options as $option_key => $option_value) {
							if(is_array($option_value)) {
								foreach ($option_value as $suboption_key => $suboption_value) {
									echo "<input type=\"hidden\" name=\"filter_data[{$option_key}][{$suboption_key}]\" value=\"{$suboption_value}\" />";
								}
							} else {
								echo "<input type=\"hidden\" name=\"filter_data[{$option_key}]\" value=\"{$option_value}\" />";
							}
						}
					?>
					<?=Form_Helper::close()?>
			<? } ?>
			<? if($list_collection_actions) {?>
				<?=Form_Helper::open("", array('onsubmit'=>"return SendForm(this)"))?>
					<? if(in_array('export_csv', array_keys($list_collection_actions))) {
						$options = array();
						foreach ($_GET as $key => $value) {
							if(in_array($key, array('cond', 'search')) || in_array($key, array_keys($filters))){
								$options[$key] = $value;
							}
						}
						foreach ($options as $option_key => $option_value) {
							if(is_array($option_value)) {
								foreach ($option_value as $suboption_key => $suboption_value) {
									echo "<input type=\"hidden\" name=\"filter_data[{$option_key}][{$suboption_key}]\" value=\"{$suboption_value}\" />";
								}
							} else {
								echo "<input type=\"hidden\" name=\"filter_data[{$option_key}]\" value=\"{$option_value}\" />";
							}
						}
					} ?>
					<div class="ta-right">
					<?php array_unshift($list_collection_actions, '- With selected -')?>
						<?= Form_Helper::dropdown('list_action', $list_collection_actions)?>
						<input type="submit" name="list_action_submit" value="Apply" class="btn btn-blue"><button type="button" class="btn btn-red" onclick="document.getElementById('export_csv_form').submit(); return false;">Export CSV</button>
					</div>
			<? } ?>
			<table class="display stylized" id="example">
				<thead>
					<tr>
						<? if($list_collection_actions) { ?>
							<th><input type="checkbox"  id="select_all"></th>
						<? } ?>
						<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
							<th>
								<p style="white-space:nowrap;">
									<? if(in_array('export_csv', array_keys($list_collection_actions))):?><input type="checkbox" name="export_csv_field[]" checked onchange="document.getElementsByName('fields['+this.value+']')[0].value = this.checked ? 1 : 0" value="<?=$field;?>" /><?endif;?>
									<a href="?order=<?=$order?>&orderby=<?=$field?>"><?=$field?></a>
								</p>
							</th>
						<? } ?>
						<?php if ($list_actions) { ?>
							<th class="actions">Actions</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>					
					<? if(is_array($data['rows'])): ?>
						<? foreach($data['rows'] as $row): ?>
						<tr class="gradeA">		
							 <? if($list_collection_actions):?>
								<td><input type="checkbox" class="select_row" name="items[]" value="<?=$row->primary_key()?>"></td>
							<? endif?>
							<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
								<td><?=$this->ft_admin->render_list_field($row, $field, $type )?></td>
							<? } ?>
							<?php if ($list_actions) { ?>
								<td><?=$this->ft_admin->render_actions($list_actions, $row, $row->_model->primary_key())?></td>
							<?php } ?>
						</tr>
						<? endforeach; ?>
					<? endif; ?>
				</tbody>
				<? if( isset($data['pagination']) ) { ?>
				<tfoot>
					<tr>
						<td><? /* global list actions */?></td>
						<td colspan="<?=count($list_fields)?>"><?=$data['pagination'];?></td>
					</tr>
				</tfoot>
				<? } ?>
			</table>
			<? if($list_collection_actions):?>
				<?=Form_Helper::close()?>
			<? endif?>	
			<div class="clear">&nbsp;</div>
			
		</section>
	</div>
</div> 