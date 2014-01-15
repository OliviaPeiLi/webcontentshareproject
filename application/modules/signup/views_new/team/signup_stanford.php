<?php if ($this->input->get('b')=='fbinvite' && strpos(Url_helper::base_url(), 'public.fandrop.com') !== false) {?>
<script type="text/javascript"><?php //RR facebook some shit override?>
	window.location.href = window.location.href.replace('public.','');
</script>
<?php } ?>
<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="universitySignup_wrapper" class="stanfordU">
	<div id="universitySignup">
		<div class="signup_content">
			<div class="universitySignup_top">
				<div class="signupLogo"></div>
			</div>
			<div class="universitySignup_bottom">
				<div class="universitySignup_title">
					<div class="universitySignup_titleLine"></div>
					<span class="universitySignup_titleText">Drop Till You Drop: Stanford</span>
					<div class="universitySignup_titleLine"></div>
				</div>
				<div class="universitySignup_bodyText">Win an iPad Mini!  We're running a contest to get users to signup, get pageviews, and increase engagement on Fandrop.  Runner-ups will receive Amazon.com gift cards.</div>
				<div class="memeHolder">
					<ul>
						<li class="meme"><?=Html_helper::img("campaign_signup/stanford/memes/3sa0o0.jpg")?></li>
						<li class="meme"><?=Html_helper::img("campaign_signup/stanford/memes/cardinalDollars.jpg")?></li>
						<li class="meme"><?=Html_helper::img("campaign_signup/stanford/memes/bMine.png")?></li>
					</ul>
				</div>
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
