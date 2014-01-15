<? //RR - We shouldnt use ->activity->*  here - it ruins the performance ?>
<? /*
$add_help = '';
if ($view === 'page' || $view === 'interest') {
	$add_help = 'title="" help="'.$this->lang->line('newsfeed_views_interest_add_help').'" pos_my="left bottom" pos_at="right center" helpgroup="interesttile"'; 
} else if ($view === 'folder') {
	$add_help = 'title="" help="'.$this->lang->line('newsfeed_views_folder_add_help').'" pos_my="bottom left" pos_at="top right" helpgroup="foldertile"';
}
*/ ?>
<?php
	$is_sample = $newsfeed->newsfeed_id <= 0;
	$is_landing = ! (bool) $this->session->userdata('id');
 	if ($is_landing) {
 		$this->user = null;
 	}
?>
<?= ($is_sample) ?
	'<script type="text/html" id="js-newsfeed_postcard"
		data-newsfeed_id = ".newsfeed_entry @data-newsfeed_id, .up_button @href, .undo_up_button @href, .share_twt_app @data-url, .fb-like @href, .item_left_cell a @href, .share_fb_app @data-newsfeed_id"
		data-description = ".drop-description"
		data-url = ".newsfeed_entry @data-url"
		data-_description_plain = ".drop_desc_plain, .share_twt_app @data-text, .newsfeed_entry @title"
		data-type =  ".newsfeed_entry @post-type"
		data-coversheet_updated = ".newsfeed_entry @data-coversheet_updated"
		data-img_width = ".newsfeed_entry @data-img_width"
		data-img_height = ".newsfeed_entry @data-img_height"
		data-_img_thumb = ".postcard_img_wrapper img @src"
		data-up_count = ".up_count"
		data-comment_count = " .num_comments"
		data-collect_count = ".js-redrop_count"
		data-_link_type_class = ".newsfeed_entry @class, .postcard_contents @data-class, .tl_icon @class"
		data-folder-folder_url = ".folder-url @href"
		data-folder-folder_name = ".folder-url"
		data-user_from-_url = " .drop-popup-user @href"
		data-user_from-_avatar_42 = ".drop-popup-user @data-avatar_42"
		data-user_from-full_name = ".drop-popup-user @data-full_name, .drop-popup-user"
		data-_source = ".itemDroppedVia_Title a @href, .itemDroppedVia_Title a"
		data-complete = ".drop-preview-img @data-complete"
	>' : '' ?>
<? //DK: IMPORTANT: PLEASE DO NOT REMOVE LINK_TYPE and TYPE ATTRIBUTES, THESE ARE NEEDED TO SET PROPER HEIGHT OF NEWSFEED ITEMS IN CSS ?>
<li class="postcard_entry newsfeed_entry <?=$newsfeed->link_type_class?> <?=$newsfeed->is_shared($this->user) ? 'liked' : ''?>"
	title="<?=str_replace('"', "'", strip_tags($newsfeed->description))?>"
	<? /* ?>type="<?=$newsfeed->link_type?>"<? */ ?>
	<?=Html_helper::item_data($newsfeed,  array('newsfeed_id', 'url', 'coversheet_updated', 'img_width', 'img_height'))?>
	<?//=$add_help?>
>
	<div class="postcard_actions">
		<div class="upbox">
			<?php $is_liked = $newsfeed->is_liked($this->user); ?>
			<a href="/add_like/drop/<?=$newsfeed->newsfeed_id?>" class="up_button" rel="ajaxButton" style="<?=$is_liked ? 'display: none' : ''?>">
				<span class="upvote_wrapper">
					<span class="upvote_contents"></span>
				</span>
				<? /* ?><div class="upvote_text">Upvote</div><? */ ?>
			</a>
			<a href="/rm_like/drop/<?=$newsfeed->newsfeed_id?>" class="undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display: none'?>">
				<span class="downvote_wrapper">
					<span class="downvote_contents"></span>
				</span>
				<? /* ?><div class="upvote_text">Upvoted</div><? */ ?>
			</a>
			<div class="up_count <?=$is_liked ? 'unlike' : 'like'?>"><?=$newsfeed->up_count?></div>
		</div>
		<div class="redropbox">
			<a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" title="Redrop">
				<span class="redrop_wrapper">
					<span class="redrop_contents"></span>
				</span>
				<? /* ?><div class="redrop_text">Redrop</div><? */ ?>
			</a>
			<div class="js-redrop_count redrop_count"><?=$newsfeed->collect_count?></div>
		</div>
	</div>
	<div class="postcard_contents pop_URL link-popup" <?=Html_helper::link_preview_popup_data($newsfeed)?>>
		<div class="item_top">
			<span class="<?=$newsfeed->link_type_class?> tl_icon"></span>
			<div class="post_caption drop-description"><?=$newsfeed->description?></div>
			<div class="drop_desc_plain" style="display:none"><?=strip_tags($newsfeed->description)?></div>
			<div class="postDetail_drop">
				<?php if ($newsfeed->folder || $is_sample) { ?>
					<?=$this->lang->line('newsfeed_views_dropped_this_in_lexicon')?>
					<a href="<?=$newsfeed->folder->get_folder_url()?>" class="folder-url" ><?=$newsfeed->folder->folder_name?></a>
				<?php } ?>
			</div>
			<div class="postDetail_user">
				By 
				<a href="<?=$newsfeed->user_from->url?>" class="drop-popup-user" <?=Html_helper::item_data($newsfeed->user_from, array('avatar_42', 'full_name'))?>>
					<?=(@$newsfeed->user_from->id == @$profile_id) ?  @$newsfeed->user_from->first_name : @$newsfeed->user_from->full_name;?>
				</a>
				<? if(@$newsfeed->user_from->role == '1'){ ?>
					<span class="roleTitle"><? /* ?><?=$this->lang->line('staff_writer');?><? */ ?></span>
				<? } elseif(@$newsfeed->user_from->role == '3'){ ?>
					<span class="roleTitle"><? /* ?><?=$this->lang->line('featured_user');?><? */ ?></span>
				<? } elseif ($is_sample) { ?>
					<span class="roleTitle js-staff_writer" style="display:none"><?=$this->lang->line('staff_writer');?></span>
					<span class="roleTitle js-featured_user" style="display:none"><?=$this->lang->line('featured_user');?></span>
				<? } ?>
				<? /* ?>
				<span class="drop-time"><?=Date_Helper::time_ago($newsfeed->time)?></span>
				<? */ ?>
				<span class="topPost_actions">
					<? if ($newsfeed->can_edit($this->user) || $is_sample) { ?>
						<span class="divIder"></span>
						<a href="#newsfeed_popup_edit" title="Edit Drop" class="newsfeed_edit_lnk" rel="popup">
							<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn')?></span>
						</a>
					<? } ?>
					<? /* ?>
					<div class="stat stat_redrops redrop_count inlinediv" style="" rel="<?=$newsfeed->collect_count?>"><span class="icon"></span><span class="num"><?=$newsfeed->collect_count?></span></div>
					<div class="stat stat_comment inlinediv" style="" rel="<?=$newsfeed->comment_count?>"><span class="icon"></span><span class="num"><?=$newsfeed->comment_count?></span></div>
					<? */ ?>
				</span>
				<span class="ext_share">
					<?=Html_helper::twitter_btn($newsfeed, array());?>
					<span class="ext_fb_default share_btn">
						<?php // $this->session->userdata('id') ? Html_helper::fb_share_btn($newsfeed) : Html_helper::fb_like_btn($newsfeed, array('style'=>'margin-left: 0')); ?>
						<?=Html_helper::fb_share_btn($newsfeed)?>
					</span>
					<?//=$newsfeed->link_type != 'text' ? Html_helper::pinterest_btn($newsfeed) : '' ?>
					<?php if ($this->is_mod_enabled('email_share') && $this->user) : ?>
						<a href="#share_email_form_wrap" class="share_email" rel="popup" data-type="newsfeed" title="Email This Drop">@ Email</a>
					<?php endif; ?>
				</span>
			</div>
		</div>
		<div class="item_bottom">
			<div class="item_left">
				<div class="item_left_table">
					<div class="item_left_cell">
						<?php if ($is_sample) { ?>
							<a href="/drop/<?=$newsfeed->url?>" class="text_wrapper" style="display:none">
								<p class="text_content"></p>
							</a>
							<div class="js-play_button" style="display:none"><span class="play_button"></span></div>
							<a href="/drop/<?=$newsfeed->url?>" class="postcard_img_wrapper" style="display:none">
								<img src="" class="thumb drop-preview-img" alt=""/>
							</a>
						<?php } else if ($newsfeed->link_type == 'text') {?>
							<a href="/drop/<?=$newsfeed->url?>" class="text_wrapper">
								<p class="text_content"><?=$newsfeed->activity->content?></p>
							</a>
						<?php } else { ?>
							<? if($newsfeed->link_type === 'embed') { ?>
								<span><span class="play_button"></span></span>
							<? } ?>
							<a href="/drop/<?=$newsfeed->url?>" class="postcard_img_wrapper">
								<?=Html_helper::img($newsfeed->img_thumb, array('class'=>"thumb drop-preview-img".($newsfeed->link_type=='image' ? ' watermarked' : ''), 'data-complete'=> $newsfeed->complete, 'alt'=>"" ))?>
							</a>
						<? } ?>
						<? //NEWSFEED OPTIONS LIKE/REDROP/COMMENT/EDIT ?>
						<div class="rate newsfeed_entry_opts">
							<? if ($newsfeed->can_edit($this->user)) { ?>
								<?php if ($this->is_mod_enabled('coversheet') && $newsfeed->link_type != 'text') {?>
									<a href="#newsfeed_edit" title="Edit Drop Coversheet" class="newsfeed_edit_lnk " rel="popup">
										<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text">Coversheet</span>
									</a>
								<?php } ?>
							<?php } ?>								
						</div>
					</div>
				</div>
			</div>
			<div class="item_bottomRow">
				<span class="bottomPost_actions">
					<? if($this->session->userdata('id')){ ?>
						<span class="btn-grey btn-grey_small inlinediv">Comment</span>
					<? }else{ ?>
						<a href="/signup?redirect_url=/drop/<?=$newsfeed->url?>" class="btn-grey btn-grey_small inlinediv"><?=$this->lang->line('newsfeed_views_comment_btn')?></a>
					<? } ?>
					<span class="stat stat_comment inlinediv"><span class="icon"></span><span class="num_comments"><?=($newsfeed->comment_count) ? $newsfeed->comment_count : ''?></span></span>
				</span>
			<? if(@$newsfeed->link_url != '' || $is_sample) { ?>
				<span class="itemDroppedVia_Title">
					<span>
					<?=$this->lang->line('newsfeed_droppped_lexicon');?>
					<?=$this->lang->line('newsfeed_via_lexicon');?></span>
					<a href="/source/<?=str_replace('.', '%2e', $newsfeed->source)?>"><?=$newsfeed->source?></a>
				</span>
			<? } ?>
			</div>
		</div>
	</div>
</li>
<?=($is_sample) ? '</script>' : '' ?>
