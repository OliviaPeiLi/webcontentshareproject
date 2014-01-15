<? $this->lang->load('profile/profile', LANGUAGE); ?>
<div id="lists" class="container" style="margin-top: 90px">
	<div class="row">
		<div class="span6 listManager_listsColumn">
			<div class="listManager_head"><h4><?=$this->lang->line('profile_stories_lexicon');?></h4></div>
			<div class="listManager_createList"><a href="/create_list" class="listManager_createList_button greyButton"><span class="ico"></span><span class="createList_buttonText"><?=$this->lang->line('profile_create_story_lexicon');?></span></a></div>
			<div class="listManager_allLists"><a href="/manage_lists" class="greyButton"><?=$this->lang->line('profile_all_stories_lexicon');?></a></div>
			
			<ul class="listManager_listList fd-scroll">
			<?php foreach ($folders as $folder) { ?>
				<li id="folder_id_<?=$folder->folder_id?>">
					<a href="/manage_lists/<?=$folder->folder_id?>">
						<?=$folder->folder_name?>
						<small><?=$folder->private ? $this->lang->line('profile_status_draft') : $this->lang->line('profile_status_published') ?></small>
					</a>
				</li>
			<?php } ?>
			</ul>
		</div>
		<div class="span18 listManager_managerColumn">
			<? $this->load->view($view)?>
		</div>
	</div>
</div>
<?Html_helper::requireJS(array('profile/lists_container'))?>
<script type="text/javascript">
	if (location.href.indexOf('/add_posts') == -1) {
		function set_containers_height() {
			console.info('set height');
			var containers_height = window.innerHeight - 100;
			document.querySelector('#lists .listManager_listsColumn .fd-scroll').style.maxHeight = (containers_height-114)+'px';

			//For folders list
			var all_lists = document.querySelector('#lists .listManager_managerColumn .allLists_body.fd-scroll'); 
			if (all_lists) all_lists.style.maxHeight = (containers_height-55)+'px';

			//For newsfeeds list
			var all_posts = document.querySelector('#lists .listManager_managerColumn .editList_upper.fd-scroll');
			if (document.querySelector('#lists .listManager_managerColumn').className.indexOf('active') > -1) {
				containers_height -= 375;
				console.info('Active', containers_height);
			}
			if (all_posts) all_posts.style.maxHeight = Math.max(0, containers_height-205)+'px';
		}
		set_containers_height();
		window.onresize = function() {
			set_containers_height();
		}
	}
</script>
