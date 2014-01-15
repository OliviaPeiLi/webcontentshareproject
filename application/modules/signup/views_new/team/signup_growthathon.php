<?php if ($this->input->get('b')=='fbinvite' && strpos(Url_helper::base_url(), 'public.fandrop.com') !== false) {?>
<script type="text/javascript"><?php //RR facebook some shit override?>
	window.location.href = window.location.href.replace('public.','');
</script>
<?php } ?>
<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="login_wrapper" class="container_24">
	<div class="signup_container signup_main">
		<div class="signup_content">
	
			<h2 id="signup_title_step1" class="inlinediv"><?=$this->lang->line('register_welcome');?></h2>
			<span id="error_fb" style="display:none"><i><?=$this->lang->line('signup_fb_already_assoc');?></i></span>
			<span id="error_twitter_old" style="display:none"><i><?=$this->lang->line('signup_tw_already_assoc');?></i></span>
			<span id="error_twitter" style="display:none"><i><?=$this->session->flashdata('login_msg')?></i></span>
	
			<div id="signup_social_wrapper">
				<div class="authentication-header">
					<a href="" id="facebook_login_button_old">
						<span></span>
					</a>
				</div>
				<? if($this->is_mod_enabled('email_signup')){ ?>
				<div class="auth_can">
					<?=$this->lang->line('signup_can_also');?>
					<a href="" id="provider_twitter_link">
						<span class="authentication-text"><?=$this->lang->line('signup_tw_lbl');?></span>
					</a>
				</div>
				<? } ?>
				<button id="signup_submit_basic" class="big_button inactive_bg" style="display:none">Next</button>
			</div>
			<? if($this->is_mod_enabled('email_signup')){ ?>
				<div id="signup_with_email">
					<?=$this->lang->line('signup_via_email_text')?>
					<a href="/signup/form<?=$_GET ? '?'.http_build_query($_GET) : ''?>"><?=$this->lang->line('sign_up_with')?><?=$this->lang->line('your')?> <?=$this->lang->line('email_address')?></a>.
				</div>
			<? } ?>
			<div id="account_already">
				<?=$this->lang->line('signup_have_an_account_msg');?> &nbsp;
				<a href="/signin<?=$_GET ? '?'.http_build_query($_GET) : ''?>"><?=$this->lang->line('signup_login_btn');?></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	php.redirect_url = '<?=urlencode($this->input->get('redirect_url',true, '/'))?>';
</script>
<?=Html_helper::requireJS(array('signup/signup'));?>
 