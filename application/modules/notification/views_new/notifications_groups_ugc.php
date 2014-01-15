<? $this->lang->load('notification/notification', LANGUAGE); ?>
<?= isset($grouped_notifications[0]) ? '<script type="template/html" id="tmpl-notification-groups"
	data-date_string="div.section_title"
>' : ''?>
<?php foreach ($grouped_notifications as $time => $notifications) { if (!$notifications) continue; ?> 
	<li class="notification_sections">
		<div class="section_title">
			<?=$time==date('Y-m-d') ? $this->lang->line('notification_today_lexicon') : (
					$time==date("Y-m-d", strtotime("yesterday")) ? $this->lang->line('notification_yesterday_lexicon') 
						: date('F d, Y', strtotime($time))
			)?>
		</div>
		<ul class="notification_list_for_section">
			<?php if ($notifications[0]->id) { ?>
				<?php $this->load->view('notifications_ugc', array('notifications'=>$notifications,'notifications_color'=>false, 'per_page'=>$per_page,"long_notify"=>true))?>
			<?php } ?>
		</ul>
	</li>
<?php } ?>
<?= isset($grouped_notifications[0]) ? '</script>' : ''?>