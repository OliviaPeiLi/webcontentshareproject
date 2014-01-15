<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $this->lang->load('newsfeed/newsfeed', LANGUAGE); ?>
<? $this->lang->load('comment/comment', LANGUAGE); ?>

<?php $this->load->view('newsfeed/newsfeed_sxsw', array('newsfeed'=>$this->newsfeed_model->sample())); ?>

<?php if(empty($newsfeeds) || count($newsfeeds) <= 0): ?>
	<li class="blank_post">
		<?php if (isset($folder) && $folder->rss_source_id) { ?>
			Collecting Drops in Progress...	
		<?php } else { ?>
			<?=$this->lang->line('newsfeed_views_no_posts_lexicon');?>
		<?php } ?>
	</li>
<?php else: ?>
	<?php foreach ($newsfeeds as $newsfeed) { ?>
		<? $this->load->view('newsfeed/newsfeed_sxsw', array('newsfeed'=>$newsfeed))?>
	<?php } ?>
	
	<? if(count($newsfeeds) > $this->config->item('tile_new_newsfeed_limit')-1):?>
		<li class="feed_bottom">
			<span class="tile_feed_bottom">
				<a class="more_news_link" href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link');?></a>
			</span>
		</li>
    <? endif; ?>
<?php endif; ?>

<?=Html_helper::requireJS(array("newsfeed/newsfeed_sxsw")) ?>