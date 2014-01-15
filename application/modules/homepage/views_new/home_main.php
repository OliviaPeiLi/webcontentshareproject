<? $this->lang->load('home/home_views', LANGUAGE); ?>
<div id="main" class="main_home">
    <div class="container_24 container" id="home">
	<div class="grid_18 alpha" id="home_left_column">
	    <? if ($this->is_mod_enabled('internal_scraper_new') && $this->session->userdata('id')) { ?>
			<?=$this->load->view('includes/internal_scraper')?>
		<? } ?>
		
	    <div id="comments_container" class="messagesContainer">
	    	<?  //Top part to be used for dropdowns ?>
		    <ul id="list_options">
			<li id="everyone_tab" class="loop_tab <?=$category_type=='popular'?'active':''?>">
			    <a href="/" rel="everyone_list_holder" class="ft-dropdown ft-dropdown-hover">
					DROPS BY EVERYONE
					<span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
			    </a>
			</li>
			<li id="everyone_list_holder" class="menu ft-dropdown-target" style="display:none;">
			    <div id="everyone_list">
					<span class="sort_menu_arrow"></span>
					<ul class="list_contents">
					    <li class="sort_list_item " rel="" style="display:block">
							<a data-item="sort_by_time" href="/drops-by-everyone/time">
								<? if($category_type=='popular' && $sort_by == 'time') { ?>
									<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								<? } else { ?>
									<span class="non_current_item"></span>
								<? } ?>
								<?=$this->lang->line('home_views_feed_sort_by_time_lexicon');?>
							</a>
					    </li>
					    <li class="sort_list_item " rel="" style="display:block">
							<a data-item="sort_by_popularity" href="/drops-by-everyone/news_rank">
								<? if($category_type=='popular' && $sort_by == 'news_rank') { ?>
									<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								<? } else { ?>
									<span class="non_current_item"></span>
								<? } ?>
								<?=$this->lang->line('home_views_feed_sort_by_popularity_lexicon');?>
							</a>
					    </li>
					</ul>
			    </div>
			</li>
			<li id="followers_tab" class="loop_tab <?=$category_type!='popular'?'active':''?>">
			    <a href="/people-you-follow/news_rank"  rel="followers_list_holder" class="ft-dropdown ft-dropdown-hover">
					PEOPLE YOU FOLLOW
					<span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
			    </a>
			</li>
			<li id="followers_list_holder" class="menu ft-dropdown-target" style="display:none;">
			    <div id="followers_list">
					<span class="sort_menu_arrow"></span>
					<ul class="list_contents">
					    <li class="sort_list_item" style="display:block">
							<a data-item="sort_by_time" href="/people-you-follow/time">
								<? if($category_type!='popular' && $sort_by == 'time'){ ?>
									<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								<? } else { ?>
									<span class="non_current_item"></span>
								<? } ?>
								<?=$this->lang->line('home_views_feed_sort_by_time_lexicon');?>
							</a>
					    </li>
					    <li class="sort_list_item " rel="" style="display:block">
							<a data-item="sort_by_popularity" href="/people-you-follow/news_rank">
								<? if($category_type!='popular' && $sort_by == 'news_rank'){ ?>
									<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								<? } else { ?>
									<span class="non_current_item"></span>
								<? } ?>
								<?=$this->lang->line('home_views_feed_sort_by_popularity_lexicon');?>
							</a>
					    </li>
					</ul>
			    </div>
			</li>
			<li id="sort_by" style="float: right;">
				<? $this->load->view('newsfeed/filter_types_menu', array('base'=>'/'.($category_type=='popular' ? 'drops-by-everyone' : 'people-you-follow').'/'.$sort_by)) ?>
			</li>
		    </ul>
		    <?=modules::run('homepage/home_newsfeed/'.$category_type, $sort_by, $filter) ?>
		    </div>
	    <div class="comments_container_bot"></div>
	</div>
	<div class="grid_6 omega" id="home_right_column">
		<? /* ?>
	    <div class="bookmarkletAd">
			<div class="bookmarkletAd_title">
			    Drop <em>any</em> part of any page.
			</div>
	    	<img src="/images/bookmarkletInfo.png">
			<div class="bookmarkletAd_bottom">
			    <a href="/about/drop_it_button">
				<div class="bookmarkletAd_arrow"></div>
				<div class="bookmarkletAd_text">Details</div>
			    </a>
			</div>
		</div>
	    </div><? */ ?>
	    <? if($this->session->userdata('id') && isset($this->user->info) && $this->user->info!='' && in_array($this->user->info, $this->config->item('leaderboard_info'))){ ?>
	    <div class="leaderBoard_banner">
		<a href="/leaderboard/<?=$this->user->info?>"><?=ucfirst($this->user->info)?> Leaderboard</a>
	    </div>
	    <? } ?>
	    <div id="user_info">
			<a href="/followings/<?=$this->user->uri_name?>">
			<div class="inlinediv info_item">
			    <div class="info_count"><?=$this->user->user_stat->followings_count?></div>
			    <div class="info_item_title"><?=$this->lang->line('home_views_following_lexicon');?></div>
			</div>
			</a>
			<a href="/followers/<?=$this->user->uri_name?>">
			<div class="inlinediv info_item"><? /* Add "middle" to the classes when Interest Pages comes back */?>
			    <div class="info_count"><?=$this->user->user_stat->followers_count?></div>
			    <div class="info_item_title"><?=$this->lang->line('home_views_followers_lexicon');?></div>
			</div>
			</a>
			<? /* DISABLE INTERESTS ?>
			<a href="/view_interests/<?=$this->session->userdata('id')?>">
			<div class="inlinediv info_item">
			    <div class="info_item_title">Interests</div>
			    <div class="info_count"><?=$interests?></div>
			</div>
			</a>
			<? */ ?>
			<div class="clear"></div>
	    </div>
	    <? if($this->is_mod_enabled('home_activities')){ ?>
		    <?=modules::run('profile/activity/index', $this->user->id)?>
	    <? } ?>
	    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~ Suggested Pages ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	    <? /*
	    <div id="home_suggested_pages" style="display: none">
		<div style="padding: 90px 140px 90px 140px;">Pages you may like</div>
		<? foreach ($suggested_pages as $suggested_page): ?>
		<a href="?cntrl=future_page_cntrl&act=show_page&page_id=<?=$suggested_page['page_id']?>">
		<img src="/images/<?=$suggested_page['avatar'] ? $suggested_page['avatar'] : 'example.jpg'?>" border="0" height="40px">
		</a>
		<?php endforeach; ?>
		</div>
		<? */ ?>
		
		<? //Ray: Popular collections ?>
		<?=modules::run('homepage/home_folders/popular_folders')?>		
	    <div class="home_right_column_bot"></div>
	</div>
	
	
	<!--~~~~~~~~~~~ Placeholder for wall/photo post input sections ~~~~~~~~~~~~~~~-->
	<div id="profile_post_section"></div>
	
	<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ wallpost from loop ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	<div id="show_loop" style="display: none;">
	    
	</div>
    </div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>

<? //Create New List dialog ?>
<div id="new_list_dialog" class="dialog" style="display: none">
    <ul>
	<li style="color:red"><?=Form_Helper::validation_errors('<p class="error">'); ?></li>
	<?=Form_Helper::open('save_list/', array(), array('from'=>$this->uri->segment(3))) ?>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_list_name_lbl');?></div>
	    <div class="form_field" style="width: 365px"><?=Form_Helper::input('list_name')?></div>
	</li>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_description_lbl');?></div>
	    <div class="form_field"><textarea name="description" rows="12" cols="70"></textarea></div>
	</li>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_list_visibility_lbl');?></div>
	    <div class="form_field">
		<div><?=Form_Helper::form_radio('privacy', 'public', TRUE); ?> <?=$this->lang->line('home_views_public_lexicon');?></div>
		<div><?=Form_Helper::form_radio('privacy', 'private'); ?> <?=$this->lang->line('home_views_private_lexicon');?></div>
	    </div>
	</li>
	<ul id="my_pages">
	    <li><?=$this->lang->line('home_views_choose_pages_lexicon');?></li>
	    <? if (isset($my_pages) && is_array($my_pages)) foreach($my_pages as $key=>$item) { ?>
	    <li><?=Form_Helper::form_checkbox('check_pages[]', $item['page_id']); ?> <a href="/interests/uri_name/<?=$item['page_id']?>"><?=$item['page_name']?> </a></li>
	    <? } ?>
	    </ul>
	<li><div class="form_label"><?=Form_Helper::submit('submit', $this->lang->line('home_views_submit_lexicon'))?></div></li>
	<?=Form_Helper::close()?>
	</ul>
</div>

<? if ($this->session->userdata('just_signup') == 1) {
	//just signup and display popup, $this->user->info has value of group
	if($this->user->info == 'catathon'){
		echo '<div id="open_catathon_intro_popup" rel="popup" data-url="#catathon_intro_popup" href="#catathon_intro_popup" data-unscrollable="1" data-hidetitlebar="1" style="display:none">open</div>';
		$this->load->view('signup/catathon_intro_popup');
	}
	$this->session->set_userdata(array('just_signup'=>0));
} ?>

<script type="text/javascript">
	php.category = '<?=$category_type ?>';
	php.lang.error.hashtaguniq = 'Selected hashtag should be unique';
</script>
<?=Html_helper::requireJS(array("home/home_main"))?>
