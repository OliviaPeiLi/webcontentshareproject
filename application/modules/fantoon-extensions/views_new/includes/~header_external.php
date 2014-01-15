<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<script src="/js/require.js"></script>
	<script type="text/javascript">
		if(typeof(console) != 'object' || typeof(console.log) == 'undefined') {
		    window.console = {};
		    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
		}
	    if (typeof Firebug != 'undefined' && typeof Firebug.Console != 'undefined') {
	    	Firebug.Console.logRow = function() {}
	    }
		<?php 
			$js_max_v = 0; 
			if (!$this->is_mod_enabled('optimized_js')) {
				foreach ($this->css_filenames as $css_filename=>$css_filename_data) 
					if (strpos($css_filename, '/js/') === 0) $js_max_v += $css_filename_data[0];
			}
		?>
		
		require.config({
			urlArgs: "v=<?=$js_max_v?>",
			baseUrl: "<?=$this->js_base?>",
			paths: {
				"jquery": "<?= ENVIRONMENT == 'development' ? 'plugins/jquery.dev' : 'jquery.min' ?>",
				"jquery-ui": "<?= ENVIRONMENT == 'development' ? 'plugins/jquery-ui.min' : 'jquery-ui.min'?>"
			},
			waitSeconds: 15
		});
		require(["jquery"], function() {
			$(document).live('update', function() {
				if (typeof FB != 'undefined') FB.XFBML.parse();
				if (typeof twttr != 'undefined') try { twttr.widgets.load(); } catch (e) {}
			}).trigger('update');
		});
		
		var php = {
				'baseUrl': '<?=Url_helper::base_url()?>',
				'userId': <?=$this->session->userdata('id') ? $this->session->userdata('id') : 0?>,
				'redirectUrl': '<?=$this->session->userdata('id') ? '/' : '/signup'?>',
				'userUrl': '<?=@$this->user->uri_name?>',
				'userAvatar': '<?=@$this->user->avatar_small?>',
				'baseCSS': '<?=$this->css_base?>',
				'csrf': {
						'name': '<?php echo $this->security->get_csrf_token_name()?>',
						'hash': '<?php echo $this->security->get_csrf_hash();?>'
					},
				'fb_app_id': '<?=$this->config->item('fb_app_key')?>',
				'fb_app_namespace': '<?=$this->config->item('fb_app_namespace')?>',
				'kissmetrics_key': '<?=$this->config->item('km_key')?>',
				'lang': {}
		};
		
		//IE Fix
		function save_tmp_message(msg) {
			//console.info('save tmp message');
			if (msg.data.indexOf('{') != 0) return;
			try {
				var data = eval('('+msg.data+')');
			} catch (e) {
				return;
			}
			if (!data.fandrop_message) return;
			//console.info('save tmp message', msg.data);
			window.tmp_message = {'data':msg.data,'origin':msg.origin,'source':msg.source};
		}
		if (window.addEventListener) {
			window.addEventListener('message', save_tmp_message);
		} else if (window.attachEvent) {
			window.attachEvent('onmessage', save_tmp_message);
		} else {
			window.onmessage = save_tmp_message;
		}		
	</script>
	<?=Html_helper::requireJS(array("includes/header_external"))?>
	
	<? // APIS ?>
	<script>
		//Facebook
		(function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = '//connect.facebook.net/en_US/all.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		window.fbAsyncInit = function() {
			FB.init({ appId: php.fb_app_id, status:true, cookie:true, xfbml: true, oauth: true });
    	};

        //Google analytics
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
		var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-29771355-1']); _gaq.push(['_trackPageview']);

		//Twitter
		(function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

		/**
		 * Mix panel
		 */
		 (function(c,a){window.mixpanel=a;var b,d,h,e;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src=("https:"===c.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.1.min.js';d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d);a._i=[];a.init=function(b,c,f){function d(a,b){var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]);a[b]=function(){a.push([b].concat(Array.prototype.slice.call(arguments,0)))}}var g=a;"undefined"!==typeof f?
		 g=a[f]=[]:f="mixpanel";g.people=g.people||[];h="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config people.identify people.set people.increment".split(" ");for(e=0;e<h.length;e++)d(g,h[e]);a._i.push([b,c,f])};a.__SV=1.1})(document,window.mixpanel||[]);
		 mixpanel.init("cf68a06851f872214bbae1b7d1bb9b3f");

		/**
		 * KissMetrics Analytics API
		 */
		var _kmq = [];		 
		(function() { var _kmk = _kmk || php.kissmetrics_key; function _kms(u){ var d = document, f = d.getElementsByTagName('script')[0], s = d.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = u; f.parentNode.insertBefore(s, f); }
		_kms('//i.kissmetrics.com/i.js'); _kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js'); if(php.userId) _kmq.push(['identify', php.userUrl]); })();
				
	</script>	
 </head>
<body id="<?=isset($no_id)&&$no_id ? '' : 'external_main'?>">
<div id="fb-root"></div>