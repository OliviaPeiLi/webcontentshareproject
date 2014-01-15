<?php $this->lang->load('comment/comment', LANGUAGE); ?>
<?php $is_sample = $comment->comment_id == 0?>
<?= $is_sample ? '<script type="template/html" id="tmpl-newsfeed_entry_comment" 
	data-comment=".body"
>' : '' ?>
<li class="commentUnit">
	<a href="<?=$comment->user_from->url?>" class="comment_avatar">
		<?=Html_helper::img($comment->user_from->avatar_42)?>
	</a>
	<div class="comment_sideContainer">
		<a href="<?=$comment->user_from->url?>" class="comment_userName">
			<?=$comment->user_from->full_name?>
		</a>
		<div class="comment_body">
			<?=stripslashes($comment->comment)?>
		</div>
		<div class="comment_time"><?=Date_Helper::time_ago($comment->time)?></div>
	</div>
	<?php $is_liked = $this->session->userdata('id') ? $comment->is_liked($this->user) : false ?>
		<span class="upbox">
			<a href="/add_like/comment/<?=$comment->comment_id?>" style="<?=$is_liked ? 'display:none' :'';?>"
				rel="ajaxButton" class="actionButton vote upvote custom-title" title="<?=$this->lang->line('comment_upvote_comment_title');?>" title-pos="left"
			>
				<span class="ico"></span><span class="num"><?=$comment->up_count?></span>
			</a>
			<a href="/rm_like/comment/<?=$comment->comment_id?>" style="<?=$is_liked ? '' :'display:none';?>"
				rel="ajaxButton" class="actionButton vote downvote custom-title" title="<?=$this->lang->line('comment_downvote_comment_title');?>" title-pos="left"
			>
				<span class="ico"></span><span class="num"><?=$comment->up_count?></span>
			</a>
		</span>
	<? if($comment->can_delete(@$this->user) || $is_sample) { ?>
		<a href="/del_comm/<?=$comment->comment_id?>" rel="ajaxButton" class="js-delete_comment"><?=$this->lang->line('delete');?></a>
	<? } ?>
</li>
<?= $is_sample ? '</script>' : '' ?>
