<? 
$_is_tmpl = $folder->folder_id <= 0;
if ( $_is_tmpl ) echo '<script type="template/html" id="tmpl-folder-item"
		data-is_owned = "div.js-folder @data-owner"
		data-folder_id = "div.js-folder @data-folder_id, div.folderbase @name, .collectionUpbox a @href, a.folder_menu_trigger_contents @rel, ul.folder_buttons_menu @id, div.follow_unfollow_btn a @href"
		data-folder_name = "div.js-folder @data-folder_name, div.folderbase @title, .folder_name"
		data-private = "div.js-folder @data-private"
		data-exclusive = "div.js-folder @data-exclusive"
		data-is_open = "div.js-folder @data-is_open"
		data-rss_source_id = "div.js-folder @data-rss_source_id"
		data-hashtag_id = "div.js-folder @data-hashtag_id"
		data-_folder_url = "div.js-folder @data-url"
		data-upvotes_count = ".up_count"
		data-newsfeeds_count = ".collection_dropcount"		
		data-total_hits = ".collection_viewcount"
		data-hashtag-_hashtag_url = ".collection_tags @href"
		data-hashtag-_hashtag_name = "div.js-folder @data-hashtag-hashtag_name, .collection_tags"
		data-sort_by = "div.js-folder @data-sort_by"
	>' ?>
		<div class="js-folder folder expandable_folder" 
			data-owner="<?= $folder->is_owned($this->session->userdata('id')) ? '1' : '0' ?>"
			data-url="<?=$folder->get_folder_url()?>"
			<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'private', 'exclusive', 'is_open', 'rss_source_id', 'rss_source->source', 'hashtag_id', 'hashtag->hashtag_name','sort_by'))?>
		>
			<div class="folderbase" title="<?=$folder->folder_name?>">
				<?php $is_liked = $folder->is_liked($this->session->userdata('id')) ?>
				<div class="collectionUpbox">
					<a class="share_count">
						<strong><?=$folder->share_count?></strong>
						<span>Shares</span>
					</a>
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
							<div class="collections_transfer_message">Collecting drops in Progress...</div>
						<?php } ?> 
					</div>
					
					<? //Collection counters ?>
					<div class="collection_counters">
						<span class="collection_dropcount"><span class="collection_dropcountContents"></span><?=(int) $folder->newsfeeds_count?></span>
						<span class="collection_viewcount"><span class="collection_viewcountContents"></span><?=(int) $folder->get_total_hits()?> </span>
						<?php //RR - changed clickable element because of http://dev.fantoon.com:8100/browse/FD-3123?>
						<a href="<?=$folder->hashtag_id ? $folder->hashtag->_hashtag_url : ''?>" class="collection_tags"><?=$folder->hashtag_id ? $folder->hashtag->_hashtag_name : ''?></a>
					</div>
				
				</div>
				<? //Folder items ?>
				<div class="folder_items">
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
				<div class="clear"></div>
			</div>

		<div class="clear"></div>
	</div>
</div>
<?= $_is_tmpl ? '</script>' : ''?>