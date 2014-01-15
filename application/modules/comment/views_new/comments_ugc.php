<?php $this->lang->load('comment/comment', LANGUAGE); ?>
<div class="folder_commentBox">
	<div class="folder_commentCount"><strong><span class="js-count_num"><?=$num_comments?></span> <?=$this->lang->line('comment_comments_count_text');?></strong></div>
	<?=Form_Helper::open('/comment', array('rel'=>'ajaxForm',"class"=>"form_row"), array('folder_id'=>$folder_id))?>
		<?=Html_helper::img($this->user ? $this->user->avatar_42 : $this->user_model->behaviors['uploadable']['avatar']['default_image'])?>
		<textarea class="folder_commentInputBox fd_mentions"  name="comment" placeholder="<?=$this->lang->line('comment_form_comment_placeholder');?>" maxlength="250" data-validate="required|maxlength"></textarea>
		<input type="submit" name="create" value="<?=$this->lang->line('comment');?>" class="actionButton">
		<span class="textLimit">250</span>
	<?=Form_Helper::close()?>
</div>
<div class="folder_comments">
	<? $this->load->view('comment/comment_ugc', array('comment'=>$this->comment_model->sample()))?>
	
	<ul class="fd-scroll commentsUL">
		<? foreach ($comments as $comment) { ?>
			<? $this->load->view('comment/comment_ugc', array('comment'=>$comment))?>
		<? } ?>
	</ul>
	
</div>
<?=Html_helper::requireJS(array("comments/comments_ugc"))?>