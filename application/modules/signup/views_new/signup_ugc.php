<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="signup-popup" style="display:none">
	<div class="modal-body">
		<div class="logSign_top">
			<div class="logSign_topText"><?=$this->lang->line('signup_no_account_text');?></div>
			<a href="" id="fb-register" class="logSign_button"><span class="ico"></span><span class="logSign_buttonText"><?=$this->lang->line('signup_signup_with_label');?> Facebook</span><span class="loadingElement"></span></a>
			<a href="" id="twt-register" class="logSign_button"><span class="ico"></span><span class="logSign_buttonText"><?=$this->lang->line('signup_signup_with_label');?> Twitter</span><span class="loadingElement"></span></a>
		</div>
		<div class="logSign_mid">
		<?=Form_Helper::open('/signup/form', 
				array(
					'rel'=>'ajaxForm', 
					'class'=>'public',
					'success' => $this->lang->line('signup_success_text'),
					'data-progress' => $this->lang->line('signup_progress_text')
				),
				 array('redirect_url'=>'/','role'=>'')
		)?>
			<div class="form_row">
				<label class="logSign_signupField logSign_signup_userName">
					<?=Form_Helper::input('uri_name', Form_Helper::set_value('uri_name'), array(
							'class'=>"input_placeholder_enh",
							//'autofocus' => 'autofocus',
							'autocomplete' => 'off',
							'data-validate' => 'required|minlength|username',
							'minlength' => 6,
							'data-error-minlength' => $this->lang->line('signup_username_minlength_err'),
							'data-error-required' => $this->lang->line('signup_username_required_err'),
					))?>
					<span class="tmp_input_holder" style="display:none"><?=ucfirst($this->lang->line('username'));?></span>
					<span class="ico"></span>
				</label>
				<span class="error"></span>
				<span class="valid"><span class="valid_contents"></span><?=$this->lang->line('signup_username_valid_msg');?></span>
				<span class="field_tip"><?=$this->lang->line('signup_username_tip');?></span>
				<span class="loading"><?=$this->lang->line('signup_validating_lexicon');?></span>
			</div>
			<div class="form_row">									
				<label class="logSign_signupField logSign_signup_userEmail">
					<?=Form_Helper::input('email', Form_Helper::set_value('email'), array(
							'class'=>"input_placeholder_enh",
							'autocomplete' => 'off',
							'data-validate' => 'required|email|uniqemail',
							'data-error-required' => $this->lang->line('signup_email_required_err'),
							'data-error-email' => $this->lang->line('signup_email_invalid_err'),
							'data-error-uniqemail' => $this->lang->line('signup_email_unique_err')
					))?>
					<span class="tmp_input_holder" style="display:none"><?=ucwords($this->lang->line('email_address'));?></span>
					<span class="ico"></span>
				</label>
				<span class="error"></span>
				<span class="valid"><span class="valid_contents"></span><?=$this->lang->line('signup_email_valid_msg');?></span>
				<span class="field_tip"><?=$this->lang->line('signup_email_tip');?></span>
				<span class="loading"><?=$this->lang->line('signup_validating_lexicon');?></span>
			</div>
			<div class="form_row">				
				<label class="logSign_signupField logSign_signup_userPass">
					<?=Form_Helper::password('password', '', array(
							'class' => "input_placeholder_enh",
							'autocomplete' => 'off',
							'data-validate' => 'required|minlength|password',
							'minlength' => 6,
							'data-error-required' => $this->lang->line('signup_pass_required_err'),
							'data-error-minlength' => $this->lang->line('signup_pass_minlength_err'),
							'data-error-password' => $this->lang->line('signup_pass_obvious_err')
					))?>
					<span class="tmp_input_holder" style="display:none"><?=$this->lang->line('password');?></span>
					<span class="ico"></span>
					<div class="score" id="scorePass" style="display: block;">
						<span><b style="width: 0%;"></b></span>
					</div>
				</label>
				<span class="error"></span>
				<span class="valid"><span class="valid_contents"></span></span>
				<span class="field_tip"><?=$this->lang->line('signup_pass_tip');?></span>
				<script type="text/javascript">
					php.lang.password = {
						'perfect': "<?=$this->lang->line('signup_pass_perfect_msg');?>",
						'weak': "<?=$this->lang->line('signup_pass_weak_msg');?>",
						'ok': "<?=$this->lang->line('signup_pass_ok_msg');?>"
					}
				</script>			
			</div>
			<div class="form_row">				
				<label class="logSign_signupField logSign_signup_userFirst">
					<?=Form_Helper::input('first_name', Form_Helper::set_value('first_name'), array(
							'class'=>"input_placeholder_enh",
							"maxlength" => 30,
							"data-validate" => "required|maxlength|specialchars",
							"data-error-required" => $this->lang->line('signup_first_name_required_err'),
							"data-error-specialchars" => $this->lang->line('signup_first_name_spec_err'),
							"data-error-maxlength" => $this->lang->line('signup_first_name_maxlength_err')
					)); ?>
					<span class="tmp_input_holder" style="display:none"><?=ucwords($this->lang->line('first_name'));?></span>
					<span class="ico"></span>
				</label>
				<span class="error"></span>
				<span class="valid"><span class="valid_contents"></span><?=$this->lang->line('signup_first_name_valid_msg');?></span>
				<span class="field_tip"><?=$this->lang->line('signup_first_name_tip');?></span>
			</div>
			<div class="form_row">				
				<label class="logSign_signupField logSign_signup_userLast">
					<?= Form_Helper::input('last_name', Form_Helper::set_value('last_name'), array(
							'class' => "input_placeholder_enh",
							"maxlength" => 30, 
							"data-validate" => "maxlength|specialchars",
							"data-error-specialchars"=> $this->lang->line('signup_last_name_spec_err'),
							"data-error-maxlength" => $this->lang->line('signup_last_name_maxlength_err')
						))?>
					<span class="tmp_input_holder" style="display:none"><?=ucwords($this->lang->line('last_name'));?></span>
					<span class="ico"></span>
				</label>
				<span class="error"></span>
				<span class="valid"><span class="valid_contents"></span><?=$this->lang->line('signup_last_name_valid_msg');?></span>
				<span class="field_tip"><?=$this->lang->line('signup_last_name_tip');?></span>
				<span class="ico"></span>
			</div>
			<div class="form_row">				
				<input id="submit_signup" type="submit" name="submit" class="blueButton logSign_submitButton" value="<?=$this->lang->line('signup');?>">
			</div>
		<?=Form_Helper::close()?>
		</div>
		<div class="logSign_bottom">
			<?=$this->lang->line('signup_have_account_msg');?> <a href="#login-popup" rel="popup"><?=$this->lang->line('login');?></a> 
		</div>
	</div>
	<?=Html_helper::requireJS(array('signup/signup_ugc'))?>
</div>