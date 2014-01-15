<!-- Page content -->
<div id="page">
	<!-- Wrapper -->
	<div class="wrapper">
		<!-- Left column/section -->
		<section class="column width6 first">					
			<?=$this->load->view('filters',$filters,true)?>
			
			<h3>Users Subscriptions</h3>
			
			<table class="display stylized" id="example">
				<thead>
				<tr>
					<? foreach($list_fields as $field=>$type):?>						
						<th><?=$field?></th>						
					<? endforeach ?>							
					<th>Subscription to all actions</th>
				</tr>			
				</thead>			
				<tbody>
					<?foreach($items as $item):?>
					<tr>
						<? foreach($list_fields as $field=>$type):?>						
							<td><?=$item->$field?></td>						
						<? endforeach ?>	
						<td>
							<?=Form_Helper::form_checkbox(
								'subscription', 
								$item->user_id, 
								$item->message && $item->comment && $item->up_link 
								&& $item->reply && $item->up_comment && $item->connection 
								&& $item->follow_folder && $item->follow_list 
								&& $item->collaboration,
								'onchange=ChangeSubscription(this)'
							);?>
						</td>
					</tr>	
					<?endforeach?>		
				</tbody>	
				<tfoot>
					<tr>
						<td></td>
						<td colspan="<?=count($list_fields)?>"><?=$this->pagination->create_links();?></td>
					</tr>
				</tfoot>		
			</table>			
			<div class="clear">&nbsp;</div>			
		</section>
	</div>
</div>

<script type="text/javascript">
	function ChangeSubscription(box){
		user_id = $(box).val();
		status = $(box).is(':checked');		
		$.post("/admin/users_subscription/change_subscription",{user_id:user_id, status:status});
	}
</script>