<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div class="js_folder folderItem">
	<?php if (@$folder->recent_newsfeeds[0]) {?>
		<?php if (@$folder->recent_newsfeeds[0]->link_type == 'text') { ?>
			<a href="<?=$folder->folder_url?>" data-folder_id="<?=$folder->folder_id?>" class="textContainer">
				<span><?=$folder->recent_newsfeeds[0]->text?></span>
			</a>
		<?php } else { ?>
			<a href="<?=$folder->folder_url?>" data-folder_id="<?=$folder->folder_id?>" class="imageContainer <?=$folder->recent_newsfeeds[0]->link_type=='image' ? ' watermarked' : ''?>">
				<?=Html_helper::img($folder->recent_newsfeeds[0]->{'img_'.$size}, array(
					'data-newsfeed_id' => $folder->recent_newsfeeds[0]->newsfeed_id,
					'onerror' => "if (this.src.indexOf('_320') > -1) this.src = this.src.replace('_320','_thumb'); else if (this.src.indexOf('150') > -1) this.src = this.src.replace('_150','_tile'); "
				))?>
			</a>
		<?php } ?>
	<?php } ?>
	<div class="info">
		<a href="<?=$folder->folder_url?>" data-folder_id="<?=$folder->folder_id?>" class="infoTitle"><?=$folder->folder_name?></a>
		<!--
		<div class="writtenBy">
			<?php if ($folder->user) { ?>
				<?=$this->lang->line('folder_written_by_lexicon');?> <a href="<?=$folder->user->url?>"><?=$folder->user->full_name?></a>
			<?php } ?>
		</div>
		-->
	</div>
	<div class="actions">
		<a href="<?=$folder->folder_url?>" class="more" style="display: none;"><?=$this->lang->line('folder_more_lexicon');?></a>
		<span class="upbox">
			<?php $is_liked = $folder ? $folder->is_liked($this->session->userdata('id')) : false ?>
			<a href="/add_like/folder/<?=$folder->folder_id?>" class="actionButton vote upvote custom-title" rel="ajaxButton" style="<?=$is_liked ? 'display:none' : ''?>" title="Upvote Story" title-pos="bottom">
				<span class="ico"></span><span class="num js_upvotes_count"><?=$folder->total_upvotes?></span>
			</a>
			<a href="/rm_like/folder/<?=$folder->folder_id?>" class="actionButton vote downvote custom-title" rel="ajaxButton" style="<?=$is_liked ? '' : 'display:none'?>" title="Remove Upvote" title-pos="bottom">
				<span class="ico"></span><span class="num js_upvotes_count"><?=$folder->total_upvotes?></span>
			</a>
		</span>
		<?php if ($folder) { ?>
			<?php // echo Html_helper::fb_share_btn($folder, array('class'=>"custom-title",'title'=>"Share on Facebook", 'title-pos'=>"bottom"))?>
			<?php // echo Html_helper::twitter_btn($folder, array('class'=>"custom-title",'title'=>"Share on Twitter", 'title-pos'=>"bottom"))?>	
		<?php } ?>
	</div>
</div>