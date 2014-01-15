<? $this->lang->load('signup/forget_password', LANGUAGE); ?>
<div id="container" class="container forgotten_pass">
	<?php if ($user) { ?>
		<div class="row">
			<div class="passwordContainer resetPasswordContainer resetPasswordStep3 offset7 span10">
				<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>
				<div class="reset_form">
						<?=Form_Helper::open('')?>
						<div class="form_row">
							<?=Form_Helper::password('new_password','',array('style'=>"", 'placeholder'=>$this->lang->line('forget_password_new_email_lbl')))?>
						</div>
						<div class="form_row">
							<?=Form_Helper::password('new_password_confirm','',array('style'=>"", 'placeholder'=>$this->lang->line('forget_password_retype_email_lbl')))?>
						</div>
						<div class="clear"></div>
						<?=$this->session->flashdata('error_msg');?>
						<div class="reset_text" style="text-align:right; width:110px; margin-right:5px;"></div>
						<div>
							<?=Form_Helper::submit('resetpassword', $this->lang->line('forget_password_resetpassword_title'), array('class'=>"reset_button blueButton"))?>
						</div>
					<?=Form_Helper::close()?>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="row">
			<div class="passwordContainer resetPasswordContainer offset7 span10">
				<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>				
				<p class="error"><?=$this->lang->line('forget_password_reset_link_expired_or_invalid')?></p>		
			</div>
		</div>
	<?php } ?>
</div> 