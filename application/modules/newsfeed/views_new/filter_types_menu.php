<? $this->lang->load('newsfeed/view_modes_menu', LANGUAGE); ?>
<div id="collection_media_filter" title="<?=$this->lang->line('view_modes_filter_title');?>">  
	<a href="">
		<span id="filter_by_menu_trigger" class="ft-dropdown ft-dropdown-hover menu_caller" rel="media_filter_menu" data-url="<?=$base?>">
			<span><?=$this->lang->line('view_modes_filter_text')?></span><span class="menuButton"><span class="menuButton_contents"></span></span>
		</span>
	</a>
	<!-- <ul id="media_filter_menu" rel="menu" class="feed_media_filter" help="<?=$this->lang->line('view_modes_filter_help');?>" title="<?=$this->lang->line('view_modes_filter_title');?>" pos_my="right top" pos_at="left center" style="display:none;"> -->
	<div id="media_filter_menu" class="feed_media_filter" title="<?=$this->lang->line('view_modes_filter_title');?>" style="display:none">
		<span class="sort_menu_arrow"></span>
		<ul class="sort_list_contents">
			<?php $get = $this->input->get() ?  '?'.http_build_query($this->input->get()) : ''?>
			<? /* ?>
			<li class="filter_option">
				<a href="<?=$base?>" id="antifilter" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_all');?>">
					<div class="inlinediv">All Media</div><div id="feed_all" class="filter_media_type inlinediv"></div>
				</a>
			</li><? */ ?><? /* ?>Uncomment this to show the "All Media" option in the filter Menu--M.E. Jan 24, 2013<? */ ?>
			<li class="filter_option">
				<a href="<?=$base?>/type/pictures<?=$get?>" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_images')?>">
					<span class="inlinediv">Image</span><span id="feed_image" class="filter_media_type inlinediv"></span>
				</a>
			</li>
			<li class="filter_option">
				<a href="<?=$base?>/type/clips<?=$get?>" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_clips')?>">
					<span class="inlinediv">HTML</span><span id="feed_HTML" class="filter_media_type inlinediv"></span>
				</a>
			</li>
			<?php if ($this->is_mod_enabled('live_drops')) { ?>
				<li class="filter_option">
					<a href="<?=$base?>/type/live_drops<?=$get?>" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_live_drops')?>">
						<span class="inlinediv">Live</span><span id="feed_RSS" class="filter_media_type inlinediv"></span>
					</a>
				</li>
			<?php } ?>
			<li class="filter_option">
				<a href="<?=$base?>/type/videos<?=$get?>" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_videos')?>">
					<span class="inlinediv">Video</span><span id="feed_video" class="filter_media_type inlinediv"></span>
				</a>
			</li>
			<li class="filter_option">
				<a href="<?=$base?>/type/texts<?=$get?>" class="inlinediv" title="<?=$this->lang->line('view_modes_filter_type_text')?>">
					<span class="inlinediv">Text</span><span id="feed_text" class="filter_media_type inlinediv"></span>
				</a>
			</li>
		</ul>
	</div>
</div>
