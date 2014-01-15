<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $this->lang->load('newsfeed/newsfeed', LANGUAGE); ?>
<? $this->lang->load('comment/comment', LANGUAGE); ?>

<? if (!$cache = $this->cache->get(@$cache_name)){ ob_start();?>
	
	<? $this->load->view('newsfeed/newsfeed_tile_new', array('newsfeed'=>$this->newsfeed_model->sample())) ?>
	
	<? if(empty($newsfeeds) || count($newsfeeds) <= 0) { ?>
		<li class="blank_post">
			<? if (isset($folder) && $folder->rss_source_id) { ?>
				Collecting Drops in Progress...	
			<? } else { ?>
				<?=$this->lang->line('newsfeed_views_no_posts_lexicon');?>
			<? } ?>
		</li>
	<? } else { ?>
		<? foreach ($newsfeeds as $newsfeed) { ?>
			<?=$this->load->view('newsfeed/newsfeed_tile_new', array('newsfeed'=>$newsfeed, 'view' => $view), true)?>
		<? } ?>
		
		<? if(count($newsfeeds) > $this->config->item('tile_new_newsfeed_limit')-1) {?>
			<li class="feed_bottom">
				<span class="tile_feed_bottom">
					<a class="more_news_link" href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link')?></a>
				</span>
			</li>
	    <? } ?>
	<? } ?>

	<?php 
		$cache = ob_get_clean();
		$this->cache->save(@$cache_name, $cache);
	?>

<? } echo $cache; ?>
<?=Html_helper::requireJS(array("newsfeed/newsfeed_tile_new")) ?>