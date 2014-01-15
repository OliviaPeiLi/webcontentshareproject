<li class="profile_drop inlinediv" <?=Html_helper::item_data($newsfeed,  array('newsfeed_id', 'url', 'img_width', 'img_height'))?>>
	<div data-url="#preview_popup" rel="popup">
		<div class="topDrop_dropTop">
			<? /* ?><div class="when_div drop-time"><?=Date_Helper::time_ago($newsfeed->time)?></div><? */ ?><? /* ?> Keep in mind that this is here in case something else is proposed to be added <? */ ?>
			<span style="" class="<?=$newsfeed->link_type_class?> tl_icon"></span>
		</div> <? //used by the popup?>
		<? if ($newsfeed->link_type == 'text') {?>
			<div class="text_wrapper">
				<p class="text_content"><?=$newsfeed->activity->content?></p>
			</div>
		<?php } else { ?>
			<div>
				<?=Html_helper::img($newsfeed->img_bigsquare, array('class'=>"drop-preview-img", 'alt'=>"", 'data-complete'=> $newsfeed->complete ));?>
			</div>
		<?php } ?>
		<div class="drop_info">
			<div class="what_who">
				<span class="what drop-description"><?=Text_Helper::character_limiter_tag($newsfeed->description, 60)?></span>
				<span class="drop_desc_plain" style="display:none"><?=strip_tags($newsfeed->description)?></span> 
				by
				<a href="<?=$newsfeed->user_from->url?>" class="who drop-popup-user show_badge" <?=Html_helper::item_data($newsfeed->user_from, array('avatar_42', 'full_name'))?>>
					<?=$newsfeed->user_from->full_name?>
				</a>
				<span class="js-redrop_count" style="display:none"><?=$newsfeed->collect_count;?></span>
			</div>
			<div class="where_div">Dropped in <a class="where folder-url" href="<?=$newsfeed->folder->get_folder_url()?>"><?=$newsfeed->folder->folder_name?></a></div>
			<? /* ?><div class="when_div drop-time"><?=Date_Helper::time_ago($newsfeed->time)?></div><? */ ?><? /* ?> Keep in mind that this is here in case something else is proposed to be added <? */ ?>
		</div>
		<div class="upbox" style="display:none">
			<div class="up_button"><?='/add_like/'.$newsfeed->type.'/'.$newsfeed->activity_id;?></div>
			<div class="undo_up_button"><?='/rm_like/'.$newsfeed->type.'/'.$newsfeed->activity_id;?></div>
			<div class="up_count <?=$newsfeed->is_liked($this->user)?'unlike':'like'?>"><?=$newsfeed->up_count?></div>
		</div>
		<? if ($newsfeed->can_edit($this->user)) { ?>
		<div class="controls" style="display:none">
			<a href="#newsfeed_popup_edit" class="newsfeed_edit_lnk edit_btn" title="Edit Drop"> </a>
		</div>
		<? } ?>	
	</div>	
</li>
