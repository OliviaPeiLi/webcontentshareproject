<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Loading...</title>
</head>
<body style="text-align:center; margin: 0">
	<? /* removed overflow hidden because its causing wrong popup iframe size */ ?>
	<? /* added overflow: hidden bc of http://test.fandrop.com/bookmarklet/snapshot_preview/18668694 - iframe sizing in popup is improved */ ?>
	<div id="html_wrapper" style="display:inline-block; text-align: left; overflow: hidden; position: relative">
		<?=$content?>
	</div>
	<script type="text/javascript">
		var el, temp = false;
		if (document.getElementsByTagName('object').length == 1) {
			el = document.getElementsByTagName('object')[0];
			temp = el.style.border;
			el.style.setProperty('border', '1px solid #F0F0F0', '!important');
			document.getElementById('html_wrapper').style.setProperty('margin', '0', '!important');
		} else if (document.getElementsByTagName('embed').length == 1) {
			el = document.getElementsByTagName('embed')[0];
			temp = el.style.border;
			el.style.setProperty('border', '1px solid #F0F0F0', '!important');
			document.getElementById('html_wrapper').style.setProperty('margin', '0', '!important');
		}
		
		window.onload = function() {
			if (document.getElementsByTagName('object').length == 1) {
				window.setTimeout(function() {
					document.title = '(FT-LOADED)';
					window.setTimeout(function() {
						el.style.setProperty('border', temp ? temp : 'none', '!important');
					},2000);
				}, 15*1000);
			} else if (document.getElementsByTagName('embed').length == 1) {
				window.setTimeout(function() {
					document.title = '(FT-LOADED)';
					window.setTimeout(function() {
						el.style.setProperty('border', temp ? temp : 'none', '!important');
					},2000);
				}, el.getAttribute('src').indexOf('.pdf') == -1 ? 15*1000 : 120 * 1000);
			} else {
				document.title = '(FT-LOADED)';
			}
		};
		<?=Scraper::communication_js()?>
	</script>
</body>
</html> 