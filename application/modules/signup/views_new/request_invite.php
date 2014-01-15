<div id="login_wrapper" class="container_24">
	<div class="signup_container request_invite">
		<div class="signup_content">
			<h3>Request an invite to join Fandrop. Or <a id="request_invite_login" href="<?=$login_url?>">Login</a> to your account.</h3>
			<?=Form_Helper::open('/request_invite');?>
				<div class="form_row">
					<?=Form_Helper::form_error('email')?>
					<?=Form_Helper::input('email', '', array('placeholder' => 'Email Address', 'class' => 'input_box')); ?>
					<?=Form_Helper::submit('submit', 'Request an Invite', array('class'=>"blue-btn"))?>
				</div>
			<?=Form_Helper::close()?>
			<? if($this->is_mod_enabled('fb_invite_request')){ ?>
			<div id="signup_social_wrapper" class="request_invite_page">
				<div class="authentication-header">
					<a href="" id="facebook_login_button_old">
						<span></span>
					</a>
				</div>
			</div>	
			<? } ?>
		</div>
	</div>
</div>
<?=Html_helper::requireJS(array('signup/request_invite'))?>