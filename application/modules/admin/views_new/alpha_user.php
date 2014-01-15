<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<!-- Left column/section -->
		<section class="column width6 first">					
			<?=$this->load->view('filters',$filters,true)?>
			
			<?=Form_Helper::open()?>
				<fieldset>
					<legend>Insert</legend>
					
					
					<p>
						<label class="required"><?=$this->lang->line('alpha_user_first_name_label');?>:</label><br/>
						<input type="text" name="first_name" class="half" value="" />
					</p>
					<p>
						<label class="required"><?=$this->lang->line('alpha_user_last_name_label');?>:</label><br/>
						<input type="text" name="last_name" class="half" value="" />
					</p>
					<p>
						<label class="required"><?=$this->lang->line('alpha_user_email_label');?>:</label><br/>
						<input type="text" name="signup_email" class="half" value="" />
					</p>
					
					<p><input type="submit" value="Insert" class="btn btn-blue" /></p>
					<p class="box"></p>
				</fieldset>

			</form>
			<div class="clear">&nbsp;</div>
			
			<h3>Waiting list</h3>
			
			<table class="display stylized" id="example">
				<thead>
					<tr>
						<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
							<th><?=$field?></th>
						<? } ?>
                        <th>Actions</th>
                        <th>Send Email</th>
                    </tr>
				</thead>
				<? $keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
				<tbody>
					<?=Form_Helper::open('/admin/alpha_user/email')?>
					<? foreach($data['waiting']['rows'] as $row){ ?>
                    	<noscript><?=$row->check;?></noscript>
						<? if($row->check == '0'){ ?>
						<tr class="gradeA" check="">
						    <? foreach($list_fields as $field=>$type){ 
							    if ($type=='hidden') continue; ?>
								<td><?=$this->ft_admin->render_list_field($row, $field, $type )?></td>
							<? } ?>
								<td>
									<a href="/admin/alpha_user/<?=$row->$primary_key?>" class="btn btn-blue">Edit</a>
									<script type="text/javascript"> $("#delete<?=$row->$primary_key ?>").on("click", function() { $.ajax({ url: "/admin/alpha_user/<?=$row->$primary_key?>", type: "DELETE", success: function(result) { window.location.href="/admin/alpha_user"; } }); }); </script><a id="delete<?=$row->$primary_key?>" href="#" class="btn btn-red">Delete</a>
								</td>
								<td>
									<?=Form_Helper::form_checkbox('alpha_users[]', $row->$primary_key, FALSE); ?>
								</td>
						</tr>
						<? } ?>
					<? } ?>
				</tbody>
                <? if(isset($data['waiting']['pagination'])): ?>
				<tfoot>
					<tr>
						<td><? /* global list actions */?></td>
						<td colspan="<?=count($list_fields)?>"><?=$data['waiting']['pagination'];?></td>
					</tr>
				</tfoot>
                <? endif; ?>
			</table>
			<p><input type="submit" value="Send Email" class="btn btn-blue" /></p>
			<div class="clear">&nbsp;</div>
			
			<h3>Sent list</h3>
			
			<table class="display stylized" id="example">
				<thead>
				<tr>
					<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
                        <th><?=$field?></th>
                    <? } ?>
					<th>Actions</th>
				</tr>
				</thead>
				<? $keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
				<tbody>
					<? foreach($data['sent']['rows'] as $row){ ?>
						<? if($row->check == '1'){ ?>
						<tr class="gradeA">
						    <? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
							<td><?=$this->ft_admin->render_list_field($row, $field, $type )?></td>
							<? } ?>
								<td><?=$this->ft_admin->render_actions($list_actions, $row, $primary_key)?></td>
						</tr>
						<? } ?>
					<? } ?>
				</tbody>
                <? if(isset($data['sent']['pagination'])): ?>
				<tfoot>
					<tr>
						<td><? /* global list actions */?></td>
						<td colspan="<?=count($list_fields)?>"><?=$data['sent']['pagination'];?></td>
					</tr>
				</tfoot>
                <? endif; ?>
			</table>
			
			<div class="clear">&nbsp;</div>
			
		</section>
	</div>
</div> 