<html>
<head>
	<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="screen" />
 	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://fantoon-dev:8888/js/custom.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://fantoon-dev:8888/js/external.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
		<div id="test_loader" style="height: 200px; width: 200px;">
			<button id="enable">Enable</button>
		</div>

<script type="text/javascript">
$(function() {
	$('#enable').click(function() {
		show_loader($('#test_loader'),100);
	}
});
</script>

</body>
</html>
