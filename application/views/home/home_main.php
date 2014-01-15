<? //echo 'just registered: '.$just_registered; ?>
<? /* ?>
<? if (($first_visit || $just_registered)) { ?>
	<?php $css = $this->load->config('css');
		$css['home'][] = 'help/walkthrough';
		$this->config->set_item('css',$css); ?>
	<a href="#grabbed_info_body" id="open_followers_info" rel="popup" data-group="walkthrough" data-height="80%" title="Additional Information" style="display:none">Walk-through</a>	
	<?=$this->load->view('signup/grabbed_info',array('friends'=>$friends, 'links'=>$links, 'folders'=>$folders_dropdown),true)?>

<? } ?>
<? */ ?>
<? /* ?>
<?php if ($first_visit): //Just registered?>
	<?php $css = $this->load->config('css');
		$css['home'][] = 'help/walkthrough';
		$this->config->set_item('css',$css); ?>
	<a href="/walkthrough/home" id="first_visit" rel="popup" data-group="walkthrough" data-height="80%" title="Walk-through" style="display:none">Walk-through</a>
<?php endif; ?>
<? */ ?>
<? $this->lang->load('home/home_views', LANGUAGE); ?>
<? $topic_category = isset($topic->topic_name) ? @$topic->topic_name : 'Everything'; ?>
<? $filter_type = ($this->input->get('type', true) !== '') ? $this->input->get('type', true) : 'All'; ?>
<div id="main" class="main_home" data-sortby="<?=$this->input->get('sort_by', true)?>" data-category="<?=$topic_category?>" data-filter="<?=$filter_type?>">
    <div class="container_24 container" id="home">
	<div class="grid_18 alpha" id="home_left_column">
		
		<?php $this->load->view('newsfeed/filter_types_menu')?>

		<? /* ?>
	    <div id="home_buttons">
			<div help="You can post content by Fetching for it with our internal scraper." title="Fetch" style="position:relative;" pos_my="bottom right" pos_at="top center">
			    <?php echo Form_Helper::form_open('', array('id'=>'scraper_form2'))?>
			    <span class="error" style="position:absolute; left:0; top:-20px; display:none">Please enter a valid URL</span>
			    <textarea name="url" id="postbox" style="float:left;" tabindex="-1" title="Paste the link or enter it in here..." contenteditable="true" class="postbox empty ui-autocomplete-input inactive required" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" placeholder="Paste the link or enter it in here…"></textarea>
			    <? //start: img upload ?>
			    <? //end: img upload ?>
			    <div id="share_buttons" class="inlinediv">
					<a id="fetch_circle" class="hover" type="list" style="" href="<?php echo site_url('share_link?type=bar')?>" rel="popup" data-form="#scraper_form2" data-group="scraper" title="Share a Link with your interests">+</a>
					<?  ?>
					<div class="share_options" style="display:none;">
						<a id="fetch_clip" type="bar" href="<?php echo site_url('share_link?type=bar')?>" rel="popup" data-form="#scraper_form2" data-group="scraper" data-tooltip="Clip a part of the page" data-width="90%">Clip</a>
						<a id="fetch_save" type="list" href="<?php echo site_url('share_link?type=list')?>" rel="popup" data-form="#scraper_form2" data-group="scraper" data-tooltip="Save the whole page">Save</a>
					</div>
					<?  ?>
			    </div>
			    <?php echo form_close()?>
	
		    </div>
	    
	    </div>
	    <? */ ?>
	    
	    <div id="comments_container" class="messagesContainer">
			<div id="controls">
			    <ul id="home_list_tabs">
			        <? /* ?><li id="recent_tab" class="loop_tab <? if ($category_type === 'recent') { echo 'active_tab';}?> ft-dropdown ft-dropdown-hover" rel="sort_list"><? */ ?>
			        <li id="recent_tab" class="loop_tab ft-dropdown ft-dropdown-hover" rel="sort_list">
						   <a href="/">
								<?=$this->lang->line('home_views_recent_btn');?>
								<span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
			            </a>
			        </li>
			        <ul id="sort_list" class="menu ft-dropdown-target" style="display:none;">

              			<li class="sort_list_item " rel="" style="display:block">
              				<a data-item="sort_by_time" href="<?=base_url()?>">
              				    <?=$this->lang->line('home_views_feed_sort_by_time_lexicon');?>
								    <? if($this->input->get('sort_by') != 'news_rank'){ ?>
								    <span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								    <? } ?>
              				</a>
              			</li>
              			<li class="sort_list_item " rel="" style="display:block">
								<a data-item="sort_by_popularity" href="<?=base_url().'?sort_by=news_rank'?>">
		                    				    <?=$this->lang->line('home_views_feed_sort_by_popularity_lexicon');?>
								    <? if($this->input->get('sort_by') == 'news_rank'){ ?>
								    <span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
								    <? } ?>
              				</a>
              			</li>

              		</ul>
			        	
						<? /* ?><li id="home_tab" class="loop_tab <? if ($category_type === 'popular') { echo 'active_tab';}?> ft-dropdown ft-dropdown-hover" rel="topics_list"><? */ ?>
						<li id="home_tab" class="loop_tab active_tab ft-dropdown ft-dropdown-hover" rel="topics_list">
							<a href="/everything">
								<?=$this->uri->segment(2) ? $topic->topic_name : $this->lang->line('home_views_popular_btn')?> <? /*<img class="tab_arrow" src="/images/bm-modearrow_d.png">*/?>
								<span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
							</a>
						</li>
					
						<ul id="topics_list" class="menu ft-dropdown-target" style="display:none;">
							<? if(isset($topic)){ ?>
							<li class="topic_list_item" rel="" style="display:block">
                				<a href="<?=base_url()?>">
                					Everything
                				</a>
                			</li>
                			<? } ?>
	                    	<? foreach ($topics as $topic) { ?>

                    			<li class="topic_list_item <?=$topic_uri_name&&$topic->uri_name==$topic_uri_name ? 'active_tab' : ''?>" rel="<?=$topic->uri_name?>" style="display:block">
                    				<a href="<?=$topic->url?>">
                    					<?=$topic->topic_name?>
                    				</a>
                    			</li>

							<? } ?>
						</ul>
					
					
					<? /* comment out lists
					<? foreach($my_lists as $list_key=>$list_value) { ?>
						<li id="list_tab_<?=$list_value['list_id']?>" class="loop_tab list_tab">
							<a class="list_newsfeed" href="/list_newsfeed/<?=$list_value['list_id']?>/home"><?=$list_value['list_name']?></a>
						</li>
					<? } ?>
					<? foreach($follow_lists as $follow_key=>$follow_value) { ?>
						<li id="list_tab_<?=$follow_value['list_id']?>" class="loop_tab">
						<a class="list_newsfeed" href="/list_newsfeed/<?=$follow_value['list_id']?>"><?=$follow_value['list_name']?>.</a>
						</li>
					<? } ?>
					<li id="more_tabs" class="menu_activator" style="display:none">
						<a href="#" class="ft-dropdown" rel="more_tabs_list">More…</a>
						<ul style="display:none" id="more_tabs_list" class="menu site_menu"></ul>
					</li>
					<li id="create_new_list">
						<a href="/new_list/<?=$this->session->userdata('id')?>" rel="popup" data-width="95%" help="Organize the content that you follow with Lists." title="Manage Your Lists" style="position:relative;">+</a>
					</li>
					*/ ?>
					
			    </ul>
			</div>
			
			<? /*
			<ul id="lists" class="menu site_menu">
				<? if($my_lists) { ?>
					<li class="heading">My Lists</li>
					<? foreach($my_lists as $list_key=>$list_value) { ?>
						<li>
						    <a class="list_newsfeed" href="/list_newsfeed/<?=$list_value['list_id']?>"><?=$list_value['list_name']?></a>
						    <a href="/del_list/<?=$list_value['list_id']?>"> <img class="remove_icon" style="width:12px" src="/images/delete_icon.png" /></a>
						    <a href="/edit_list_add_pages/<?=$list_value['list_id']?>"> <img style="width:12px" src="/images/edit_icon.png" /></a>
						</li>
					<? } ?>
				<? } ?>
				<li id="create_new_list"><a href="/new_list">Create New List</a></li>
				<? if($follow_lists) { ?>
					<li class="heading">Followed Lists</li>
					<? foreach($follow_lists as $follow_key=>$follow_value) { ?>
						<li>
						    <a class="list_newsfeed" href="/list_newsfeed/<?=$follow_value['list_id']?>"><?=$follow_value['list_name']?>.</a>
						    <a href="/unfollow_list/<?=$follow_value['list_id']?>/home"> Followed list</a>
						</li>
					<? } ?>
				<? } ?>
			</ul>
		    */ ?>
		    
		    <?php $this->load->view('newsfeed/view_modes_menu', array(
				'url' => '/newsfeed/home/'.$category_type.'/'.$topic_uri_name
			))?>
			
			<? //Newsfeed contents ?>
			<ul id="list_newsfeed" class="home_comments_list home_left_newsfeed newsfeed newsfeed_placeholder typeView<?=ucfirst($this->input->get('view',true,camelize($this->config->item('default_newsfeed_view'))));?> messages fd-autoscroll" data-url="/profile/main/get_newsfeed_new/<?=$category_type?>/<?=$topic_uri_name?>?sort_by=<?=$this->input->get('sort_by',true,'time');?>&drop_type=<?=$drop_type?>" <?php /*style="opacity:0"*/?>>
				<?=modules::run('profile/main/get_newsfeed_new', $category_type, $topic_uri_name, $this->input->get('view',true,$this->config->item('default_newsfeed_view')), '', '', $drop_type,$this->input->get('sort_by',true,'time'));?>
		    	<ul id="CommentTemplateList">
					<li class="templateComment">
						<img class="commentAvatar" src="" alt="">
						<p class="commentStatus"><a href=""></a></p>
						<p class="commentTime"><?=convert_datetime(date("Y-m-d H:i:s"))?></p>
					</li>
				</ul>
			    <?=$this->load->view('newsfeed/newsfeed_delete_popup','',true)?>
			</ul>
			<a id="ScrollToTop" href="#top" class="Button WhiteButton Indicator" style="display:none"><strong><?=$this->lang->line('home_views_scroll_to_top_btn');?></strong><span></span></a>
	    </div>
	    <div class="comments_container_bot"></div>
	</div>
	<div class="grid_ omega" id="home_right_column">
	    <div id="user_info">
			<a href="/followings/<?=$this->session->userdata('id')?>">
			<div class="inlinediv info_item">
			    <div class="info_item_title"><?=$this->lang->line('home_views_following_lexicon');?></div>
			    <div class="info_count"><?=$this->user->following?></div>
			</div>
			</a>
			<a href="/followers/<?=$this->session->userdata('id')?>">
			<div class="inlinediv info_item"><? /* Add "middle" to the classes when Interest Pages comes back */?>
			    <div class="info_item_title"><?=$this->lang->line('home_views_followers_lexicon');?></div>
			    <div class="info_count"><?=$this->user->follower?></div>
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
		<? $this->benchmark->mark('activity_html_start');?>
		<?php if (!$cache = $this->cache->get('home_activity_'.$this->user->id)) { ob_start()?>
	    <div class="sidebar_list">
			<div class="sidebar_comments">
			    <ul id="home_friends_newsfeed" class="newsfeed home_right_newsfeed newsfeed_placeholder typeViewFull news_ticker fd-autoscroll">
				<? foreach ($ticker as $fkey => $activity)
				{
					echo $this->load->view('newsfeed/activity', array('view'=>'home','activity'=>$activity), true);
				} ?>
				<? if (count($ticker) <= 0) { ?>
					<div class="blank_post"><?=$this->lang->line('home_views_no_activity_lexicon');?></div>
				<? } ?>
				<? if(count($ticker) > 29) {?>
					<div id="activity_feed_bottom" class="feed_bottom">
						<a class="more_news_link" href="#"><?=$this->lang->line('home_views_more_news_lexicon');?></a>
						<div class="last_timestamp" style="display: none;"><?=end($ticker)->time?></div>
						<div class="category_type" style="display:none"><?=$category_type?></div>
						<div class="view" style="display:none"><?=$this->lang->line('home_views_home_lexicon');?></div>
					</div>
				<? } ?>
				</ul>
			</div>
	    </div>
		<?php $cache = ob_get_clean(); $this->cache->save('home_activity_'.$this->user->id, $cache, $this->config->item('cache_expire')); } echo $cache?>
		<? $this->benchmark->mark('activity_html_end');?>
	    <?php //} ?>
	    <div class="home_right_column_bot"></div>
	</div>
	
	
	<!--~~~~~~~~~~~ Placeholder for wall/photo post input sections ~~~~~~~~~~~~~~~-->
	<div id="profile_post_section">
	<? $profile_id = $this->session->userdata['id']; ?>
	</div>
	
	<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ wallpost from loop ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	<div id="show_loop" style="display: none;">
	    
	</div>
    </div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<? //Load view for selecting a folder to collect into ?>
<?php echo $this->load->view('folder/collect','', true)?>

<? //Create New List dialog ?>
<div id="new_list_dialog" class="dialog" style="display: none">
    <ul>
	<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
	<? echo Form_Helper::form_open('save_list/'); ?>
	<? echo form_hidden('from',$this->uri->segment(3)); ?>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_list_name_lbl');?></div>
	    <div class="form_field" style="width: 365px"><? echo Form_Helper::form_input('list_name');?></div>
	</li>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_description_lbl');?></div>
	    <div class="form_field"><textarea name="description" rows="12" cols="70"></textarea></div>
	</li>
	<li>
	    <div class="form_label"><?=$this->lang->line('home_views_list_visibility_lbl');?></div>
	    <div class="form_field">
		<div><? echo form_radio('privacy', 'public', TRUE); ?> <?=$this->lang->line('home_views_public_lexicon');?></div>
		<div><? echo form_radio('privacy', 'private'); ?> <?=$this->lang->line('home_views_private_lexicon');?></div>
	    </div>
	</li>
	<ul id="my_pages">
	    <li><?=$this->lang->line('home_views_choose_pages_lexicon');?></li>
	    <? if (isset($my_pages) && is_array($my_pages)) foreach($my_pages as $key=>$item) { ?>
	    <li><? echo form_checkbox('check_pages[]', $item['page_id']); ?> <a href="/interests/uri_name/<?=$item['page_id']?>"><?=$item['page_name']?> </a></li>
	    <? } ?>
	    </ul>
	<li><div class="form_label"><? echo Form_Helper::form_submit('submit', $this->lang->line('home_views_submit_lexicon')); ?></div></li>
	<? echo form_close();?>
	</ul>
</div>

<div id="link_pane" style="display:none"><iframe style="width:100%; height:100%; overflow:hidden"></iframe></div>

<? $loop_tabs =  isset($my_loops) ? $my_loops : @$loops; ?>
<? $loop_tabs[]['loop_id'] = '0'; ?>
<? //$all_lists = array_merge((array)$my_lists, (array)$follow_lists); ?>
<script type="text/javascript">
	php.baseUrl = '<?=base_url()?>';
	php.loop_tabs = <?=json_encode($loop_tabs)?>;
	php.profile_id = '<?=$profile_id?>';
	php.content_url = '/get_more_newsfeed?type=interests_feed&view=home&view_type=page_view_timeline';
</script>
<?=requireJS(array("home/home_main","common/autoscroll_new"))?> 
