<?php //@update 8/23/2012 RR - Removed data-newsfeed_id attribute from all elements except container. JS is also updated.?>
<? $is_liked = $newsfeed->is_liked($this->user); ?>
<? $is_landingpage = ! ( (bool) $this->session->userdata('id') ) ?>
<? $is_sample = $newsfeed->newsfeed_id <= 0; ?>
<?php if ($is_sample) echo '<script type="template/html" id="js-newsfeed_contest"
		data-newsfeed_id = ".newsfeed_entry @data-newsfeed_id"
		data-title = ".drop-title span"
		data-description = ".drop-description"
		data-url = ".newsfeed_entry @data-url"
		data-_description_plain = ".newsfeed_entry @title, .drop_desc_plain"
		data-link_url= ".drop-link, .drop-link @href"
		data-link_type = ".newsfeed_entry @data-link-type"
		data-type =  ".newsfeed_entry @post-type"
		data-coversheet_updated = ".newsfeed_entry @data-coversheet_updated"
		data-img_width = ".newsfeed_entry @data-img_width"
		data-img_height = ".newsfeed_entry @data-img_height"
		data-_img_thumb = ".photoContainer img.drop-preview-img @src"
		data-complete = ".drop-preview-img @data-complete"
		data-_img_tile = ".drop-title img @src"
		data-_link_type_class = ".tl_icon @class"
		data-share_count = ".js-share_count"
		data-points = ".js-points_count"
		data-share_btns = ".ext_share";
	> ' ?>
<li class="newsfeed_entry tile_new_entry <?=$newsfeed->is_shared($this->user)?'liked':''?> <?=(@$is_landingpage) ? 'landing_page_item' : ''?>"
	title="<?=str_replace('"', "'", strip_tags($newsfeed->description))?>"
	<?=Html_helper::item_data($newsfeed, array('newsfeed_id', 'url', 'coversheet_updated', 'img_width', 'img_height', 'link_type', 'share_goal', 'top_prize','sub_type'))?>
>
	<div class="action_box inlinediv">
		<div class="redropbox inlinediv">
			<a class="redrop_button">
				<? if (in_array($contest->url, array('fndemo','crowdfunderio','cite'))) { ?>
					<span class="js-points_count redrop_count"><?=(int) $newsfeed->points?></span>
					<span class="share_text">Points</span>
				<?php } else { ?>
					<span class="js-share_count redrop_count"><?=$newsfeed->share_count?></span>
					<span class="share_text">Shares</span>
				<?php } ?>
			</a>
		</div>
	</div>
		<span class="ext_share">
			<?=Html_helper::twitter_btn($newsfeed)?>
			<?php if (!in_array($contest->url, array('fndemo','crowdfunderio'))) { ?>
				<?=Html_helper::fb_share_btn($newsfeed)?>
				<?=Html_helper::gplus($newsfeed)?>
				<?php if (!in_array($contest->url, array('cite'))) { ?>
					<?=Html_helper::pinterest_btn($newsfeed)?>
					<?=Html_helper::likedin($newsfeed)?>
					<?php if ($this->is_mod_enabled('email_share') && $this->user) { ?>
						<a href="#share_email_form_wrap" class="share_email" rel="popup" data-type="newsfeed" title="Email This Drop">@ Email</a>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</span>
	<span class="<?=$newsfeed->link_type_class?> tl_icon" style="display:none"></span>
    <div class="tile_new_entry_subcontainer">
			<!-- can be  place, music, sunny -->
			<div class="cell_info">
				<span class="cell_info_container">
					<span class="post_what">
						<span class="drop-title">						
							<strong class="<?=$newsfeed->img_width >= 500 ? 'watermarked' : ''?>"><?=Html_helper::img($newsfeed->img_tile, array('alt'=>""))?></strong>
							<span><?=$newsfeed->title?></span>
						</span>
						<? if ($contest->url == 'fndemo') {?>
							<a href="<?=$newsfeed->short_url ? $newsfeed->short_url : Url_helper::base_url('/drop/'.$newsfeed->url)?>" target="_blank"><?=$newsfeed->short_url ? $newsfeed->short_url : Text_Helper::character_limiter_strict(Url_helper::base_url('/drop/'.$newsfeed->url), 60)?></a>
						<? } ?>
						<span class="drop-description"><?=$newsfeed->description?></span>
						<a href="<?=$newsfeed->link_url?>" target="_blank" class="drop-link"><?=$newsfeed->link_url?></a>
						<span class="drop_desc_plain" style="display:none"><?=strip_tags($newsfeed->description)?></span>
					</span>
	
					<? if ($newsfeed->can_edit($this->user) || $is_sample) { ?>
					   <span class="post_who">
							<span class="topPost_actions">
									<a href="#newsfeed_popup_edit" title="Edit Drop" class="newsfeed_edit_lnk" rel="popup">
										<span class="edit_wrapper"><span class="edit_contents"></span></span><span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn')?></span>
									</a>
							</span>
						</span>
					<? } ?>
				</span>
			</div>
			
			<div class="post_col" title="">
				<? if ($is_sample) { ?>
					<div class="photoContainer">
						<img src="" class="drop-preview-img" alt=""/>
						<div class="play_container">
							<span class="play_button"></span>
						</div>
					</div>
				<? } elseif ($newsfeed->link_type != 'text') { ?>
					<div class="photoContainer">
						<?=Html_helper::img($newsfeed->img_thumb, array(
							'class'=>"drop-preview-img"
									.($newsfeed->link_type=='image' ? ' watermarked' : '')
									.($newsfeed->link_type!='embed'&&$newsfeed->complete&&preg_match('/(jpg|jpeg|png)$/i', $newsfeed->img_thumb) ? ' has_zooming' : ''),
							'alt'=>"",
							'data-complete' => $newsfeed->complete,
						))?>	
						<? if ($newsfeed->link_type === 'embed') { ?>
							<div class="play_container">
								<span class="play_button"></span>
							</div>
						<? } ?>				
					</div>
				<? } ?>
			</div>
	    </div>
	</li>
<?=$is_sample ? '</script>' : '' ?>