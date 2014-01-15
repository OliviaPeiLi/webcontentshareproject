<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div class="clipboard-popup external_login" id="login">
	<div class="clipboard-popup-header"></div> 
	<a href="" class="clipboard-popup-close"></a>
	<div class="clipboard-popup-content">
		<div id="login_logoContainer">
			<span id="login_logoImage"></span>
		</div>
		<?=Form_Helper::open('bookmarklet/external_login', array('id'=>'bookmarklet_login_form'))?>
			<span class="loginLeft">
				<div class="form_row external-login">
					<a href="javascript:;" id="facebook_login_button_header">
						<span></span>
					</a>			
				</div>
				<div class="form_row external-login">
					<a href="" id="provider_twitter_link">
						<span></span>
					</a>				
				</div>
			</span>
			<span class="loginRight">
				<span class="emailPassword">
					<?=Form_Helper::validation_errors()?>
					<div class="error"><i><b style="color:red;"><?=$message?></b></i></div>
					<div class="form_row">
						<?=Form_Helper::input('email', '', array('placeholder'=>$this->lang->line('email'), 'id'=>"login_email"))?>
					</div>
					<div class="form_row">
						<?=Form_Helper::password('password', '', array('placeholder'=>$this->lang->line('password'), 'id'=>"login_password"))?>
					</div>
					<div class="form_row">
						<a href="/forgotpassword" target="_blank" class="forgotPass"><?=$this->lang->line('forgot_password');?>?</a>
					</div>
				</span>
				<span class="loginButton">
					<div class="form_row login">
						<?php echo Form_Helper::submit('submit', $this->lang->line('login'), array('id'=>"login_submit", 'class'=>"blue_btn"))?>
					</div>
				</span>
			</span>			
		<?=Form_Helper::close()?>
	</div>
</div>
<?=Html_helper::requireJS(array('bookmarklet/login'))?>