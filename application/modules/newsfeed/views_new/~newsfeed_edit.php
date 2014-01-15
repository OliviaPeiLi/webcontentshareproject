<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<div id="newsfeed_timeline_edit_<?=$newsfeed->newsfeed_id?>" class="modal newsfeed_timeline_edit keep_on_mouseleave" style="display:none; position:absolute">
	<div class="modal-header">
		<button class="close_popup new_close1">​</button>
		<h3>Edit</h3>​
	</div>
	<div class="modal-body">
		<?=form_open('/bookmarklet/edit?id='.$newsfeed->newsfeed_id, 
					array('class'=>"edit_post_form",'rel'=>"ajaxForm",'data-edit_newsfeed_id'=>$newsfeed->newsfeed_id),
					array('submit'=>$this->lang->line('newsfeed_views_save_lexicon'))
		)?>
			<div class="form_row">
				<label>Title</label>
				<textarea name="activity[link][caption]" 
							data-validate="required|maxlength|hashtag" 
							data-error-hashtag="You need to use at least one hashtag"
							data-error-required="The title cannot be blank"
							class="media_text fd_mentions" maxlength="250"><?=strip_tags(@$newsfeed->description)?></textarea>
				<span class="maxLength">250</span>
				<div class="error" style="display:none;"></div>
			</div>
			<div class="form_row">
				<?php foreach ($hashtags as $hashtag) {?>
					<a href="<?=$hashtag?>" class="hashtag"><?=$hashtag?></a>
				<?php } ?>
			</div>
			<div class="form_row">
				<label>Source</label>
				<?=Form_Helper::form_input('activity[link][link]',strip_tags($newsfeed->activity->link), 'class="media_text source_url"'); ?>
			</div>
			<div class="form_row actions">
				<a class="done_button blue_bg" href=""><?=$this->lang->line('newsfeed_views_save_lexicon');?></a>
				<a href="#delete_dialog" rel="popup" class="delete_button blue_bg timeline_delete_btn" data-delurl="/del_link/<?=$newsfeed->newsfeed_id?>"><?=$this->lang->line('newsfeed_views_delete_drop_lexicon');?></a>
				<div class="data_status" style="display:none"><?=$this->lang->line('newsfeed_views_saved_lexicon');?></div>
				<input type="submit" style="display:none"/>
			</div>
		<?php echo form_close()?>
	</div>
</div>
<?=Html_helper::requireJS(array('newsfeed/newsfeed_popup_edit'))?>
