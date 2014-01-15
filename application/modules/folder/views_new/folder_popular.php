<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div class="sidebar_list">
	<div class="sidebar_comments">
		<div id="landing_left_title">Popular Stories</div>
		<? $this->load->view('folder/folder_item_popular', array('folder'=> $this->folder_model->sample())) ?>
		
		<ul id="popular_collections_list" class="fd-autoscroll" data-url="<?=$url?>" data-template="#js-popular-collection">
			<?php foreach ($folders as $folder) { ?>
				<? $this->load->view('folder/folder_item_popular', array('folder' => $folder))?>
			<?php } ?>
			
			<li class="feed_bottom">
				<a><?=$this->lang->line('folder_get_more_news_btn')?></a>
			</li>
		</ul>
	</div>
</div>
<?=Html_helper::requireJS(array('folder/popular_collections'))?>