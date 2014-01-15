<? $this->lang->load('signup/forget_password', LANGUAGE); ?>
<div id="container" class="container_24 forgotten_pass">
    <div class="passwordContainer resetPasswordContainer">
		<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>
		<p class="error"><?=isset($error)?$error:""?></p>
		<div class="reset_form">
		    <?=Form_Helper::form_open('', array('rel'=>'ajaxForm', 'class'=>'error public', 'id'=>'forgetpassword_check_form'));?>
				<div>
					<div class="form_row">
						<div class="error" style="display:none;"></div>
						<?=Form_Helper::form_input('email', '', array(
							'id' => "reset_pass_input",
							'placeholder' => $this->lang->line('forget_password_type_email_lbl'),
							'data-validate' => 'required|email',
							'data-error-required' => 'Email must not be empty',
							'data-error-email' => 'Invalid Email'
						))?>
					</div>
					<div class="form_row">
						<?=Form_Helper::form_submit('forgotpassword', $this->lang->line('forget_password_resetpassword_submit'), 'class="lightBlue_bg"')?>
					</div>
				</div>
		    <?=form_close(); ?>
		</div>
    </div>
    <div class="passwordContainer resetPasswordStep2" style="display:none;">
		<h3><?=$this->lang->line('forget_password_resetpassword_title');?></h3>
		<div class="reset_form">
		    <div class="reset_text reset_margin">
			<?=$this->lang->line('forget_password_check_email_msg');?>
		    </div>
		</div>
    </div>
</div> 
<?=Html_helper::requireJS(array("forget_password/forget_password"))?>
