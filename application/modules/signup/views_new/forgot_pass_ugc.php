<? $this->lang->load('signup/forget_password', LANGUAGE); ?>
<div id="forgot-popup" style="display:none">
	<div class="modal-body">
		<div class="logSign_mid">
			<?=Form_Helper::open('/forgotpassword', array('rel'=>'ajaxForm', 'class'=>'public','data-error'=>'popup'))?>
				<div class="form_row">
					<?=Form_Helper::form_input('email', '', array(
						'id' => "reset_pass_input",
						'placeholder' => $this->lang->line('email'),
						'data-validate' => 'required|email',
						'data-error' => 'popup',
						'data-error-required' => $this->lang->line('forget_password_email_required_err'),
						'data-error-email' => $this->lang->line('forget_password_email_invalid_err')
					))?>
					<div class="error" style="display:none;"></div>
				</div>
				<div class="form_row">
					<?=Form_Helper::submit('forgetpassword', $this->lang->line('forget_password_resetpassword_submit'), array('class'=>"blueButton logSign_submitButton"))?>
				</div>
			<?=Form_Helper::close()?>
			<div class="resetPasswordStep2" style="display:none;">
				<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>
				<div class="reset_form">
				    <div class="reset_text reset_margin">
					<?=$this->lang->line('forget_password_check_email_msg');?>
				    </div>
				</div>
		    </div>
		</div>
		<div class="logSign_bottom">
			<a href="#login-popup" title="<?=$this->lang->line('login');?>" rel="popup" class="logSign_bottomButton"><?=$this->lang->line('login');?></a><a href="#signup-popup" title="<?=$this->lang->line('signup');?>" rel="popup" class="logSign_bottomButton"><?=$this->lang->line('signup');?></a>
		</div>
	</div>
	<?=Html_helper::requireJS(array("signup/forgot_pass_ugc"))?>
</div>