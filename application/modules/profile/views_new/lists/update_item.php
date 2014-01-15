<?=$newsfeed->newsfeed_id <= 0 ? '<script type="template/html" id="manage_post_item" 
	data-newsfeed_id = ".itemDelete @href, .itemUnit @id, .setAsCover @href"
	data-link_url = ".itemUnit @data-link_url"
	data-_img_thumb = ".itemLink img @src"
	data-description = ".itemDescription .itemDescription_text"
>' : ''?>
<li class="itemUnit" id="newsfeed_id_<?=$newsfeed->newsfeed_id?>" data-link_url="<?=$newsfeed->link_url?>">
	<a class="itemLink" href="">
		<?php if ($newsfeed->newsfeed_id <= 0 ) { ?>
			<span class="textContent" style="display:none"></span>
			<img src="" style="display:none"/>
		<?php } elseif ($newsfeed->link_type == 'text') { ?>
			<span class="textContent"><?=$newsfeed->activity->content?></span>
		<?php } else { ?>
			<?=Html_helper::img($newsfeed->img_thumb)?>
		<?php } ?>
		<span class="ico <?=$newsfeed->link_type?>"></span>
		<span class="itemDescription"><span class="itemDescription_text"><?=strip_tags($newsfeed->description)?></span></span>
	</a>
	<a href="/del_link/<?=$newsfeed->newsfeed_id?>" class="itemDelete custom-title" title="Delete post" title-pos="right" data-confirm="Delete <?=strip_tags($newsfeed->description)?>?"><span class="ico"></span></a>
	<a href="" class="itemEdit custom-title" title="Edit post details" title-pos="right"><span class="ico"></span></a>
	<a href="/set_as_cover/<?=$newsfeed->newsfeed_id?>" rel="ajaxButton" class="setAsCover custom-title <?=$cover_newsfeed_id == $newsfeed->newsfeed_id ? 'covered js-disabled' : '';?>" title="<?=$cover_newsfeed_id == $newsfeed->newsfeed_id ? 'Set as list cover image' : 'Set as list cover image';?>" title-pos="right"><span class="ico"></span></a>
</li>
<?=$newsfeed->newsfeed_id <= 0 ? '</script>' : ''?>