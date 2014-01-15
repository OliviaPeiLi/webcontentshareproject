<?php
include '../application/modules/fantoon-extensions/helpers/helper.php';
include '../application/modules/fantoon-extensions/libraries/Scraper.php';
include '../application/modules/fantoon-extensions/helpers/MY_url_helper.php';
$scraper = new Scraper();

function fix_iframe_src($matches) {
	$url = rawurldecode($_GET['url']);
	if (substr($matches[1], 0, 2) == '//' || substr($matches[1], 0, 7) == 'http://' || substr($matches[1], 0, 8) == 'https://') {
		$domain = str_replace(array('http://','https://','//', 'www.'), '', $matches[1]);
		if (strpos($domain, '/') !== false) $domain = substr($domain, 0, strpos($domain, '/'));
		$self_domain = str_replace(array('http://','https://', '//', 'www.'), '', $url);
		if (strpos($self_domain, '/') !== false) $self_domain = substr($self_domain, 0, strpos($self_domain, '/'));
		if ($domain != $self_domain) return $matches[0];
	} 
	//return $matches[0];
	return str_replace($matches[1], "/external/index.php?url=".urlencode(make_full($url, $matches[1])), $matches[0]);
}

$url = rawurldecode(@$_GET['url']); 
if (!$url) {
	header("Location: ".(strpos($_SERVER["HTTP_REFERER"],'/external/index.php?') !== false ? '/' : $_SERVER["HTTP_REFERER"]));
	//die("<h1>The site could not be initialized. Try using the 'Drop it' button.");
}
$contents = $scraper->request($url,1, isset($_REQUEST['cookie']) ? $_REQUEST['cookie'] : null );
if (strpos($url, '.jpg') !== false) {
	header('Content-Type: image/jpeg');
	die($contents);
} elseif (strpos($url, '.png') !== false) {
	header('Content-Type: image/png');
	die($contents);
} elseif (strpos($url, '.gif') !== false) {
	header('Content-Type: image/gif');
	die($contents);
} elseif (strpos($url, '.swf') !== false) {
	header('Content-Type: application/x-shockwave-flash');
	die($contents);
} elseif (strpos($url, '.css') !== false) {
	header('Content-Type: text/css');
	die($contents);
} elseif (strpos($url, '.js') !== false) {
	header('Content-Type: application/javascript');
	$contents = preg_replace('#if[ ]*\([^\)]*(window.)*(top.|parent.|self.)*(location)*[ ]*![=]+[ ]*(window.)*(self|top|parent.)*(location)*(.host)*[ ]*\)#', 'if (false)', $contents);
	$contents = preg_replace('#if[ ]*\((top|parent).frames.length#', 'if(0', $contents);
	$contents = str_replace('if (window != window.top)', 'if (false)', $contents);
	$contents = str_replace('if (top.location != location)', 'if (false)', $contents);
	if ($_GET['base']) {
		$parsed = parse_url($_GET['base']);
		$contents = preg_replace('#(window.)*(document.)*location.pathname#', '"'.$parsed['path'].'"', $contents);
	}
	die($contents);
}

if (!$contents) {
	die("<h1>The site could not be initialized. Try using the 'Drop it' button.");
}
$css = '<link rel="stylesheet" href="https://s3.amazonaws.com/fantoon-dev/css/pNzkx.css" type="text/css"/>';
$css .= '<style>#fd-iframe-overlay {position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 2147483644;}</style>';

$headers = $scraper->headers();
if (isset($headers['content_type']) && strpos($headers['content_type'], 'image/') !== false) {
	//header("Content-Type: {$headers['content_type']}");
	//header("Content-Length: {$headers['download_content_length']}");
	$contents = '
		<html>
			<head></head>
			<body><img src="'.$url.'"/></body>
		</html>
	';
}
//Debug
//$contents = preg_replace('#<script[^>]>.*?</script>#msi', '', $contents);

//Change relative links to full
//$contents = preg_replace('#/\*.*?\*/#msi', '', $contents);
$contents = preg_replace_callback('#href="(.*?)"#msi', create_function('$matches', 'return "href=\"".make_full("'.$url.'", $matches[1])."\"";'), $contents);
$contents = preg_replace_callback('#src="(.*?)"#msi', create_function('$matches', 'return "src=\"".make_full("'.$url.'", $matches[1])."\"";'), $contents);
$contents = preg_replace_callback('#href=\'(.*?)\'#msi', create_function('$matches', 'return "href=\"".make_full("'.$url.'", $matches[1])."\"";'), $contents);
$contents = preg_replace_callback('#src=\'(.*?)\'#msi', create_function('$matches', 'return "src=\"".make_full("'.$url.'", $matches[1])."\"";'), $contents);

//$contents = preg_replace_callback('#<style[^>]*>[^@]*@import ["\'](.*?)["\'];[^<]*</style>#msi', create_function('$matches', 'return "<link rel=\"stylesheet\" type=\"text/css\" href=\"".make_full("'.$url.'", $matches[1])."\" media=\"all\"/>";'), $contents);
//$contents = preg_match_all('#<style[^>]*>([^@]*@import ["\'](.*?)["\'];[^<]*)</style>#msi', $contents, $matches);
//$contents = preg_replace_callback('#@import ["\'](.*?)["\'];#msi', create_function('$matches', 'return "<link rel=\"stylesheet\" type=\"text/css\" href=\"".make_full("'.$url.'", $matches[1])."\" media=\"all\"/>";'), $contents);
preg_match_all('#@import[^"\'\n]*["\'](.*?)["\']#msi', $contents, $matches);
foreach ($matches[1] as $match) {
	$import = '<link rel="stylesheet" type="text/css" href="'.make_full($url, $match).'" media="all"/>';
	$contents = str_replace('<head>', '<head>'.$import, $contents); 
}
$import1 = '<script type="text/javascript">
	window.onload = function() {
		fd_fix_objects();
	}
	function fd_fix_objects() {
		var els = document.getElementsByTagName("OBJECT");
		console.info(els);
		for (var i=0; i<els.length;i++) {
			console.info(els[i]);
			var params = els[i].getElementsByTagName("PARAM");
			var added = false;
			for (var j=0;j<params.length;j++) {
				if (params[j].getAttribute("name") == "wmode") {
					prams[j].setAttribute("value", "transparent");
					added = true;
				}
			}
			console.info(added);
			if (!added) {
				var param = document.createElement("PARAM");
					param.setAttribute("name", "wmode");
					param.setAttribute("value", "transparent");
				els[i].appendChild(param);
			}
		}
	}
</script>
';
$contents = str_replace('</body>', '</body>'.$import1, $contents); 
//Set iframes to local
$contents = preg_replace_callback('#<object[^>]*type="text/html"[^>]*data=["\'](.*?)["\'][^>]*>[^<]*</object>#msi', 'fix_iframe_src', $contents);
$contents = preg_replace_callback('#<iframe[^>]*src=["\'](.*?)["\'][^>]*>.*?</iframe>#msi', 'fix_iframe_src', $contents);

//Site specific protection scripts
$contents = preg_replace('#<script type="text/javascript" src="http://www.wired.com/js/cn-fe-common/cn.js"></script>#msi', '', $contents);
$contents = preg_replace('#<script type="text/javascript">[\r\n\s\t]+//<!--[\r\n\s\t]*var platformEnvironment.*?</script>#msi', '', $contents);
$script = '<script type="text/javascript">
			var els = document.getElementsByTagName("embed");
			for (var i=0; i< els.length; i++) {
				if (els[i].src.indexOf("http://s.ytimg.com/yt/swfbin/") > -1) {
					var v_id = els[i].getAttribute("flashvars").match(/&video_id=([^%]*?)($|&)/);
					var iframe = \'<iframe width="\'+els[i].offsetWidth+\'" height="\'+els[i].offsetHeight+\'" src="http://www.youtube.com/embed/\'+v_id[1]+\'" frameborder="0" allowfullscreen></iframe>\';
					var div = document.createElement("div");
						div.innerHTML = iframe;
					els[i].parentNode.replaceChild(div, els[i]);
				}
			}
		   </script>';
$contents = preg_replace('#</body>#', $script.'</body>', $contents);
//Used to debug some protected site
//$preg = '#<script type="text/javascript">[\r\n\s\t]+//<!--[\r\n\s\t]*.*?</script>#msi';
//preg_match_all($preg, $contents, $matches);
//print_r('<textarea style="display:none">'.implode("\r\n", $matches[0]).'</textarea>');

//Usual protection scripts
$contents = preg_replace('#<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js"></script>#msi', '', $contents);
$contents = preg_replace('#<script type=\'text/javascript\' src="[^>"]*ecomfw.min.js\?ver=3.3.1"></script>#msi', '', $contents);

$contents = preg_replace('#</head>#msi', $css.'</head>', $contents);

die($contents);