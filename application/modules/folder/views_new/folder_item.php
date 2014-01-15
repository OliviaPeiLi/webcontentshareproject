<? 
$_is_tmpl = $folder->folder_id <= 0;
if ( $_is_tmpl ) echo '<script type="template/html" id="tmpl-folder-item"
		data-folder_id = "div.js-folder @data-folder_id, div.folderbase @name, .collectionUpbox a @href, a.folder_menu_trigger_contents @rel, ul.folder_buttons_menu @id, div.follow_unfollow_btn a @href, .fb_share_collection @data-folder_id, .share_email @data-folder_id"
		data-folder_name = "div.js-folder @data-folder_name, div.folderbase @title, .folder_name, .share_twt_app @data-text"
		data-private = "div.js-folder @data-private"
		data-_collaborators_json = "div.js-folder @data-collaborators_json"
		data-exclusive = "div.js-folder @data-exclusive"
		data-is_open = "div.js-folder @data-is_open"
		data-rss_source_id = "div.js-folder @data-rss_source_id"
		data-hashtag_id = "div.js-folder @data-hashtag_id"
		data-_folder_url = "div.js-folder @data-url, .share_twt_app @data-url, .js_folder_uri @href"
		data-upvotes_count = ".up_count"
		data-newsfeeds_count = ".collection_total_drops"		
		data-total_hits = ".collection_total_hits"
		data-hashtag-_hashtag_url = ".collection_tags @href"
		data-hashtag-_hashtag_name = "div.js-folder @data-hashtag-hashtag_name, .collection_tags"
		data-sort_by = "div.js-folder @data-sort_by"
		data-pinterest_url = ".pin-it-button @href"
		data-total_upvotes = ".up_count"
	>' ?>
	
		<div class="js-folder folder expandable_folder" 
			data-url="<?=$folder->get_folder_url()?>"
			<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'private', 'exclusive', 'is_open', 'rss_source_id', 'rss_source->source', 'hashtag_id', 'hashtag->hashtag_name','sort_by', 'collaborators_json'))?>
		>
			<? $first_item = isset($first_item) ? '' : 'help="'.$this->lang->line('folder_collections_help').'" pos_my="left top" pos_at="right bottom"'; ?>
			<div class="folderbase" <?=$first_item?> title="<?=$folder->folder_name?>">
				<?php $is_liked = $folder->is_liked($this->session->userdata('id')) ?>
				<div class="collectionUpbox">
					<a href="/add_like/folder/<?=$folder->folder_id?>" class="up_button" rel="ajaxButton" style="<?=$is_liked ? 'display:none' : ''?>">
						<span class="upvote_wrapper">
							<span class="upvote_contents"></span>
						</span>
					</a>
					<a href="/rm_like/folder/<?=$folder->folder_id?>" class="undo_up_button" rel="ajaxButton" style="<?=$is_liked ? '' : 'display:none'?>">
						<span class="downvote_wrapper">
							<span class="upvote_contents"></span>
						</span>
					</a>
					<div class="up_count"><?=$folder->get_total_upvotes()?></div>
				</div>
				<? //Top part of each collection ?>
				<div class="folder_info">
					<? //Folder Title ?>
					<div class="folder_title">
						<span class="folder_name"><?=Text_Helper::character_limiter_strict(@$folder->folder_name, 72)?></span>
						<span class="private_icon" style="<?=$folder->private == '0' || $folder->private == '' ? 'display:none': '' ?>">&nbsp;</span>
						<span class="shared_icon" style="<?=isset( $folder->folder_contributors[0]->user_id ) ? '' : 'display:none' ?>">&nbsp;</span>
						<span class="open_icon" style="<?=$folder->is_open ? '' : 'display:none' ?>"></span>
						<?php if ($_is_tmpl || $folder->is_in_progress()) { ?>
							<span class="collections_transfer_message">Collecting drops in Progress...</span>
						<?php } ?> 		
						<?php if (isset($folder->user->id)) : ?>
							<div class="folder_userName js-userdata">
								by <a href="<?=$folder->user->url?>" class="js_folder_user_name"><?=$folder->user->full_name?></a>
							</div>
						<?php else: ?>
							<div class="folder_userName js-userdata" style="display: none">
								by <a href="" class="js_folder_user_name"></a>
							</div>
						<?php endif;?>
					</div>
					<? //Collection counters ?>
					<div class="collection_counters">
						<span class="collection_dropcount"><span class="collection_dropcountContents"></span><span class="collection_total_drops"><?= Text_Helper::restyle_text((int) $folder->newsfeeds_count)?></span></span>
						<span class="collection_viewcount"><span class="collection_viewcountContents"></span><span class="collection_total_hits"><?= Text_Helper::restyle_text((int) $folder->get_total_hits())?></span></span>
						<?php // RR - changed clickable element because of http://dev.fantoon.com:8100/browse/FD-3123?>
						<a href="<?=$folder->hashtag_id ? $folder->hashtag->_hashtag_url : ''?>" class="collection_tags"><?=$folder->hashtag_id ? $folder->hashtag->_hashtag_name : ''?></a>
					</div>
					<span class="collectionsTweets">
						<?=Html_helper::twitter_btn($folder)?>
						<?=Html_helper::fb_share_btn($folder)?>
						<?//=Html_helper::pinterest_btn($folder)?>						
						<?php if ($this->is_mod_enabled('email_share') && $this->user) { ?>
							<?php $is_disabled = count($folder->recent_newsfeeds) == 0 ? true : false;?>
							<a href="#share_email_form_wrap" class="share_email <?=$is_disabled ? "disabled inactive" : "";?>" rel="popup" title="Email This Drop" data-type="folder" data-folder_id="<?=$folder->folder_id?>">&nbsp;</a>
						<?php } ?>
					</span>
				</div>
				<? //Folder items ?>
				<a href="<?=$folder->get_folder_url()?>" class="folder_items js_folder_uri">
					<? foreach ($folder->recent_newsfeeds as $key => $item) {  if ($key >= 3) break;?>
							<span class="img_wrapper folder_item <?=@$item->link_type == 'embed' ? 'collection_play_button':''?>">
							<? if ($item->link_type == 'text') { ?>
								<span class="bookmarked_text">
									<?=$item->text?>
								</span>
							<?php } else { //photo and other link types ?>
								<? //RR - onerror is temp attribute until the thums are updated?>
								<?=Html_helper::img($item->img_bigsquare, array(
									'alt'=>strip_tags($item->description_plain),
									'title'=>strip_tags($item->description_plain),
									'onerror'=>"if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')",
									'data-newsfeed_id'=>$item->newsfeed_id
								))?>
							<? } ?>
							<?php if (@$item->link_type == 'embed') {?>
								<span class="play_button"></span>
							<?php } ?>
						</span>
					<? }  ?>
					<? for ($i=@$key ;$i < 3; $i++ ) { ?>
						<span class="img_wrapper folder_item">
							<? if ( $_is_tmpl ) { ?>
								<span class="bookmarked_text"></span>
								<img src="" alt="" title="" onerror="if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')" />
								<span class="play_button"></span>
							<? } ?>
						</span>
					<? } ?>
					<span class="clear"></span>
				</a>
				<? //Folder buttons ?>
				<? // http://dev.fantoon.com:8100/browse/FD-3056 ?>
				<? if ( $folder->is_owned($this->session->userdata('id')) || $folder->is_followed($this->session->userdata('id')) || $_is_tmpl ) { ?>
					<div class="folder_menu_trigger">
						<a class="folder_menu_trigger_contents menu_caller ft-dropdown" rel="folder_buttons_menu_<?=$folder->folder_id?>"></a>
						<ul id="folder_buttons_menu_<?=$folder->folder_id?>" class="folder_buttons_menu folder_buttons" style="display:none">
							<li><span class="folder_menu_arrow"></span></li>
							<? //Embed Collection ?>
							<li class="folder_menu_option">
								<a href="#embed_collection_overview" rel="popup" title="<?=$this->lang->line('folder_embed_folder_popup_title');?>" class="folder_embed">
									<?=$this->lang->line('folder_embed_folder_popup_btn');?>
								</a>
							</li>
							<? //+Add Collection ?>
							<? /*BP: causing problems, don't want to waste time to debug right now since it is disabled anyway
							if ($this->is_mod_enabled('folders_contributors') && ( $folder->is_open || $_is_tmpl ) ) {?>
								<li class="folder_menu_option">
									<a href="#drop_into_folder_popup" rel="popup" class="add_button" data-title="+Add">+Add</a>
								</li>
							<? }*/ ?>
							
							<? //if ($profile_id == $userdata_id) { ?>
							<? if ( ( $folder->can_edit($this->session->userdata('id')) ) || $_is_tmpl ) { ?>
								<? //Edit Collection ?>
								<li class="folder_menu_option">
									<a href="#edit_folder_popup" rel="popup" data-title="<?=$this->lang->line('folder_edit_collection_title');?>" class="folder_edit edit_button folder_edit_btn" title="<?=$this->lang->line('folder_edit_collection_btn');?>"><span class="edit_contents_light_wrapper"><span class="edit_contents_light"></span></span><?=$this->lang->line('folder_edit_collection_btn');?></a>
								</li>
								<? //Delete Collection ?>
								<li class="folder_menu_option">
									<a href="#delete_folder" rel="popup" data-hidetitlebar="true" class="folder_delete js_folder_list" title="<?=$this->lang->line('folder_delete_collection_btn');?>"><?=$this->lang->line('folder_delete_collection_btn');?></a>
								</li>
							<? } ?>
						
						</ul>
					</div>
				<? } ?>
				<? //Follow/Unfollow button for a collection ?>
				<? if ( ! $folder->is_owned($this->session->userdata('id')) || $_is_tmpl ) { ?>
					<div class="follow_unfollow_btn">
						<?php if ($this->session->userdata('id')) : ?>
							<? $is_followed = $folder->is_followed($this->session->userdata('id')); ?>
							<a href="/unfollow_folder/<?=$folder->folder_id?>" rel="ajaxButton" data-type="html" class="folder_unfollow unfollow_button" style="<?=$is_followed ? '' : 'display:none'?>"><?=$this->lang->line('folder_following_btn');?></a>
							<a href="/follow_folder/<?=$folder->folder_id?>" rel="ajaxButton" data-type="html" class="folder_follow lightBlue_bg" style="<?=$is_followed ? 'display:none' : ''?>"><?=$this->lang->line('folder_follow_btn');?></a>
						<?php else : ?>
							<a href="/signup?redirect_url=<?=$folder->get_folder_url();?>" rel="ajaxButton" data-type="html" class="folder_follow lightBlue_bg" style="<?=$is_followed ? 'display:none' : ''?>"><?=$this->lang->line('folder_follow_btn');?></a>
						<?php endif; ?>
					</div>
				<? } ?>
				<div class="clear"></div>
			</div>
		</div>
<?= $_is_tmpl ? '</script>' : ''?>