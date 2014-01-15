<? $this->lang->load('folder/folder', LANGUAGE); ?>
<? if(isset($cache_name) && !$cache = $this->cache->get($cache_name)) { ?>
	<? ob_start(); ?>
	<div id="folders" class="<?=isset($is_profile) && $is_profile ? 'profile' : ''?>">
		<? $this->load->view('folder/folder_list_ugc_item', array('folder'=>$this->folder_model->sample() )) ?>
		
		<ul class="fd-autoscroll" data-url="<?=htmlentities($url)?>" data-template="#js-folder_ugc">
		<?php if (count($folders) > 0) : ?>
			<?php foreach ($folders as $folder) {  ?>
				<? $this->load->view('folder/folder_list_ugc_item', array('folder'=>$folder , "is_profile"=>@$is_profile )) ?>
			<?php } ?>
			<? if (count($folders) >= $per_page) { ?>
				<li class="feed_bottom">
					<a><?=$this->lang->line('folder_get_more_news_btn');?></a>
				</li>
			<? } ?>
		<?php else : ?>
				<li class="no_results"><?=$this->lang->line('folder_no_stories_lexicon');?></li>
		<?php endif; ?>
		</ul>
	</div>
	<a id="ScrollToTop" href="#top" class="Button WhiteButton Indicator custom-title" title="<?=$this->lang->line('folder_to_top_lexicon');?>" title-pos="left" style="display:none">
		<strong><?=$this->lang->line('folder_scroll_to_top_alt');?></strong><span></span>
	</a>
	<?
	$cache = ob_get_clean();
	$this->cache->save($cache_name, $cache);
}
print $cache;
?>

<?php if ($this->user) { ?>
	<?php if (isset($contest)) {?>
		<?php if ($contest->user_id == $this->session->userdata('id')) { ?>
			<? $this->load->view('folder/contest_popup')?>	
		<?php } ?>
	<?php } ?>
	<? $this->load->view('folder/delete_collection_popup')?>
<?php } ?>
<? $this->load->view('folder/folder_embed_popup')?>	


<?=Html_helper::requireJS(array("folder/folder_main_ugc"))?>
