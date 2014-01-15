<? $this->lang->load('signup/forget_password', LANGUAGE); ?>
<div id="container" class="container_24 forgotten_pass">
	<?php if ($user) { ?>
		<div class="passwordContainer resetPasswordContainer resetPasswordStep3">
			<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>
			<div class="reset_form">
				
				<?=Form_Helper::open('')?>
					<div class="form_row">
						<?=Form_Helper::password('new_password','',array('style'=>"width:250px;", 'placeholder'=>$this->lang->line('forget_password_new_email_lbl')))?>
					</div>
					<div class="form_row">
						<?=Form_Helper::password('new_password_confirm','',array('style'=>"width:250px", 'placeholder'=>$this->lang->line('forget_password_retype_email_lbl')))?>
					</div>
					<div class="clear"></div>
					<?=$this->session->flashdata('error_msg');?>
					<div class="reset_text" style="text-align:right; width:110px; margin-right:5px;"></div>
					<div>
						<?=Form_Helper::submit('resetpassword', $this->lang->line('forget_password_resetpassword_title'), array('class'=>"reset_button lightBlue_bg"))?>
					</div>
				<?=Form_Helper::close()?>
			</div>
		</div>
	<?php } else { ?>
		<div class="passwordContainer resetPasswordContainer">
			<h3>Reset Password</h3>				
			<p class="error"><?=$this->lang->line('forget_password_reset_link_expired_or_invalid')?></p>		
	    </div>
	<?php } ?>
</div> 