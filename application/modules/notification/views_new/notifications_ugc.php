<? $this->lang->load('notification/notification', LANGUAGE) ?>
<? $_is_tmpl =  isset($notifications[0]) && $notifications[0]->id <= 0;
if ( $_is_tmpl ) { ?>
	<?=Notifications_helper::samples($notifications[0])?>
	<?='<script type="template/html" id="tmpl-notification-list-item"
			data-id="li[data-id] @data-id"
			data-_user-_avatar_73=".avatar @src"
			data-_user-url = "a.avatar_holder @href"
			data-converted_time = "small.converted_time"
			data-_timestamp = ".converted_time @data-timestamp"
	>'?>
<? } ?>
<?php if (count($notifications) > 0) { ?>
	<?php foreach ($notifications as $notification) { ?>
		<?php if (!$notification->user) continue; ?>
		<?php if ($notification->type == 'badge') continue; ?>
		<li class="<?=isset($notifications_color) && $notifications_color ? ($notification->read ? '': 'unread_notification') : 'notification_entry'?>" data-id="<?=$notification->id?>">
			<a href="<?=$notification->user->url?>" class="avatar_holder">
				<?=Html_helper::img($notification->user->_avatar_73, array(
					'class'=>"avatar",
					'alt'=>"avatar",
					//'onerror'=>"this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'"
				));?>
			</a>
			<div class="notification_holder">
				<?=Notifications_helper::notification($notification->type, $notification)?>
			</div>
			<div class="notification_iconHolder">
				<div class="notification_<?=$notification->type?> notification_icon"></div>
			</div>
			<div class="clear"></div>
		</li>
	<?php } ?>
<?php } else {  ?>
	<li class="notification_entry"><?=$this->lang->line('notification_no_notif_lexicon');?></li>
<?php } ?>
<?=$_is_tmpl ? '</script>' : ''?>
<?php if (!$_is_tmpl && !isset($long_notify) && isset($notification_count) && $notification_count > $per_page ) {  // && count($notifications) > $per_page ?>
	<li id="notification_feed_bottom" class="feed_bottom" data-offset="1">
		<a class="more_news_link" href="javascript:;"><?=$this->lang->line('notification_load_more_lexicon');?></a>
	</li>
<?php } ?>
<?=Html_helper::requireJS(array("notification/notification"))?>
