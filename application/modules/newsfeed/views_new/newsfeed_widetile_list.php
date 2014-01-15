<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>

	<? 
	$tmp = 0; 
	$group_size = ($this->input->post('group_size',true)) ? $this->input->post('group_size',true) : $this->config->item('widetile_newsfeed_limit');
	?>
	<?php foreach ($newsfeeds as $newsfeed): ?>
		<? //$item_arr = $item->as_array(); ?>
		<? //print_r($item_arr); ?>
		<?=$this->load->view('newsfeed/newsfeed_widetile', array('newsfeed'=>$newsfeed), true)?>
		<? if ($tmp<$group_size) {
			$last_timestamp = $newsfeed->newsfeed_id;
		}
		$tmp++; ?>

	<?php endforeach;?>
	
	<? if (count($newsfeeds) > $this->config->item('widetile_newsfeed_limit')-1): ?>
    	<div class="clear"></div>
    	
    	<? if (isset($page_id) || $view === 'home') {
    		$feed_bottom_id = 'interests_feed_bottom';
    	} else {
    		$feed_bottom_id = 'folder_feed_bottom';
    	} ?>
        <div id="<?=$feed_bottom_id?>" class="feed_bottom widetile_feed_bottom" style="clear:both;">

            <a class="more_news_link" href="javascript:;"><?=$this->lang->line('newsfeed_views_more_news_link');?></a>
            <div class="last_timestamp" style="display:none"><?=$last_timestamp?></div>
            <div class="category_type" style="display:none"><?=$category_type?></div>
            <div class="topic_id" style="display:none"><?=@$topic_id?></div>
        </div>
    <? else: ?>
    	<? if ($this->input->post('autoscroll',true)): ?>
        	<div class="widetile_no_more_news"><?=$this->lang->line('newsfeed_views_no_posts_msg');?></div>
        <? endif; ?>
    <? endif; ?>

<?=Html_helper::requireJS(array("newsfeed/newsfeed_list","newsfeed/newsfeed_widetile","common/autoscroll_new"))?>