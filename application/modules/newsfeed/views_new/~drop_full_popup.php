<?php //This view is used for the popup loaded from notification or other non instant code ?>
<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<?php
	$post_like = FALSE;
	if(isset($newsfeed->up_count) && $newsfeed->up_count > 0){
		$this->load->model('like_model');
		$post_like = $this->like_model->count_by(array('user_id'=>$this->session->userdata('id'),$newsfeed->type.'_id'=>$newsfeed->activity_id));
	}
?>
<?php if ($this->user) {
	$this->load->view('folder/collect');
}?>
<div class="modal-body" id="preview_full_popup">
	<div id="main-content">
		<span class="tl_icon <?=$newsfeed->link_type_class?>"></span>
		<h2 class="pop_up_title"><?=$newsfeed->description?></h2>
		<a href="" class="close" data-dismiss="modal"></a>
		<div class="preview_popup_main">
			<?php if ($newsfeed->link_type == 'text') { ?>
				<div class="text_wrapper">
					<p class="text_content"><?=$newsfeed->activity->content?></p>
				</div>
			<?php } else { ?>
				<img class="thumb-img" src="<?=$newsfeed->img_thumb?>"/>
				<img class="full-img" src="<?=$newsfeed->img?>" style="display:none"/>
				<?php if (!in_array($newsfeed->link_type, array('photo','image'))) { ?>
					<iframe src="/bookmarklet/snapshot_preview/<?=$newsfeed->newsfeed_id?>" style="display:none"></iframe>
				<?php } ?>
			<?php } ?>
			<div class="controls newsfeed_entry_comment_options">
				<a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" data-newsfeed_id="<?=$newsfeed->newsfeed_id?>" title="<?=$this->lang->line('newsfeed_views_redrop_lexicon');?>">
					<span class="redrop_wrapper"><span class="redrop_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_redrop_lexicon');?></span>
				</a>
				<? if ($newsfeed->can_edit($this->user)) { ?>
					<a href="" class="newsfeed_edit_lnk edit_btn ft-dropdown" rel="newsfeed_timeline_edit_<?=$newsfeed->newsfeed_id?>" title="Edit Drop">
						<span class="edit_wrapper"><span class="edit_contents"></span></span>
						<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn');?></span>
					</a>
				<? } ?>
				<a href="<?=$newsfeed->type=='link' ? '/add_like/'.$newsfeed->type.'/'.$newsfeed->activity_id : ''?>" class="up up_button" <?=$post_like?'style="display:none"':''?> rel="ajaxButton">
					<span class="up_wrapper"><span class="up_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_like_btn');?></span>
				</a>
				<a href="<?=$newsfeed->type=='link'? '/rm_like/'.$newsfeed->type.'/'.$newsfeed->activity_id : ''?>" class="up undo_up_button" <?=$post_like?'':'style="display:none"'?> rel="ajaxButton">
					<span class="undo_up_wrapper"><span class="undo_up_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_liked_btn');?></span>
				</a>
				<a class="newsfeed_hide_comments_lnk newsfeed_comments_lnk" rel="0" href="javascript:;">
					<span class="view_comments_wrapper"><span class="view_comments_contents"></span></span>
					<span class="actionButton_text">COMMENT</span>
				</a>
			</div>
			<? if ($newsfeed->can_edit($this->user)) { ?>
				<?=$this->load->view('newsfeed/newsfeed_edit',array('newsfeed'=>$newsfeed)); ?>
			<? } ?>
		</div><!-- End .left -->
	</div>
	<div id="right">
		<div class="preview_popup_comments">
			<div class="item_info">
				<div class="post_detail">
					<div class="postDetail_drop">Dropped in <a href="<?=$newsfeed->folder->get_folder_url()?>" class="folder_link"><?=$newsfeed->folder->folder_name?></a></div>
					<div class="postDetail_user">By <a href="<?=$newsfeed->user_from->url?>" class="user_link"><?=$newsfeed->user_from->full_name?></a></div>					
				</div>
				<div class="social">
					<a href="https://twitter.com/share" class="twitter-share-button" data-text="<?=substr(strip_tags($newsfeed->description.' - Fandrop'), 0, 100)?>" data-url="<?=base_url('/drop/'.$newsfeed->newsfeed_id)?>" data-count="none"></a>
					<? if ($this->session->userdata('id')) { ?>
						<a id="share_fb_app" href=""><span class="share_fb_app_span"></span></a>
					<? } else { ?>
						<fb:like class="fb-like" href="<?=base_url('/drop/'.$newsfeed->newsfeed_id)?>" send="false" width="90" layout="button_count" show_faces="false"></fb:like>
					<? } ?>
				</div>
			</div>
			<div class="comments-container">
			    <div class="comments_list">
		      		<?=modules::run('comment/comments', $newsfeed->newsfeed_id)?>
					<div class="comments-bottom">
						<div class="newsfeed_entry_add_comment">
							<? 
							//$user_thumb = !empty($this->user) ? @$this->user->avatar_small : s3_url().$this->user_model->behaviors['uploadable']['avatar']['default_image'];
							$user_thumb = $this->user->avatar_small;
							?>
							<img class="previewPopup_userAvatar" src=<?=@$user_thumb?> alt="avatar"/>
							<?=form_open('/comment', array('rel'=>'ajaxForm','class'=>'comments_form'), array('newsfeed_id'=>$newsfeed->newsfeed_id))?>
								<div class="form_row">
									<textarea name="comm_msg" class="fd_mentions" placeholder="Write a comment..."></textarea>
									<input type="submit" name="comment" value="comment"/>
								</div>
							<?=form_close()?>
						</div>
					</div>
					<div class="more-info">
						<?=$this->load->view('newsfeed/popup_right', array('newsfeed'=>$newsfeed,'no_comments'=>true))?>
					</div>
				</div>
			</div>
		</div><!-- End .preview_popup_comments -->
	</div>
	<script type="text/javascript">
		php.fb_id = <?=isset($this->user->fb_id) ? $this->user->fb_id : 0?>;
		php.twtr_id = <?=isset($this->user->twtr_id) ? $this->user->twitter_id : 0?>;
	</script>
</div><!-- End .modal-body -->
