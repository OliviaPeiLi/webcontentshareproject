<? $this->lang->load('invite/profile', LANGUAGE); ?>
<div id="invite_popup" style="display:none">
	<div class="modal-header1" style="display: none">
		<button class="close_popup new_close1" data-dismiss="modal">​</button>
	</div>
	<div class="modal-body">
	<?=Form_Helper::open('/invite_email',
		array('id' => 'invite_friends', 'rel' => 'ajaxForm', 'success'=>$this->lang->line('invite_einvite_form_success_msg')),
		array('email[]' => '')
	)?>
			<div id="friend_name"></div>
			<div id="friend_email"></div>
			<textarea name="message" id="invite_friends_comment" placeholder="<?=$this->lang->line('invite_message_label');?>"></textarea>
			<div class="bottom_row">
				<?=Form_Helper::submit('send_invite', $this->lang->line('invite_invite_lexicon'), array('class'=>"actionButton"))?>
			</div>
	<?=Form_Helper::close()?>
	</div>
</div>