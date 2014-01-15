<? $this->lang->load('profile/profile', LANGUAGE); ?>
	
<div id="container" class="main_profile container_24">
	<div id="main_profile_content" class="grid_24">
		<? try { //Loads special view if exists?>
			<? $this->load->view('profile/profile_top_'.strtolower(User_model::$roles[$profile_user->role])); ?> 
		<? } catch (Exception $e) { ?> 
			<? $this->load->view('profile/profile_top') ?>
		<? } ?>
		<?php if ($type == 'collections') { ?>
			<div class="profile_contents_top_bar">
				<div class="profile_contents">
					<a href="" class="collections-dropdown ft-dropdown ft-dropdown-hover" rel="folders_list">
						<? if ($profile_user->role == 4) { ?>
							<span class="menuText">Collections</span><span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
						<?php } else { ?>
							<span class="menuText">STORIES</span><span class="tab_arrow"><span class="tab_arrow_contents"></span></span>
						<?php } ?>
					</a>
				</div>
				<span class="profile_top_bar_line <?=$profile_user->role == 4 ? 'winsxsw' : ''?>"></span>
				<div id="folders_list" style="display:none;">
					<span id="folders_menu_arrow"></span>
					<div class="fd-scroll">
						<ul class="collection_list_contents">
						<? foreach ($collection_dropdown as $folder_item) { ?>
							<? if (!$folder_item->can_view($this->session->userdata('id') ? $this->user->id : 0)) continue; ?>
							<li class="folder_list_item">
							  <a href="<?=$folder_item->get_folder_url()?>">
								<?=Text_Helper::character_limiter_strict(strip_tags(@$folder_item->folder_name), 40)?>
								<? if ($folder_item->private === '1') { ?>
								  <span class="private_icon"><span class="private_icon_contents"></span></span>
								<? } ?>
								<? if (isset($folder_item->folder_contributors[0]->user_id)) { ?>
								  <span class="shared_icon"><span class="shared_icon_contents"></span></span>
								<? } ?>
								<? if ($folder_item->is_open) { ?>
								  <span class="open_icon"><span></span></span>
								<? } ?>
							  </a>
							</li>
						<? } ?>
						</ul>
					</div>
				</div>
			</div>
		<? } ?>
		
		<div class="comments <?=isset($hide_main_border) ? $hide_main_border : ''?>" id="comments_mid">
			<div id="show_newsfeeds">
				<? if ($type == 'collections') { ?>
					<?=Modules::run('profile/profile_folder/'.$type, $profile_user->id)?>
				<? } elseif ($type == 'contests') { ?>
					<?=Modules::run('profile/profile_contests/'.$type, $profile_user->id)?>
				<? } elseif (in_array($type, array('drops', 'upvotes', 'mentions'))) { ?>
					<div id="contents_top_bar">
						<span class="top_bar_line"></span>
						<div id="contents_sort_by">
							<? $this->load->view('newsfeed/filter_types_menu', array('base'=>'/'.$type.'/'.$profile_user->uri_name)) ?>
						</div>
					</div>
					<div id="all_folders_feed" class="auxilary messagesContainer">
						<?=Modules::run('profile/profile_newsfeed/'.$type, $profile_user->id, $filter)?>
					</div>
				<? } elseif (in_array($type, array('followings', 'followers'))) { ?>
					<?=Modules::run('profile/profile_connection/'.$type, $profile_user->id)?>
				<? } elseif (in_array($type, array('settings'))) { ?>
					<?=Modules::run('profile/profile/edit')?>
				<? } elseif (in_array($type, array('info'))) { ?>
					<?=Modules::run('profile/profile/user_info', $profile_user->id)?>
				<? } else { ?>
					Profile sub page not found: <?=$type?>
				<? } ?>
			</div>
			<?php $this->benchmark->mark('profile_view_sub_header_newsfeeds_end')?>
		</div> <!-- End comments -->
		<?php $this->benchmark->mark('profile_view_sub_header_end')?>				
	</div> <!--  End profile_main_content -->
</div>

<script type="text/javascript">
	php.title = '<?=$profile_user->first_name?>&#39;s Favorites';
	php.profile_id =  '<?=$profile_user->id?>';
	php.isFollowingFollowerPage = '<?=@$isFollowingFollowerPage?>';
	//php.loop_tabs = <?//=json_encode($loop_tabs)?>;
	php.peopleshouldknow = <?=@json_encode($peopleshouldknow)?>;
</script>
<?=Html_helper::requireJS(array("profile/profile"))?> 
