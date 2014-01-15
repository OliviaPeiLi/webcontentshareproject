/**
 * Asyc load of third part APIs
 */
define(function() {
	var test  = 'asd 1';

	/**
	 * KissMetrics Analytics API
	 */
	 
	 (function() {
	  var _kmk = _kmk || php.kissmetrics_key;
	  function _kms(u){
	    setTimeout(function(){
	      var d = document, f = d.getElementsByTagName('script')[0],
	      s = d.createElement('script');
	      s.type = 'text/javascript'; s.async = true; s.src = u;
	      f.parentNode.insertBefore(s, f);
	    }, 1);
	  }
	  _kms('//i.kissmetrics.com/i.js');
	  _kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
		if(php.userId){
			  _kmq.push(['identify', php.userUrl]);
		}
	  })();
	  

	/** 
	 * Google analytics API
	 */
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	  
	/**
	 * Google plusOne API
	 */
	/*(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();*/
	  
	/**
	 * Twitter
	 */
	(function() {
	    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
	    t.src = '//platform.twitter.com/widgets.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
	    t.onload = function() {
		    twttr.events.bind('tweet', function(e) {
			    //var url = e.target.attributes['src'];
			    //var url = unescape(e.target.src.match("url=(.*?)&")[1]);
			    var tmp_url = e.target.src.split('%2F');
			    var newsfeed_id = tmp_url[tmp_url.length-1];
					var user = php.userUrl ? php.userUrl : 0;
					if (user) {
						_kmq.push(['identify', ''+user+'']);
					}
					_kmq.push(['record', 'tweeted a drop', {'newsfeed_id':''+newsfeed_id+''}]);
		    });
	    };
	})();
	  
	/**
	 * Facebook
	 */
	  (function() {
	    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
	    t.src = '//connect.facebook.net/en_US/all.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
	  })();
	  
	  window.fbAsyncInit = function() {
			FB.init({
			//appId      : '315227451867064', // App ID
			appId	   : php.fb_app_id,
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true,  // parse XFBML
			oauth	   : true,
			//frictionlessRequests :true //override request popup
			});
			
			FB.Event.subscribe('edge.create',
				function(response) {
					console.log('FB LIKE'+response);
					var tmp_path = response.split('/');
					var newsfeed_id = tmp_path[tmp_path.length-1];
					console.log('sending to KissMetrics', newsfeed_id);
					var user = php.userUrl ? php.userUrl : 0;
					if (user) {
						_kmq.push(['identify', ''+user+'']);
					}
					_kmq.push(['record', 'facebook-liked a drop', {'newsfeed_id':''+newsfeed_id+''}]);
				}
		   );
		   
      };

	/**
	 * ?????????? API
	 */
	var _qevents = _qevents || [];
	(function() {
		var elem = document.createElement('script');
		elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
		elem.async = true;
		elem.type = "text/javascript";
		var scpt = document.getElementsByTagName('script')[0];
		scpt.parentNode.insertBefore(elem, scpt);
	})();
	_qevents.push({ qacct:"p-72o3dycVi2Y2-" });
	
	/**
	 * Typekit
	 */
	//RR 8/3/2012 - disabled we can use google fonts or default (faster load and no logo on the page)
	  /*(function() {
	    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
	    t.src = 'http://use.typekit.com/hhy8jlj.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
	    if (s.addEventListener){
		    s.addEventListener('load', function (e) { try{Typekit.load();}catch(e){} }, false);
		} else if (s.attachEvent){
		    s.attachEvent('onload', function (e) { try{Typekit.load();}catch(e){} }, false);
		}
	  })();
	*/
	/**
	 * Mix panel
	 */
	 //OLDER MIXPANEL
	 /*
	(function(d,c){var a,b,g,e;a=d.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===d.location.protocol?"https:":"http:")+'//api.mixpanel.com/site_media/js/api/mixpanel.2.js';b=d.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b);c._i=[];c.init=function(a,d,f){var b=c;"undefined"!==typeof f?b=c[f]=[]:f="mixpanel";g="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config".split(" ");
	for(e=0;e<g.length;e++)(function(a){b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,0)))}})(g[e]);c._i.push([a,d,f])};window.mixpanel=c})(document,[]);
	mixpanel.init("cf68a06851f872214bbae1b7d1bb9b3f");
	*/
	//NEW MIXPANEL
	(function(c,a){window.mixpanel=a;var b,d,h,e;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src=("https:"===c.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.1.min.js';d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d);a._i=[];a.init=function(b,c,f){function d(a,b){var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]);a[b]=function(){a.push([b].concat(Array.prototype.slice.call(arguments,0)))}}var g=a;"undefined"!==typeof f?
	g=a[f]=[]:f="mixpanel";g.people=g.people||[];h="disable track track_pageview track_links track_forms register register_once unregister identify name_tag set_config people.identify people.set people.increment".split(" ");for(e=0;e<h.length;e++)d(g,h[e]);a._i.push([b,c,f])};a.__SV=1.1})(document,window.mixpanel||[]);
	mixpanel.init("cf68a06851f872214bbae1b7d1bb9b3f");
	/**
	 * Uservoice
	 */
	  (function() {
	    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
	    t.src = '//widget.uservoice.com/w6uSxs0ZX4rA7N7YzViUwA.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
	  })();

});