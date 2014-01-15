<? $this->lang->load('notification/notification', LANGUAGE) ?>
<?
//BP: todo: $notifications_color and $notifications_short wouldn't work with autoscroll
//          they require additional changes which are not in place because they seem unused

//BP: define some sub-templates as function so we can use the same code to
//    to print the template populated with data from php or to print it as script type=template/html
$_is_tmpl =  isset($notifications[0]) && $notifications[0]->id <= 0;
if ( $_is_tmpl ) { ?>
	<?=Notifications_helper::samples($notifications[0])?>
	<?='<script type="template/html" id="tmpl-notification-list-item"
			data-id="li[data-id] @data-id"
			data-_user-_avatar_73="img.avatar @src"
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
				<?=Html_helper::img($notification->user->avatar_73, array(
					'class'=>"avatar",
					'alt'=>"avatar",
					//'onerror'=>"this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'"
				));?>
			</a>
			<div class="notification_holder">
				<div class="notification" data-id="<?=$notification->id?>">	
					<?=Notifications_helper::notification($notification->type, $notification)?>
					<small class="converted_time"><?=$notification->converted_time?></small>
				</div>
			</div>
			<div class="notification_iconHolder">
				<div class="notification_<?=$notification->type?> notification_icon"></div>
			</div>
			<div class="clear"></div>
		</li>
	<?php } ?>
<?php } else {  ?>
	<li class="notification_entry">No notifications</li>
<?php } ?>
<?=$_is_tmpl ? '</script>' : ''?>
<?php if (!$_is_tmpl && isset($short_notification) && $short_notification == true ) { //  && count($notifications) > $per_page ?>
	<li id="notification_feed_bottom" class="feed_bottom" data-offset="1">
		<a class="more_news_link" href="javascript:;">Load More Notifications</a>
	</li>
<?php } ?>
<?=Html_helper::requireJS(array("notification/notification"))?>
