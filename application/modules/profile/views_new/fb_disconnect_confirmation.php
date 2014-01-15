<div id="disconnect_fb_confirmation" style="display:none">
	<div class="modal-body">
		<div id="fb_disconnect_warning_container">
			<span class="warning_message_icon"></span>
			<strong><?=$this->lang->line('fb_disconnect_warning_message');?></strong>
		</div>
		<div class="bottom_row">
		<a href="/disconnect_fb" class="blue_bg confirm_yes" rel="ajaxButton"><?=$this->lang->line('fb_warning_confirm_yes');?></a>
		<a class="blue_bg confirm_no" data-dismiss="modal"><?=$this->lang->line('fb_warning_cancel');?></a>
		</div>
	</div>
</div>

