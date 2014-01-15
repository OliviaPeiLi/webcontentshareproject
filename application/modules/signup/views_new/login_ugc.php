<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="login-popup" style="display:none">
	<div class="modal-body">
		<div class="logSign_top">
			<a href="" id="fb-login" class="logSign_button"><span class="ico"></span><span class="logSign_buttonText"><?=$this->lang->line('signup_login_with_label');?> Facebook</span><span class="loadingElement"></span></a>
			<a href="" id="twt-login" class="logSign_button"><span class="ico"></span><span class="logSign_buttonText"><?=$this->lang->line('signup_login_with_label');?> Twitter</span><span class="loadingElement"></span></a>
		</div>
		<div class="logSign_mid">
			<?=Form_Helper::open('/signin', array('rel'=>'ajaxForm','class'=>'public','data-error'=>'popup'), array('redirect_url'=>Url_helper::current_url()))?>
				<span class="error"></span>
				<div class="form_row">
					<input type="text" name="email" value="" placeholder="<?=$this->lang->line('email');?>" class="input_placeholder_enh"/>
				</div>
				<div class="form_row">
					<input type="password" name="password" value="" placeholder="<?=$this->lang->line('password');?>" class="input_placeholder_enh"/>
				</div>
				<div class="form_row">
					<label>
						<input type="checkbox" name="remember" value="1" checked="checked"/>
						<span class="checkText"><?=$this->lang->line('signup_remember_me_label');?></span>
					</label>
				</div>
				<div class="form_row">
					<input class="blueButton logSign_submitButton" type="submit" name="login" value="<?=$this->lang->line('login');?>"/>
				</div>
			<?=Form_Helper::close()?>
		</div>
		<div class="logSign_bottom">
			<a href="#signup-popup" class="logSign_bottomButton" title="<?=$this->lang->line('signup');?>" rel="popup"><?=$this->lang->line('signup');?></a><a href="#forgot-popup" class="logSign_bottomButton" title="<?=$this->lang->line('forgot_password');?>" rel="popup"><?=$this->lang->line('forgot_password');?></a>
		</div>
	</div>
	<?=Html_helper::requireJS(array('signup/login_ugc'))?>
</div>