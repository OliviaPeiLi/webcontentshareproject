<div id="sxsw_top" class="generiContest" <?=$contest->url=='cite' ? 'style="background:black"' : ''?>>
	<div class="titleContainer">
		<?php if ($contest->url == 'cite') { ?>
			<a href="/cite" style="padding:0; display:block; height: 200px">
				<?=Html_helper::img('contestFiles/cite.png', array('alt'=>""))?>
			</a>
		<?php } else { ?>
			<a href="<?=$contest->url=='fndemo' ? 'http://www.foundersnetwork.com/fndemo.php' : '/'.$contest->url?>" <?=$contest->url=='fndemo' ? 'target="_blank"' : '' ?> class="sxsw-title">
				<?=Html_helper::img($contest->logo_thumb, array('alt'=>""))?>
			</a>
		<?php } ?>
		<?php if ($contest->url =='fndemo') { ?>
			<a href="http://www.citeconference.com/ehome/index.php?eventid=50397&" target="_blank" class="sxsw-title">
				<?=Html_helper::img('contestFiles/fndemo.png', array('alt'=>""))?>
			</a>
		<?php } ?>
		<?php if (isset($folder) && !(!isset($newsfeed) && $contest->url == 'demo') && $contest->url != 'fndemo' && $contest->url != 'cite') { ?>
			<a href="/<?=$contest->url?><?=$contest->is_simple ? '' : '/'.$folder->folder_uri_name?>/dashboard" class="dashboard">Live Dashboard</a>
		<? } else { ?>
			<?php /*<a href="//dashboard" class="dashboard">Best Overall Dashboard</a>*/ ?>
		<? } ?>
	</div>
	<?php if ($contest->url == 'demo') { ?>
	<div id="contest_tabs">
		<div class="contest_tabs_container">
			<a href="#" class="contest_tab active">Home</a>
			<a href="#contest-prizes" class="contest_tab">How It Works</a>
			<a href="#contest-sponsors" class="contest_tab">Press</a>
			<a href="/<?=$contest->url?><?=$contest->is_simple || !isset($folder) ? '' : '/'.$folder->folder_uri_name?>/dashboard" class="contest_tab">Live Dashboard</a>
		</div>
	</div>
	<?php } ?>
</div>

<script type="text/javascript">
	if (window != top) {
		document.querySelector('#sxsw_top .titleContainer').style.display = 'none';
	}
	var container  = document.getElementById('container');
	if (container) {
		container.parentNode.insertBefore(document.getElementById('sxsw_top'), container);
	}
</script>