<?php
$this->lang->load('newsfeed/newsfeed_views', LANGUAGE); 
$this->lang->load('newsfeed/newsfeed', LANGUAGE);

$this->load->view('newsfeed/newsfeed_postcard', array('newsfeed'=>$this->newsfeed_model->sample()));
foreach ($newsfeeds as $newsfeed) { 
	$this->load->view('newsfeed/newsfeed_postcard', array('newsfeed'=>$newsfeed));
}
?>

<? if (count($newsfeeds) >= $this->config->item('postcard_newsfeed_limit')) { ?>
	<div class="feed_bottom postcard_feed_bottom">
		<a href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link');?></a>
	</div>
<? } elseif ($this->input->post('autoscroll',true)) { ?>
	<div class="postcard_no_more_news"><?=$this->lang->line('newsfeed_views_no_posts_msg');?></div>
<? } ?>
<?=Html_helper::requireJS(array("newsfeed/newsfeed_postcard"))?> 