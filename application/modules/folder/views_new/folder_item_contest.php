<?  $_is_tmpl = $folder->folder_id <= 0 ?>
<?= $_is_tmpl ? '<script type="template/html" id="tmpl-folder-item"
		data-folder_id = "div.js-folder @data-folder_id, div.folderbase @name, .collectionUpbox a @href, a.folder_menu_trigger_contents @rel, ul.folder_buttons_menu @id, div.follow_unfollow_btn a @href"
		data-folder_name = "div.js-folder @data-folder_name, div.folderbase @title, .folder_name"
		data-_folder_url = "div.js-folder @data-url"
		data-share_count = ".share_count strong"
		data-newsfeeds_count = ".collection_total_drops"		
		data-total_hits = ".collection_total_hits"
		data-ends_at = "div.js-folder @data-ends_at"
		data-info = "div.js-folder @data-info"
		data-is_open = "div.js-folder @data-is_open"
	>' : '' ?>
		<div class="js-folder folder expandable_folder" 
			data-url="<?=$folder->folder_url?>"
			<?php if ($_is_tmpl || $folder->can_edit($this->session->userdata('id'))) { ?>
				<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'ends_at', 'info', 'is_open'))?>
			<?php } ?>
		>
			<div class="folderbase" title="<?=$folder->folder_name?>">
				<div class="collectionUpbox">
					<a class="share_count">
						<strong><?=in_array($contest->url, array('fndemo','crowdfunderio','cite')) ? $folder->points : (Int) $folder->share_count?></strong>
						<span><?=in_array($contest->url, array('fndemo','crowdfunderio','cite')) ? 'Points' : 'Shares' ?></span>
					</a>
				</div>
				<? //Top part of each collection ?>
				<div class="folder_info">
					<? //Folder Title ?>
					<div class="folder_title">
						<span class="folder_name"><?=Text_Helper::character_limiter_strict(@$folder->folder_name, 72)?></span>
					</div>
					<? //Collection counters ?>
					<div class="collection_counters">
						<span class="collection_dropcount"><span class="collection_dropcountContents"></span><span class="collection_total_drops">
							<?=(int) $folder->newsfeeds_count?>
						</span></span>
						<span class="collection_viewcount"><span class="collection_viewcountContents"></span><span class="collection_total_hits">
							<?=(int) $folder->total_hits + (in_array($contest->url, array('fndemo','crowdfunderio','cite')) ? $folder->ga_views : 0)?>
						</span></span>
					</div>
				</div>
				<? //Folder items ?>
				<a href="<?=$folder->folder_url?>" class="folder_items">
					<?  foreach ($folder->recent_newsfeeds as $key => $item) {  if ($key >= 3) break;?>
						<span class="img_wrapper folder_item <?=$item->link_type == 'embed' ? 'collection_play_button':''?>">
							<? if ($item->link_type == 'text') { ?>
								<span class="bookmarked_text"><?=$item->content?></span>
							<?php } else { ?>
								<?=Html_helper::img($item->img_bigsquare, array(
									'alt'=>$item->description_plain,
									'title'=>$item->description_plain,
									'onerror'=>"if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')"
								))?>
								<?php if ($item->link_type == 'embed') {?>
									<span class="play_button"></span>
								<?php } ?>
							<? } ?>
						</span>
					<? }  ?>
					<? for ($i=@$key ;$i < 3; $i++ ) { ?>
						<span class="img_wrapper folder_item">
							<? if ( $_is_tmpl ) { ?>
								<span class="bookmarked_text"></span>
								<img src="" alt="" title="" onerror="if (this.src.indexOf('_bigsquare') > -1) this.src = this.src.replace('_bigsquare','_tile')" />
								<span class="play_button"></span>
							<?php } ?>
						</span>
					<? } ?>
					<span class="clear"></span>
				</a>
				<?php if ($folder->can_edit($this->session->userdata('id')) || $_is_tmpl) { ?>
					<div class="folder_menu_trigger">
						<a class="folder_menu_trigger_contents menu_caller ft-dropdown" rel="folder_buttons_menu_<?=$folder->folder_id?>"></a>
						<ul id="folder_buttons_menu_<?=$folder->folder_id?>" class="folder_buttons_menu folder_buttons" style="display:none">
							<li><span class="folder_menu_arrow"></span></li>
							<li class="folder_menu_option">
								<a href="#edit_folder_popup" rel="popup" data-title="<?=$this->lang->line('folder_edit_collection_title');?>" class="folder_edit edit_button folder_edit_btn" title="<?=$this->lang->line('folder_edit_collection_btn');?>"><span class="edit_contents_light_wrapper"><span class="edit_contents_light"></span></span><?=$this->lang->line('folder_edit_collection_btn');?></a>
							</li>
							<li class="folder_menu_option">
								<a href="#delete_folder" rel="popup" data-hidetitlebar="true" class="folder_delete js_folder_list" title="<?=$this->lang->line('folder_delete_collection_btn');?>"><?=$this->lang->line('folder_delete_collection_btn');?></a>
							</li>
						</ul>
					</div>
				<?php } ?>

				<div class="clear"></div>
			</div>
		</div>
<?= $_is_tmpl ? '</script>' : ''?>