<?php 
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
}else{
   	define('ENVIRONMENT', 'development');
}

if(ENVIRONMENT == 'staging'){
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(ENVIRONMENT == 'production'){
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
	define('BASEPATH', __DIR__.'/../../system/');
}
include(BASEPATH.'../scripts/config.php');
include(BASEPATH.'../application/config/config.php');
include(BASEPATH.'../application/config/s3.php');
include_once BASEPATH.'../scripts/db.php';
include BASEPATH.'../application/modules/fantoon-extensions/libraries/Scraper.php';
include BASEPATH.'../application/modules/fantoon-extensions/helpers/MY_url_helper.php';
require_once BASEPATH.'../application/modules/fantoon-extensions/libraries/S3.php';
$scraper = new Scraper();

$tmp_path = BASEPATH.'../uploads/';

//UPDATE links SET s3_img = (SELECT img FROM links_tmp WHERE link_id = links.link_id)
while (1) {
	//$res = mysql_query("SELECT link_id, link, (SELECT newsfeed_id FROM newsfeed WHERE type = 'link' AND activity_id = link_id) as newsfeed_id
	//					 FROM links WHERE link_id = 11352");
	$res = mysql_query("SELECT link_id, link, (SELECT newsfeed_id FROM newsfeed WHERE type = 'link' AND activity_id = link_id) as newsfeed_id
						 FROM links WHERE thumb_generated = 0 AND link_type = 'content' ORDER BY link_id ASC LIMIT 100");
	if (!$res) break;
	$has_it = false;
	while ($row = mysql_fetch_assoc($res)) {
		if (!$row) break;
		if (strpos($row['link'], 'vbox7.com') !== false || strpos($row['link'], 'incredibox.com') !== false || strpos($row['link'], 'gimmebar.com') !== false) {
			mysql_query("UPDATE links SET thumb_generated = 1 WHERE link_id = ".$row['link_id']);
			continue;
		}
		$has_it = true;
		$dir = $tmp_path.'/screenshots/drop-'.$row['newsfeed_id'].'/';
		mkdir($dir, 0777);
		echo "request: ".$row['newsfeed_id'],' '.$row['link']."\r\n";
		$contents = $scraper->request($row['link']);
		if (strpos($row['link'], '#') !== false) {
			list($link, ) = explode('#', $row['link'], 2); 
		} else {
			$link = $row['link'];
		}
		
		//Change relative links to full
		$old = 0;
		if (strpos($row['link'], 'http://www.multirama.bg') !== false) $old = 1;
		//$contents = preg_replace('#/\*.*?\*/#msi', '', $contents);
		$contents = preg_replace_callback('#href="(.*?)"#msi', create_function('$matches', 'return "href=\"".make_full("'.$row['link'].'", $matches[1], '.$old.' )."\"";'), $contents);
		$contents = preg_replace_callback('#src="(.*?)"#msi', create_function('$matches', 'return "src=\"".make_full("'.$row['link'].'", $matches[1], '.$old.')."\"";'), $contents);
		$contents = preg_replace_callback('#href=\'(.*?)\'#msi', create_function('$matches', 'return "href=\"".make_full("'.$row['link'].'", $matches[1], '.$old.')."\"";'), $contents);
		$contents = preg_replace_callback('#src=\'(.*?)\'#msi', create_function('$matches', 'return "src=\"".make_full("'.$row['link'].'", $matches[1], '.$old.')."\"";'), $contents);
		$contents = preg_replace_callback('#data=[\'"](.*?)[\'"]#msi', create_function('$matches', 'return "data=\"".make_full("'.$row['link'].'", $matches[1], '.$old.')."\"";'), $contents);
		$contents = preg_replace('#</object>#msi', '<param name="base" value="'+$link+'"/></object>', $contents);
		
		//$contents = preg_replace_callback('#<style[^>]*>[^@]*@import ["\'](.*?)["\'];[^<]*</style>#msi', create_function('$matches', 'return "<link rel=\"stylesheet\" type=\"text/css\" href=\"".make_full("'.$row['link'].'", $matches[1])."\" media=\"all\"/>";'), $contents);
		//$contents = preg_match_all('#<style[^>]*>([^@]*@import ["\'](.*?)["\'];[^<]*)</style>#msi', $contents, $matches);
		//$contents = preg_replace_callback('#@import ["\'](.*?)["\'];#msi', create_function('$matches', 'return "<link rel=\"stylesheet\" type=\"text/css\" href=\"".make_full("'.$row['link'].'", $matches[1])."\" media=\"all\"/>";'), $contents);
		preg_match_all('#@import[^"\'\n]*["\'](.*?)["\']#msi', $contents, $matches);
		foreach ($matches[1] as $match) {
			$import = '<link rel="stylesheet" type="text/css" href="'.make_full($row['link'], $match, $old).'" media="all"/>';
			$contents = str_replace('<head>', '<head>'.$import, $contents); 
		}
				
		//Set iframes to local
		//$contents = preg_replace_callback('#<object[^>]*type="text/html"[^>]*data=["\'](.*?)["\'][^>]*>[^<]*</object>#msi', 'fix_iframe_src', $contents);
		//$contents = preg_replace_callback('#<iframe[^>]*src=["\'](.*?)["\'][^>]*>.*?</iframe>#msi', 'fix_iframe_src', $contents);
		
		//Site specific protection scripts
		//http://www.incredibox.com/en/#/application		
		$contents = preg_replace_callback('#swfobject\.embedSWF\("(.*?)"#msi', create_function('$matches', 'return "params.base = \"'.$link.'\";".str_replace($matches[1], make_full("'.$row['link'].'", $matches[1], '.$old.'), $matches[0]);'), $contents);
		
		$contents = preg_replace('#<script type="text/javascript" src="http://www.wired.com/js/cn-fe-common/cn.js"></script>#msi', '', $contents);
		$contents = preg_replace('#<script type="text/javascript">[\r\n\s\t]+//<!--[\r\n\s\t]*var platformEnvironment.*?</script>#msi', '', $contents);
		//http://www.brainyquote.com/
		$contents = preg_replace('#<script[^>]*/js/header_01\.js[^>]*></script>#msi', '', $contents);
		
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
				
		
		file_put_contents($dir.'index.php', $contents);
		S3::putObject($contents, $config['s3_bucket'], 'uploads/screenshots/drop-'.$row['newsfeed_id'].'/index.php', S3::ACL_PUBLIC_READ);
			
		mysql_query("UPDATE links SET thumb_generated = 1 WHERE link_id = ".$row['link_id']);
		echo "  updated \r\n";
	}
	if (!$has_it) break;
}
