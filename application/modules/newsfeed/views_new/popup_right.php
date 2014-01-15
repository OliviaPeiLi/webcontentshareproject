<? $this->lang->load('newsfeed/newsfeed', LANGUAGE); ?>
<?php 
	$like_limit = 5;
	$likes = $newsfeed->likes;
	$like_count = count($likes);	
?>

<?php if ($show_comments) { ?>
	<?=$this->load->view('comment/comments',array('comments'=>$newsfeed->get('comments')->order_by('time', 'ASC')->get_all()),true); ?>
	
	<div class="comments-bottom">
		<div class="comments-bottom-container">
			<?=Html_helper::img($this->user ? $this->user->avatar_25 : Uploadable_Behavior::get_default_image(null, $this->user_model->behaviors['uploadable']['avatar']['default_image']), array(
				'class'=>"previewPopup_userAvatar", 'alt'=>"avatar"
			))?>
			<?=Form_Helper::open('/comment', array('rel'=>'ajaxForm','class'=>'comments_form'), array('newsfeed_id'=>$newsfeed->newsfeed_id))?>
				<div class="form_row">
					<textarea name="comment" class="fd_mentions" data-maxlength="250" cols="36" rows="4" placeholder="Write a comment..."></textarea>
					<span class="comment_char_count" style="display:none;">250</span>
					<input type="submit" class="blue_bg" name="submit" value="Comment" style="display:none;"/>
				</div>
				<span class="error"></span>
			<?=Form_Helper::close()?>
		</div>
	</div>
<?php } ?>

<? if($newsfeed->folder->type != 1){ ?>
<div id="popup_right">
	<div class="popup_right-likes" style="<?=$newsfeed->up_count ? 'display:block' : 'display:none'?>">
		<h3><?=$this->lang->line('newsfeed_likes_lexicon');?></h3>
		<ul data-uid="<?=$this->session->userdata('id')?>">
			<? if($this->session->userdata('id')){?>
				<li class="sample like" style="display:none">
					<a href="<?=$this->user->url?>" data-user_id="<?=$this->user->id?>">
						<?=Html_helper::img($this->user->avatar_25, array('title'=>$this->user->full_name, 'width'=>"30", 'height'=>"30", 'class'=>"avatar", 'onerror'=>"if (this.src.indexOf('indigo_thumb.png') == -1) this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'", 'alt'=>""))?>
					</a>
				</li>
			<? } ?>
			<? if (!$like_count) { ?>
				<? /* ?>
				<li class="no_likes"><?=$this->lang->line('newsfeed_no_likes_text')?></li>
				<? */ ?>
			<? } else { ?>
				<? for($l_i=0; $l_i<min($like_count,$like_limit); $l_i++) { $like = $likes[$l_i]; ?>
					<li>
						<a href="<?=$like->user_from->url?>" class="show_badge" data-user_id="<?=$like->user_from->id?>">
							<?=Html_helper::img($like->user_from->avatar_25, array('title'=>$like->user_from->full_name, 'width'=>"30", 'height'=>"30", 'class'=>"avatar", 'onerror'=>"if (this.src.indexOf('indigo_thumb.png') == -1) this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'", 'alt'=>""))?>
						</a>
					</li>
				<? } ?>
			<? } ?> 
		</ul>
		<? if ($like_count > $like_limit) { ?>
			<? /* ?>
			<div><a rel="popup" title="People that upvoted this drop" class="open_up_window up_link" id="up_link_<?=$newsfeed->newsfeed_id?>" href="/get_up_data/<?=$newsfeed->activity_id?>/<?=$newsfeed->type?>">+<?=$like_count-$like_limit?> <?=$this->lang->line('newsfeed_more_likes_lexicon');?></a></div>
			<? */ ?>
			<div>+<label id="like_more_count"><?=$like_count-$like_limit?></label> <?=$this->lang->line('newsfeed_more_likes_lexicon');?></div>
		<? } ?>
	</div>

	<?php $last_redrops = $newsfeed->last_redrops(4); ?>
	<?php $redrop_count  = $newsfeed->collect_count; ?>
	<div class="popup_right-redrops">
			<h3 <?=$last_redrops ? "" : 'style="display:none;"'?>><?=$this->lang->line('newsfeed_redrops_lexicon');?></h3>
			<ul>
				<? if($this->session->userdata('id')){?>
					<li class="sample redrop" style="display:none">
						<a href="<?=$this->user->url?>">
							<?=Html_helper::img($this->user->avatar_42, array('class'=>"avatar"))?>
						</a>
						<span>
							<a href="<?=$this->user->url?>"><?=$this->user->full_name?></a>
							<?=$this->lang->line('newsfeed_into_lexicon');?>
							<a href="<?=$this->user->url?>" class="folder_name"></a>
						</span>
					</li>
				<? } ?>
				<? if (! $redrop_count) { ?>
					<li class="no_redrops"><?=$this->lang->line('newsfeed_no_redrops_msg')?></li>
				<? } else { ?>
					<? foreach ($last_redrops as $drop) { ?>
						<li class="redrop">
							<a href="" class="show_badge" data-user_id="<?=$drop->user_from->id?>">
								<?=Html_helper::img($drop->user_from->avatar_42, array('class'=>"avatar"))?>
							</a>
							<span>
								<a href="<?=$drop->user_from->url?>" class="show_badge" data-user_id="<?=$drop->user_from->id?>"><?=$drop->user_from->full_name?></a>
								<?=$this->lang->line('newsfeed_into_lexicon');?>
								<a href="<?=$drop->folder->get_folder_url()?>" class="folder_name"><?=$drop->folder->folder_name?></a>
							</span>
						</li>
					<? } ?> 
				<? } ?>
			</ul>
			<div <?=$redrop_count > 4 ? '' : 'style="display:none;"' ?>>
				+<label id="redrop_more_count"><?=$redrop_count-4?></label> <?=$this->lang->line('newsfeed_more_redrops_lexicon');?>
			</div>
	</div>
	
	<span class="drop-link" style="display:none"><?=$newsfeed->link_url?></span>
	<? if($newsfeed->orig_user_id != $newsfeed->user_id_from){ ?>
		<div id="popup_right-original">
			<h3>
				Originally dropped by
				<a href="<?=@$newsfeed->orig_user->url?>"><?=@$newsfeed->orig_user->full_name?></a>
			</h3>
			<? foreach($orig_user_newsfeeds as $item) { ?>
				<? if ($item->link_type == 'text') { ?>
					<span class="source_link_img">
						<a href="<?=$item->link_url?>"><span class="source_text_thumb"><?=Text_Helper::character_limiter_strict($item->activity->content, 28)?></span></a>
					</span>					
				<? } else { ?>
					<span class="source_link_img">
						<a href="<?=$item->url?>">
							<?=Html_helper::img($item->img_square, array('width'=>"50", 'height'=>"50"))?>
						</a>
					</span>
				<? } ?>
			<? } ?>
			<? if($this->session->userdata('id') != @$newsfeed->orig_user_id) { ?>
				<?php $follow_orig_user = $this->user ? $this->user->is_following($newsfeed->orig_user_id) : false?>
				<div id="follow_button_div_<?=@$newsfeed->orig_user_id?>" class="source_dropper_follow_button_container">
					<button class="button blue_bg actionButton follow_button_align followPerson_button unfollow_button request_unfollow" rel="ajaxButton" data-url="/unfollow_user/<?=$newsfeed->orig_user_id?>" style="<?=$follow_orig_user ? 'display:block' :'display:none'?>">Following</button>
					<button class="button blue_bg follow_button_align followPerson_button request_follow" rel="ajaxButton" data-url="/follow_user/<?=@$newsfeed->orig_user_id?>" style="<?=$follow_orig_user ? 'display:none' :'display:block'?>">Follow</button>
				</div>
			<? } ?>
		</div>
	<? } ?>	
	<? if($newsfeed->link_url){ ?>
		<div class="popup_right-source">
			<h3>
				<?=$this->lang->line('newsfeed_droppped_lexicon');?>
				<?=$this->lang->line('newsfeed_via_lexicon');?>
				<a href="/source/<?=str_replace('.', '%2e', $newsfeed->source)?>"><?=$newsfeed->source?></a>
			</h3>
			<? foreach($same_source as $newsfeed) { ?>
				<? if ($newsfeed->link_type == 'text') { ?>
					<span class="source_link_img">
						<? // http://dev.fantoon.com:8100/browse/FD-2619 ?>
						<a href="/drop/<?=$newsfeed->url?>">
							<span class="source_text_thumb"><?=Text_Helper::character_limiter_strict(strip_tags($newsfeed->activity->content), 28)?></span>
						</a>
					</span>
				<? } else { ?>
					<span class="source_link_img">
						<a href="/drop/<?=$newsfeed->url?>">	
							<?=Html_helper::img($newsfeed->img_square, array('width'=>"50", 'height'=>"50"))?>
						</a>
					</span>
				<? } ?>
			<? } ?>
		</div>
	<? } ?>
	
</div> 

<? } ?>
