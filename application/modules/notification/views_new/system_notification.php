<div id="systemNotification">
	<? $this->load->view('notification/system_notification_'.$notification->template_name) ?>
	<span id="notification_close" rel="ajaxButton" data-url="/system_notification_close/<?=$notification->id?>"><span id="notification_close_contents"></span></span>
</div>
<?=Html_helper::requireJS(array("notification/system_notification")) ?> 