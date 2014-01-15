<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/event/events.php ) --> ' . "\n";
} ?>
<?
	// TODO: refactor the event model to list invitees inside each event
	//rather than generating an event for each invitee in the list
	//idea: create invitees column in events table
	$this->lang->load('edit_event/edit_event_views', LANGUAGE);
?>

<div id="events_main">
	<? if ($display_type === 'show_event_details') { 
		echo '<div class="container_24">'; 
		echo '<div class="grid_24">';
	} else {
		//echo '<h2>List of events</h2>';
	}	
	?>
	
	<? if ($view_type === 'show_event') { ?>

		<? foreach ($events as $key => $value) {
			if($value['event_id'] != $eid){
				$news_array=unserialize($value['data']);
				?>
			    <div class="event_top">
					<h2><?=$this->lang->line('edit_event_views_event_title');?></h2>
			    </div>
			    <div class="event_main">
			    	<div class="event_content_container inlinediv">
						<!--~~~~~~~~~~~~~~ WHEN section (time/date of event) ~~~~~~~~~~~~~~~~~~~~~~~-->
						<ul>
							<li class="event_title left">
								<?=$this->lang->line('edit_event_start_time_lbl');?>
							</li>
							<li class="event_when">
								<?=date('l, F j, Y g:i A',strtotime($value['start_time'])); ?>
							</li>
						</ul>
						<div class="clear"></div>
						<ul>
							<li class="event_title left">
								<?=$this->lang->line('edit_event_end_time_lbl');?>
							</li>
							<li class="event_when">
								<?=date('l, F j, Y g:i A',strtotime($value['end_time'])); ?>
							</li>
						</ul>
						<div class="clear"></div>

					    <!--~~~~~~~~~~~~ Whether Event is private or public ~~~~~~~~-->
					    <ul>
							<li class="event_title left">
							    <?=$this->lang->line('edit_event_event_privacy_lbl');?>
							</li>
							<li class="event_privacy"><?=$value['privacy'];?> <?=$this->lang->line('edit_event_event_lexicon');?></li>
					    </ul>
					    <div class="clear"></div>
						
						<!--~~~~~~~ Event Options (edit, delete, invite ppl, attendance choices) ~~~~~~~~~~~~~~~~~~~~--> 
						<ul>
							<li class="event_title left">
								<?=$this->lang->line('edit_event_attendance_lbl');?> 
							</li>
							<li>
								<ul class="dashed_box">
									<li class="event_attendance">
										<a href="/response/<? echo $this->uri->segment(2).'/'.$value['event_id']?>/yes"><?=$this->lang->line('edit_event_yes_lexicon');?></a> | 
										<a href="/response/<? echo $this->uri->segment(2).'/'.$value['event_id']?>/no"><?=$this->lang->line('edit_event_no_lexicon');?></a>
									</li>
									<? if($owner_priv == 1 || $admin_priv == 1){?>
										<li class="event_attendance">
										    <a href="/del_event/<? echo $this->uri->segment(2).'/'.$value['event_id'];?>"><?=$this->lang->line('edit_event_cancel_btn');?></a>
										    | <a href="/edit_event/<? echo $this->uri->segment(2).'/'.$value['event_id'];?>"><?=$this->lang->line('edit_event_edit_btn');?></a>
										    | <a href="#" class="ft-dropdown" rel="more_invitees_<?=$value['event_id']?>"><?=$this->lang->line('edit_event_invite_btn');?></a>
										</li>
									<? }?>
									<div class="clear"></div>
								</ul>
							</li>
							<li class="clear"></li>
							<li>
								<ul id="more_invitees_<?=$value['event_id']?>" class="more_invitees">
									<li><?=$this->lang->line('edit_event_invite_lbl');?></li>
									<div class="clear"></div>
									<? foreach ($value['more'] as $k=>$item){	?>
										<li><? echo form_checkbox('check_guests[]', $item['user_id']); ?> <a href="/profile/<?=$item['uri_name']?>/<? echo $item['user_id']?>"><?=$item['first_name'].' '.$item['last_name'];?></a></li>
									<? }?>
								</ul>
							</li>
						</ul>
						<div class="clear"></div>
						
						<!--~~~~~~~~~~~ WHERE section (location, address) ~~~~~~~~~~~~~~-->
					    <ul>
							<li class="event_title left">
							    <?=$this->lang->line('edit_event_where_lbl');?>
							</li>
							<li>
							    <ul class="event_details">
									<li>
									    <div class="detail_label"><?=$this->lang->line('edit_event_location_lbl');?>: </div>
									    <div class="detail_field"><?=$value['location'];?></div>
									</li>
									<li>
									    <div class="detail_label"><?=$this->lang->line('edit_event_address_lbl');?>: </div>
									    <div class="detail_field"><?=$value['address'];?></div>
									</li>
									<li>
									    <div class="detail_label"><?=$this->lang->line('edit_event_city_lbl');?>: </div>
									    <div class="detail_field"><?=$value['city'];?></div>
									</li>
									<li>
									    <div class="detail_label"><?=$this->lang->line('edit_event_zip_code_lbl');?>: </div>
									    <div class="detail_field"><?=$value['zip_code'];?></div>
									</li>
							    </ul>
							</li>
					    </ul>
					    <div class="clear"></div>
					    
					    <!--~~~~~~~~~~~ WHAT section (description) ~~~~~~~~~~~~~~-->
					    <ul>
							<li class="event_title left">
							    <?=$this->lang->line('edit_event_what_lbl');?>
							</li>
							<li class="body">
							    <?=$value['description'];?>
							</li>
					    </ul>
					    <div class="clear"></div>
					    
						<? if($value['attendees'] == '1' || $this->session->userdata['page_id'])
						{ ?>
							<!--~~~~~~~~~~~~ WHO section (who is invited, who is attending) ~~~~~~~~~~~~~~-->
						    <ul>
								<li class="event_title left">
								    <?=$this->lang->line('edit_event_who_lbl');?>
								</li>
								<li>
								    <ul class="event_invitees body">
										<?
										
										$invitees['yes'] = array();
										$invitees['no'] = array();
										foreach ($events as $k => $v) {
											if($value['event_id'] == $v['event_id'])
											{
												array_push($invitees[$v['response']], $v['user_link']);
											}//end if
										}//end loop
										
										$for_response = '';
										?>
										<? if (count($invitees['yes']) > 0) { ?>
											<div><?=$this->lang->line('edit_event_attending_lexicon');?></div>
											<ul class="people">
												<? 
												foreach($invitees['yes'] as $person) {
													echo '<li>'.$person.'</li>';
												}
												?>
											</ul>
										<? } ?>
										<? if (count($invitees['no']) > 0) { ?>
											<div><?=$this->lang->line('edit_event_not_attending_lexicon');?></div>
											<ul class="people">
												<?
												foreach($invitees['no'] as $person) {
													echo '<li>'.$person.'</li>';
												}
												?>
											</ul>
										<? } ?>
								    </ul>
									<? if($for_response){
										echo $for_response;?> <?=$this->lang->line('edit_event_responded_lexicon');?>
									<? }//end if ?>
								</li>
						    </ul>
						    <div class="clear"></div>
						<? } ?>
					    <div class="clear"></div>
					</div>
					
					<!--~~~~~~~~~~~~~~~~~~~~~~~ Event Image (flyer) ~~~~~~~~~~~~~~~~~~~~~~-->
					<div id="event_image_placeholder" class="inlinediv">
						<? if ($value['img'] !== '') { ?>
							<img class="event_image_display preview_img" src="<?=s3_url()?>pages/<?=$value['page_id'];?>/events/<?=$value['img'];?>"/>
						<? } else { ?>
							<img class="event_image preview_img" src="/images/defaultEvent.png"/>
						<? }?>
					</div>
					<div class="clear"></div>
<?  ?>								
					<? if($show_comments)
					{?>
					
					    <!--~~~~~~~~~~~~~~~~~~~~~~ COMMENTS ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					    <ul class="comments">
							<li class="event_title left inlinediv"><?=$this->lang->line('edit_event_comments_lbl');?></li>
							<li class="body inlinediv">
								<? //include('application/modules/comment/views/comments.php'); ?>
                <?=$this->load->view('comment/comments',array('view_type'=>$view_type),true);?>
							</li>
						</ul>
					<? } ?>
<?  ?>
				</div>
				<div class="event_bot"></div>
				
			<? } //end if
			$eid = $value['event_id'];	
		} //end loop ?>
				
<!--~~~~~~~~~~~~~~ LIST OF EVENTS ~~~~~~~~~~~~~~~~~~~-->
	<? } else if ($view_type === 'events') { ?>
		<? //print_r($events); ?>
		<? if (count($events) <= 0) { ?>
			<?=$this->lang->line('edit_event_no_events');?>
		<? } ?>
		<? foreach ($events as $key => $value) {
			if($value['event_id'] != $eid) {
				?>
				<div class="event_entry">
					<div class="details inlinediv">
						<div class="event_title">
							<a href="/events/<? echo $value['page_id'].'/'.$value['event_id'];?>"><h2><?=$value['event_name']; ?></h2></a>
						</div>
						<div class="event_time">
							<?=date('l, F j, Y g:i A',strtotime($value['start_time']))?>
							<?=date('l, F j, Y g:i A',strtotime($value['end_time']))?>
						</div>
						<div class="event_entry_description">
							<?=substr($value['description'],0,200);?>
							<? if (strlen($value['description']) > 200) { echo '...'; } ?>
						</div>
						<div>
							<span class="list_detail_label"><?=$this->lang->line('edit_event_location_lbl');?>: </span>
							<span class="list_detail_value"><?=$value['location'].', '.$value['address'].', '.$value['city'].' '.$value['zip_code'];?></span>
						</div>
						<div class="event_entry_privacy">
							<?=ucwords($value['privacy']);?> <?=$this->lang->line('edit_event_event_lexicon');?>
						</div>
						<div>
							<div><?=$response[$value['event_id']]?></div>
						</div>
						<div class="event_entry_options">
							<div class="button_row">
								<? echo 'Are you attending? '; ?> 
								<div class="event_button"><a href="/response/<? echo $value['page_id'].'/'.$value['event_id']?>/yes"><?=$this->lang->line('edit_event_yes_lexicon');?></a></div> 
								<div class="event_button"><a href="/response/<? echo $value['page_id'].'/'.$value['event_id']?>/no"><?=$this->lang->line('edit_event_no_lexicon');?></a></div>
							</div>
							<div class="button_row">
								<? if($owner_priv == 1 || $admin_priv == 1){?>
									
									<div class="event_button"><a href="/del_event/<? echo $value['page_id'].'/'.$value['event_id'];?>"><?=$this->lang->line('edit_event_cancel_btn');?></a></div>
									<div class="event_button"><a href="/edit_event/<? echo $value['page_id'].'/'.$value['event_id'];?>"><?=$this->lang->line('edit_event_edit_btn');?></a></div>
									<div class="event_button"><a href="#" class="ft-dropdown" rel="more_invitees_<?=$value['event_id']?>"><?=$this->lang->line('edit_event_invite_btn');?></a></div>
								<? }?>
								<? if($this->uri->segment(3) == 0)
								{ ?>
									<div class="event_button">
										<a href="/events/<? echo $value['page_id'].'/'.$value['event_id'];?>"><?=$this->lang->line('edit_event_event_details');?></a>
									</div>
								<? }?>
							</div>
						</div>
					</div>
					<div class="picture inlinediv">
						<? if ($value['img'] !== '') { ?>
							<img class="event_image_list" src="<?=s3_url()?>pages/<?=$value['page_id'];?>/events/<?=$value['img'];?>"/>
						<? } else { ?>
							<img class="event_image_list" src="/images/defaultEvent.png"/>
						<? }?>
					</div>
				</div>
				<? $eid = $value['event_id']; ?>
			<? } ?>
		<? } ?>
	<? } ?>

	<? if($this->uri->segment(3) == 0)
    { ?>
		<? if($owner_priv == 1 || $admin_priv == 1) { ?>
			<div class="event_create">
				<button name="new_event"  id="new_event_btn"><?=$this->lang->line('edit_event_create_event');?></button>
			</div>
			<div id="new_event_form" style="display: <? if(validation_errors()){echo 'block'; }else{ echo 'none'; }?>;">
				<h2><?=$this->lang->line('edit_event_new_event_heading');?></h2>
			
				<ul>
					<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
					<? echo form_open_multipart('new_event/'.$this->uri->segment(2)); ?>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_title_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('event_name').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_picture_lbl').'</div>';
						echo '<div class="form_field page_contents_form"><input type="file" name="userfile" size="20" />'; ?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_start_date_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('start_time', set_value('start_time', date("Y-m-d H:i:s")), 'id="start_time" class="datepicker"').'</div>'; ?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_end_time_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('end_time', set_value('end_time', date("Y-m-d H:i:s")), 'id="end_time" class="datepicker"').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_location_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('location').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_address_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('address').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_city_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('city').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_zip_code_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.Form_Helper::form_input('zip_code').'</div>';?>
					</li>
					<li>
						<? echo '<div class="form_label">'.$this->lang->line('edit_event_details_lbl').'</div>';
						echo '<div class="form_field page_contents_form">'.form_textarea('description').'</div>'; ?>
					</li>
					<li>
						<div class="form_label"><?=$this->lang->line('edit_event_event_type_lbl');?>: </div>
						<div class="form_field">
							<? $js_1 = 'onClick = "return changeItem(0)"';
							   $js_2 = 'onClick = "return changeItem(1)"'; ?>
							<div><? echo '<div class="detail_field">'.form_radio('privacy', 'public', TRUE, $js_1); ?><?=$this->lang->line('edit_event_public_type');?></div>
							<div><? echo '<div class="detail_field">'.form_radio('privacy', 'private', FALSE, $js_2); ?><?=$this->lang->line('edit_event_private_type');?></div>
						</div>
					</li>
					<li><a id="invite_btn" href="#" class="ft-dropdown" rel="invitees" style="display: none;"><?=$this->lang->line('edit_event_invite_ppl_btn');?></a></li>
					<ul id="invitees">
						<li><?=$this->lang->line('edit_event_invite_lbl');?></li>
						<? foreach ($page_users as $key=>$value){?>
							<li><? echo form_checkbox('check_guests[]', $value['user_id']); ?> <a href=/profile/<?=$value['uri_name']?>/<? echo $value['user_id'].'>'.$value['first_name'].' '.$value['last_name'];?>></a></li>
						<? }?>
					</ul>
					<li><? echo form_checkbox('show_attendees', '1'); ?><?=$this->lang->line('edit_event_attendees_lbl');?></li>
					<li><? echo form_checkbox('send_notifications', '1'); ?><?=$this->lang->line('edit_event_notifications_lbl');?></li>
					<li><? echo form_submit('submit', $this->lang->line('edit_event_submit_btn')); ?></li>
					<? echo form_close(); ?>
				</ul>
			</div>
		<? }?>
	<? }?>
<? if ($display_type === 'show_event_details') { echo '</div></div>'; }; ?>
</div>

<script type="text/javascript">
$(function() {
	$('button#new_event_btn').live('click', function() {
		$('#new_event_form').show('blind');
		$(this).hide();
	});

});

</script>

<script language="javascript">

function changeItem(i)
{
	if(i == 1)
	{
		document.getElementById("invite_btn").style.display = "block";
	}
	else
	{
		document.getElementById("invite_btn").style.display = "none";
	}
}

</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/event/events.php ) -->' . "\n";
} ?>
