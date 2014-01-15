<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<div id="preview_popup" class="no-auto-position" style="display:none">
	<div class="modal-body">
		<div class="item_top">
			<div class="stats_container inlinediv">
				<div class="upbox inlinediv">
					<a class="up_button" rel="ajaxButton" href="{up_link}">
						<span class="upvote_wrapper">
							<span class="upvote_contents"></span>
						</span>
					</a>
					<a class="undo_up_button" rel="ajaxButton" href="{undo_up_link}">
						<span class="downvote_wrapper">
							<span class="downvote_contents"></span>
						</span>
					</a>
					<div class="up_count"></div>
				</div>
				<div class="redropbox inlinediv">
					<a href="#collect_popup" class="redrop_button" rel="popup" title="<?=$this->lang->line('newsfeed_views_redrop_lexicon');?>">
						<span class="redrop_icon">
							<span class="redrop_iconContents"></span>
						</span>
					</a>
					<div class="redrop_count"></div>
				</div>
			</div>
			<div class="post_detail inlinediv">
				<div>
					<span class="tl_icon"></span>
					<h2 class="pop_up_title drop-description js-description">{drop_desc}</h2>
				</div>
				<div class="postDetail_drop"><?=$this->lang->line('newsfeed_views_posted_in_lexicon');?> <a href="{folder_url}" class="folder_link">{folder_name}</a></div>
				<div class="postDetail_user">
					<?=$this->lang->line('by');?> <a href="{user_link}" class="user_link">{user_fullname}</a>
					<span class="roleTitle"><?=$this->lang->line('newsfeed_views_staff_writer_lexicon');?></span>
					<span class="topPost_actions">
						<span class="divIder"></span>
						<a href="#newsfeed_popup_edit" class="newsfeed_edit_lnk edit_btn" rel="popup" title="<?=$this->lang->line('newsfeed_views_edit_post_lexicon');?>">
							<span class="edit_wrapper"><span class="edit_contents"></span></span>
							<span class="actionButton_text"><?=$this->lang->line('edit');?></span>
						</a>
					</span>
				</div>
				<div id="permalinks" class="has-link">
					<a href="javascript:;" target="_blank">
						<img src="" id="link_favicon" alt=""><span class="linktext"></span>
					</a>
					<?php /* RR - newsfeed share is disabled by alexi request ?>
					<div class="social">
						<a href="<?=base_url();?>" class="share_twt_app" data-count="none"></a>
						<a href="" class="share_fb_app"><span class="ico"></span></a>
						<a href="" class="pin-it-button"><span></span></a>
						<? if (isset($contest) || $this->uri->segment(1) == 'winsxsw') { ?>
							<a href="" class="share_gplus_app"><span class="ico"></span></a>
							<a href="" class="share_likedin_app"><span class="ico"></span></a>
						<? } ?>
						<?php if ($this->is_mod_enabled('email_share') && $this->user) { ?>
							<a href="#share_email_form_wrap" class="share_email" rel="popup" data-type="newsfeed" title="Email This Drop">@ Email</a>
						<?php } ?>
					</div>
					<? */ ?>
				</div>
			</div>
		</div>
				
		<a href="" class="fullscreen_txt"><?=$this->lang->line('newsfeed_popup_fullscreen');?></a>
		<a href="" class="close" data-dismiss="modal"></a>
		<div class="preview_popup_main">
			<div class="images_container">
				<img src="" class="thumb-img" alt=""/>
				<img src="" class="full-img" alt=""/>
			</div>
			<iframe src=""></iframe>
		</div><!-- End .left -->
	</div> <!-- End .modal-body -->
	<a class="popup_arrow disabled" id="popup_arrow_left"></a>
	<a class="popup_arrow disabled" id="popup_arrow_right"></a>
	<script type="text/javascript">
		php.fb_id = <?=isset($this->user->fb_id) ? $this->user->fb_id : 0?>;
		php.twtr_id = <?=isset($this->user->twtr_id) ? $this->user->twitter_id : 0?>;
	</script>
	<?=Html_helper::requireJS(array("newsfeed/drop_preview_popup_ugc"))?>
</div><!-- End #preview_popup -->
