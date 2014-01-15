<? if (@$hide_header !== '1') { ?>
<div id="header" class="header_basic">
	<div class="container_24" id="login_main">
		<div class="grid_6">
			<a href="/"><div id="homeLink"></div></a>
		</div>
		<div class="grid_18" id="login_entry">
			<? if (isset($include_login) && $include_login) { ?>
				<? if(ENVIRONMENT != 'production'){ ?>
					<div id="login_button_container">
						<a id="fb_login_button"><div class="authentication-text"></div></a>
						
						<a id="twitter_login_button" href="/twitter/login" ><div class="authentication-text"></div></a>
					</div>
				<? } ?>
				<div id="login" >&nbsp;&nbsp;
					<?=Form_Helper::open('login')?>
					<div id="login_input">
						<?=Form_Helper::input('email', '', array('placeholder' => $this->lang->line('email'), 'id' => 'login_user')); ?>
						<?=Form_Helper::password('password', '', array('placeholder' => $this->lang->line('includes_views_pass_placeholder'), 'id' => 'login_pass', 'class' => 'password')); ?>
						<?=Form_Helper::submit('submit', $this->lang->line('includes_views_login_submit'), array('class'=>"login blue_bg"))?>
					</div>
					<div id="login_remember">
						<span class="header_error"><?=$this->session->flashdata('error_msg')?></span>
						<span class="header_error"><?=$this->session->flashdata('error_fb_msg')?></span>
						<span class="header_error"><?=$this->session->flashdata('error_twitter_msg')?></span>
						<?= Html_helper::anchor('/forgetpassword',$this->lang->line('includes_views_forgot_pass_anchor')) ?>
						<input type="checkbox" name="remember" id="remember" checked="checked" /><?=$this->lang->line('includes_views_remember_lbl');?>
					</div>
					<?=Form_Helper::close()?>
					<? // <div><a href="/signup">Register</a></div>  ?>
				</div>
			<? } ?>
		</div>
	</div>
</div>
<? } ?> 