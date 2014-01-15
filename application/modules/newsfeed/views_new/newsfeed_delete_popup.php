<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<div id="delete_dialog" class="delete_post_popup delete_dialog" style="display:none;">
	<div class="delete_dialog_container">
		<span class="warning_message_icon"></span>
		<div class="message">
			<div class="message_main_text"><strong><?=$this->lang->line('newsfeed_views_delete_post_warning1');?></strong></div>
		</div>
		<div class="bottom_row">
			<a href="javascript:;" class="blue_bg greyButton delete_yes" rel="ajaxButton"><?=$this->lang->line('newsfeed_views_delete_lexicon');?></a>
			<a href="javascript:;" class="blue_bg blueButton delete_no" data-dismiss="modal"><?=$this->lang->line('newsfeed_views_cancel_btn');?></a>
		</div>
	</div>
</div> 