<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<!-- Left column/section -->
		<section class="column width6 first">					
			<?=$this->load->view('filters',$filters,true)?>
			
			<h3>Users</h3>
			
			<table class="display stylized" id="example">
				<thead>
				<tr>
				<? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
					<? $pos = strrpos($type, "_unsortable"); 
						if($pos === false){ ?>
							<th><a href="?order=<?=$order?>&orderby=<?=$field?>"><?=$field?></a></th>
						<? }else{ ?>
							<th><?=$field?></th>
						<? } ?>
				<? } ?>
					<th>Actions</th>
				</tr>
				</thead>
				<? $keys = array_keys($list_fields,'primary_key'); $primary_key = $keys[0]; ?>
				<tbody>
					<?= Form_Helper::form_open('send_user_email')?>
					<div class="width6">
					<h6>Subject</h6>
						<?=Form_Helper::input('subject', Form_Helper::set_value('subject', 'Please enter Subject'), array('id'=>"contactus_email", 'class'=>"contactus_field input_placeholder", 'size'=>"50"))?>
					</div>
					<div>
					<h6>Message</h6>
					<textarea id="contactus_body" class="input_placeholder wysiwyg width6" name='msg_body'></textarea>
					</div>
					<div>
						<h6>Send to All</h6>
						<?=Form_Helper::form_checkbox('send_to_all', 'all', FALSE); ?>
					</div>
					<div>
						<h6>Newsletter?</h6>
						<?=Form_Helper::form_checkbox('newsletter', TRUE, FALSE); ?>
						<h6>Top Message</h6>
						<?=Form_Helper::input('top_message', Form_Helper::set_value('top_message', 'Top Message'), array('class'=>"contactus_field input_placeholder", 'size'=>"90"))?>
						<h6>Collection Title</h6>
						<?=Form_Helper::input('collection_title', Form_Helper::set_value('collection_title', 'Collection Title'), array('class'=>"contactus_field input_placeholder", 'size'=>"90"))?>
						<h6>Top Drop ID</h6>
						<?=Form_Helper::input('top_drop_id', Form_Helper::set_value('top_drop_id', 'Top Drop ID'), array('class'=>"contactus_field input_placeholder", 'size'=>"10"))?>
					</div>
					<div>
						<h6>Collection ID</h6>
						<? for($i=0;$i<6;$i++){ ?>
							<?=Form_Helper::input('folder_id[]', Form_Helper::set_value('folder_id[]', 'Collection ID'), array('class'=>"contactus_field input_placeholder"))?>
						<? } ?>
					</div>
					<div>
						<h6>Newsfeed ID</h6>
						<? for($i=0;$i<6;$i++){ ?>
							<?=Form_Helper::input('newsfeed_id[]', Form_Helper::set_value('newsfeed_id[]', 'Newsfeed ID'), array('class'=>"contactus_field input_placeholder", 'size'=>"10"))?>
						<? } ?>
					</div>
					<div>
						<h6>Temp Newsletter?</h6>
						<?=Form_Helper::form_checkbox('temp_newsletter', TRUE, FALSE); ?>
					</div>
				    <? if(is_array($data['rows'])): ?>
						<? foreach($data['rows'] as $row): ?>

						<tr class="gradeA">
						    <? foreach($list_fields as $field=>$type){ if ($type=='hidden') continue; ?>
							<td><?=$this->ft_admin->render_list_field($row, $field, $type )?></td>
							<? } ?>
								<td><?=$this->ft_admin->render_actions($list_actions, $row, $primary_key)?></td>
								<td>
									<?=Form_Helper::form_checkbox('users_id[]', $row->$primary_key, FALSE); ?>
								</td>
						</tr>
						<? endforeach; ?>
                    <? endif; ?>
					<p><input type="submit" value="Send Email" class="btn btn-blue" /></p>
					
				</tbody>
                <? if( isset($data['pagination']) ): ?>
				<tfoot>
					<tr>
						<td><? /* global list actions */?></td>
						<td colspan="<?=count($list_fields)?>"><?=$data['pagination'];?></td>
					</tr>
				</tfoot>
                <? endif; ?>
			</table>
			
			<div class="clear">&nbsp;</div>
			
		</section>
	</div>
</div> 