<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:addthis="http://www.addthis.com/help/api-spec" lang="en">
<head>
	<link rel="icon" type="image/png" href="<? $base_url;?>/images/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>404</title>
	<link rel="stylesheet" href="/css/base.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/960fluid.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/header_lean.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/common.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/external.css" type="text/css" media="screen" />
</head>
<body>
	<div id="fb-root" style="display:none"></div>
	<div id="header">
		<div class="container_24" id="login_main">
			<div class="grid_6 suffix_8">
	            <a href="/"><div id="homeLink"></div></a>
	        </div>
			<div class="grid_10" id="login_entry">
				<? if (isset($include_login) && $include_login) { ?>
					<div id="login" >&nbsp;&nbsp;</div>
				<? } ?>
			</div>
		</div>
	</div>
	
	<div class="container container_24">
	    <div class="grid_24 alpha omega">
		<div id="fourOhFour_container">
		    <?php /*<h1><?=$heading?></h1>*/?>
		    <div id="fourOhFour_text"><?=$message?></div>
		    <div id="fourOhFour_goBack"><a href="/">Let's Go Back</a></div>
		</div>
	    </div>
	</div>
</body>