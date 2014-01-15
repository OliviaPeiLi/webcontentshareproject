<? $this->lang->load('invite/profile', LANGUAGE); ?>
<?=Form_Helper::open('/invite_email', array('id'=>'email_invite_friends', 'class'=>'error', 'rel'=>'ajaxForm', 'success'=>$this->lang->line('invite_form_success_msg')));?>
	<? for($i=1;$i<6;$i++) { ?>
	    <div class="form_row">
	    	<label>
	    		<?=$this->lang->line('invite_enter_email_label');?>
		    	<span class="error"><span class="error_contents"></span></span>
		    	<span class="valid"><span class="valid_contents"></span><?=$this->lang->line('invite_enter_email_valid_msg');?></span>
		    	<span class="success"><span class="success_contents"></span><?=$this->lang->line('invite_enter_email_success_msg');?></span>   
	    	</label>
			<?=Form_Helper::input('email['.$i.']', @$messages[$i]['email'], array(
					'class'=>"inviteField_email", 
					'data-validate'=>'invitedemail', 
					'data-error-email'=>$this->lang->line('invite_email_invalid_err'), 
					'data-error-invitedemail'=>$this->lang->line('invite_email_invited_err'), 
					'placeholder'=>sprintf($this->lang->line('invite_email_placeholder_tpl'), $i)
			))?>
		</div>
	<? } ?>
	<div class="form_row">
		<?=Form_Helper::textarea('message', '', array('class'=>"inviteMessage", 'placeholder'=>$this->lang->line('invite_message_placeholder')))?>
	</div>
	<div class="form_row">
		<?=Form_Helper::submit('submit', $this->lang->line('invite_invite_email_submit'), array('class'=>"inviteField_button blueButton", 'id'=>"submit_invites"))?>
	</div>
<?=Form_Helper::close()?>