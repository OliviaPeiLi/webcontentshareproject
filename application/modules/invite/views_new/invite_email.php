<div id="emailInviter" class="inviteContent inviteForm" style="display:block;">
	<ul id="invite_content_wrapper">
		<?=Form_Helper::open('/invite_email', array('id'=>'email_invite_friends', 'class'=>'error', 'rel'=>'ajaxForm', 'success'=>'Your friend has been successfully invited!') )?>
			<? for($i=1;$i<6;$i++) { ?>
			    <li class="invite_email_field form_row">
			    	<label>
			    		Enter your friend's email
				    	<span class="error"><span class="error_contents"></span></span>
				    	<span class="valid"><span class="valid_contents"></span>Valid Email</span>
				    	<span class="success"><span class="success_contents"></span>Invite Sent!</span>   
			    	</label>
					<?=Form_Helper::input('email['.$i.']', @$messages[$i]['email'], array('class'=>"inviteField_email", 'data-validate'=>'email|invitedemail', 'data-error-email'=>'Invalid Email', 'data-error-invitedemail'=>'The email was invited already', 'placeholder'=>"Email Address $i"))?>
				</li>
			<? } ?>
			<?=Form_Helper::textarea('message', '', array('class'=>"inviteMessage", 'placeholder'=>"Add a personal note (optional):"))?>
			<?=Form_Helper::submit('submit', 'Send Invites', array('class'=>"inviteField_button blue_bg blue_bg_tall", 'id'=>"submit_invites"))?>
		<?=Form_Helper::close()?>
	</ul>
</div>