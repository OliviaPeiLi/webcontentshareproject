<?php echo  (isset($sample) && $sample) ? 
'<script type="template/html" id="js-message"
		data-id = "div.msg_entry @data-message_id, div.msg_entry @data-url, .js-delete_float @href"
		data-msg_info-user_from-avatar_42 = ".user_from_avatar_image @src"
		data-msg_info-user_from-url = ".sender_name @href"
		data-msg_info-user_from-full_name = ".sender_name"
		data-msg_info-from = ".inbox_msg_line @data-from"
		data-msg_info-time_ago = ".timestamp"
		data-msg_body = ".body_message"
	>
' : '';
?>
<div class="msg_thread_entry msg_entry js-msg_entry <?=$message->msg_info->from==$this->user->id ? ($message->msg_info->display_status ? 'read' : 'unread') : ''?> <?=(isset($sample) && $sample) ? " .js-sample" : ""?>">
	<div class="inbox_msg_line <?=$message->msg_info->from==$this->user->id ? 'msg_from_you' : 'msg_from_others'?>" data-from="" style="position: relative;">
		<div class="user_info inlinediv">
			<div class="avatar">
				<?=Html_helper::img($message->msg_info->user_from->avatar_42, array("class"=>"user_from_avatar_image"))?>
			</div>
			<div class="sender_name">
				<a href="<?=$message->msg_info->user_from->url?>" class="sender_name"><?=$message->msg_info->user_from->full_name?></a>
			</div>
		</div>
		<div class="js-body body inlinediv">
			<p class="body_message"><?=nl2br_except_pre(htmlspecialchars($message->msg_body))?></p>
			<div class="timestamp"><?=Date_Helper::time_ago($message->msg_info->time)?></div>
		</div>
		<a href="/del_msg/<?=$message->msg_id?>" class="js-delete_float delete_float" rel="ajaxButton"></a>
		<div class="clear"></div>

	</div>
</div>
<?php echo isset($sample) ? '</script>' : '';?>