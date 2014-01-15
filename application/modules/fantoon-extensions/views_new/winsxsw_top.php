<div id="sxsw_top">
	<a <? /* ?>href="http://venturebeat.com/"<? */ ?> target="_blank" class="vb-logo">
		<?=Html_helper::img('contestFiles/venturebeat_logo.png', array('alt'=>""))?>
	</a>
	<a href="/winsxsw" class="sxsw-title">
		<?=Html_helper::img('winsxswLogo.png', array('alt'=>""))?>
	</a>
	<?php if (isset($folder)) { ?>
		<? /* ?><a href="/winsxsw/<?=$folder->folder_uri_name?>/dashboard" class="dashboard">Live Dashboard</a><? */ ?>
	<? } else { ?>
		<a href="/winsxsw/best_overall__ends_on_march_15th_11_59_pm_pst/dashboard" class="dashboard">Best Overall Dashboard</a>
	<? } ?>
</div>
<script type="text/javascript">
	var container  = document.getElementById('container');
	if (container) {
		container.parentNode.insertBefore(document.getElementById('sxsw_top'), container);
	}
</script>