<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $this->lang->load('newsfeed/newsfeed', LANGUAGE); ?>
<? $this->lang->load('comment/comment', LANGUAGE); ?>

<?php $this->load->view('newsfeed/newsfeed_contest', array('newsfeed'=>$this->newsfeed_model->sample())); ?>

<?php if(empty($newsfeeds) || count($newsfeeds) <= 0) { ?>
	<li class="blank_post">
		<?=$this->lang->line('newsfeed_views_no_posts_lexicon');?>
	</li>
<?php } else { ?>
	<?php foreach ($newsfeeds as $newsfeed) { ?>
		<? $this->load->view('newsfeed/newsfeed_contest', array('newsfeed'=>$newsfeed))?>
	<?php } ?>
	
	<? if(count($newsfeeds) >= $per_page) {?>
		<li class="feed_bottom">
			<span class="tile_feed_bottom">
				<a class="more_news_link" href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link');?></a>
			</span>
		</li>
    <? } ?>
<?php } ?>

<?=Html_helper::requireJS(array("newsfeed/newsfeed_contest")) ?>