<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link type="text/css" href="<?=base_url()?>css/base.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=base_url()?>css/960_24_col.css" type="text/css" media="screen">
<link type="text/css" href="<?=base_url()?>css/jquery-ui-1.8.14.custom.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/parseur.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/utils.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jit.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.timers.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/canvas2image.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/base64.js"></script> 

<script type="text/javascript">
	//alert(<?=json_encode($tree_str)?>);
	
	var initvar = {
			background: '#FFEEFF', 
			nodeColor: '#999', 
			captionColor: '#FFF', 
			captionTagColor: '#999',
			edgeColor: '#0000FF',
          edgeType: 'line', //'line', 'arrow'
			dim:'10',
			fontSize: '10',
			treeLength: 1,
			numberOfCircles: 4,
	          width:  700,
	          height: 600,
	          imagesEnabled: 1,
          imagesSize: 25
	};
	initvar = php.initvar = objmerge(initvar, <?=json_encode($config)?>);
	
	var sizes = {normal:1, bigger: 1.5, smaller: 0.5, huge: 3};
	sizes = php.sizes = objmerge(sizes, <?=json_encode($sizes)?>);
	
	var source = php.source = "<?=$tree_str?>";
	
	php.baseUrl = '<?=base_url()?>';
	//php.source = source;
	
	require(["jquery","graph/graphy"], function ($) {
	});
</script>

<!--<script type="text/javascript" src="<?=base_url()?>js/topic_script.js"></script>-->

<style>
	#infovis-bkcanvas 
	{
		background-color: #fff;
	}
</style>

</head>
<body>


<div id="main">
    <div class="container_24">
        <div id="infovis">
            <div id="slider" class="slide" style="position: absolute; z-index: 20; margin-left: 7px;"></div>
        </div>                                    
    </div>
</div>

</body>
</html>