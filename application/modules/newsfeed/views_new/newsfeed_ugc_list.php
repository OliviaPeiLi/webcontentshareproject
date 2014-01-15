<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $this->load->view('newsfeed/newsfeed_ugc', array('newsfeed'=>$this->newsfeed_model->sample())) ?>
	
<? if($newsfeeds) { ?>
	<? foreach ($newsfeeds as $newsfeed) { ?>
		<? $this->load->view('newsfeed/newsfeed_ugc', array('newsfeed'=>$newsfeed))?>
	<? } ?>
	
	<? if(count($newsfeeds) > $this->config->item('ugc_newsfeed_limit')-1) {?>
		<li class="feed_bottom">
			<a href="javascript:;"><?=$this->lang->line('newsfeed_views_load_more_lexicon');?></a>
		</li>
    <? } ?>
<? } else { ?>
	<li class="no-items">
		<? if (isset($folder) && $folder->rss_source_id) { ?>
			<?=$this->lang->line('newsfeed_views_in_progress_lexicon');?>	
		<? } else { ?>
			<?=$this->lang->line('newsfeed_views_no_posts_lexicon');?>
		<? } ?>
	</li>
<? } ?>

<?=Html_helper::requireJS(array("newsfeed/newsfeed_ugc")) ?>