<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<div class="new_activity_feed fd-scroll fd-autoscroll js_activity" data-template="#js-activity" data-maxscrolls="-1" data-url="/activity/<?=$profile_id?>" id="activity_container" data-container="#activity_container">
	<? $this->load->view('profile/activity_item', array('activity'=>$this->activity_model->sample())) ?>
	<ul>
		<? foreach ($activity as $activity_item) { ?>
			<? $this->load->view('profile/activity_item', array('activity'=>$activity_item)) ?>
		<? } ?>
		
		<? if(count($activity) > 15): ?>
			<li id="activity_feed_bottom" class="feed_bottom">
				<a class="more_news_link" href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link');?></a>
			</li>
		<? endif; ?>
	</ul>
</div>
<?=Html_helper::requireJS(array('profile/activity'))?>