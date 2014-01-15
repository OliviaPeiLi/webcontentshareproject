<?=$thread->thread_id <= 0 ? '
 <script type="template/html" id="js-message"
		data-id = ""
		data-thread_id = "div.msg_entry @data-url, div.msg_entry @data-message_id, .js-delete_float @href"
		data-avatar_73 = "img.user_from_avatar_image"
		data-message = ".body_user_link"
		data-msg_info-time_ago = ".time_ago"
		data-msg_info-message = ".content_message"
	>
' : ''?>
<div class="msg_entry inbox_msg_line js-msg_entry <?=$thread->msg_info->display_status ? 'read' : 'unread';?> <?=$thread->thread_id <= 0 ? ".js-sample" : "";?>" data-url="/view_msg/<?=$thread->thread_id?>" style="position:relative;"
	data-message_id="<?=$thread->thread_id;?>">

	<div class="avatars_preview">
		<?=Html_helper::img($thread->msg_info->user_from->avatar_73, array("class"=>"user_from_avatar_image"))?>
		<ul>
			<?php if (count($thread->users) > 0 && $thread->thread_id > 0) { ?>
				<?php foreach($thread->users as $k=>$user) {// if ($k == 0) continue; ?>
				<li class="<?=($k+1)==count($thread->users) ? "last" : ""?> sub_image_item">
					<?=Html_helper::img($user->avatar_25, array("width"=>"22","height"=>"22","alt"=>$user->full_name))?>
				</li>
				<? } ?>
			<? } else { // call template?>
				<li class="last sub_image_item">
					<?=Html_helper::img("",array("width"=>"22","height"=>"22","alt"=>""))?>
				</li>
			<?php } ?>			
		</ul>
	</div>
	
	<div class="conversation_title">
		<span class="js-convo_partners convo_partners">
			<?php foreach($thread->users as $k => $user) { if ($user->id == $this->user->id) continue; ?>
				<a href="<?=$user->url?>" class="convo_partners_item"><?=$user->full_name?></a><?= $k < count($thread->users) - 1 ? ',' : ''?>
			<?php } ?>
		</span>
	</div>
	
	<div class="conversation_bottom">
			<p>
				<? if ($thread->msg_info->from != $this->user->id) { ?>
					<span class="body_user_link"><?=$thread->msg_info->user_from->full_name?>: </span>
				<? } ?>
				<span class="content_message"><?=$thread->msg_info->message?></span>
			</p>	
			<span class="time_ago"><?=Date_Helper::time_ago($thread->time)?></span>
			<a href="/del_thread/<?=$thread->thread_id?>" class="js-delete_float delete_float" rel="ajaxButton"></a>
	</div>
	<div class="clear"></div>						
</div>
<?=$thread->thread_id <= 0 ? '
	</script>
' : ''?>