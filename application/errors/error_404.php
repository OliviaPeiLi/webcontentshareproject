<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:addthis="http://www.addthis.com/help/api-spec" lang="en">
<head>
	<link rel="icon" type="image/png" href="/images/favicon.ico">
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
				<div id="login" >&nbsp;&nbsp;

				</div>
			<? } ?>
		</div>
	</div>
</div>


<div class="container container_24">
    <div class="grid_24 alpha omega">
	<div id="fourOhFour_container">
	    <h1>Oops!</h1>
	    <div id="fourOhFour_text">The page you're looking for could not be found.</div>
	    <div id="fourOhFour_goBack"><a href="/">Let's Go Back</a></div>
	</div>
    </div>
</div>

<script type="text/javascript">
	/**
	 * Mix panel
	 */
	(function(d,c){var a,b,g,e;a=d.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===d.location.protocol?"https:":"http:")+'//api.mixpanel.com/site_media/js/api/mixpanel.2.js';b=d.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b);c._i=[];c.init=function(a,d,f){var b=c;"undefined"!==typeof f?b=c[f]=[]:f="mixpanel";g="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config".split(" ");
	for(e=0;e<g.length;e++)(function(a){b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,0)))}})(g[e]);c._i.push([a,d,f])};window.mixpanel=c})(document,[]);
	mixpanel.init("cf68a06851f872214bbae1b7d1bb9b3f");


		//MIXPANEL
		console.log('SENDING MIXPANEL');
		var referrer = document.referrer;
		if (referrer == '') referrer = 0;
		mixpanel.track('404_error', {'referrer':document.referrer});
</script>

</body>
</html>