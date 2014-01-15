<? $this->lang->load('search/search_views', LANGUAGE); ?>
	<? /* ?>
	<h1 class="primary_title"><?=$this->lang->line('search_views_results_page_heading');?></h1>
	<? */ ?>
	<h1 class="primary_title"><?=$keyword?></h1>
	<div id="main" class="search_results">
		<div class="top_container">			
			<div id="results_sections">
				<? if($search_category == 'drops') { ?>
					<?php if ($this->input->get('action') == 'created_collection' && $folder_info) { ?>
						<div id="created_collection_msg">
							Your list <a href="<?=$folder_info->get_folder_url();?>" id="new_collection_name"><?=$folder_info->folder_name;?></a> has been created. Start dropping content into it!
							<span class="close"></span>
						</div>
					<?php } ?>
					<div id="search_sort_by">
						<? $this->load->view('newsfeed/filter_types_menu', array('base'=>'/search/drops')) ?>
					</div>
					<div class="search_result_middot inlinediv">&middot;</div>
				<? } ?>
				<div class="search_result_dropcomments_container inlinediv <?=$search_category == 'drops' ? 'active' : ''?>">
					<a href="/search/drops?q=<?=urlencode($keyword)?>"><?=$this->lang->line('search_views_drops_lexicon');?></a>
				</div>
				<div class="search_result_middot inlinediv">&middot;</div>
				<div class="search_result_people inlinediv <?=$search_category == 'people' ? 'active' : ''?>">
					<a href="/search/people?q=<?=urlencode($keyword)?>"><?=$this->lang->line('search_views_people_lexicon');?></a>
				</div>				
			</div>
		</div>
		<? if(! $has_results){ ?>
			<div class="no_results"><?=$this->lang->line('search_views_no_results_msg');?></div>
		<? } else { ?>
			<div class="search_content">
				<? if ($search_category == 'drops') { ?>
					<div id="comments_container" class="messagesContainer">
						<? $this->load->view('newsfeed/newsfeed_general')?>
					</div>
					<div class="sidebar_comments">
						<?=Modules::run('search/search/collections_search', true)?>
					</div>
				<? } elseif ($search_category == 'people') {?>
					<? $this->load->view('profile/user_list')?>
				<? } ?>
			</div>
		<? } ?>			
	</div>
<script type="text/javascript">
	php.query = '<?=str_replace("'", "\'", $keyword)?>';
</script>
<?=Html_helper::requireJS(array("search/search"))?> 
