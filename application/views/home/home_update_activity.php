<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/home/home_update_activity.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('home/home_views', LANGUAGE); ?>
<? //print_r($ticker);
	//$this->load->model('newsfeed_activity_model');
	foreach ($ticker as $fkey => $activity)
	{
		//$last_timestamp['friends'] = $activity->id;
		echo $this->load->view('newsfeed/activity', array('view'=>'profile','activity'=>$activity), true);
		//$type = 'profile';
		//$view_type = 'home';
		//$newsfeed_id = $this->newsfeed_activity_model->get($fvalue['aid'])->newsfeed_id;
		//include('application/views/newsfeed/activity.php');
	}
    if (count($ticker) > 29) { ?>
        <div id="activity_feed_bottom" class="feed_bottom">
            <a class="more_news_link" href="#"><?=$this->lang->line('home_views_more_news_lexicon');?></a>
            <div class="last_timestamp" style="display: none;"><?=end($ticker)->id?></div>
            <div class="category_type" style="display:none">none</div>
            <div class="view" style="display:none"><?=$view_type?></div>
        </div>
    <? } else { ?>
            <div class="no_more_news"><?=$this->lang->line('home_views_no_more_news_lexicon');?></div>
    <? } ?>
    
    <?=requireJS(array("jquery","common/init_badge"))?> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/home/home_update_activity.php ) -->' . "\n";
} ?>
