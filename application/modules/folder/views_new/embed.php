<? $this->lang->load('folder/folder', LANGUAGE); ?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:addthis="http://www.addthis.com/help/api-spec" lang="en">
<head>
	<link rel="icon" type="image/png" href="/images/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<title><?= $title ?></title>
	
	<link rel="stylesheet" href="/css/base.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/960fluid.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/NEW/header_lean.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/NEW/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/NEW/common.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/NEW/external.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/NEW/folder/folder.css" type="text/css" medaia="screen" />

</head>

<body>
		<div class="js-folder folder expandable_folder" rel="<?=$folder->folder_id?>" data-folder_name="<?=$folder->folder_name?>" data-disp_name="<?=@$folder->folder_name?>" data-url="<?=$folder->get_folder_url()?>" data-uri_name="<?=Url_helper::url_title(@$folder->folder_name)?>" data-private="<?=$folder->private?>" data-exclusive="<?=$folder->exclusive?>" data-is_open="<?=$folder->is_open?>">
			<?
				$first_item = isset($first_item) ? '' : 'help="'.$this->lang->line('folder_collections_help').'" pos_my="left top" pos_at="right bottom"';
			?>
			<div name="<?=$folder->folder_id?>" class="folderbase" <?=$first_item?> title="<?=$folder->folder_name?>">
				
				<? //top part of each collection ?>
				<div class="folder_info">
					<? //Folder Title ?>
					<div class="folder_title">
						<a href="<?=$folder->get_folder_url()?>" target="_blank">
							<span class="folder_name"><?=Text_Helper::character_limiter_strict(@$folder->folder_name, 24)?></span>
						</a>
						<span class="private_icon" style="<? if($folder->private === '0' || $folder->private === '') { echo 'display:none;'; }?>">&nbsp;</span>
						<? if(isset($folder->folder_contributors[0]->user_id)){echo '<span class="shared_icon">&nbsp;</span>';} ?>
						<? if ($folder->is_open) { ?>
							<span class="open_icon" style=""><span></span></span>
						<? } ?>
						<div class="folder_userName js-userdata">
							by <a href="<?=Html_helper::base_url($folder->user->_url);?>" class="js_folder_user_name"><?=$folder->user->first_name . " " . $folder->user->last_name;?></a>
						</div>
					</div>
					
					<? //Collection counters ?>
					<div class="collection_counters">
						<? if (isset($folder)) { ?>
							<span class="collection_dropcount"><span class="collection_dropcountContents"></span><?=$folder->newsfeeds_count?></span>
						<? } else { ?>
							<div class="collection_dropcount"><span class="collection_dropcountContents"></span>0</div>
						<? } ?>
						<? if (isset($folder)) { ?>
							<span class="collection_viewcount"><span class="collection_viewcountContents"></span><?=$folder->get_total_hits()?> </span>
						<? } else { ?>
							<span class="collection_viewcount"><span class="collection_viewcountContents"></span>0</span>
						<? } ?>
						<span class="collection_tags"><?=$folder->hashtag? $folder->hashtag->_hashtag_name : ""; ?></span>
					</div>
				
				</div>
				<? //Folder items ?>
				<a href="<?=$folder->get_folder_url()?>" class="folder_items">
					<? foreach ($folder->recent_newsfeeds as $key => $item) { if ($key >= 3) break; ?>
						<span class="img_wrapper folder_item <?=@$item->link_type == 'embed' ? 'collection_play_button':''?>">
						<? if ($item->link_type == 'text') { ?>
							<span class="bookmarked_text">
								<?=$item->content?>
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
					<? } ?>
					<? for ($i=$key ;$i < 3; $i++ ) { ?>
						<span class="img_wrapper folder_item"></span>
					<? } ?>
					<span class="clear"></span>
				</a>

				<div class="clear"></div>
			</div>
		</div>
</body>
</html>
