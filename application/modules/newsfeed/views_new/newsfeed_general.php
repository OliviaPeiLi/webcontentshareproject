<? $this->lang->load('home/home_views', LANGUAGE); ?>
<ul id="list_newsfeed" class="newsfeed typeView<?=ucfirst(camelize($view));?> fd-autoscroll" data-url="<?=@$url?>" data-template="#js-newsfeed_<?=$view?>">
	<?=$this->load->view('newsfeed/newsfeed_'.$view.'_list')?>
</ul>

<a id="ScrollToTop" href="#top" class="Button WhiteButton Indicator custom-title" title="Jump to top of page" title-pos="left" style="display:none">
	<strong><?=$this->lang->line('home_views_scroll_to_top_btn');?></strong><span></span>
</a>
<?php if ($this->user) {
	$this->load->view('folder/collect');
	$this->load->view('newsfeed/coversheet_popup');
} ?>