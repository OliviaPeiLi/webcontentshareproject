<div id="inviteContainer">

	<div id="invite_title">
		<h1>Invite Your Friends to Fandrop</h1>
	</div>

	<div id="invite_body">
		<div id="inviteTop">
			<div class="inviteTop_text">Want to earn rewards for inviting your friends to Fandrop? Send them this handy referral URL and earn points once they sign up.</div>
			<? $signup_url = in_array($this->user->info, array('catathon','stanford','growthathon')) ? $this->user->info : 'signup'; ?>
			<textarea id="inviteTop_textarea"><?=Url_helper::base_url().$signup_url.'?ref='.$this->user->uri_name?></textarea>
		</div>
		<div id="inviteLeft" class="inlinediv">
			<ul>
				<li>
					<a href="" class="emailInvite <?=$type=='email' ? 'selected' : ''?>">
						<span class="inviterIcon"><span class="invitesFavicon" alt="Email Favicon"></span>Email</span>
					</a>
				</li>
				<li>
					<a href="" class="facebookInvite <?=$type=='facebook' ? 'selected' : ''?>">
						<span class="inviterIcon"><span class="invitesFavicon" alt="Facebook Favicon"></span>Facebook</span>
					</a>
				</li>
				<li>
					<a href="" class="gmailInvite <?=$type=='gmail' ? 'selected' : ''?>">
						<span class="inviterIcon"><span class="invitesFavicon" alt="Gmail Favicon"></span>Gmail</span>
					</a>
				</li>
				<li>
					<a href="" class="yahooInvite <?=$type=='yahoo' ? 'selected' : ''?>">
						<span class="inviterIcon"><span class="invitesFavicon" alt="Yahoo! Favicon"></span>Yahoo!</span>
					</a>
				</li>
			</ul>
		</div>
		<div id="sectionRightLoader">
			<?=Html_helper::img("loading_icons/100x100.gif", array('width'=>"100", 'height'=>"100"))?>
			<span>Loading contacts...</span>
		</div>
		<div class="error gmail gmail_error" style="display:none">
			<p>We couldn't retrieve your Gmail contacts because you haven't connected Gmail and Fandrop. Click the button to temporarily connect Gmail.</p>
			<p>We will never send out any email messages without asking.</p>
		</div>
		<div class="error gmail yahoo_error" style="display:none">
			<p>We couldn't retrieve your Yahoo contacts because you haven't connected Yahoo and Fandrop. Click the button to temporarily connect Yahoo.</p>
			<p>We will never send out any email messages without asking.</p>
		</div>		
		<div id="inviteRight" class="inlinediv">
			<?=Modules::run('invite/invite/invite_'.$type)?>
		</div>
		<!--
		<div id="inviteRight_fblogin_fail" class="inlinediv">
			<span>Facebook login fail due to authority.</span>
		</div>
		-->


		
	</div>
</div>

<script type="text/javascript">
	php.invite_type = '<?=$type?>';
	php.auth_gmail = '<?=(bool) $this->session->userdata('access_token_gmail')?>';
	php.auth_yahoo = '<?=(bool) $this->session->userdata('yahoo_data')?>';
	
	//To investigate
	// <?=$this->user->avatar?>
	php.picture = '<?=Html_helper::img($this->user->avatar_73, array())?>';
	php.fb_invite_description = "<?=$this->user->first_name?> is on Fandrop, a place to collect and share the best discoveries on the web. Join Fandrop to see <?=$this->user->first_name?>'s stories and start dropping!";
	php.fb_invite_message = "(<?=$this->user->first_name?>) is on Fandrop. Join to see <?=$this->user->gender=='m' ? 'his':'her'?> stories";
	php.fb_invite_title = "Join me on Fandrop!";
	php.basepath = '<?=BASEPATH.'signup?a=fb&b=b87jgzfke5'?>';
	php.user_id = '<?=$this->session->userdata('id')?>';
	php.username = '<?=$this->user->uri_name?>';
</script>
<?=Html_helper::requireJS(array("profile/invite"))?>
