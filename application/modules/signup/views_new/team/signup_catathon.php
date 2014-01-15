<?php if ($this->input->get('b')=='fbinvite' && strpos(Url_helper::base_url(), 'public.fandrop.com') !== false) {?>
<script type="text/javascript"><?php //RR facebook some shit override?>
	window.location.href = window.location.href.replace('public.','');
</script>
<?php } ?>
<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="universitySignup_wrapper" class="nyanDrop">
	<div id="universitySignup">
		<div class="signup_content">
			<div class="universitySignup_top">
				<div class="signupLogo"></div>
			</div>
			<div class="universitySignup_bottom">
				<div class="universitySignup_title">
					<div class="universitySignup_titleLine"></div>
					<span class="universitySignup_titleText"></span>
					<div class="universitySignup_titleLine"></div>
				</div>
				<div class="universitySignup_bodyText">Win up to $500!  We're running a contest to get users to signup, get pageviews, and increase engagement on Fandrop.  Make your way to the top of our leaderboard and you just might win our grand prize!</div>
				<div class="universitySignup_prizeText"><span>1st Place - $500</span><span>2nd Place - $200</span><span>3rd Place - $100</span></div>
				<div class="memeHolder">
					<ul>
						<li class="meme"><?=Html_helper::img("campaign_signup/catathon/step_graphics/cat-a-thon_01.png")?></li>
						<li class="meme"><?=Html_helper::img("campaign_signup/catathon/step_graphics/cat-a-thon_02.png")?></li>
						<li class="meme"><?=Html_helper::img("campaign_signup/catathon/step_graphics/cat-a-thon_03.png")?></li>
					</ul>					
				</div>
				<a href="http://michael.fantoon.com/leaderboard/catathon" class="signupLeaderboard_link">Catathon Leaderboard</a>
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
					<? if($this->is_mod_enabled('email_signup')){ ?>
						<div id="signup_with_email">
							<?=$this->lang->line('signup_via_email_text')?>
							<a href="/signup/form<?=$_GET ? '?'.http_build_query($_GET) : ''?>"><?=$this->lang->line('your')?> <?=$this->lang->line('email_address')?></a>.
						</div>
					<? } ?>
					<div id="account_already">
					<?=$this->lang->line('signup_have_an_account_msg');?> &nbsp;
						<a href="/signin<?=$_GET ? '?'.http_build_query($_GET) : ''?>"><?=$this->lang->line('signup_login_btn');?></a>
					</div>
					<iframe class="universitySignupFB_facepile" src="http://www.facebook.com/plugins/facepile.php?size=small&app_id=<?=$this->config->item('fb_app_key')?>" scrolling="no" frameborder="0" allowTransparency="true" width="552" max_rows="1"></iframe>
				</div>
			</div>
			<div class="universitySignup_watermark"></div>
		</div>
	</div>	
	
<? //------------------- #HASH SEARCH RESULTS --------------------------// ?>
<div id="main" class="search_results" data-query="<?=$this->input->get('q',true)?>">
	
	<? /* if(!isset($results_count) || $results_count == 0){ ?>
		<div class="no_results"><?=$this->lang->line('search_views_no_results_msg');?></div>
	<? } else { */ ?>
		<div id="show_newsfeeds">
			<div id="folder_contents"  class="messagesContainer">
				<?=$this->load->view('newsfeed/newsfeed_general',array('newsfeeds'=>$newsfeeds,'view'=>'postcard'))?>
			</div>
		</div>
	<? // } ?>			
</div>
<? //------------------- ======================== --------------------------// ?>


	
</div>

<?=$this->load->view('signup/signup_team_newsfeed');?>

<script type="text/javascript">
	php.redirect_url = '<?=urlencode($this->input->get('redirect_url',true, '/'))?>';
</script>
<?=Html_helper::requireJS(array('signup/signup'));?>
