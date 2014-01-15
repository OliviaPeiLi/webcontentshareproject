<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/home/home_update_interest.php ) --> ' . "\n";
} ?>
<? $this->lang->load('home/home_views', LANGUAGE); ?>
<? $moveon = false; ?>
	<?
	//DIfferent layouts for pages
	//if ($this->input->post('view',true) === 'page' || $this->input->post('view',true) === 'home') {
	if ($view === 'page') {
		if ($view_layout === 'page_view_grid') {
			//echo 'GRID LAYOUT';
			//$newsfeed_layout = 'grid'
			$moveon = true;
		} else if ($view_layout === 'page_view_timeline') {
			$moveon = true;
		} else if ($view_layout === 'page_view_tile') {
			$moveon = false;
			echo requireJS(array("jquery","newsfeed/newsfeed","newsfeed/newsfeed_tile","comments/newsfeed_comments"));
		} else if ($view_layout === 'page_view_magazine') {
			$moveon = false;
		} else {
			$moveon = true;
			echo requireJS(array("jquery","newsfeed/newsfeed","newsfeed/newsfeed_list","comments/newsfeed_comments"));
		}
	} else {
		$moveon = true;
	}
	
	?>

	<? if ($moveon) { ?>
		<? foreach ($newsfeeds as $newsfeed)
		{
			//$news_array=unserialize($fvalue['data']);
			//$in_folder = 
			//$data['in'] = $data['can_comment'] = $this->page_model->user_in($news_array['page_id_to']);
			$in = '1';
			?>
			
			<? $last_timestamp['interests'] = $newsfeed->time; ?>
			<?//$last_timestamp['interests']?>
			<?
			if ($view === 'home') {
				if ($view_layout === 'page_view_timeline') {
					/* include('application/views/newsfeed/newsfeed_timeline.php'); */
					echo $this->load->view('newsfeed/newsfeed_timeline', array('newsfeed'=>$newsfeed), true);
				} else {
					//include('application/views/newsfeed/newsfeed_home_interests.php');
					echo $this->load->view('newsfeed/newsfeed_home_interests');
				}
			} else {
				//DIfferent layouts for pages
				if ($view === 'page') {
					if ($view_layout === 'page_view_grid') {
            //include('application/views/newsfeed/newsfeed_grid.php');
            echo $this->load->view('newsfeed/newsfeed_grid', '', true);
					} else if ($view_layout === 'page_view_magazine') {
						//include('application/views/newsfeed/newsfeed_mag.php');
            echo $this->load->view('newsfeed/newsfeed_mag', '', true);
					} else {
						//include('application/views/newsfeed/newsfeed.php');
            echo $this->load->view('newsfeed/newsfeed', '', true);
					}
				} else {
					//include('application/views/newsfeed/newsfeed_home_interests.php'); 
          echo $this->load->view('newsfeed/newsfeed_home_interests', '', true);
				}
			}
			?>
		<? } ?>
		
	    <? if (count($newsfeeds) > 15) { ?>
	        <div id="interests_feed_bottom" class="feed_bottom" style="clear:both;">
	            <a class="more_news_link" href="#"><?=$this->lang->line('home_views_more_news_lexicon');?></a>
	            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['interests']; ?></div>
	        </div>
	    <? } else { ?>
	    	<? if ($this->input->post('autoscroll',true) === '1') { ?>
	        	<li class="no_more_news"><?=$this->lang->line('home_views_no_more_news_lexicon');?></li>
	        <? } ?>
	    <? } ?>
	    <?=requireJS(array("jquery","common/init_badge"))?>
	<? } ?>
	
	<? //Tile Layout code ?>
	<? if ($view_layout === 'page_view_tile') { ?>
        <? //if(!isset($fvalue)){ $fvalue=null; } ?>
		<? $last_timestamp['interests'] = $newsfeed->time; ?>
		<? if ($this->input->post('autoscroll',true) !== '1') { ?>
		<div id="tile_layout">
			<div id="wrappers">
		<? } ?>
				<? $f_count = 0; ?>
				<? foreach ($newsfeeds as $newsfeed)
				{
					//$news_array=unserialize($fvalue['data']);
					$newsfeed_id = $newsfeed->newsfeed_id;
					//$data['in'] = $data['can_comment'] = $this->page_model->user_in($news_array['page_id_to']);
					?>
					<? //include('application/views/newsfeed/newsfeed_tile.php'); ?>
					<?=$this->load->view('newsfeed/newsfeed_tile','',true); ?>
					<? $last_timestamp['interests'] = $newsfeed->time; ?>
					<?//$last_timestamp['interests']?>
					<?
					$f_count++;
					?>
				<? } ?>
				<div class="clear"></div>
			<? if ($this->input->post('autoscroll',true) !== '1') { ?>
			</div>
			<? } ?>
		    <? if (count($newsfeeds) > 11) { ?>
		    	<div class="clear"></div>
		        <div id="interests_feed_bottom" class="feed_bottom tile_feed_bottom" style="clear:both;">
		            <a class="more_news_link" href="#"><?=$this->lang->line('home_views_more_news_lexicon');?></a>
		            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['interests']; ?></div>
		        </div>
		    <? } else { ?>
		    	<? if ($this->input->post('autoscroll',true) === '1') { ?>
		        	<li class="no_more_news"><?=$this->lang->line('home_views_no_more_news_lexicon');?></li>
		        <? } ?>
		    <? } ?>
		    

		    
			<?  ?>
		<? if ($this->input->post('autoscroll',true) !== '1') { ?>
		</div>
			<? //Load view for selecting a folder to collect into ?>
			<? //include('application/modules/folder/views/collect.php'); ?>
			<?=$this->load->view('folder/collect','',true); ?>
		<? } ?>
		

		<div class="clear"></div>
		
	<? } ?>
	
	<? //Magazine Layout code ?>
    <? if ($view_layout === 'page_view_magazine') { ?>
    	<? if ($this->input->post('ajax') !== '1') { ?>
	    	<div style="display:none"></div>
			
		<? } ?>
	    
        <?
        if ($this->input->post('ajax') === '1') {
        	die(json_encode(array('items'=>$mag_lay->rend_arr(),'last_timestamp'=>strtotime($this->input->post('last_timestamp',true))+1)));
        } else { 
        	
        }
        ?>
        <?php //echo $mag_lay->rend_major();?>
    <? } ?> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/home/home_update_interest.php ) -->' . "\n";
} ?>
