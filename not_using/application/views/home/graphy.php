<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link type="text/css" href="<?=base_url()?>css/base.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=base_url()?>css/960_24_col.css" type="text/css" media="screen">
<link type="text/css" href="<?=base_url()?>css/jquery-ui-1.8.14.custom.css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/require.js"></script>
	<!--<script src="/js/require_order.js"></script>-->
	<script type="text/javascript">
		require.config({
			baseUrl: "/js/modules",
			paths: {
				"jquery": "http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min",
				"jquery-ui": "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min"
			}
		});
		var php = {
				baseUrl: '<?=base_url()?>'
				};
	</script>
	
	<?=requireJS(array(
		"jquery",
		"jquery-ui",
		"plugins/jquery.mousewheel",
		"plugins/jquery.timers",
		"plugins/canvas2image",
		"plugins/base64",
		"graph/parseur",
		"graph/jit",
		"plugins/utils"
	))?>

<script type="text/javascript">
					
		var win_width = $(window).width();
		var win_height = $(window).height();
						
		var initvar = {
				background: '#ffffff',
				nodeColor: '#000',
				captionColor: '#000',
				//captionBgColor: '#222',
				captionTagColor: '#000',
				edgeColor: '#ffffff',
				edgeType: 'line',
				nodeWidth: 40,
				nodeHeight: 40,
				fontSize: 11,			
				numberOfCircles: 0,
				//width:  900,
				//height: 600,
				width: win_width,
				height: win_height,			
			  	imagesSize: 50,
			  	zoomInSteps: 100, //or auto
			  	zoomOutSteps:100,
			  	imageDelayZoomSize:70,			  				  	
			  	ajaxFetchLevels: 1,
			  	captionTruncateLimit: 15,
			  	ajaxSubtreeMaxLength: 5,
			  	adEdgeColor: '#bcc',
			  	adEdgeWidth: '2',
				moreWidth:3,
				moreColor:'#f00',
				zoomEveryNodes:10,
				labelAddHeight:10,
				labelAddWidth:10,
				scaleMultiplier:1.01
		};
		initvar = php.initvar = objmerge(initvar, <?=json_encode($config)?>);
		var sizes = {normal:1, bigger: 1.5, smaller: 0.5, huge: 3};
		sizes = php.sizes = objmerge(sizes, <?=json_encode($sizes)?>);		
		
		var source = php.source = "<?=$tree_str?>";
		var init_postback = php.init_postback = <?=$ipb?>; 
	</script>
	<?=requireJS(array("jquery","jquery-ui","graph/graphy"))?>	
</head>
<body>

	<? //print_r($tree_str); ?>
	<!--<div class="container_24">-->
	<div>

		<? 
		echo form_open();
		echo form_close();
		?>

		<div id="infovis" style="position: relative;">

			<div id="zoomer" style="position: absolute; z-index: 20; right: 7px; margin-right: 60px; margin-top: 40px; top: 0px !important">
				<div id="plus" class="unselectable"><div class="unselectable">+</div></div>
				<div id="slider" class="slide" ></div>
				<div id="minus" class="unselectable"><div class="unselectable">-</div></div>				
			</div>
			<div id="loading-div" class="slide" >NOW LOADING</div>
		</div>		

	</div>
</body>
</html>