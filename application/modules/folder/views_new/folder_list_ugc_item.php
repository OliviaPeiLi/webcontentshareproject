<? $this->lang->load('folder/folder', LANGUAGE); ?>
<?php if ($folder->folder_id <= 0) echo '<script type="template/html" id="js-folder_ugc"
	data-folder_id = ".upbox a @href, .edit @href, .fb_share_collection @data-folder_id"
	data-folder_name = "h2 a, .share_twt_app @data-text"
	data-_folder_url = "h2 a @href, .newsfeed_dropInfo .newsfeed_dropInfo_nameAndDateLink @href, .fb_share_collection @data-url, .share_twt_app @data-url"
	data-_user-_url = ".newsfeed_dropInfo_nameAndDateLink @href"
	data-user-avatar = ".newsfeed_dropInfo_nameAndDateLink img @src"
	data-_user-_full_name = ".newsfeed_dropInfo_dropUser"
	data-created_at = ".newsfeed_dropInfo_dropDate"
	data-total_upvotes = ".js_upvotes_count"
>' ?>
<li class="folder_ugc">
	<h2><a href="<?=$folder->folder_url?>"><?=$folder->folder_name?></a></h2>
	<div class="hashtags">
		<a href="<?=$folder->user->url?>" class="newsfeed_dropInfo_nameAndDateLink inlinediv">
			<?=Html_helper::img($folder->user->avatar_42)?>
			<span class="newsfeed_dropInfo_nameAndDate inlinediv">
				<span class="newsfeed_dropInfo_dropUser"><?=$folder->user->full_name?></span>
				<span class="newsfeed_dropInfo_dropDate"><?=date('F d', strtotime($folder->created_at))?></span>
			</span>
		</a>
		<span class="hashtagContainer inlinediv">
			<? foreach ($folder->folder_hashtags as $i=>$folder_hashtag) { if (!$folder_hashtag->hashtag) continue; ?>
				<a href="<?=$folder_hashtag->hashtag->hashtag_url?>" class="hashtagUnit inlinediv"><?=str_replace('#', '', $folder_hashtag->hashtag->hashtag_name)?></a>
			<? } ?>
		</span>
	</div>
	<div class="newsfeed_dropInfo">
		
		<div class="actions inlinediv">
			<? if ($folder->can_edit($this->user) || $folder->folder_id <= 0) { ?>
				<a href="/manage_lists/<?=$folder->folder_id?>" title="<?=$this->lang->line('folder_edit_folder_popup_title');?>" class="actionButton edit custom-title" title-pos="bottom">
					<?=$this->lang->line('folder_edit_folder_popup_title');?>
				</a>
			<? } ?>
			<span class="upbox">
				<?php $is_liked = $folder->is_liked($this->session->userdata('id')); ?>
				<a href="/add_like/folder/<?=$folder->folder_id?>" style="<?=$is_liked ? 'display: none;' : '';?>"
					class="actionButton vote upvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('folder_upvote_story_lexicon');?>" title-pos="bottom"
				>
					<span class="ico"></span><span class="num js_upvotes_count"><?=$folder->total_upvotes?></span>
				</a>
				<a href="/rm_like/folder/<?=$folder->folder_id?>" style="<?=$is_liked ? '' : 'display: none;';?>" 
					class="actionButton vote downvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('folder_remove_upvote_story_lexicon');?>" title-pos="bottom"
				>
					<span class="ico"></span><span class="num js_upvotes_count"><?=$folder->total_upvotes?></span>
				</a>
			</span>
			<?=Html_helper::fb_share_btn($folder)?>
			<?=Html_helper::twitter_btn($folder)?>
		</div>
	</div>
	<div class="newsfeed_dropContent">
		<?php $no_drops = count($folder->recent_newsfeeds) == 0 && $is_profile == true && $folder->folder_id > 0;?>
			<div class="no_drops_in_list" style="<?=$no_drops ? 'display:block': 'display: none' ;?>"><?=$this->lang->line('folder_no_posts_lexicon');?></div>
			<div class="newsfeed_dropContent_container container drops_num_<?=count($folder->recent_newsfeeds);?>" style="<?=$no_drops ? 'display:none': 'display: block' ;?>">
				<div class="newsfeed_upperContent">
					<? if ($folder->folder_id <= 0) { ?>
						<div class="photo-container" style="display:none">
							<div>
								<img src="" class="drop-preview-img" alt=""/>
							</div>
						</div>
						<span class="play_container" style="display:none">
							<span class="play_button"></span>
						</span>
						<p class="text-container" style="display:none"></p>
					<? } elseif (!isset($folder->recent_newsfeeds[0])) { ?>
						<div></div>
					<? } elseif ($folder->recent_newsfeeds[0]->link_type == 'text') { ?>
						<p class="text-container"><?=$folder->recent_newsfeeds[0]->text?></p>
					<? } else { ?>
						<div class="photo-container <?=$folder->recent_newsfeeds[0]->link_type == 'image' ? 'watermarked' : ''?>">
							<div>
								<?=Html_helper::img($folder->recent_newsfeeds[0]->img_576, array(
									'class'=>"drop-preview-img", 
									'onerror' => "if (this.src.indexOf('_576') > -1) this.src = this.src.replace('_576', '_full'); else if (this.src.indexOf('_full') > -1) this.src = this.src.replace('_full','');",
									'alt' =>strip_tags($folder->recent_newsfeeds[0]->description_plain),
									'data-type'=>$folder->recent_newsfeeds[0]->link_type,
									'data-vurl'=>($folder->recent_newsfeeds[0]->link_type === 'embed' ? '/bookmarklet/snapshot_preview/' . $folder->recent_newsfeeds[0]->newsfeed_id : '')
								)) ?>
							</div>
						</div>
						<? if ($folder->recent_newsfeeds[0]->link_type === 'embed') { ?>
							<span class="play_container">
								<span class="play_button"></span>
							</span>
						<? } ?>
					<? } ?>
				</div>
				<div class="row newsfeed_lowerContent">
					<?php for ($i=1;$i<4;$i++) {?>
					<div class="span6">
						<? if ($folder->folder_id <= 0) { ?>
							<div class="photo-container" style="display:none">
								<img src="" class="drop-preview-img" alt=""/>
							</div>
							<span class="play_container" style="display:none">
								<span class="play_button"></span>
							</span>
							<p class="text-container" style="display:none"></p>
						<? } elseif (!isset($folder->recent_newsfeeds[$i])) { ?>
							<div></div>
						<? } elseif ($folder->recent_newsfeeds[$i]->link_type == 'text') { ?>
							<p class="text-container"><?=$folder->recent_newsfeeds[$i]->text?></p>
						<? } else { ?>
							<div class="photo-container <?=$folder->recent_newsfeeds[$i]->link_type ? 'watermarked' : ''?>">
								<?=Html_helper::img($folder->recent_newsfeeds[$i]->img_thumb, array('class'=>"drop-preview-img", 'alt' => strip_tags($folder->recent_newsfeeds[$i]->description_plain)))?>
							</div>
							<? if ($folder->recent_newsfeeds[$i]->link_type === 'embed') { ?>
								<span class="play_container">
									<span class="play_button"></span>
								</span>
							<? } ?>
						<? } ?>
					</div>
					<?php } ?>
				</div>
			</div>
	</div>
</li>
<?=$folder->folder_id <= 0 ? '</script>' : '' ?>