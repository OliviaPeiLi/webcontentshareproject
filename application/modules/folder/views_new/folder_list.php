<? $this->lang->load('folder/folder', LANGUAGE); ?>
<?php if ($this->is_mod_enabled('folders_contributors')) { 
	$this->load->view('folder/drop_into_folder_popup');
} ?>
<? if(isset($show_header) && $show_header === true && count($folders) > 0): ?>
	<h2>Stories</h2>
<? endif; ?>
<? if(isset($collections_cache)) ob_start(); ?>
<div id="folders">
	<?php if ($folders && end($folders)->type && @$this->folder_model->types[end($folders)->type]) { ?>
		<? $this->load->view('folder/folder_item_'.$this->folder_model->types[end($folders)->type], array('folder'=>$this->folder_model->sample() )) ?>
	<?php } else { ?>
		<? $this->load->view('folder/folder_item', array('folder'=>$this->folder_model->sample() )) ?>
	<?php } ?>
	<div id="all_folders" class="fd-autoscroll" data-template="#tmpl-folder-item" data-maxscrolls="-1" data-url="<?=$url?>">
		<? //RR - search results use this template so $profile_user may not be set ?>
		<? if (isset($profile_user) && $profile_user->id == $this->session->userdata('id')) { ?> 
			<? //The New Collection box ?>
			<div id="create_new_collection_folder" class="js-folder folder expandable_folder unclickable">
				<div class="folderbase">
					<div class="new_collection_icon"></div>
					<div class="new_folder_options">
						<?php if (!isset($contest) && $this->is_mod_enabled('rss_auto_port')) { ?>
							<?php $has_fb_coll = $this->user->has_fb_collection()?>
							<?php $has_twt_coll = $this->user->has_twitter_collection()?>
							<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonGeneric" class="custom-title standalone_btn">
								<span class="buttonContents"></span>
								<!--Create A New Collection-->
							</div>
							<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonRSS" class="custom-title standalone_btn" data-rss_source_id="-1">
								<span class="buttonContents"></span>
								<!--Create Blog-Stream Collection-->
							</div>
							<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonFacebook" class="custom-title standalone_btn <?=$has_fb_coll ? 'disabled' : ''?>" data-rss_source_id="1">
								<span class="buttonContents"></span>
								<!--Create Facebook Collection-->
							</div>
							<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonTwitter" class="custom-title standalone_btn <?=$has_twt_coll ? 'disabled' : ''?>" data-rss_source_id="2">
								<span class="buttonContents"></span>
								<!--Create Twitter Collection-->
							</div>
						<?php } else { ?>
							<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonGeneric" class="custom-title standalone_btn">
								<span class="buttonContents"></span>
								<!--Create A New Collection-->
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } else if (isset($contest) && $contest->user_id == $this->session->userdata('id')) { ?>
			<div id="create_new_collection_folder" class="js-folder folder expandable_folder unclickable">
				<div class="folderbase">
					<div class="new_collection_icon"></div>
					<div class="new_folder_options">
						<div data-url="#edit_folder_popup" rel="popup" id="newCollection_buttonGeneric" class="custom-title standalone_btn newCategory_button">
							<span class="buttonContents"></span>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		
		<? foreach ($folders as $folder) { ?>
			<? try { //Loads special view if exists?>
				<? $this->load->view('folder/folder_item_'.$this->folder_model->types[$folder->type], array('folder'=>$folder,"show_shares"=>isset($show_shares) ? TRUE : FALSE )) ?>
			<?php } catch (Exception $e) { ?>
				<? $this->load->view('folder/folder_item', array('folder'=>$folder,"show_shares"=>isset($show_shares) ? TRUE : FALSE )) ?>
			<?php } ?>
		<? } ?>
			
		<? if (count($folders) == $per_page) { ?>
			<div class="feed_bottom">
				<a><?=$this->lang->line('folder_get_more_news_btn');?></a>
			</div>
		<? } ?>
	</div>
	<div id="all_folders_feed"> </div>
</div>
<a id="ScrollToTop" href="#top" class="Button WhiteButton Indicator" style="display:none"><strong><?=$this->lang->line('folder_scroll_to_top_alt');?></strong><span></span></a>

<?php if ($this->user) { ?>
	<?php if (isset($contest)) {?>
		<?php if ($contest->user_id == $this->session->userdata('id')) { ?>
			<? $this->load->view('folder/contest_popup')?>	
		<?php } ?>
	<?php } else { ?>
		<? $this->load->view('folder/create_collection_popup')?>	
	<?php } ?>
	<? $this->load->view('folder/delete_collection_popup')?>
<?php } ?>
<? $this->load->view('folder/folder_embed_popup')?>	

<? if(isset($collections_cache)) {
	$cache = ob_get_clean(); 
	$this->cache->save($collections_cache, $cache, $this->config->item('cache_expire'));
	echo $cache;
} ?>

<?=Html_helper::requireJS(array("folder/folder_main"))?>