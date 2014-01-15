<?php $is_sample = $comment->comment_id == 0?>
<?= $is_sample ? '<script type="template/html" id="tmpl-newsfeed_entry_comment" 
	data-val=".comment_body, .comment_content"
>' : '' ?>
<div class="newsfeed_entry_comment" data-comment_id="<?=$comment->comment_id?>" style="<?=$is_sample ? 'display:none' : ''?>">
	<? if($comment->can_delete(@$this->user) || $is_sample) { ?>
		<a class="close delete_comment" rel="ajaxButton" href="/del_comm/<?=$comment->comment_id?>">Delete</a>
	<? } ?>
	<div class="newsfeed_entry_comment_avatar">
		<a href="<?=@$comment->user_from->url?>" class="show_badge" data-user_id="<?=$comment->user_id_from?>">
			<?=Html_helper::img($comment->user_from->avatar_25, array('class'=>"newsfeed_comment_avatar_img", 'onerror'=>"this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'", 'alt'=>""))?>
		</a>
	</div>

	<div class="comment_info">
		<span class="user_name">
			<a href="<?=@$comment->user_from->url?>"><?=@$comment->user_from->full_name?></a>
		</span>
		<span class="comment_content">
			<?=stripslashes($comment->comment)?>
		</span>
	</div>
	<? /* ?><span class="newsfeed_time"><?=Date_Helper::time_ago($comment->time)?></span><? */ ?><!-- Date Stamp sits here -->
	<? if ($this->session->userdata('id')) { ?>
		<div class="newsfeed_entry_comment_options">
			<?php $is_liked = $is_sample || (bool) $comment->is_liked($this->user->id); ?>
			<a href="/add_like/comment/<?=$comment->comment_id?>" class="up up_button" rel="ajaxButton" style="<?=$is_liked ? 'display:none' : ''?>">
				<span class="upvote_text">Upvote</span><span class="middot">&middot;</span>
				<span class="up_wrapper"><span class="up_contents"></span></span>
				<span class="actionButton_text"><?=$comment->up_count ? $comment->up_count : ''?></span>
			</a>
			<a href="/rm_like/comment/<?=$comment->comment_id?>" class="up undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display:none'?>">
				<span class="upvote_text">Upvoted</span><span class="middot">&middot;</span>
				<span class="undo_up_wrapper"><span class="undo_up_contents"></span></span>
				<span class="actionButton_text"><?=$comment->up_count?></span>
			</a>
		</div>
	<? } ?>
	<div class="clear"></div>
</div>
<?= $is_sample ? '</script>' : '' ?>