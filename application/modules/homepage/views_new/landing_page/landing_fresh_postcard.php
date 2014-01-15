<? $this->lang->load('signup/signup', LANGUAGE); ?>
<? $this->lang->load('home/home_views', LANGUAGE); ?>

<div id="landing_page">
	<? /* ?>
	<div id="extremeTop_fresh">
		<iframe id="fb_facepile" src="http://www.facebook.com/plugins/facepile.php?size=small&app_id=<?=$this->config->item('fb_app_key')?>" scrolling="no" frameborder="0" allowTransparency="true" width="380" max_rows="1"></iframe>
		<div class="clear"></div>
	</div>
	<? */ ?>
	<div id="landing_left" style="display:inline-block">
		<div id="landing_page_newsfeed">
		    <ul id="list_options">			
				<li id="everyone_tab" class="loop_tab active">
				 <a href="/" class="ft-dropdown ft-dropdown-hover" rel="everyone_list">
					DROPS BY EVERYONE
					<span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
				 </a>
				</li>
				<li id="everyone_list_holder">
					<ul id="everyone_list" class="menu ft-dropdown-target" style="display:none;">
						<li><span class="sort_menu_arrow"></span></li>
						<li>
							<ul class="list_contents">
								<li class="sort_list_item" style="display:block">
								 <a data-item="sort_by_time" href="/drops-by-everyone/time">
									<? if($sort_by == 'time') { ?>
										<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
									<? } else { ?>
										<span class="non_current_item"></span>
									<? } ?>
									<?=$this->lang->line('home_views_feed_sort_by_time_lexicon');?>
								 </a>
								</li>
								<li class="sort_list_item" style="display:block">
								 <a data-item="sort_by_popularity" href="/drops-by-everyone/news_rank">
									<? if($sort_by != 'time') { ?>
									<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
									<? } else { ?>
									<span class="non_current_item"></span>
									<? } ?>
									<?=$this->lang->line('home_views_feed_sort_by_popularity_lexicon');?>
								 </a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			
				<li id="sort_by" style="float: right;">
					<? $this->load->view('newsfeed/filter_types_menu', array('base'=>'/drops-by-everyone/'.$sort_by)) ?>
				</li>
			 </ul>
			 <?=modules::run('homepage/home_newsfeed/'.$category_type, $sort_by, $filter)?>
		</div>
	</div>
	<div id="landing_right" style="display:inline-block; vertical-align:top">
		
			<? /* ?>
			<li class="folderSignup">
				<div>
					<div class="folderSignup_text">The hottest <span>social stuff</span> on the web!</div>
					<div class="folderSignup_drop"></div>
					<a class="folderSignup_button" href="/signup">Request Invite</a>
				</div>
			</li>
			<? */ ?>
			<? /* ?>
			<div class="bookmarkletAd">
				<div class="bookmarkletAd_title">
				    Drop <em>any</em> part of any page.
				</div>
				<?=Html_helper::img("bookmarkletInfo.png")?>
				<div class="bookmarkletAd_bottom">
				    <a href="/about/drop_it_button">
						<div class="bookmarkletAd_arrow"></div>
						<div class="bookmarkletAd_text">Details</div>
				    </a>
				</div>
			</div>
			<? */ ?>
			<?=modules::run('homepage/home_folders/popular_folders')?>
	</div>	
</div>

<div id="fandrop_intro_video_wrap" class="js-video_init video_init modal hide" style="display:none;width:854px;height:510px;max-width:1280px;max-height:720px;background:#000;">
	<iframe id="fandrop_intro_video" data-videourl="http://www.youtube.com/embed/k9YtbLYtXTg?wmode=opaque&amp;hd=1&amp;feature=player_embedded" src="http://www.youtube.com/embed/k9YtbLYtXTg?wmode=opaque&amp;hd=1&amp;feature=player_embedded" width="854" height="510" style="width:854px;height:510px;max-width:1280px;max-height:720px" frameborder="0" allowfullscreen></iframe>
</div>

<?=Html_helper::requireJS(array('common/video_init','social/fb_facepile','landing_page/landing_page_fresh'))?>
