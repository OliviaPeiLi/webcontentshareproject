<div id="invite_popup" style="display:none">
	<div class="modal-header1">
		<button class="close_popup new_close1" data-dismiss="modal">​</button>
	</div>
	<div class="modal-body">
	<?=Form_Helper::open('/invite_email',
		array('id' => 'invite_friends', 'rel' => 'ajaxForm', 'success'=>'Invite sent successfully'),
		array('email[]' => '')
	)?>
			<div id="friend_name"></div>
			<div id="friend_email"></div>
			<textarea name="message" id="invite_friends_comment" placeholder="Message (Optional)"></textarea>
			<div class="bottom_row">
				<?=Form_Helper::submit('send_invite','Invite', array('class'=>"blue_bg"))?>
			</div>
	<?=Form_Helper::close()?>
	</div>
</div>