<? if (!@$hide_header) { ?>
    <div id="header">
    	<? //TOP PART OF HEADER ?>
        <div id="navigation">
            <div class="container_24">
                <h1>
                    <a href="/" title="">Fandrop<span></span></a>
                </h1>
                <div id="search">
	            	<? // =====Search bar===== ?>
	                <form action="/search" method="get">
						<div id="header_search" class="autocomplete_input">
							<?=Form_Helper::main_search('q')?>
							<button type="submit" id="searchButton" class="search_button"><?=$this->lang->line('includes_views_search_lexicon');?></button>
						</div>                  
	                </form>
	           	</div>
                <? //==Account options ?>
                <? if ($this->session->userdata('id')) { ?>
	                <div id="hdr_account">
	                	<div id="account_menu" class="inlinediv">
							<a href="<?=$this->user->url?>">
								<?=Html_helper::img($this->user->avatar_25, array('id'=>"account_avatar", 'width'=>"25", 'height'=>"25",  'alt'=>"nav_pic", 'onerror'=>"if (this.src.indexOf('indigo_thumb.png') == -1) this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'"))?>
							</a>
							<a href="<?=$this->user->url?>" id="account_link" class="menu_caller ft-dropdown ft-dropdown-hover" rel="account_options">
								<div id="account_name" class="inlinediv"><?=$this->user->full_name?></div> 
								<div id="account_arrow" class="inlinediv"><span class="dropdown_menu_arrow"></span></div>
							</a>
					    </div>
						<div id="account_options">
							<ul id="options" class="menu">
								<?php if ($this->is_mod_enabled('list_manager')) { ?>
									<li><a href="/manage_lists">Story Manager</a></li>
								<?php } else { ?>
									<li><a href="<?=$this->user->url?>" class="js-profile_url"><?=$this->lang->line('includes_views_collections_link');?></a></li>
								<?php } ?>
								<li><a href="/messages" id="acctmenu_msgs"><?=$this->lang->line('includes_views_messages_link');?></a></li>
								<li><a href="/account_options"><?=$this->lang->line('includes_views_settings_link');?></a></li>
								
								<? /* ?>
								<? if ($this->user->fb_id==0) { ?>
									<a id="external_connect_fb" href="/fb_connect" title="Connect to Facebook"><li>Connect to Facebook</li></a>
								<? } ?>
								<? if ($this->user->twitter_id==0) { ?>
									<a id="external_connect_twtr" href="/twitter_connect" title="Connect to Twitter"><li>Connect to Twitter</li></a>
								<? } ?>
								
									<a href="/invites"><li><?=$this->lang->line('includes_views_invite_link');?></li></a>
								<? */ ?>
								<li><a href="/logout"><?=$this->lang->line('includes_views_logout_link');?></a></li>
							</ul>
						</div>
	                </div>
                <? } ?>
            </div>
            <? // =====Bottom header links (notifications/requests/messaging===== ?>
	        <? if($this->session->userdata('id')){ ?>
			    <div id="nav_buttons">
					<ul class="headerIcons">
					    <? //==Header: Page/profile switcher (for pages only) ?>
					    <? if(isset($this->session->userdata['page_id']) && $this->session->userdata['page_id']){ ?>
							<li class="hdrnav_item hdr_switcher inlinediv" id="hdr_switcher">
								<a href="/switch_back" title="" class="switcher"></a>
							</li>
					    <? }?>
					     <? //==Header: notifications
					     	$notification_num = $this->notification_model->count_by(array('user_id_to'=>$this->user->id, 'read'=>'0'));
					     	$notifications_class = $notification_num>99 ? 'count_too_wide' : 'unselectable'.($notification_num ? '' : ' empty_count');
					     ?>
					     <? //Header: Notification icon ?>
					     <li class="hdrnav_item hdr_notifications menu_caller inlinediv">
							<a href="" id="hdr_notifications" class="info ft-dropdown count <?=$notifications_class?>" rel="notifications"><?=$notification_num?></a>
							<? //Header: Notification menu ?>
							<div id="notifications" class="menu">
								<span class="header_menu_arrow"></span>
								<div id="scroll_notifications" class="fd-scroll fd-autoscroll js-notification" data-template="#tmpl-notification-list-item" data-maxscrolls="-1" data-url="/notifications_read" data-container="#scroll_notifications">
							    	<ul>
										<?=modules::run('notification/notification/get_read')?>										
									</ul>
								</div>
								<div class="nohover notification_link_all"><a href="/show_all"><?=$this->lang->line('includes_views_see_all_notifications');?></a></div>
						    </div>
						    <?=Html_helper::requireJS(array('newsfeed/drop_full_popup')); //used by the notifications?>
						</li>
				    </ul>
				</div>
			<? } ?>
				<? /*if($this->session->userdata('id') && $this->user->fb_id > 0){ ?>
					<li id="social_mode_container">
						<?
						//@UPDATE - DK - 8/25/2012 - fixed tooltip on signup for social button
						$cl = 'custom-title';
						$temp = false;
						if($this->user->fb_id>0 && $this->user->auto_share == '' || $temp) {
							?>
							<div id="social_btn_tooltip" class="tab_label_1">
								<span></span>
								<div class="social_btn_tooltip_desc"><?=$this->lang->line('social_mode_balloon_off')?></div>
									<div class="social_btn_tooltip_buttons">
										<a id="social_btn_tooltip_buttons_ok" class="blue_bg inlinediv">TURN ON</a>
										<a id="social_btn_tooltip_buttons_cancel" class="blue_bg inlinediv">CANCEL</a>	
									</div>
							</div>
							<?
							$cl = '';
						} else {
							$cl = 'custom-title';
						}
						?>
						<a rel="ajaxButton" class="sharelink_enable <?=$cl?>" href="/enable_auto_share" title="<?=$this->lang->line('social_mode_balloon_off')?>" title-pos="bottom" title-class="tab_label_1" style="<?=$this->user->auto_share ? 'display:none' : ''?>">Social Mode OFF</a>
						<a rel="ajaxButton" class="sharelink_disable <?=$cl?>" href="/disable_auto_share" title="<?=$this->lang->line('social_mode_balloon_on')?>" title-pos="bottom" title-class="tab_label_1" style="<?=$this->user->auto_share ? '' : 'display:none'?>">Social Mode ON</a>
					</li>
				<? }*/ ?>
				<? //==Header Links (Home/Whats Hot/Profile) ?>                    
				<ul id="headerLinks" class="headerLinks">
						
						<? if($this->session->userdata('id')) { ?>
					    	<?php if (! $this->is_mod_enabled('invite5') || $this->alpha_user_model->count_by(array('user_id'=>$this->session->userdata('id'))) >= 5) { 
					    		$this->load->view("includes/drop_button_nav"); ?>
<!--						    <li class="hdrnav_item">
						    	<a href="#get_bookmarklet_dialog" id="add_collect" rel="popup" data-position="top" class="test_addcollect <?=$this->session->userdata('invite_more')? 'addCollect_highlight' : ''?>"><?=$this->lang->line('includes_views_add_btn');?></a>
						    	<? if ($this->session->userdata('invite_more')) { ?>
									<div id="dropit-message" class="tab_label_2">
										<span class="left"></span>
										Behold, the Drop It! button...
										<a href="" class="close_btn"></a>
									</div>
								<? } ?>
						    </li>-->
						    <?php } ?>
					   
						    <li class="hdrnav_item">
						    	<a href="/invites"><?=$this->lang->line('includes_views_invite_link2')?></a>
						    </li>
					    <? } ?>
					    <? //Header Links: About ?>
					     	<li class="hdrnav_item">
							<a id="about_link" href="/about" class="menu_caller ft-dropdown ft-dropdown-hover" rel="about_menu"><?=$this->lang->line('includes_views_about_btn');?><div id="about_arrow" class="inlinediv"><span class="dropdown_menu_arrow"></span></div></a>
							<div id="about_menu">
								<ul id="about_options" class="menu">
									<li><a href="/about"><?=$this->lang->line('includes_views_about_btn');?></a></li>
									<li><a href="/promoters"><?=$this->lang->line('includes_views_promoters_btn')?></a></li>
									<li><a href="/publishers"><?=$this->lang->line('includes_views_publishers_btn')?></a></li>
									<? /* ?><li><a href="/about/team"><?=$this->lang->line('includes_views_team_btn');?></a></li><? */ ?>
									<li><a href="/about/contactus"><?=ucwords($this->lang->line('contact_us'));?></a></li>
									<li><a href="/about/jobs"><?=$this->lang->line('includes_views_jobs_btn');?></a></li>
									<? if($this->session->userdata('id')):?>
										<li><a href="/about/drop_it_button"><?=$this->lang->line('includes_views_drop_it_button_btn');?></a></li>
									<? endif; ?>
									<li><a href="/about/copyright"><?=$this->lang->line('includes_views_copyright_and_privacy');?></a></li>
									<li><a href="/about/partners"><?=$this->lang->line('includes_views_partners');?></a></li>
									<? /* ?><? if ($this->session->userdata('id')) { ?>
										<li><a href="/walkthrough/<?=$view?>" rel="popup" data-group="walkthrough" data-height="80%" title="<?=strpos($view, '/') === false ? ucfirst($view) : 'Help'?>"><?=$this->lang->line('includes_views_help_btn');?></a></li>
									<? } ?><? */ ?>
								</ul>
							</div>
							</li>
						
					    <? //Header Links: Home ?>
					    <li class="hdrnav_item"><a href="/" title=""><?=$this->lang->line('includes_views_home_btn');?></a></li>
						<?/*<? if(!$this->session->userdata('id')){ ?>
							<div id="landing_ext_netwks">
								<div class="addthis_toolbox">
									<div class="addthis_toolbox_wrapper">
										<? //twitter?>
										<? //facebook?>
										<div class="clear"></div>
									</div>
								</div>
							</div>

					    <? } ?>*/?>
						<? //Header Links: Login ?>
						<? /* ?><? if($this->is_mod_enabled('open_signup')){ ?><? */ ?>
							<? if(!$this->session->userdata('id')){ ?>
								<li id="hdrnav_login">
									<a id="header_login" href="/signin">Login</a>
								</li>
	
							<? } ?>
						<? /* ?><? } ?><? */ ?>
						<? //Header Links: Login ?>
						<? /* ?>
						<? if(!$this->session->userdata('id')){ ?>
							<li id="hdrnav_signup">
								<a id="header_signup" href="/signup">Request Invite</a>
							</li>

					    <? } ?>
						<? */ ?>
				</ul>
				<? /*
				<? if ($this->session->userdata('id')) { ?>
				    <div id="help_container" style="z-index:8;display:none">
						<div id="help_bar">
						    <span id="help_bar_message"><?=$this->lang->line('includes_views_help_mode_enabled_msg');?></span>
						    <input type="button" id="btn_help" value="?" />
						</div>
				    </div>
				   	<?=Html_helper::requireJS(array("help/help"))?>	
			    <? } ?>
			    <? */ ?>		
		</div>
	<? //Notifications old position?>
	
	
	<? // Bottom part of the header is here. Can be turned off with the $no_tranding_bar flag set to true. 
	     // if flag is not set or set to false, we display trending bar ?>
	<? if ((!isset($no_trending_bar) || !$no_trending_bar)) { ?>
		<?=modules::run('homepage/main/trending_bar');?>
	<? } ?>
	
	<? if($this->session->userdata('id') && $this->is_mod_enabled('system_notification') && $this->router->is_homepage()){ ?>
		<?=modules::run('notification/system_notification/get')?>
	<? } ?>
    </div>

    <?php if ($this->session->userdata('id')) { ?>
    	<? // RR moved share_email view to includes/template because header is not used in contests page?>    
	    <?php if ( ! $this->is_mod_enabled('invite5') || $this->alpha_user_model->count_by(array('user_id'=>$this->session->userdata('id'))) >= 5) { ?>
	    	<?  $this->load->view('includes/info_dialog')?>
	    <?php } ?>
	<?php } ?>
    
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