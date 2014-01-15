<? $this->benchmark->mark('folder_folder_ugc_start'); ?>
<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div class="folderTop" id="folderTop">
	<div class="row infoContainer">
		<div class="span24 gfOverride">
			<div class="row-fluid info">
				<div class="span24">
					<h1><?=$folder->folder_name?></h1>
					
				</div>
			</div>
		</div>
		<div class="hashtags">
			<div class="span24 gfOverride">
				<? foreach ($folder->folder_hashtags as $i=>$folder_hashtag) { if (!$folder_hashtag->hashtag) continue; ?>
					<a href="<?=$folder_hashtag->hashtag->hashtag_url?>" class="inlinediv"><?=str_replace('#', '', $folder_hashtag->hashtag->hashtag_name)?></a>
				<? } ?>
			</div>
		</div>
	</div>
	<div class="row infoContainer_bottom">
		<div class="span24 gfOverride">
			<div class="row-fluid info">
				<div class="row-fluid">
					<div class="drop-deets span11">
						<div class="info_nameContainer">
							<?=Html_helper::img($folder->user->avatar_42)?>
							<div class="info_nameAndDate inlinediv">
								<a href="<?=$folder->user->url?>" class="info_folderUser"><?=$folder->user->full_name?></a>
								<div class="info_folderDate"><?=date('F d', strtotime($folder->created_at))?></div>
							</div>
						</div>
						<div class="inlinediv">
							<div class="info_folderCounters">
								<? /* <span class="info_folderViewcount"><span class="ico"></span><span class="num"><?= Text_Helper::restyle_text((int) $folder->get_total_hits())?> <?=$this->lang->line('views');?></span></span> */ ?>
								<? /* ?><span class="info_folderDropcount"><span class="ico"></span><span class="num"><span class="js-total-redrop-count"><?=Text_Helper::restyle_text((int)$folder->newsfeeds_count)?></span> <?=$this->lang->line('posts');?></span></span><? */ ?>
							</div>
						</div>
					</div><!--/.drop-deets-->
					<div class="span13">
						<div class="actions">
							<? if ($folder->can_edit($this->user) || $folder->folder_id <= 0) { ?>
								<a href="/manage_lists/<?=$folder->folder_id?>" class="actionButton edit custom-title" title="Edit Story" title-pos="bottom">
									<span class="ico"></span>
									<?=$this->lang->line('folder_edit_story_lexicon');?>
								</a>
							<? } ?>
							<?php if ($this->user && $this->user->role == 2 && $this->is_mod_enabled('landing_ugc')) { ?>
								<a href="/rem_landing_folder/<?=$folder->folder_id?>" rel="ajaxButton" class="actionButton rem_landing_folder custom-title" style="<?=$folder->is_landing ? '' : 'display:none'?>" title="<?=$this->lang->line('folder_remove_from_land_lexicon');?>"><?=$this->lang->line('folder_remove_from_land_lexicon');?></a>
								<a href="/set_landing_folder/<?=$folder->folder_id?>" rel="ajaxButton" class="actionButton set_landing_folder custom-title" style="<?=$folder->is_landing ? 'display:none' : ''?>" title="<?=$this->lang->line('folder_add_to_land_lexicon');?>"><?=$this->lang->line('folder_add_to_land_lexicon');?></a>
							<?php } ?>
							<span class="upbox">
								<?php $is_liked = $folder->is_liked($this->session->userdata('id')) ?>
								<a href="/add_like/folder/<?=$folder->folder_id?>" style="<?=$is_liked ? 'display:none' : ''?>"
									class="actionButton vote upvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('folder_upvote_lexicon');?>" title-pos="bottom"
								>
									<span class="ico"></span><span class="num"><?=$folder->total_upvotes?></span>
								</a>
								<a href="/rm_like/folder/<?=$folder->folder_id?>" style="<?=$is_liked ? '' : 'display:none'?>"
									class="actionButton vote downvote custom-title" rel="ajaxButton" title="<?=$this->lang->line('folder_downvote_lexicon');?>" title-pos="bottom" 
								>
									<span class="ico"></span><span class="num"><?=$folder->total_upvotes?></span>
								</a>
							</span>
							<?=Html_helper::fb_share_btn($folder)?>
							<?=Html_helper::twitter_btn($folder)?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="folder" class="container">
	<div class="row folderLower">
		<div class="span15">
			<div class="row" style="display:none;">
				<span class="span3">
					<a href=""><?=$this->lang->line('folder_most_popular_lexicon');?></a>
				</span>
				<span class="span3">
					<a href=""><?=$this->lang->line('folder_newest_first_lexicon');?></a>
				</span>
				<span class="span3">
					<a href=""><?=$this->lang->line('folder_oldest_first_lexicon');?></a>
				</span>
				<span class="span3 pull-right">
					<?=$folder->total_hits?>
				</span>
				<span class="span3 pull-right">
					<?=$folder->total_upvotes?>
				</span>
			</div>
			<div class="drops">
				<?
					$cache_name = 'folder_folder_newsfeed_index_folder_'.$folder->folder_id.'_filter_'.$filter;
					if(!$cache = $this->cache->get($cache_name)) {
						ob_start();
						echo modules::run('folder/folder_newsfeed/index', $folder->folder_id, $filter);
						$cache = ob_get_clean();
						$this->cache->save($cache_name, $cache);
					}
					print $cache;
				?>
			</div>
		</div>
		<div class="span8">
			<div style="display:none;">
				<?php $is_liked = $folder->is_liked($this->user->id)?>
				<span class="upbox">
					<a href="/add_like/folder/<?=$folder->folder_id?>" class="actionButton vote upvote">
						<span class="ico"></span><span class="num"><?=$folder->upvotes_count?></span>
					</a>
					<a href="/rm_like/folder/<?=$folder->folder_id?>" class="actionButton vote downvote" style="display: none;">
						<span class="ico"></span><span class="num"><?=$folder->upvotes_count?></span>
					</a>
				</span>
				<?=Html_helper::twitter_btn($folder)?>
				<?=Html_helper::fb_share_btn($folder)?>
			</div>
			<div class="folder_commentsPanel">
				<?
					$cache_name = 'comment_comment_folder_comments_folder_id_'.$folder->folder_id;
					if(!$cache = $this->cache->get($cache_name)) {
						ob_start();
						echo modules::run('comment/comment/folder_comments', $folder->folder_id);
						$cache = ob_get_clean();
						$this->cache->save($cache_name, $cache);
					}
					print $cache;
				?>
			</div>
		</div>
	</div>
</div>
<?php echo Html_helper::requireJS(array("folder/folder_ugc","folder/folder_main"))?>
<? $this->benchmark->mark('folder_folder_ugc_end'); ?>