<? $this->lang->load('signup/signup', LANGUAGE);?>
<div id="login_wrapper" class="container_24">
	<div class="signup_container login">
			<div id="external_login">
				<div id="signup_social_wrapper">
				    <div class="authentication-header">
						<a id="facebook_login_button_header" href="javascript:;"><span></span></a>
				    </div>
				    <div class="clear"></div>
				    <div class="authentication-header">
						<a href="" id="provider_twitter_link"><span></span></a>
				    </div>	    
				</div>
			</div><!-- End #external_login -->
	
			<div id="email_login">
				<div id="login" >
					<?=Form_Helper::open('/signin', array('id'=>'login_form','asd'=>'asd'), array(
							'redirect_url' => $this->input->get('redirect_url')
						))?>
						<div id="login_input">
							<div id="login_field_container">								
								<?=Form_Helper::input('email', Form_Helper::set_value('email'), array('placeholder' => $this->lang->line('email'), 'id' => 'login_user')); ?>
								<?=Form_Helper::password('password', '', array('placeholder' => $this->lang->line('signup_form_pass_placeholder'), 'id' => 'login_pass', 'class' => 'password')); ?>
								<?=Form_Helper::form_error('email')?>
								<?=Form_Helper::form_error('password')?>
								<div class="error">
									<?=isset($login_error) ? $login_error : $this->session->flashdata('login_error') ?>
								</div>
							</div>
							<input type="submit" id="submitLogin" class="login lightBlue_bg" value="<?=$this->lang->line('signup_landing_login_link_btn');?>"/>
						</div>
						<div id="login_remember">	
							<input type="checkbox" name="remember" id="remember" checked="checked" /><?=$this->lang->line('signup_form_remember_checkbox');?>
						</div>
						<div id="login_error" class="error">
							<?=$this->session->flashdata('login_error')?>
						</div>
					<?=Form_Helper::close()?>
					<div id="login_links"><a href="/signup">Register</a><span id="login_divider">&middot;</span><?= Html_helper::anchor('/forgetpassword', $this->lang->line('signup_forgot_pass_lexicon')) ?></div>
				</div>
			</div><!-- end #"email_login" -->
		</div>
	<div id="facebook_error_msg" class="modal" style="display: none">
		<div class="success-msg">
			<div class="text_loading"></div>
		</div>
	</div>		
</div>
<script type="text/javascript">
	php.redirect_url = '<?=str_replace("'", "\'", $this->input->get('redirect_url',true, '/'))?>';
</script>
<?=Html_helper::requireJS(array('signup/login'))?>
