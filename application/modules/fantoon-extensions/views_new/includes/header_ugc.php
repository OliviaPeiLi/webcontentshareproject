<? if (@!$hide_header) { ?>
	<div id="header">
		<div class="header_content">
			
			<div class="left">
				<a href="/promoters" class="info-btn ft-dropdown custom-title" rel="about-dropdown"><?=$this->lang->line('includes_views_menu_lexicon');?></a>
				<div id="about-dropdown">
				<span class="arrow"></span><!--/.arrow-->
				<ul>
					<li><a href="/about"><?=$this->lang->line('includes_views_about_btn')?></a></li>
					<? /* RR - about design is not ready
					<li><a href="/about/contactus"><?=ucwords($this->lang->line('contact_us'))?></a></li>
					<li><a href="/about/jobs"><?=$this->lang->line('includes_views_jobs_btn')?></a></li>
					<? if($this->session->userdata('id')) { ?>
						<li><a href="/about/drop_it_button"><?=$this->lang->line('includes_views_drop_it_button_btn')?></a></li>
					<? } ?>
					<li><a href="/about/copyright"><?=$this->lang->line('copyright_privacy')?></a></li>
					*/ ?>
					<li><a href="/about/partners"><?=$this->lang->line('includes_views_partners')?></a></li>
					<? /* ?>
					<li><a href="/promoters"><?=$this->lang->line('includes_views_promoters_btn')?></a></li><? */ ?>
					<li><a href="/publishers"><?=$this->lang->line('includes_views_publishers_btn')?></a></li>
				</ul>
				</div><!--/#about-dropdown-->

				<div id="search">
					<form action="/search" method="get">
						<div id="header_search" class="autocomplete_input">
							<button type="submit" id="searchButton" class="search_button"></button>
							<?=Form_Helper::main_search('q', array('style'=>'width: 220px'))?>
						</div>                  
					</form>
				</div><!--/#search-->
			</div><!--/..left-->
			
			<!--LOGO--><a href="/" title="" class="header_logo">Fandrop</a>
			
			<div class="right">
				<?php if ($this->session->userdata('id')) { ?>
					<?php if ($this->is_mod_enabled('list_manager')) { ?>
						<a href="/create_list" id="list_link" class="custom-title" rel="list_options" title="Create New Story" title-pos="bottom">
							<span class="listsButton">
								<span class="ico"></span>
							</span>
						</a>
					<?php } ?>
					
					<? /* http://dev.fantoon.com:8100/browse/FD-5130
						//==Header: notifications
				     	$notification_num = $this->notification_model->count_by(array('user_id_to'=>$this->user->id, 'read'=>'0'));
				     	$notifications_class = $notification_num>99 ? 'count_too_wide' : 'unselectable'.($notification_num ? '' : ' empty_count');
				     ?>
				     <? //Header: Notification icon ?>
					<a href="" id="hdr_notifications" class="ft-dropdown <?=$notifications_class?>" rel="notifications"><?=$notification_num?></a>
					<div id="notifications">
						<span class="header_menu_arrow"></span>
						<div id="scroll_notifications" class="fd-scroll fd-autoscroll js-notification" data-template="#tmpl-notification-list-item" data-maxscrolls="-1" data-url="/notifications_read" data-container="#scroll_notifications">
					    	<ul>
								<?=modules::run('notification/notification/get_read')?>										
							</ul>
						</div>
						<div class="nohover notification_link_all"><a href="/show_all"><?=$this->lang->line('includes_views_see_all_notifications');?></a></div>
				    </div>
				    */ ?>
					
					<a href="<?=$this->user->url?>" id="account_link" class="ft-dropdown custom-title" rel="account_options" title="User Menu" title-pos="bottom">
						<!--<?=Html_helper::img($this->user->avatar_25)?>-->
						<!--<span class="header_userName"><?=$this->user->full_name?></span>-->
					</a>
					<ul id="account_options">
						<li><a href="/manage_lists" title="">Manage Stories</a></li>
						<li><a href="<?=$this->user->url?>"><?=$this->lang->line('includes_views_collections_link')?></a></li>
						<?php /* RR -desgin not ready?>
						<li><a href="/messages"><?=$this->lang->line('includes_views_messages_link')?></a></li>
						*/ ?>
						<li><a href="/account_options"><?=$this->lang->line('includes_views_settings_link')?></a></li>
						<li><a href="/logout"><?=$this->lang->line('includes_views_logout_link')?> (<?=$this->user->uri_name;?>)</a></li>
					</ul>
				<?php } else { ?>
					<div class="sign_inUp inlinediv">
						<a href="#login-popup" rel="popup" title="<?=$this->lang->line('login');?>" class="signin-btn header-btn"><?=$this->lang->line('login');?></a>
						<a href="#signup-popup" rel="popup" title="<?=$this->lang->line('signup');?>" class="signup-btn header-btn"><?=$this->lang->line('signup');?></a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div id="loading-messages" class="modal" style="display: none">
		<div class="success-msg">
			<?=Html_helper::img("loading_icons/bigRoller_32x32.gif", array('alt'=>""));?>
			<div class="text_loading"><?=$this->lang->line('includes_views_sharing_lexicon');?></div> <!-- TODO - make all javascript working with this message -->
		</div>
	</div>	
	<? if (!@$no_trending_bar) { ?>
		<?=modules::run('homepage/main/trending_bar');?>
	<? } ?>
<? } ?>
<?php if (!$this->session->userdata('id')) { ?>
	<? $this->load->view('signup/login_ugc')?>
	<? $this->load->view('signup/signup_ugc')?>
	<? $this->load->view('signup/forgot_pass_ugc')?>
<?php } ?>
<?=Html_helper::requireJS(array("includes/header_ugc"))?>