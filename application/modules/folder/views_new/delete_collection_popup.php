<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div id="delete_folder" class="delete_folder_popup folder_delete delete_dialog" style="display:none">
	<div class="delete_dialog_container">
		<span class="warning_message_icon"></span>
		<div class="message">
			<div class="message_main_text">
				<strong><?=isset($contest) ? str_replace('list', 'category', $this->lang->line('folder_confirmation_msg1')) : $this->lang->line('folder_confirmation_msg1')?></strong>
			</div>
			<?=$this->lang->line('folder_confirmation_msg2');?>
		</div>
		<div class="bottom_row">
			<a href="" class="blue_bg greyButton delete_yes" rel="ajaxButton" ><?=$this->lang->line('folder_delete_btn');?></a>
			<a href="" class="blue_bg blueButton delete_no" data-dismiss="modal"><?=$this->lang->line('folder_cancel_btn');?></a>
		</div>
	</div>
</div> 