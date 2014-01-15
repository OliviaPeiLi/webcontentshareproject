<? $this->lang->load('home/home_views', LANGUAGE); ?>
<?
    foreach ($newsfeeds as $newsfeed)
	{
		//$news_array=unserialize($fvalue['data']);
		$last_timestamp['friends'] = $newsfeed->time;
		//$last_timestamp['friends']
    //include('application/views/newsfeed/newsfeed.php');
    $this->load->view('newsfeed/newsfeed',
      array('view_type'=>$view_type, 'page_id'=>$page_id, 'post_type'=>$post_type, 'profile_id'=>$profile_id, 'fvalue'=>$fvalue));
	}
    if (count($newsfeeds) > 15) { ?>
        <div id="friends_feed_bottom" class="feed_bottom">
            <a class="more_news_link" href="#"><?=$this->lang->line('home_views_more_news_lexicon');?></a>
            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['friends']; ?></div>
        </div>
    <? } else { ?>
            <div class="no_more_news"><?=$this->lang->line('home_views_no_more_news_lexicon');?></div>
    <? }
