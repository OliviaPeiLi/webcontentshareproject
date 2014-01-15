<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/event/edit_event.php ) --> ' . "\n";
	} ?>
<div class="container_24" id="main">
<? $this->lang->load('edit_event/edit_event_views', LANGUAGE); ?>
    <div class="event_top edit_event_top">
        <h2><?=$this->lang->line('edit_event_main_heading');?></h2>
    </div>
    <div class="edit_event_main">
	<? foreach($event as $key => $value){?>
		<div class="grid_13 alpha" id="edit_event_form">
			<ul class="edit_form">
				<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
				<li>
                	<h6><?=$value['event_name']?></h6>
                </li>
                <li class="last">
					<? $attrs = array('id' => 'event_form'); ?>
					<? echo form_open_multipart('update_event/'.$this->uri->segment(2).'/'.$this->uri->segment(3), $attrs); ?>
						<div>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_title_lbl');?></li>
								<li><?=Form_Helper::form_input('event_name', set_value('event_name', $value['event_name']), 'class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_picture_lbl');?></li>
								<li class="image_select"><? echo '<input type="file" name="userfile" id="event_img" class="blue_bg img_upload_for_preview" obj_type="event" obj_id="'.$this->uri->segment(3).'" size="20" />'; ?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_start_date_lbl');?></li>
								<li><?=Form_Helper::form_input('start_time', set_value('start_time', $value['start_time']), 'id="start_time" class="datepicker text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_end_time_lbl');?></li>
								<li><?=Form_Helper::form_input('end_time', set_value('end_time', $value['end_time']), 'id="end_time" class="datepicker text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_location_lbl');?></li>
								<li><?=Form_Helper::form_input('location', set_value('location', $value['location']), 'class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_address_lbl');?></li>
								<li><?=Form_Helper::form_input('address', set_value('address', $value['address']), 'class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_city_lbl');?></li>
								<li><?=Form_Helper::form_input('city', set_value('city', $value['city']), 'class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_zip_code_lbl');?></li>
								<li><?=Form_Helper::form_input('zip_code', set_value('zip_code', $value['zip_code']), 'class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_details_lbl');?></li>
								<li><?=form_textarea('description', set_value('description', $value['description']), 'style="width: 360px" class="text_input"')?></li>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"><?=$this->lang->line('edit_event_event_type_lbl');?>: </li>
								<li>
								<? $js_1 = 'onClick = "return changeItem(0)"';
								   $js_2 = 'onClick = "return changeItem(1)"'; ?>
									<?=form_radio('privacy', 'public', ($value['privacy'] === 'public'), $js_1); ?><span><?=$this->lang->line('edit_event_public_type');?></span>
									<?=form_radio('privacy', 'private', ($value['privacy'] === 'private'), $js_2); ?><span><?=$this->lang->line('edit_event_private_type');?></span>
									<span id="invite_btn" style="display:<? if($value['privacy'] == 'private'){echo 'inline';}else{ echo 'none';}?>;"><a href="#" class="menu_caller ft-dropdown" rel="invitees"><?=$this->lang->line('edit_event_invite_ppl_btn');?></a></span>
								</li>
								

							</ul>
							<ul class="clear"></ul>
							<ul id="invitees" class="menu">
								<li><?=$this->lang->line('edit_event_invite_guests_lbl');?></li>
								<div class="clear"></div>
								<? foreach ($value['more'] as $k=>$item){	?>
									<li><? echo form_checkbox('check_guests[]', $item['user_id']); ?> <a href=/profile/<?=$item['uri_name']?>/<? echo $item['user_id'].'>'.$item['first_name'].' '.$item['last_name'];?>></a></li>
								<? }?>
							</ul>
							<ul class="clear"></ul>
							<ul>
								<li class="left"> </li>
								<li><? echo form_checkbox('show_attendees', '1', ($value['attendees'] === '1')); ?><?=$this->lang->line('edit_event_attendees_lbl');?></li>
							</ul>
							<ul>
								<li class="left"> </li>
								<li><? echo form_checkbox('send_notifications', '1'); ?><?=$this->lang->line('edit_event_notifications_lbl');?></li>
							</ul>
							<ul>
								<li class="left"><? echo form_hidden('img', $value['img']); ?></li>
								<li><? echo form_submit('submit', $this->lang->line('edit_event_submit_btn'), 'class="blue_bg"'); ?></li>
							</ul>
						</div>
					<? echo form_close(); ?>
				</li>
			</ul>
		</div>
		<div class="grid_11 omega">
			<div id="event_image_placeholder">
				<? if ($value['img'] !== '') { ?>
					<img id="previewField" class="event_image_display preview_img" src="<?=s3_url()?>pages/<?=$value['page_id'];?>/events/<?=$value['img'];?>" alt="Preview"/>
				<? } else { ?>
					<img id="previewField" class="event_image_display preview_img" src="/images/defaultEvent.png" alt="<?=$this->lang->line('edit_event_preview_lexicon');?>"/>
				<? }?>
				<dic class="clear">
			</div>
		</div>
	<? break; }?>
	</div>
	<div class="event_bot"></div>
</div>

<script type="text/javascript">
$(function() {
/*
	$('#new_event_submit').live('click', function () {
		alert($(this).closest('form').attr('id'));
		$(this).closest('form').submit();
		$('#img_form').submit();
		return false;
	});
	
	$('#event_image_placeholder').click(function(e) {
		if($('#img_form').css('display') === 'none') {
			$('#img_form').show();
		}
	});
	*/
});

</script>

<SCRIPT type="text/javascript">
/*
function preview(what){
	alert(what.value);
	document.getElementById("previewField").src=what.value;
}
*/
</SCRIPT>

<script language="javascript">

initFileUpload();

function changeItem(i)
{
	if(i == 1)
	{
		document.getElementById("invite_btn").style.display = "inline";
	}
	else
	{
		document.getElementById("invite_btn").style.display = "none";
	}
}

</script>
<!-- Some JS -->
<script type="text/javascript">
	var event_img = new Image();
	event_img.onload = function() {
		var img_w = this.width;
		var img_h = this.height;
		var comp_w = $('.event_image_display').width();
		var asp = img_h/img_w;
		//alert(img_w+'x'+img_h);
		if (img_h > img_w) {
			$('.event_image_display').css('width',comp_w+'px');
			$('.event_image_display').css('height',(comp_w*asp)+'px');
		}
	}
	event_img.src = "<?=s3_url()?>pages/<?=$value['page_id'];?>/events/<?=$value['img'];?>";
</script> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/event/edit_event.php ) -->' . "\n";
} ?>
