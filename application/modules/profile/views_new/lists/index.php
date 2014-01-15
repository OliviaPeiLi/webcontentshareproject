<div class="listHead allLists_head"><h4>Your stories (<?=count($folders)?>)</h4></div>
<ul class="allLists_body fd-scroll fd-autoscroll" data-url="<?=Url_helper::current_url()?>" data-template="#manage_list_item">
	<? $this->load->view('profile/lists/index_item', array('folder'=>$this->folder_model->sample()))?>
	<?php foreach ($folders as $key=>$folder) { if ($key >= 18) break;?>
		<? $this->load->view('profile/lists/index_item', array('folder'=>$folder))?>
	<?php } ?>
	<li class="feed_bottom">Loading more stories...</li>
</ul>
<div class="listGrad"></div>
<?=Html_helper::requireJS(array('profile/lists_index'))?>