<?= $folder->folder_id <= 0 ? '
	<script type="text/html" id="js-popular-collection"
		data-folder_uri = ".js-folder @data-url, .share_twt_app @data-url, .folder_pics_container @href"
		data-folder_id = ".collectionUpbox a @href, .js-folder @data-id, .fb_share_collection @data-folder_id, div.follow_unfollow_btn a @href, .share_email @data-folder_id"
		data-upvotes_count = ".up_count"
		data-folder_name = ".folder_name, .share_twt_app @data-text"
		data-uri_name = ".js-userdata a @href"
		data-full_name = ".js-userdata a"
		data-newsfeeds_count = ".total_drops"
		data-hashtag-_hashtag_url = ".folder_tags @href"
		data-hashtag-_hashtag_name = ".folder_tags"
		data-hits = ".total_hits"
		data-pinterest_url = ".pin-it-button @href"
	>' : '' ?>
<li class="js-folder folder" data-id="<?=$folder->folder_id?>" data-url="<?=$folder->folder_url?>">
	<div class="collectionUpbox">
		<?php $is_liked = $folder->is_liked($this->session->userdata('id'))?>
		<a href="/add_like/folder/<?=$folder->folder_id?>" class="up_button" rel="ajaxButton" style="<?=$is_liked ? 'display:none' : ''?>">
			<span class="upvote_wrapper">
				<span class="upvote_contents"></span>
			</span>
		</a>
		<a href="/rm_like/folder/<?=$folder->folder_id?>" class="undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display:none'?>">
			<span class="downvote_wrapper">
				<span class="upvote_contents"></span>
			</span>
		</a>
		<div class="up_count"><?=$folder->upvotes_count?></div>
	</div>
	<div class="folder_info">
		<div class="folder_name"><?=$folder->folder_name ?></div>
		<? /* ?><div class="folder_name"><?=Text_Helper::character_limiter_strict($folder->folder_name, 27)?></div><? */ ?>
		<div class="folder_userName js-userdata">
			by <a href="<?=$folder->user->url?>"><?=$folder->user->full_name?></a>
			<? if($folder->user->role == '1'){ ?>
				<span class="roleTitle js-staff_writer"><?=$this->lang->line('staff_writer');?></span>
			<? } elseif($folder->user->role == '3'){ ?>
				<span class="roleTitle js-featured_user"><?=$this->lang->line('featured_user');?></span>
			<? } elseif ($folder->folder_id <= 0) { //Sample item ?>
				<span class="roleTitle js-staff_writer" style="display:none"><?=$this->lang->line('staff_writer');?></span>
				<span class="roleTitle js-featured_user" style="display:none"><?=$this->lang->line('featured_user');?></span>
			<? } ?>
		</div>
		<div class="folder_stats">
			<span class="folder_drops"><span class="folder_dropsContents"></span><span class="total_drops"><?=Text_Helper::restyle_text($folder->newsfeeds_count, 1)?></span></span>
			<? /* ?>Commenting out the Views again.<? */ ?>
			<span class="folder_views"><span class="folder_viewsContents"></span><span class="total_hits"><?=Text_Helper::restyle_text($folder->get_total_hits())?></span></span>
			<? //RR - 1/30/2013 - added hashtag_id check because of the php notice?>
			<?php if ($folder->hashtag_id) {?>
				<a href="<?=$folder->hashtag->_hashtag_url?>" class="folder_tags"><?=$folder->hashtag->_hashtag_name?></a>
			<?php } else { ?>
				<a href="" class="folder_tags"></a>
			<?php } ?>
		</div>
		<span class="collectionsTweets">
			<?=Html_helper::twitter_btn($folder)?>
			<?=Html_helper::fb_share_btn($folder)?>
			<?//=Html_helper::pinterest_btn($folder)?>
			<?php if ($this->is_mod_enabled('email_share') && $this->user) : ?>
				<?php $is_disabled = count($folder->recent_newsfeeds) == 0 ? true : false;?>
				<a href="#share_email_form_wrap" class="share_email<?=$is_disabled ? " disabled inactive" : "";?>" rel="popup" title="Email This Drop" data-type="folder" data-folder_id="<?=$folder->folder_id?>">&nbsp;</a>
			<?php endif;?>
		</span>		
	</div>
	<a href="<?=$folder->get_folder_url()?>" class="folder_pics_container">
		<? $count = 0; ?>
		<? foreach ($folder->recent_newsfeeds as $key => $item) {  if ($key >= 3) break;?>
				<span class="img_wrapper folder_item <?=@$item->link_type == 'embed' ? 'collection_play_button':''?>">
				<? if ($item->link_type == 'text') { ?>
					<span class="bookmarked_text">
						<?=$item->text?>
					</span>
				<?php } else { //photo and other link types ?>
					<? //RR - onerror is temp attribute until the thums are updated?>
					<?=Html_helper::img($item->img_bigsquare, array(
						'alt'=>strip_tags($item->description_plain),
						'title'=>strip_tags($item->description_plain),
						'onerror'=>"if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')",
						'data-newsfeed_id'=>$item->newsfeed_id
					))?>
				<? } ?>
				<?php if (@$item->link_type == 'embed') {?>
					<span class="play_button"></span>
				<?php } ?>
			</span>
		<? }  ?>
		<? for ($i=@$key ;$i < 3; $i++ ) { ?>
			<span class="img_wrapper folder_item">
				<? if ( $folder->folder_id <= 0 ) { ?>
					<span class="bookmarked_text"></span>
					<img src="" alt="" title="" onerror="if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')" />
					<span class="play_button"></span>
				<? } ?>
			</span>
		<? } ?>
	</a>
		<? if ( ! $folder->is_owned($this->session->userdata('id')) || $folder->folder_id <= 0 ) { ?>
			<div class="follow_unfollow_btn">
				<?php if ($this->session->userdata('id')) : ?>
					<? $is_followed = $folder->is_followed($this->session->userdata('id')); ?>
					<a href="/unfollow_folder/<?=$folder->folder_id?>" rel="ajaxButton" data-type="html" class="folder_unfollow unfollow_button" style="<?=$is_followed ? '' : 'display:none'?>"><?=$this->lang->line('folder_following_btn');?></a>
					<a href="/follow_folder/<?=$folder->folder_id?>" rel="ajaxButton" data-type="html" class="folder_follow lightBlue_bg" style="<?=$is_followed ? 'display:none' : ''?>"><?=$this->lang->line('folder_follow_btn');?></a>
				<?php else:  ?>
					<a href="/signup?redirect_url=<?=$folder->get_folder_url();?>" data-type="html" class="folder_follow lightBlue_bg"><?=$this->lang->line('folder_follow_btn');?></a>
				<?php endif; ?>					
			</div>
		<? } ?>
		<div class="clear"></div>
</li>
<?=$folder->folder_id <= 0 ? '</script>' : ''?>