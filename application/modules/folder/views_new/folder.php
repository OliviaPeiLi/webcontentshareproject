<? $this->lang->load('folder/folder', LANGUAGE); ?>
<? $this->lang->load('home/home_views', LANGUAGE); ?>
<div id="container" class="main_profile container_24">
	<div id="main_folder_content" class="grid_24 main_profile_content_folder">
		<?php try { //Try to load special view if exists ?>
    		<? $this->load->view('folder/folder_top_'.$this->folder_model->types[$folder->type])?>
    	<?php } catch (Exception $e) { ?>
    		<? $this->load->view('folder/folder_top')?>
    	<?php } ?>
    	
		<?php if (!$folder->type) { ?>
			<div id="folder_contents_top_bar">
				<span class="folder_top_bar_line"></span>
				<div id="folder_sort_by">
					<? $this->load->view('newsfeed/filter_types_menu', array('base'=>$folder->folder_url)) ?>
				</div>
			</div>
		<?php } ?>
		<div id="folder_contents" rel="<?=$folder->folder_id?>" class="messagesContainer">
			<? /*if($transfer_msg) { ?>
			    <div id="transfer_message"><?=$transfer_msg; ?></div>
			<?} */?>
			<?=modules::run('folder/folder_newsfeed/index', $folder->folder_id, $filter)?>
		</div>
		
		<?=$this->load->view('folder/delete_collection_popup')?>
	</div>
</div>
	
<script type="text/javascript">
	<?php if ($folder->type==0) {?>
		php.back_url = '<?=$folder->user->url?>';
		php.profile_id = '<?=$folder->user->id?>';
	<? } ?>
	php.folder_id = '<?=$folder->folder_id?>';
</script>

<?=Html_helper::requireJS(array("folder/folder"))?>
