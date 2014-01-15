<? $this->lang->load('invite/profile', LANGUAGE); ?>
<div id="inviteContainer" class="container" style="margin-top: 90px">
	<div class="row">
		<div class="span24 inviteContainer_head">
			<h2><?=$this->lang->line('invite_friends_title');?></h2>
		</div>
	</div>
	<div class="row">
		<div class="span24 inviteContainer_top">
			<div class="inviteContainer_topText"><?=$this->lang->line('invite_friends_text');?></div>
			<? $signup_url = in_array($this->user->info, array('catathon','stanford','growthathon')) ? $this->user->info : 'signup'; ?>
			<textarea id="inviteTop_textarea"><?=Url_helper::base_url().$signup_url.'?ref='.$this->user->uri_name?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="span6 inviteMenusColumn">
			<a href="#emailInvite" class="<?=$type=='email' ? 'selected' : ''?> emailInvite inviteMenus_button">
				<span class="ico" alt="<?=$this->lang->line('email');?> Favicon"></span><span class="inviteMenus_buttonText"><?=$this->lang->line('email');?></span>
			</a>
			<a href="#facebookInvite" class="<?=$type=='facebook' ? 'selected' : ''?> facebookInvite inviteMenus_button">
				<span class="ico" alt="Facebook Favicon"></span><span class="inviteMenus_buttonText">Facebook</span>
			</a>
			<a href="#gmailInvite" class="<?=$type=='gmail' ? 'selected' : ''?> gmailInvite inviteMenus_button">
				<span class="ico" alt="Gmail Favicon"></span><span class="inviteMenus_buttonText">Gmail</span>
			</a>
			<a href="#yahooInvite" class="<?=$type=='yahoo' ? 'selected' : ''?> yahooInvite inviteMenus_button">
				<span class="ico" alt="Yahoo! Favicon"></span><span class="inviteMenus_buttonText">Yahoo!</span>
			</a>
		</div>
		
		<div class="span18 invitesColumn" id="inviteRight">
			<?=Modules::run('invite/invite/invite_'.$type)?>		
		</div>
		
		<div class="mainLoader" style="display:none">
			<?=Html_helper::img("loading_icons/100x100.gif", array('width'=>"100", 'height'=>"100"))?>
			<span><?=$this->lang->line('invite_loading_state');?></span>
		</div>
		<div class="error gmail_error" style="display:none">
			<p><?=$this->lang->line('invite_gmail_err_p1');?></p>
			<p><?=$this->lang->line('invite_gmail_err_p2');?></p>
		</div>
		<div class="error yahoo_error" style="display:none">
			<p><?=$this->lang->line('invite_yahoo_err_p1');?></p>
			<p><?=$this->lang->line('invite_yahoo_err_p2');?></p>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	php.invite_type = '<?=$type?>';
	php.auth_gmail = '<?=(bool) $this->session->userdata('access_token_gmail')?>';
	php.auth_yahoo = '<?=(bool) $this->session->userdata('yahoo_data')?>';
	
	//To investigate
	php.picture = '<?=$this->user->avatar_73?>';
	// php.picture = '<?=$this->user->avatar?>';
	php.fb_invite_description = "<?=sprintf($this->lang->line('invite_fb_invite_description_tpl'), $this->user->first_name, $this->user->first_name);?>";
	php.fb_invite_message = "<?=sprintf($this->lang->line('invite_fb_invite_message_tpl'), $this->user->first_name, ($this->user->gender=='m' ? 'his':'her'));?>";
	php.fb_invite_title = "<?=$this->lang->line('invite_join_me_lexicon');?>";
	php.basepath = '<?=BASEPATH.'signup?a=fb&b=b87jgzfke5'?>';
	php.user_id = '<?=$this->session->userdata('id')?>';
	php.username = '<?=$this->user->uri_name?>';
</script>
<?=Html_helper::requireJS(array("invite/invite"))?>
