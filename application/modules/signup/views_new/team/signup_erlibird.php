<?php if ($this->input->get('b')=='fbinvite' && strpos(Url_helper::base_url(), 'public.fandrop.com') !== false) {?>
<script type="text/javascript"><?php //RR facebook some shit override?>
	window.location.href = window.location.href.replace('public.','');
</script>
<?php } ?>
<? $this->lang->load('signup/signup', LANGUAGE); ?>
<div id="login_wrapper" class="container_24">
	<div id="erlibird" class="signup_container signup_main">
		<div class="signup_content">
			<div class="erlibirdTop">
				<h1 id="" class="inlinediv">Hi there Erlibird!</h1>
			</div>
			<div class="erlibirdBottom">
				<div class="erlibird_bottomLeft">
					<h2 id="signup_title_step1" class="inlinediv">If you can collect the coolest experiences on the web and drop them all into one spot, you get Fandrop. Fandrop is the best place to easily discover, collect, and share with others your favorite digital content.</h2>
					<span id="error_fb" style="display:none"><i><?=$this->lang->line('signup_fb_already_assoc');?></i></span>
					
					<div id="signup_social_wrapper">
						<div class="authentication-header">
							<a href="" id="facebook_login_button_old">
								<span></span>
							</a>
						</div>
						<button id="signup_submit_basic" class="big_button inactive_bg" style="display:none">Next</button>
					</div>
				</div>
				
				<object class="show_animation_video animation_video_ff" id="animation_video" width="440" height="248">
					<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<param name="wmode" value="opaque"></param>
					<embed src="https://www.youtube.com/v/-5iQOQJ2qpI?autoplay=0&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
				</object>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	php.redirect_url = '<?=urlencode($this->input->get('redirect_url',true, '/'))?>';
</script>
<?=Html_helper::requireJS(array('signup/signup'));?>
 
