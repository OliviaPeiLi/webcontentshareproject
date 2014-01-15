<? $this->load->config('folder/config.php'); ?>
<? $this->lang->load('folder/folder', LANGUAGE); ?>
<? //User info not needed for private messaging ?>
<div id="folder_top" class="top_container">
	<?php $is_liked = $folder->is_liked($this->session->userdata('id'))?>
	<div class="small-margin-bottom">
		<div class="action_box inlinediv">
			<div class="upbox inlinediv">
				<a href="/add_like/folder/<?=$folder->folder_id?>" class="up_button" rel="ajaxButton" style="<?=$is_liked ? 'display:none' : ''?>">
					<div class="upvote_wrapper">
						<span class="upvote_contents"></span>
					</div>
				</a>
				<a href="/rm_like/folder/<?=$folder->folder_id?>" class="undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display:none'?>">
					<div class="downvote_wrapper">
						<span class="upvote_contents"></span>
					</div>
				</a>
				<div class="up_count"><?=$folder->total_upvotes?></div>
			</div>
		</div>
		<div class="top_row inlinediv">
			<a id="folder_title" href="<?=$folder->user->url?>">
				<h2 class="inlinediv" title="<?=$folder->folder_name?>"><?=Text_helper::character_limiter($folder->folder_name, $this->config->item('folder_name_chars_limit'))?></h2>
			</a>
			<div class="collections-dropdownContainer">
				<div class="collections-dropdown ft-dropdown ft-dropdown-hover" rel="folders_list">
					<span class="menuText"><?=$this->lang->line('folder_collections_menu_title');?></span>
					<span class="menuButton"><span class="menuButton_contents"></span></span>
					<div id="folders_list" style="display:none;">
						<span id="folders_menu_arrow"></span>
						<div class="fd-scroll">
							<ul>
							<? foreach ($collection_dropdown as $folder_item) { ?>
							  <? if ($folder_item->can_view($this->session->userdata('id') ? $this->user->id : 0)) { ?>
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
							<? } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="main-info">
				<div class="row owner-info">
					<span>by</span>
					<a class="user" href="<?=$folder->user->url?>"><?=$folder->user->full_name?></a>
					<? if($folder->user->id != $this->session->userdata('id') && !$this->session->userdata('page_id')){ ?>
						<strong id="follow_button_div_<?=$folder->user->id?>">
						<?php if ($this->session->userdata('id')) : ?>
							<button class="button unfollow_button request_unfollow" rel="ajaxButton" data-url="/unfollow_folder/<?=$folder->folder_id?>" style="<?=$folder->is_followed($this->session->userdata('id')) ? '' : 'display:none'?>"><?=$this->lang->line('folder_following_lbl');?></button>
							<button class="button blue_bg request_follow" rel="ajaxButton" data-url="/follow_folder/<?=$folder->folder_id?>" style="<?=$folder->is_followed($this->session->userdata('id')) ? 'display:none' : ''?>"><?=$this->lang->line('folder_follow_btn');?></button>
						<?php else : ?>
							<a href="/signup?redirect_url=<?=Html_helper::base_url($folder->_folder_url);?>" class="button blue_bg request_follow"><?=$this->lang->line('folder_follow_btn');?></a>
						<?php endif; ?>
						</strong>
					<? } ?>
					<? if ($folder->can_edit(@$this->user->id)) { ?>
						<a href="#edit_folder_popup" rel="popup" title="Edit" data-title="<?=$this->lang->line('folder_edit_link_title');?>" 
							class="edit_folder_btn edit_button folder_edit_btn standalone_btn"
							<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'hashtag_id', 'hashtag->hashtag_name','private','exclusive','is_open', 'rss_source_id', 'rss_source->source', 'sort_by', 'collaborators_json'))?>
							data-edittype="collection"
						>
							<?=$this->lang->line('folder_edit_link_btn');?>
						</a>
						<a href="#delete_folder" rel="popup" class="del_folder_btn del_button folder_del_btn standalone_btn" data-folder_id="<?=$folder->folder_id?>">
							<?=$this->lang->line('folder_delete_collection_btn');?>
						</a>						
						<?=$this->load->view('folder/create_collection_popup',array('button'=>$this->lang->line('folder_save_collection_btn')),true)?>
					<? } ?>
					<?php if ($this->user && $this->user->role == 2 && $this->is_mod_enabled('landing_ugc')) { ?>
						<a href="/rem_landing_folder/<?=$folder->folder_id?>" rel="ajaxButton" class="rem_landing_folder" style="<?=$folder->is_landing ? '' : 'display:none'?>">Remove from Landing</a>
						<a href="/set_landing_folder/<?=$folder->folder_id?>" rel="ajaxButton" class="set_landing_folder" style="<?=$folder->is_landing ? 'display:none' : ''?>">Add to Landing</a>
					<?php } ?>
					<? if($folder->user->role == '1'){ ?>
						<div class="roleTitle"><?=$this->lang->line('staff_writer');?></div>
					<? } elseif($folder->user->role == '3'){ ?>
						<div class="roleTitle"><?=$this->lang->line('featured_user');?></div>
					<? } ?>
				</div>
				
				<? /* ?><div class="row">
					<a href="javascript:history.back();" class="btn-grey back-btn"><span></span>Back</a>
				</div> <? */ ?>
			</div>
		</div>
	</div>
	<div>
		<div class="col">
			<div class="row">
				<div class="profile_info_stats">
					<a href="javascript:;" class="num_views"><span><strong><?=$folder->get_total_hits()?></strong><small><?=$this->lang->line('folder_view'.($folder->get_total_hits() > 1?'s':'').'_lbl')?></small></span></a>
					<a href="javascript:;" class="num_drops"><span><strong><?=$folder->newsfeeds_count?></strong><small><?=$this->lang->line('folder_drop'.($folder->newsfeeds_count > 1?'s':'').'_lbl')?></small></span></a>
				</div>
			</div>
		</div>
		<div class="col">
			<h3>
				Hashtag
				<? if (@$folder->can_edit(@$this->user->id)) { ?>
					<div class="topic_add inlinediv">
						<a class="topic_add_btn standalone_btn" rel="popup" href="#edit_folder_popup" data-edittype="hashtags" data-title="<?=$this->lang->line('folder_show_more_topics');?>"
							<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'hashtag_id', 'hashtag->hashtag_name','exclusive','is_open','private','user_id', 'collaborators_json'))?>
						>
							<span class="topic_showmore" style="display:none;"></span>
						</a>
						<a class="topic_add_btn standalone_btn" rel="popup" href="#edit_folder_popup" data-edittype="hashtags" data-title="Change hashtag"
							<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'hashtag_id', 'hashtag->hashtag_name','exclusive','is_open','private','user_id', 'collaborators_json'))?>
						>
							<span class="item_label"><?= ($folder->hashtag_id > 0) ? "Change" : "+Add"?></span>
						</a>
					</div>
				<? } ?>
			</h3>
			<script type="template/html" id="js-hashtag" data-_hashtag_url="a @href" data-_hashtag_name="span">
				<li class="js-hashtag topic item">
					<a class="item_link" href="">
						<span class="item_label"></span>
					</a>
				</li>
			</script>
			<ul id="collection_topic_list">
				<? if($folder->hashtag_id > 0){ ?>
					<li class="js-hashtag topic item" rel="<?=@$folder->hashtag_id?>" style="">
						<a class="item_link" href="<?=@$folder->hashtag->hashtag_url;?>">
							<span class="item_label"><?=@$folder->hashtag->hashtag_name?></span>
						</a>
					</li>
				<? } elseif($this->session->userdata('id') && ($folder->user_id == $this->session->userdata('id') || in_array($this->user->role, array('1','2')))) { ?>
					<? // Quang: if changing this content, please also update edit_folder_popup ?>
					<? // search #collection_topic_list for location ?>
					<li class="js-hashtag topic item warning" style="background-color: rgb(255, 200, 0)">
						<a class="item_link" href="#">
							<span class="item_label" id="select_hashtag">Select a hashtag</span>
						</a>
					</li>
				<? }?>
			</ul>
		</div>
		<div class="col">
			<h3>
				Collaborators
				<?  if ($folder->user_id == $this->session->userdata('id')) { ?>
					<div class="collaborator_add inlinediv">
						<a class="collaborator_add_btn standalone_btn" rel="popup" href="#edit_folder_popup" data-edittype="collaborators" data-title="<?=$this->lang->line('folder_edit_link_title_collab');?>"
							<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'hashtag_id', 'hashtag->hashtag_name','exclusive','is_open','private','user_id', 'collaborators_json'))?>
						>
							<span class="item_label">+Add</span>
						</a>
					</div>
				<? }  ?>
			</h3>
			<script type="template/html" id="js-collaborator" data-url="a @href" data-img="img @src" data-name="img @title">
				<li class="collaborator item">
					<a href="" class="item_link">
						<img src="" width="30" height="30" title="" />
					</a>
				</li>
			</script>
			<ul id="collection_collaborator_list">
				<? foreach($folder->folder_contributors as $user) { ?>
					<li class="collaborator item" rel="<?=@$user->user->id?>" style="">
						<a href="<?=@$user->user->url?>" class="item_link">
							<?=Html_helper::img(@$user->user->avatar_42, array('width'=>"30", 'height'=>"30", 'title'=>@$user->user->full_name))?>
						</a>
					</li>
				<? } ?>
			</ul>
		</div>
		<div class="right">
			<div class="right_col">
				<? if (!$folder->private) { ?>
					<div class="shareText">Share Collection on: </div>
					<?=Html_helper::anchor('', ' ', array('class'=>'share_twt_app', 'data-text' => $folder->folder_name, 'data-url'=>$folder->get_folder_url()))?>
					<a href="" id="share_fb_app_coll" data-folder_id="<?=$folder->folder_id?>" class="fb_share_collection <?=$folder->is_shared(@$this->user->id) || count($folder->recent_newsfeeds) == 0 ? 'disabled_bg' : ''?>"><span class="share_fb_app_span"></span></a>
					<?//=Html_helper::pinterest_btn($folder)?>
					<?php if ($this->is_mod_enabled('email_share') && $this->user) : ?>
						<a href="#share_email_form_wrap" class="share_email" rel="popup" title="Email This Drop" data-type="folder" data-folder_id="<?=$folder->folder_id?>">&nbsp;</a>
					<?php endif;?>
					<a href="#embed_collection_overview" rel="popup" id="share_embed" class="btn-grey" title="Embed List on Your Website" data-folder_id="<?=$folder->folder_id?>">
						<span id="share_embed_contents"></span>
					</a>
				<? } ?>
			</div>
			<? /* ?>
			<div class="right_col">
				<div class="profile_info_stats">
					<a href="javascript:;" class="num_views"><span><strong><?=$folder->get_total_hits()?></strong><small><?=$this->lang->line('folder_view'.($folder->get_total_hits() > 1?'s':'').'_lbl')?></small></span></a>
					<a href="javascript:;" class="num_drops"><span><strong><?=$folder->drops?></strong><small><?=$this->lang->line('folder_drop'.($folder->drops > 1?'s':'').'_lbl')?></small></span></a>
				</div>
			</div>
			<? */ ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<? $this->load->view('folder/folder_embed_popup'); ?>
