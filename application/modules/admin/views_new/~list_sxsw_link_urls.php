<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<!-- Left column/section -->
		<section class="column width6 first">					
			<?=$this->load->view('filters',$filters,true)?>
			
			<h3>Data tables</h3>
			
			<table class="display stylized" id="example">
				<thead>
					<tr>
                        <th><?=$this->lang->line('admin_name_column');?></th>
                        <th><?=$this->lang->line('admin_user_column');?></th>
						<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
							<th><?=$field?></th>
						<? } ?>
                        <th><?=$this->lang->line('admin_actions_column');?></th>
					</tr>
				</thead>
				<tbody>
					<? $keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
				    <? if(is_array($data)){ $rows = $data; }else{ $rows = $data->get_all(); } ?>

				    <? foreach($rows as $row){ ?>
					<tr class="gradeA">
					    <td><?=$row->sxsw_user->first_name?> <?=$row->sxsw_user->last_name?></td>
					    <td><img src="<?=$row->sxsw_user->thumbnail?>"></td>
					    <? foreach($list_fields as $field=>$type):  ?>
							<? if ($type=='hidden') continue; ?>
                            <td><?=$this->ft_admin->render_list_field($row, $field, $type )?></td>
						<? endforeach; ?>
						<? $list_actions = array('delete'=>'Delete'); ?>
						<td><?=$this->ft_admin->render_actions($list_actions, $row->$primary_key)?></td>
					</tr>
					<? } ?>
				</tbody>
				<tfoot>
					<tr>
						<td><? /* global list actions */?></td>
						<td colspan="<?=count($list_fields)?>"><?=$data->pagination->create_links();?></td>
					</tr>
				</tfoot>
			</table>
			
			<div class="clear">&nbsp;</div>
			
		</section>
	</div>
</div> 