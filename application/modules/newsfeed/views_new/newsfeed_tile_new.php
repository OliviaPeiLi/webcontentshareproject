<?php //@update 8/23/2012 RR - Removed data-newsfeed_id attribute from all elements except container. JS is also updated.?>

<? $is_liked = $newsfeed->is_liked($this->user); ?>
<? $is_landingpage = ! ( (bool) $this->session->userdata('id') ) ?>
<? $is_sample = $newsfeed->newsfeed_id <= 0; ?>
<?php if ($is_sample) echo '<script type="template/html" id="js-newsfeed_tile_new"
		data-newsfeed_id = ".newsfeed_entry @data-newsfeed_id, .up_button @href, .undo_up_button @href, .share_twt_app @data-url"
		data-description = ".drop-description"
		data-url = ".newsfeed_entry @data-url"
		data-_description_plain = ".newsfeed_entry @title, .drop_desc_plain, .share_twt_app @data-text"
		data-link_type = ".newsfeed_entry @type"
		data-type =  ".newsfeed_entry @post-type"
		data-coversheet_updated = ".newsfeed_entry @data-coversheet_updated"
		data-img_width = ".newsfeed_entry @data-img_width"
		data-img_height = ".newsfeed_entry @data-img_height"
		data-_img_thumb = ".photoContainer img.drop-preview-img @src"
		data-up_count = ".up_count"
		data-comment_count = " .num_comments"
		data-collect_count = ".js-redrop_count"
		data-_link_type_class = ".tile_new_entry_subcontainer @data-class, .tl_icon @class"
		data-folder-folder_url = ".folder-url @href"
		data-folder-folder_name = ".folder-url"
		data-user_from-_url = " .drop-popup-user @href"
		data-user_from-_avatar_42 = ".drop-popup-user @data-avatar_42"
		data-user_from-full_name = ".drop-popup-user @data-full_name, .drop-popup-user"
		data-_source = ".itemDroppedVia_Title a @href, .itemDroppedVia_Title a"
		data-complete = ".drop-preview-img @data-complete"
	> ' ?>
<li class="newsfeed_entry tile_new_entry <?=$newsfeed->is_shared($this->user)?'liked':''?> <?=(@$is_landingpage) ? 'landing_page_item' : ''?>"
	title="<?=str_replace('"', "'", strip_tags($newsfeed->description))?>"
	<?=Html_helper::item_data($newsfeed, array('newsfeed_id', 'url', 'coversheet_updated', 'img_width', 'img_height'))?>
>
	<div class="action_box inlinediv">
		<div class="upbox inlinediv" style="vertical-align:top">
			<a href="/add_like/drop/<?=$newsfeed->newsfeed_id?>" class="up_button" rel="ajaxButton" style="<?=$is_liked ? 'display: none' : ''?>">
				<div class="upvote_wrapper">
					<span class="upvote_contents"></span>
				</div>
			</a>
			<a href="/rm_like/drop/<?=$newsfeed->newsfeed_id?>" class="undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display: none'?>">
				<div class="downvote_wrapper">
					<span class="downvote_contents"></span>
				</div>
			</a>
			<div class="up_count <?=$is_liked ? 'unlike' : 'like'?>"><?=$newsfeed->up_count?></div>
		</div>
		<?  ?>
		<div class="redropbox inlinediv">
			<a href="#collect_popup" class="redrop_button" rel="popup" title="Redrop">
				<div class="redrop_icon">
					<span class="redrop_iconContents"></span>
				</div>
			</a>
			<div class="js-redrop_count redrop_count"><?=$newsfeed->collect_count?></div>
		</div>
	</div>
	<span class="<?=$newsfeed->link_type_class?> tl_icon" style="display:none"></span>
    <div class="tile_new_entry_subcontainer" <?=Html_helper::link_preview_popup_data($newsfeed)?>>
			<!-- can be  place, music, sunny -->
			<div class="cell_info">
				<span class="cell_info_container">
					<span class="<?=$newsfeed->link_type_class?> tl_icon"></span>
					<span class="post_what">
						<span class="drop-description"><?=$newsfeed->description?></span>
						<span class="drop_desc_plain" style="display:none"><?=strip_tags($newsfeed->description)?></span>
					</span>
				   <div class="post_who">
				   	<?php if ($newsfeed->folder) { ?>
						<div class="postDetail_drop">
							Dropped in <a href="<?=($newsfeed->folder || ($this->uri->segment(1) == 'collection') || $this->uri->segment(1) == 'source') ? $newsfeed->folder->get_folder_url() : ''?>" class="folder-url"><?=$newsfeed->folder->folder_name?></a>
						</div>				   	
					<?php } ?>
						By <a href="<?=$newsfeed->user_from->url?>" class="drop-popup-user" <?=Html_helper::item_data($newsfeed->user_from, array('avatar_42', 'full_name'))?>>
							<?=$newsfeed->user_from->full_name?>
						</a>
						<? if ($is_sample) { ?>
							<span class="roleTitle js-staff_writer" style="display:none"><?=$this->lang->line('staff_writer');?></span>
							<span class="roleTitle js-featured_user" style="display:none"><?=$this->lang->line('featured_user');?></span>
						<? } else if($newsfeed->user_from->role == '1'){ ?>
							<span class="roleTitle"><?=$this->lang->line('staff_writer');?></span>
						<? } elseif($newsfeed->user_from->role == '3'){ ?>
							<span class="roleTitle"><?=$this->lang->line('featured_user');?></span>
						<? } ?>
						<? /*<span class="drop-time" style="display:none"><?=Date_Helper::time_ago($newsfeed->time)?></span>*/ ?>
						<span class="topPost_actions">
							<? if ($newsfeed->can_edit($this->user) || $is_sample) { ?>
								<span class="divIder"></span>
								<a href="#newsfeed_popup_edit" title="Edit Drop" class="newsfeed_edit_lnk" rel="popup">
									<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn')?></span>
								</a>
								<?php if ($this->is_mod_enabled('coversheet') && $newsfeed->link_type != 'text') {?>
								<a href="#newsfeed_edit" title="Edit Drop Coversheet" class="newsfeed_edit_lnk " rel="popup">
									<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text">Coversheet</span>
								</a>
								<?php } ?>
							<? } ?>
						</span>
					</div>
				</span>
				<? /* ?>
				<span class="cell_counters">
					<span class="<?=$newsfeed->link_type_class?> tl_icon"></span>
					<span>
						<a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" title="Redrop" data-group="collect_dialog" data-type="link" data-id="<?=$newsfeed->activity_id?>" class="cell_status_text redrop_count">
							<span class="cell_actionIcon">&nbsp;</span><span class="num js-redrop_count"><?=$newsfeed->collect_count?></span>
						</a>
					</span>
					<span>
						<a class="cell_status_text comment_count">
							<? //RR - .num_comments is used by js to change its contents when a comment is added or removed?>
							<span class="cell_actionIcon">&nbsp;</span><span class="num_comments"><?=$newsfeed->comment_count?></span>
						</a>
					</span>
				</span>
				<? */ ?>
				<span class="ext_share">
					<?=Html_helper::twitter_btn($newsfeed)?>
					<div class="ext_fb_default share_btn">
						<?=Html_helper::fb_share_btn($newsfeed)?>
					</div>
					<?//=$newsfeed->link_type != 'text' ? Html_helper::pinterest_btn($newsfeed) : '' ?>
					<?php if ($this->is_mod_enabled('email_share') && $this->user) : ?>
						<a href="#share_email_form_wrap" class="share_email" rel="popup" data-type="newsfeed" title="Email This Drop">@ Email</a>
					<?php endif;?>
				</span>
			</div>
			
			<div class="post_col" title="">
				<? if ($is_sample) { ?>
					<div class="photoContainer">
						<img src="" class="drop-preview-img" alt=""/>
						<div class="play_container" style="display:none">
							<span class="play_button"></span>
						</div>
					</div>
					<div class="textContainer">
						<span><p class="large_text"></p></span>
						<div class="text_content" style="display:none"></div>
					</div>
				<? } elseif ($newsfeed->link_type != 'text') { ?>
					<div class="photoContainer">
						<?=Html_helper::img($newsfeed->img_thumb, array(
							'class'=>"drop-preview-img"
									.($newsfeed->link_type=='image' ? ' watermarked' : '')
									.($newsfeed->link_type!='embed'&&$newsfeed->complete&&preg_match('/(jpg|jpeg|png)$/i', $newsfeed->img_thumb) ? ' has_zooming' : '')
									.($newsfeed->complete ? ' complete' : ''),
									'alt'=>"",
							'data-complete'=> $newsfeed->complete
						))?>
						<? if ($newsfeed->link_type === 'embed') { ?>
							<div class="play_container">
								<span class="play_button"></span>
							</div>
						<? } ?>
					</div>
				<? } else { ?>
					<div class="textContainer">
						<span><p class="large_text"><?=Text_Helper::character_limiter(strip_tags($newsfeed->activity->content), 400)?></p></span>
						<div class="text_content" style="display:none"><?=$newsfeed->activity->content?></div>
					</div>
				<? } ?>
			</div>
			<div class="item_bottomRow">
				<span class="bottomPost_actions">
					<? if($this->session->userdata('id')){ ?>
						<span href="" class="btn-grey btn-grey_small inlinediv"><?=$this->lang->line('newsfeed_views_comment_btn')?></span><? /* ?>change href to sign up page <? */ ?>
					<? }else{ ?>
						<a href="/signup?redirect_url=/drop/<?=$newsfeed->url?>" class="btn-grey btn-grey_small inlinediv"><?=$this->lang->line('newsfeed_views_comment_btn')?></a>
					<? } ?>
					<div class="stat stat_comment inlinediv"><span class="icon"></span><span class="num_comments"><?=$newsfeed->comment_count ? $newsfeed->comment_count : ''?></span></div>
				</span>
			<? if(@$newsfeed->link_url != '' || $is_sample){ ?>
				<span class="itemDroppedVia_Title">
					<span>
					<?=$this->lang->line('newsfeed_droppped_lexicon');?>
					<?=$this->lang->line('newsfeed_via_lexicon');?></span>
					<a href="/source/<?=str_replace('.', '%2e', $newsfeed->source)?>" class="drop_source"><?=$newsfeed->source?></a>
				</span>
			<? } ?>
			</div>
			<? //Cell Info Used to be here, place back here in case of a redesign ?>
				
			
			<? //NEWSFEED OPTIONS LIKE/REDROP/COMMENT/EDIT ?>
			<div class="rate newsfeed_entry_opts">
				<? /* ?><a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" title="Redrop" data-group="collect_dialog" data-type="link" data-id="<?=$newsfeed->activity_id?>">
					<span class="redrop_wrapper"><span class="redrop_contents"></span></span><span class="actionButton_text"><?=$this->lang->line('newsfeed_views_redrop_lexicon')?></span>
				</a><? */ ?>
				<? //Newsfeed view/hide comments to links ?>
				<? //RR - 10/10/2012 - removed this we dont need it its just doing unneded db query ?>
				<? //if(!isset($newsfeed->activity->comments)){ $newsfeed->activity->comments=null; } ?>
				<? /* ?><a href="javascript:;" title="Comment Drop" class="newsfeed_hide_comments_lnk newsfeed_comments_lnk">
					<span class="view_comments_wrapper"><span class="view_comments_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_comment_btn')?></span>
				</a>
				<? if ($newsfeed->can_edit($this->user)) { ?>
					<a href="#newsfeed_popup_edit" title="Edit Drop" class="newsfeed_edit_lnk" rel="popup">
						<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn')?></span>
					</a>
					<?php if ($this->is_mod_enabled('coversheet') && $newsfeed->link_type != 'text') {?>
					<a href="#newsfeed_edit" title="Edit Drop Coversheet" class="newsfeed_edit_lnk " rel="popup">
						<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text">Coversheet</span>
					</a>
					<?php } ?>
				<? } 
				/*
				if ($this->session->userdata('id') != $newsfeed->user_id_from) { ?>
					<span class="newsfeed_up_buttons">
						<a class="up_rank newsfeed_up_lnk up_button" <?=$hide_up?> rel="ajaxButton" href="<?=$up_link?>">
							<span class="up_wrapper" title=""><span class="up_contents"></span></span><span class="actionButton_text"><?=strtoupper($this->lang->line('newsfeed_views_like_btn'));?></span>
						</a>
						<a class="up_rank newsfeed_undo_up_lnk undo_up_button" <?=$hide_unup?> rel="ajaxButton" href="<?=$unup_link?>">
							<span class="undo_up_wrapper" title=""><span class="undo_up_contents"></span></span><span class="actionButton_text"><?=strtoupper($this->lang->line('newsfeed_views_liked_btn'));?></span>
						</a>
					</span>
				<?  } */ ?>
			</div>

			<? /* ?><div class="cell_status <?=$newsfeed->can_edit($this->user) ? 'tile_bot' : 'tile_top'?>">
				<a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" title="Redrop" data-group="collect_dialog" data-type="link" data-id="<?=$newsfeed->activity_id?>" class="cell_status_text redrop_count">
					<span class="cell_actionIcon"><span></span><?=$newsfeed->collect_count?></span>
				</a>
				<? /* ?>
				<div class="hidden_up" style="display:none"><?=$up_link?></div>
				<div class="hidden_unup" style="display:none"><?=$unup_link?></div>
				<a class="cell_status_text up_count <?=$post_like?'unlike':'like'?>" title="" rel="ajaxButton" href="<?=$like_link?>" data-count="<?=$newsfeed->up_count?>">
					<span class="cell_actionIcon"><span></span><b><?=$newsfeed->up_count?></b></span>
				</a>
				<a class="cell_status_text comment_count">
					<span class="cell_actionIcon"><span></span><?=$newsfeed->comment_count?></span>
				</a>
			</div><? */ ?>
	    </div>
	</li>
<?=$is_sample ? '</script>' : '' ?>