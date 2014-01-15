<?=$folder->folder_id <= 0 ? '<script type="template/html" id="manage_list_item"
	data-folder_id="a @href"
	data-folder_name=".listUnit_info"
>' : ''?>
<li class="listUnit">
	<a href="/manage_lists/<?=$folder->folder_id?>">
		<span class="listUnit_upper">
			<? if (isset($folder->recent_newsfeeds[0])) { ?>
				<? if ($folder->recent_newsfeeds[0]->img && $folder->recent_newsfeeds[0]->link_type != 'text') { ?>
					<?=Html_helper::img($folder->recent_newsfeeds[0]->img_thumb)?>
				<? } else { ?>
					<span class="textContent"><?=$folder->recent_newsfeeds[0]->text?></span>
				<? } ?>
			<? } elseif ($folder->folder_id <= 0) { ?>
				<img src="" style="display:none"/>
				<span class="textContent" style="display:none"></span>
			<? }?>					
		</span>
		<span class="listUnit_lower">
			<? for ($i=1;$i<=3;$i++) { ?>
				<? if (isset($folder->recent_newsfeeds[$i])) { ?>
					<? if ($folder->recent_newsfeeds[$i]->img && $folder->recent_newsfeeds[$i]->link_type != 'text') { ?>
						<?=Html_helper::img($folder->recent_newsfeeds[$i]->img_bigsquare)?>
					<? } else { ?>
						<span class="textContent"><?=$folder->recent_newsfeeds[$i]->text?></span>
					<? } ?>
				<? } elseif ($folder->folder_id <= 0) { ?>
					<img src="" style="display:none"/>
					<span class="textContent" style="display:none"></span>
				<? } ?>
			<? } ?>
		</span>
		<span class="listUnit_info"><?=$folder->folder_name?></span>
	</a>
</li>
<?=$folder->folder_id <= 0 ? '</script>' : ''?>