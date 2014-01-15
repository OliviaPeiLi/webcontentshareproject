<? if (@$hide_header !== '1') { ?>
	<div id="fandrop_intro_video_wrap" class="js-video_init video_init modal hide" style="display:none;width:854px;height:526px;max-width:1280px;max-height:720px;background:#000;">
		<iframe id="fandrop_intro_video" data-videourl="http://www.youtube.com/embed/mJG6utDahrY?wmode=opaque&amp;hd=1&amp;feature=player_embedded" width="854" height="510" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
	</div>
	<? $my_following_id = null; ?>
    <div id="headerLanding">
    	<? //TOP PART OF HEADER ?>
        <div id="landingNavigation">
			<div class="landingLogo"></div>
			<div class="container_24">
				<iframe class="headerFB_facepile" src="http://www.facebook.com/plugins/facepile.php?size=small&amp;app_id=<?=$this->config->item('fb_app_key')?>&amp;colorscheme=dark"scrolling="no" frameborder="0" allowTransparency="true" width="380" max_rows="1"></iframe>
				<ul class="landingNav_buttons">
					<li><a href="/about/contactus" style="display: none;"><?=ucwords($this->lang->line('contact_us'));?></a></li>
					<li><a id="header_signup" href="/signup" style="display: none;">Request Invite</a></li>
					<? if(!$this->session->userdata('id')){ ?>
						<li>
							<div id="landing_ext_netwks_lowerBar">
								<div class="addthis_toolbox">
									<div class="addthis_toolbox_wrapper">
										<? //twitter?>
										<a href="https://twitter.com/share" class="twitter-share-button" data-related="fandrop1" data-text="<?=$this->lang->line('twitter_share_text');?>" data-count="none"></a>
										<? //facebook?>
										<div class="fb-like" data-href="https://www.facebook.com/pages/Fandrop/280213925380172" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
										<style>
											#trending_bar .fb_iframe_widget iframe { right: 0 }
										</style>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</li>
					<? } ?>
				</ul>
			</div>
	    </div>
	    <? if (!$this->input->cookie('disable_login_window')) { ?>
	    	<? if($this->is_mod_enabled('open_signup')){ ?>
				<div class="expandingBox signup_open">
					<div class="expandingBox_contents">
						<div class="landing_right inlinediv">
							<span class="inlinediv">
								<div class="landingTagline_box"></div>
								<div class="landingButton_box">
									<div>
										<span class="landingSignup_button">
											<a id="expanding_signup" href="/signup">Start Now!</a>
										</span>
									</div>
									<div>
										<span class="landingLogin_button">
											<a id="expanding_login" href="/signin">Log In</a>
										</span>
									</div>
								</div>
							</span>
							<div class="new_landingVideo_box inlinediv">
								<a href="#fandrop_intro_video_wrap" rel="popup" class="new_landingVideo_button">
									<span class="ringPlay"></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			<? } else { ?>
				<div class="expandingBox signup_closed">
					<div class="expandingBox_contents">
						<div class="landing_right inlinediv">
							<span class="inlinediv">
								<div class="landingTagline_box"></div>
								<div class="js-landing-signup landingSignup_box">
									<div class="landingSignup_text1 js-invite-text">Be the first to find out when Fandrop is ready</div>
									<div class="emailInviteSuccess_text js-invite-success" style="display:none">Thank you</div>
									<? /*<div class="landingSignup_text2">We'll let you know when it's ready.</div>*/ ?>
									<div class="js-invite-init landingSignup_textarea">
										<?=Form_Helper::open('/request_invite', array('class'=>'newsfeed_list_new_comment', 'rel'=>'ajaxForm', 'data-type'=>'json', 'data-nopopupinfo'=>'true'))?>
											<div class="landingSignup_inputContainer"><input name="email" type="text" placeholder="Enter your email" class="js-emailInviteSignup_input"><span class="landingSignup_goButton"><input type="submit" value=""></span></div>
											<? /* ?><span class="landingSignup_goButton"><input type="submit" value="">Go<span class="goArrow"></span></span> // Old Version<? */ ?>
										<?=Form_Helper::close()?>
									</div>
									<div class="js-invite-error requestInvite_error" style="display:none"></div>
								</div>
								<div class="landingButton_box2">
									<div>
										<span class="landingLogin_button2">
											<a id="expanding_login" href="/signin">Log In</a>
										</span>
									</div>
								</div>
							</span>
							<div class="new_landingVideo_box inlinediv">
								<a href="#fandrop_intro_video_wrap" rel="popup" class="new_landingVideo_button">
									<span class="ringPlay"></span>
								</a>
							</div>
						</div>
					</div>
				</div>

			<? } ?>
		<? } ?>
	</div>
	
	<? $this->load->view('signup/request_invite_popup')?>
	<? if(!$this->session->userdata('invite_popup_shown') && !$this->session->userdata('id') && !$this->is_mod_enabled('open_signup') && empty( $_COOKIE['wli'] )) { ?>
		<?php $this->session->set_userdata('invite_popup_shown', true)?>
		<a href="#emailInvite_box" rel="popup" id="requestInvitePopup_trigger" style="display:none" title="">request invite</a>
	<? } ?>
	
	<? // Bottom part of the header is here. Can be turned off with the $no_tranding_bar flag set to true. ?> 
	<? // if flag is not set or set to false, we display trending bar ?>
	<? if ((!isset($no_trending_bar) || !$no_trending_bar)) { ?>
		<?=modules::run('homepage/main/trending_bar');?>
	<? } ?>
    <?php if ($this->newsfeed_model && !in_array($this->uri->segment(1), array('bookmarklet_walkthrough','choose_category','drop'))) { ?>
    	<?=$this->load->view('newsfeed/drop_preview_popup')?>
    <?php } ?>
    
    <? // RR moved share_email view to includes/template because header is not used in contests page?>
	<a href="#redrop-popup" rel="popup" id="open_redrop_info" title="Error" style="display:none">Error popup</a>
	<div id="redrop-popup" class="modal fade in" style="display:none">
		<div class="modal-body">
			<div id="redrop_warning_container">
				<span class="warning_message_icon"></span>
				<strong><?=$this->lang->line('includes_views_user_redrop_msg');?></strong>
			</div>
			<div class="bottom_row">
				<a class="blue_bg" data-dismiss="modal"><?=$this->lang->line('includes_views_ok_btn');?></a>
			</div>
		</div>
	</div>
    
  	<div id="loading-messages" class="modal" style="display: none">
		<div class="success-msg">
			<?=Html_helper::img("loading_icons/bigRoller_32x32.gif", array('alt'=>""));?>
			<div class="text_loading">Sharing</div>
		</div>
	</div>
<? } ?>
<?=Html_helper::requireJS(array("includes/header"))?>