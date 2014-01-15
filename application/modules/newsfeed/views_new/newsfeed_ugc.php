<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $is_liked = $newsfeed->is_liked($this->user); ?>
<? $is_sample = $newsfeed->newsfeed_id <= 0; ?>
<?php if ($is_sample) echo '<script type="template/html" id="js-newsfeed_ugc"
		data-newsfeed_id=".share_fb_app @data-newsfeed_id,.newsfeed_entry @data-newsfeed_id, .upvote @href, .downvote @href"
		data-description=".js-description "
		data-link_type=".newsfeed_entry @data-link_type"
		data-url=".newsfeed_entry @data-url"
		data-img_width=".newsfeed_entry @data-img_width"
		data-img_height=".newsfeed_entry @data-img_height"
		data-user_from-_avatar_25=".user_from_avatar @src"
		data-user_from-full_name=".newsfeed_dropInfo_dropUser"
		data-user_from-_url=".newsfeed_dropInfo_nameAndDateLink @href"
		data-styled_time=".newsfeed_dropInfo_dropDate"
		data-up_count=".js_upvotes_count"
		data-collect_count=".js_collect_count"
		data-_source = ".itemDroppedVia_Title a @href, .itemDroppedVia_Title a"
	> ' ?>
<li class="newsfeed_entry" <?=Html_helper::item_data($newsfeed, array('newsfeed_id', 'url', 'link_type', 'coversheet_updated', 'img_width', 'img_height'))?>>
	<div class="newsfeed_dropContent" <?=$newsfeed->link_type == 'content' || $is_sample ? Html_helper::link_preview_popup_data($newsfeed) : ''?>>
		<h2 class="js-description"><?=$newsfeed->description?></h2>
		<div class="newsfeed_dropInfo">
			<div class="newsfeed_dropInfoContent">
				<a href="<?=$newsfeed->user_from->url?>" class="newsfeed_dropInfo_nameAndDateLink inlinediv">
					<?=Html_helper::img($newsfeed->user_from->avatar_42, array("class"=>"user_from_avatar"))?>
					<span class="newsfeed_dropInfo_nameAndDate inlinediv">
						<span class="newsfeed_dropInfo_dropUser"><?=$newsfeed->user_from->full_name?></span>
						<span class="newsfeed_dropInfo_dropDate"><?=date('F d', strtotime($newsfeed->time))?></span>
					</span>
				</a>
				<? if ($newsfeed->can_edit($this->user) || $is_sample) { ?>
					<? //RR - remove popup - http://dev.fantoon.com:8100/browse/FD-4735?>
					<!--
					<a href="/manage_lists/<?=$newsfeed->folder->folder_id?>?edit=<?=$newsfeed->url?>" title="Edit Post" class="actionButton edit js_edit_newsfeed">
						<?=$this->lang->line('edit')?>
					</a>
					-->
					<a href="#newsfeed_popup_edit" title="<?=$this->lang->line('newsfeed_views_edit_post_lexicon');?>" class="actionButton edit js_edit_newsfeed custom-title" rel="popup"><?=$this->lang->line('newsfeed_views_edit_post_lexicon');?></a>
				<? } ?>
				<div class="actions inlinediv">
					<span class="upbox">
						<?php $is_liked = $newsfeed->is_liked( $this->user ); ?>
						<a href="/add_like/drop/<?=$newsfeed->newsfeed_id?>" style="<?=$is_liked ? 'display: none;' : '';?>"
							class="actionButton vote upvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('newsfeed_views_upvote_lexicon');?>" title-pos="bottom" 
						>
							<span class="ico"></span><span class="num js_upvotes_count"><?=$newsfeed->up_count?></span>
						</a>
						<a href="/rm_like/drop/<?=$newsfeed->newsfeed_id?>" style="<?=$is_liked ? '' : 'display: none;';?>"
							class="actionButton vote downvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('newsfeed_views_downvote_lexicon');?>" title-pos="bottom"
						>
							<span class="ico"></span><span class="num js_upvotes_count"><?=$newsfeed->up_count?></span>
						</a>
					</span>
					<a href="#collect_popup" class="actionButton redrop_button custom-title" rel="popup" title="<?=$this->lang->line('newsfeed_views_redrop_lexicon');?>">
						<span class="ico"></span><span class="num js_collect_count"><?=$newsfeed->collect_count?></span>
					</a>
					<?=Html_helper::fb_share_btn($folder)?>
					<?=Html_helper::twitter_btn($folder)?>
				</div>
			</div>
		</div>

		<div class="newsfeed_dropContent_container">
			<? if ($is_sample) { ?>
				<div class="photo-container" style="display:none">
					<div>
						<img src="" class="drop-preview-img" alt="" onerror="if (this.src.indexOf('_576') > -1) this.src = this.src.replace('_576', '_full'); else if (this.src.indexOf('_full') > -1) this.src = this.src.replace('_full','');"/>
					</div>
				</div>
				<span class="play_container" style="display:none">
					<span class="play_button"></span>
				</span>
				<p class="text-container" style="display:none"></p>
			<? } elseif ($newsfeed->link_type == 'text') { ?>
				<p class="text-container"><?=$newsfeed->activity->content?></p>
			<? } else { ?>
				<div class="photo-container <?=$newsfeed->link_type=='image' ? ' watermarked' : ''?>">
					<div>
						<?=Html_helper::img($newsfeed->img_576, array(
							'onerror' => "if (this.src.indexOf('_576') > -1) this.src = this.src.replace('_576', '_full'); else if (this.src.indexOf('_full') > -1) this.src = this.src.replace('_full','');",
							'class'=>"drop-preview-img"
								.($newsfeed->link_type!='embed'&&$newsfeed->complete&&preg_match('/(jpg|jpeg|png)$/i', $newsfeed->img_thumb) ? ' has_zooming' : '')
								.($newsfeed->complete ? ' complete' : ''),
								'alt' => strip_tags(str_replace('"', "'", $newsfeed->description)),
						))?>
					</div>
				</div>
				<? if ($newsfeed->link_type === 'embed') { ?>
					<span class="play_container" data-link_type="<?=$newsfeed->link_type;?>">
						<span class="play_button"></span>
					</span>
				<? } ?>
			<? } ?>
		</div>
		<? if ($is_sample || $newsfeed->source != "") { ?>
			<div class="itemDroppedVia_Title">
				<span><?=ucfirst($this->lang->line('via'));?></span>
				<a href="http://<?=($newsfeed->source);?>" target="_blank" class="itemDroppedVia_Title"><?=$newsfeed->source;?></a>
			</div>
		<? } else { ?>
			<div class="itemDroppedVia_Title">
				<span style="display: none"><?=ucfirst($this->lang->line('via'));?></span>
				<a href="" target="_blank" class="itemDroppedVia_Title"></a>
			</div>
		<? } ?>
	</div>
</li>
<?=$is_sample ? '</script>' : '' ?>