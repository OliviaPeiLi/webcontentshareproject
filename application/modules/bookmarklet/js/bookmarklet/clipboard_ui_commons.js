/*
 * Extensions to clipboard_ui.js. This file contains just simple functions to improove the code readabilty.
 */

jQuery.fn.extend({
	/**
	 * Extends the default document.elementFromPoint(x, y) to be compatible with IE
	 */
	elementFromPoint : function(x,y) {
		var doc = this.get(0) ? this.get(0).ownerDocument : document;
		if (navigator.userAgent.indexOf('MSIE') > -1) {
			var IE_sucks = doc.elementFromPoint(x,y); //RR - Do not change the var name at any reason!
		}
		if(!doc.elementFromPoint) return null;
		var el = doc.elementFromPoint(x,y);
		if (el && (el.Cd && el.Cd.d == 'load' || el.parentNode.Cd && el.parnetNode.Cd.d == 'load' || el.__e3_) ) { //gmaps
			parent = el;
			if (parent.__e3_) parent = parent.parentNode;
			while(parent.tagName == 'DIV') {
				if (!parent.Cd && parent.__e3_) break;
				parent = parent.parentNode;
			}
			while(parent.__e3_) parent = parent.parentNode;
			el =  parent;
		}
		if (el && el.__e_ && doc.location.href.indexOf('maps.google.') > -1) {
			el = jQuery(doc).find('#tileContainer')[0];
		}
		return el;
	},
	
	/**
	 * Inserts text into textarea at cursor
	 */
	insertAtCaret: function(myValue) {
		var obj;
		if( typeof this[0].name !='undefined' ) obj = this[0]; else obj = this;
		
		if (typeof(selectionStart) != 'undefined') {
			var startPos = obj.selectionStart;
			var endPos = obj.selectionEnd;
			var scrollTop = obj.scrollTop;
			obj.value = obj.value.substring(0, startPos)+myValue+obj.value.substring(endPos,obj.value.length);
			obj.focus();
			obj.selectionStart = startPos + myValue.length;
			obj.selectionEnd = startPos + myValue.length;
			obj.scrollTop = scrollTop;
		} else if (typeof (document.selection.createRange) != 'undefined') {
			obj.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			obj.focus();
		} else {
			obj.value += myValue;
			obj.focus();
		}

	},
	
	/**
	 * determines if el2 is inside el1
	 */
	isInside: function(target, base_x, base_y) {
		var x1 = this.offset().left,
			y1 = this.offset().top,
			w1 = this.width(),
			h1 = this.height(),
			x2 = target.offset().left + base_x,
			y2 = target.offset().top + base_y,
			w2 = target.width(),
			h2 = target.height();
		if(this.hasClass('hd')) {
			console.info(this, [x1, y1, w1, h1],  target, [x2, y2, w2, h2]);
		}
		return x2 < x1+w1 && x2+w2 > x1
				&& y2 < y1+h1 && y2+h2 > y1;
	},
	
	/**
	 * similar to css closest but with a css rule
	 */
	closestCss: function(rule, value) {
		_parent = this;
		while(!_parent.is('html')) {
			if (_parent.css(rule) == value) return _parent;
			_parent = _parent.parent();
		}
		return _parent;
	},
	
	/**
	 * determines if el2 is prev of el1.  Mostly used to compare zIndexes
	 */
	isPrev: function(target) {
		var this_parent = this[0], this_parent_prev = this[0];
		var target_parent, target_parent_prev;
		while(this_parent) {
			target_parent_prev = target_parent = target[0];
			while(target_parent) {
				if (this_parent == target_parent) break;
				target_parent_prev = target_parent;
				target_parent = target_parent.parentNode;
			}
			if (this_parent == target_parent) break;
			this_parent_prev = this_parent;
			this_parent = this_parent.parentNode;
		}
		var prev_els = jQuery(target_parent_prev).prevAll();
		for (var i in prev_els) {
			if (prev_els[i] == this_parent_prev) return false;
		}
		return true;
	},
	
	/**
	 * Zoom the selected contents to appear in the preview popup without scrollbars
	 */
	resizeToWindow: function(options) {
		var min_zoom = 0.6;
		var zoom = 1;
		var self = this;
		
		this.data('zoom', zoom);

		if (this.width() > options.max_width /*||  this.height() > options.max_height*/) {

			zoom = Math.round(options.max_width/this.width()*100)/100;
			if (zoom < min_zoom) zoom = min_zoom;
			if (zoom > 1) zoom = 1;
			
			this.data('zoom',zoom);
			
			if (true || 'zoom' in document.body.style || '-moz-transform' in document.body.style) {
				//for the container
				var content = this.find('iframe').contents().find('body');
				var css = {
						'zoom':zoom,
						'-moz-transform': 'scale('+zoom+')',
						'-moz-transform-origin': '0 0'
					};
				
				try { //http://www.weavetexfashion.com/baby-frocks.html
					content.css(css);
				} catch (e) {

				}
			} else { // IE7
				console.info('IE7 zoom');
				//TO-DO
			}
		}
		return this;
	},
	
	getFontFaces: function() {
		var faces = '';
		var css = document.styleSheets; 
		for (var i=0;i<css.length;i++) {
			if (css[i].href && (css[i].href.indexOf('/ft/') > -1 || css[i].href.indexOf('fandrop.com') > -1)) continue;
			console.info(css[i].href);
			if (css[i].href && css[i].href.indexOf('fonts.kbb.com') > -1) {
				faces += "@import '"+css[i].href+"';\n";
			} else {
				try { //External stylesheets break on FF
					var rules = css[i].cssRules;
					if (rules) for (var j=0;j<rules.length;j++) {
						if (rules[j] instanceof CSSFontFaceRule) {
							faces += rules[j].cssText+"\n";
						}
					}
				} catch (e) {
					faces += "@import '"+css[i].href+"';\n";
				}
			}
		}
		return faces;
	},
	
	/**
	 * Add overlays over iframes in to avoid cross site js errors 
	 */
	addIframeOverlays: function(options) {
		var self = this;
		this.find(".fd-remote-iframe.visible").each(function() {
			var iframe = jQuery(this);
			var css = {
					'width': iframe.width(),
					'height': iframe.height(),
					'left': iframe.offset().left,
					'top': iframe.offset().top
				}
			if (self.closest('#clip_overlay').length) {
				css['left'] -= self.closest('#clip_overlay').offset().left + 1;
				css['top'] -= self.closest('#clip_overlay').offset().top + 1;
			}
			for (var i in options) {
				css[i] = options[i];
			}
			var div = jQuery('<div class="ft-iframe-overlay" style="opacity:0"></div>').css(css);
			self.append(div);
		});
	},
	
	/**
	 * remove the overlays added in the preview popup
	 * @see addIframeOverlays
	 */
	removeIframeOverlays: function(options) {
		jQuery(this).find('.ft-iframe-overlay').remove();
	},
	
	/**
	 * Returns the innerHTML + the selected tag and its attributes
	 */
	outerHTML: function (s)
	{
		return this[0].outerHTML || new XMLSerializer().serializeToString(this[0]);
	},
	
	/**
	 * Processes all parent elements of the selected html to add proper background. It stops processing if
	 * reaches non transparent image or solid color
	 */
	setBackground: function(source, callback, base) {
		console.info('get background image');
		if (!source.length) {
			console.info('Source element doesnt exists');
			return;
		}
		var self = this;
		var temp = source;
		
		//Need to check for transparent images bc of: http://dev.fantoon.com:8100/browse/FD-3436
		if (self.is('img') && self.attr('src').indexOf('.png') == -1) {
			console.info('Non transparent image - no background');
			callback.call(this); return;
		}

		for (var i=0;i<3;i++) {  //check 3 elements with background above for backgrounds bc of transparency
			while (temp.css('background-image') == 'none') {
				if (temp.css('background-color') != 'transparent' && temp.css('background-color') != 'rgba(0, 0, 0, 0)') {
					console.info('found solid color', temp.css('background-color'));
					self.copy_style(temp, 'background', base);
					self.css('background-color', temp.css('background-color')); //http://www.samsung.com/global/galaxys3/
					console.info(self[0], self.css('background-color'));
					callback.call(this); return;
				}
				if (temp.is('html')) {
					console.info('no background found');
					callback.call(this); return;
				} else {
					temp = temp.parent();
				}
			}
			if (temp[0] != source[0]) {
				self.parent().prepend('<div/>');
				self = self.parent().find('div:first');
				self.css({'position':'absolute','z-index':-i-1}).width(self.width()).height(self.height());
			}
			
			console.info('found background: ',temp.css('background-image'), self);
			self.copy_style(temp, 'background', base);
			console.info(self.css('background-image'));
			
			if (!self.attr('rel')) {
				self.css({
					'left': temp.offset().left - source.offset().left,
					'top': temp.offset().top - source.offset().top,
					'width': temp.width(), 'height': temp.height()
				});
			}

			//the background is non transparent - no need to recurse more
			console.info('background-repeat',temp.css('background-repeat'));
			console.info('background-color',temp.css('background-color'));
			if (temp.css('opacity') == '1' && temp.css('background-image').indexOf('.png') == -1) {
				if(temp.css('background-repeat') == 'repeat' ||  temp.css('background-color') != 'transparent') {
					callback.call(this);
					return;
				}
			}
			console.info('background is transparent. moving up in hiearhy ', i+1);
			temp = temp.parent();

		}
		console.info('added 3 backgrounds - no need to add more');
		callback.call(self);
		return;
	},
	
	/**
	 * Copies the inline and css styling from source element to the target
	 * @param source (html element) - it gets the styling to it and appends them to "this"
	 * @param filter (string) - copies just the styles which mactch the filter
	 * @param base (link) - appends it to image links like background: url({base}../images/foo.png)
	 */
	copy_style: function(source, filter, base) {
	    //http://dev.fantoon.com:8100/browse/FD-1116 some styles should be forced to des elements, or it will lost
		var hardcode_css = ['width','height'];
		if (!this.length) return [];
		var self = this[0];
		var failcheck = false;
		if (self.tagName in {'PARAM':''}) return [];
		var dest_style, source_style, temp;
		if(window.getComputedStyle){
			dest_style = window.getComputedStyle(self);
			source_style = window.getComputedStyle(source[0]);
			if (source_style) {
				for(var i = 0, l = dest_style.length; i < l; i++){
					if (filter && dest_style[i].indexOf(filter) == -1) continue;
					temp = source_style.getPropertyValue(dest_style[i]);
					if (base && temp.indexOf('url(') > -1) temp = temp.replace('url(','url('+base); //Proxy
					
					if (dest_style.getPropertyValue(dest_style[i]) != temp || jQuery.inArray(dest_style[i], hardcode_css) != -1) {
						failcheck = true;
						self.style.setProperty(dest_style[i], temp);
					}
				}
				if (failcheck && !this.attr('style')) this.hide(); //http://www.videojug.com/
			} else {
				console.info('source style not found ', self);
			}
		};
		if(dest_style = self.currentStyle){ //IE
			source_style = source[0].currentStyle;
			for(var prop in dest_style){
				if (dest_style[prop] != source_style[prop]) {
					self.style[prop] = source_style[dest_style[i]];
				}
			};
	   };
	   console.info("HEIGHT", self, self.height, self.style.height);
	   return this;
	},
	
	/**
	 * Returns true if the element has absolute positioning and its transparent.
	 */
	isAbsolute: function() {
		var $this = jQuery(this);
		var background_color = $this.css('background-color');
		var background_image = $this.css('background-image');
		var position = $this.css('position');
		var opacity = $this.css('opacity');
		return position == 'absolute'
			&& (background_color == 'rgba(0, 0, 0, 0)' || opacity == 0 || background_color == 'transparent')
			&& background_image == 'none'
	},
	
	/*
	 * Converts href, src and data attributes of the html elements to use absolute url. 
	 */
	toAbsURL: function(donor) {
		var src, attr, base;
		var attrs = {'A':'href','IMG':'src','OBJECT':'data','EMBED':'src','IFRAME':'src','LINK':'href','INPUT':'src'};
		if (!donor) donor = this;
		this.each(function() {
			if (!attrs[this.tagName] || (this.tagName == 'INPUT' && this.type != 'image') ) {
				return;
			}
			attr = attrs[this.tagName];
			if (jQuery('base[href]').length) {
				base = jQuery('base[href]').attr('href');
			} else if (donor[0].ownerDocument) {
				base = donor[0].ownerDocument.location.href;
				if (base.indexOf('#') > -1) base = base.split('#')[0];
				if (base.indexOf('?') > -1) base = base.split('?')[0];
				base = base.replace(/\/[^\/]*$/, '');
			} else {
				console.info('base not found', donor);
			}
			var l = location; l.base = base;
			jQuery(this).attr(attr, jQuery.toAbsURL(jQuery(this).attr(attr), l) );
		});
		return this;
	},
	
	/**
	 * Gets the selected text as html and filters allowed tags
	 * @deprecated
	 */
	getHtmlSelection: function(e) {
		if (window.getSelection) {
			var sel = window.getSelection();
			var html = "";
			for (var i=0;i<sel.rangeCount;i++) {
				var nNd = document.createElement("div");
				var w = sel.getRangeAt(i);
				//w.surroundContents(nNd);
				nNd.appendChild(w.cloneContents());
				jQuery(nNd).find('script, link, style').remove();
				//for (var i=0;i<els.length;i++) nNd.removeChild(els[i]);
				//html += nNd.innerHTML//.replace(new RegExp('<[^ap][^>]+>([^<]*?)</[^ap][^>]+>','gi'),'$1');
				html += nNd.innerHTML
							.replace(/<\/(br|p|h[1-6]|div)>/ig,'<br class="fd-line-break"/></$1>')
							.replace(new RegExp('<([^/ab][^>]*)>','ig'), '')

			}
			return html;
		} else if (document.selection && document.selection.createRange) {
			return (document.selection.createRange()).htmlText;
		}
		return null;
	},
	
	/**
	 * Finds list of images which appear as a slideshow and adds 'ft-slideshow' class to their parent,
	 * so the content can be processsed as a slideshow
	 * @to-do
	 */
	findSlideshows: function() {
		/*var selectors = [], className;
		this.find('img:visible').each(function() {
			if (this.width < 100 || this.height < 100) return;
			var $this = $(this)
			var $parent = $this;
			for (var i=0; i<4; i++) {
				className = $parent.attr('class');
				if (className) {
					selectors[i] = className.indexOf(' ') > -1 ? className.explode(' ') : '.'+className;
				} else {
					className = $parent[0].tagName;
				}
				$parent = $this.parent();
			}
			
			console.info(selectors);
			
			for (var sel in selectors) {
				if ($this.parent().find(sel).length >= 3) {
					$this.parent().addClass('ft-slideshow').attr('data-selector', sel);
					return;
				}
				
			}
			
		});*/
	}
});


/* Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 *
 * Requires: 1.2.2+
 */

if (jQuery.event.fixHooks) {
	var types = ['DOMMouseScroll', 'mousewheel'];
	for ( var i=types.length; i; ) {
		jQuery.event.fixHooks[ types[--i] ] = jQuery.event.mouseHooks;
	}
}

jQuery.event.special.mousewheel = {
	setup: function() {
		if ( this.addEventListener ) {
			var types = ['DOMMouseScroll', 'mousewheel'];
			for ( var i=types.length; i; ) {
				this.addEventListener( types[--i], ft_mouse_wheel_handler, false );
			}
		} else {
			this.onmousewheel = ft_mouse_wheel_handler;
		}
	},

	teardown: function() {
		if ( this.removeEventListener ) {
			var types = ['DOMMouseScroll', 'mousewheel'];
			for ( var i=types.length; i; ) {
				this.removeEventListener( types[--i], ft_mouse_wheel_handler, false );
			}
		} else {
			this.onmousewheel = null;
		}
	}
};
jQuery.fn.extend({
	mousewheel: function(fn) {
		return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
	},

	unmousewheel: function(fn) {
		return this.unbind("mousewheel", fn);
	}
});
function ft_mouse_wheel_handler(event) {
	var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
	event = jQuery.event.fix(orgEvent);
	event.type = "mousewheel";

	// Old school scrollwheel delta
	if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
	if ( orgEvent.detail	 ) { delta = -orgEvent.detail/3; }

	// New school multidimensional scroll (touchpads) deltas
	deltaY = delta;

	// Gecko
	if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
		deltaY = 0;
		deltaX = -1*delta;
	}

	// Webkit
	if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
	if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }

	// Add event and delta to the front of the arguments
	args.unshift(event, delta, deltaX, deltaY);

	return (jQuery.event.dispatch || jQuery.event.handle).apply(this, args);
}
jQuery.fn.rightClick = function (method)
{
	jQuery(this).bind('contextmenu rightclick', function (e)
	{
		e.preventDefault();
		method();
		return false;
	});
	return this;
};

/**
 * Converts relative urls to absolute
 * @param s (string) - the link to be converted
 * @param l (object) - document.location
 */
jQuery.toAbsURL = function(src, l) {
	if (!src) return src;
	if (!l.base) {
		var base = location.href;
		if (base.indexOf('#') > -1) base = base.split('#')[0];
		if (base.indexOf('?') > -1) base = base.split('?')[0];
		base = base.replace(/\/[^\/]*$/, '');
		l.base = base;
	}
	
	if (src.substring(0,3) == './/') return l.base+src.replace('.//','/');
	if (src.substring(0,2) == '//') return 'http:'+src;
	if (/^\w+:/.test(src)) return src;
	
	var host = l.protocol + '//' + l.host;
	if (src.indexOf('/') == 0) return host + src;
	
	var path = l.base.replace(host, '');

	matches = src.match(/\.\.\//g);
	if (matches) {
	  src = src.substring(matches.length * 3);
	  for (var i = matches.length; i--;) {
		path = path.substring(0, path.lastIndexOf('/'));
	  }
	}
	console.info(host, path, src);
	return host + path + '/' + src;
}

/**
 * Adds the thumbnails for the videos so they can be processed by the backend and also converts the inline videos
 * to their embed code so we can use them
 */
jQuery.fn.parseVideos = function() {
	var self = this;
	//Fix autoplay
	if (window.location.href.indexOf('liveleak.com/') > -1) {
		self.find("object[name*='player_']").each(function() {
			jQuery(this)
				.addClass('ft-video')
				.attr('data-thumb', jQuery("meta[property='og:image']").attr('content').replace('_thumb_','_sf_'));
		});
	}
	
	//http://www.reverbnation.com
	$('#profile_songs_container li').each(function() {
		var artist = $('meta[property="og:video"]').attr('content');
		if (!artist) return;
		artist = artist.split('?')[1].replace('id=','');
		var song_id = $(this).find('[data-song-id]').attr('data-song-id');
		var iframe = '<iframe class="ft-video-iframe" src="http://www.reverbnation.com/widget_code/html_widget/'+artist
						+'?widget_id=50&pwc[design]=default&pwc[background_color]=%23333333&pwc[included_songs]=0'
						+'&pwc[song_ids]='+song_id+'&pwc[photo]=0&pwc[size]=custom"'
						+'width="'+$(this).width()+'" height="76px" frameborder="0" scrolling="no"></iframe>';
		$(this).after(iframe);
		$(this).hide().remove();
	});
	
	//http://vine.co/
	$("video[src*='vines.s3.amazonaws.com/videos/']").each(function() {
		var src = $("meta[property='twitter:player']").attr('content');
		var thumb = $("meta[property='og:image']").attr('content');
		var w = $(this).width();
		var h = $(this).height();
		var iframe = '<iframe width="'+w+'" height="'+h+'" class="ft-video-iframe" frameborder="0" scrolling="no" data-thumb="'+thumb+'" src="'+src+'"></iframe>';
		$(this).after(iframe);
		$(this).hide().remove();
	});
	
	//Vimeo
	$("object[data*='/moogaloop.swf'], embed[src*='vimeo.com/moogaloop.swf']").each(function() {
		if ($(this).parent().is('object')) {
			var $this = $(this).parent();
		} else {
			var $this = $(this);
		}
		//$this.addClass('ft-video');
		var v_id = $this.closest('.player').attr('id').split('_')[1];
		var iframe = '<iframe src="http://player.vimeo.com/video/'+v_id+'" class="ft-video-iframe" width="'+$this.width()+'" height="'+$this.height()+'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		$this.after(iframe);
		$this.hide().remove();
	});
	
	//metacafe
	$("object[data*='s.mcstatic.com']").addClass('ft-video');

	//cosmolearning.com
	if (location.href.indexOf('cosmolearning.com') > -1) {
		var thumb = jQuery("link[rel='image_src']").attr('href').replace('-thumbnail-w100','');
		jQuery('#wsj_fp').addClass('ft-video').attr('data-thumb', thumb);
	}

	//http://www.huffingtonpost.com
	if (location.href.indexOf('huffingtonpost.com') > -1) {
		var thumb = jQuery("meta[property='og:image']").attr('content');
		jQuery('#adaptvDiv0 object').addClass('ft-video').attr('data-thumb', thumb);
	}

	//http://video.forbes.com/recentVideo
	if (location.href.indexOf('video.forbes.com') > -1) {
		var v_id = jQuery('div#image a').attr('href').replace(/^\/+/,"");
		var thumb = jQuery('div#image img').attr('src');
		var iframe = "<iframe class='ft-video-iframe' data-thumb='"+thumb+"' src='http://www.forbes.com/video/embed/embed.html?format=frame&width=672&height=378&video="+v_id+"&mode=render' width='672' height='378' frameborder='0' scrolling='no' marginwidth='0' marginheight='0'></iframe>";
		jQuery('#videoPlayer').html(iframe);
	}

	//http://www.washingtonpost.com/
	if (location.href.indexOf('washingtonpost.com') > -1) {
		self.find("object.OoyalaVideoPlayer").each(function() {
			var $this = jQuery(this);
			var v_id = location.href.replace('_video.html','_inline.html');
			var thumb = jQuery("meta[property='og:image']").attr('content').replace('_90w','_480x270');
			var iframe = '<iframe width="480" height="270" class="ft-video-iframe" frameborder="0" scrolling="no" data-thumb="'+thumb+'" src="'+v_id+'"></iframe>';
			$this.after(iframe);
			$this.hide().remove();
		});
	}

	self.find("iframe[src*='yahoo.com/video']").addClass('ft-video-iframe');

	//eHow
	self.find("object[data*='http://cdn-i.dmdentertainment.com/']").each(function() {
		jQuery(this).addClass('ft-video').attr('data-thumb', jQuery("meta[property='og:image']").attr('content'));
	});

	self.find("object[data*='http://c.brightcove.com/services/viewer/']").each(function() {
		jQuery(this).addClass('ft-video').attr('data-thumb', jQuery("meta[property='og:image']").attr('content'));
	});
	self.find("embed[src*='http://hw-static.worldstarhiphop.com/videos/']").each(function() {
		jQuery(this).addClass('ft-video').attr('data-thumb', jQuery("meta[property='og:image']").attr('content'));
	});
	self.find("object[data*='.yimg.com/']").each(function() {
		var $this = jQuery(this);
		var iframe = '<iframe frameborder="0" width="630" height="354" class="ft-video-iframe" src="'+jQuery("meta[property='og:video']").attr('content')+'"></iframe>';
		$this.after(iframe);
		$this.hide().remove();
	});
	self.find("embed[src*='.dmcdn.net/flash/dmplayerv4/'], object[data*='.dmcdn.net/flash/dmplayerv4/']").each(function() {
		var $this = jQuery(this);
		var v_id = jQuery("meta[property='og:video']").attr('content').match(/\/video\/(.*?)\?/);
		var thumb = jQuery("meta[property='og:image']").attr('content');
		var iframe = '<iframe frameborder="0" width="620" height="352"  class="ft-video-iframe" src="http://www.dailymotion.com/embed/video/'+v_id[1]+'" data-thumb="'+thumb+'"></iframe>';
		$this.after(iframe);
		$this.hide().remove();
	});
	self.find("object[data='http://s.wsj.net/media/swf/VideoPlayerMain.swf']").each(function() {
		//console.info(this);
		var $this = jQuery(this);
		var v_id = jQuery("meta[property='og:url']").attr('content').match(/\/video\/(.*?)\./);
		var thumb = jQuery("meta[property='og:image']").attr('content');
		//console.info($this, v_id);
		var iframe = '<object id="wsj_fp" width="512" height="363" class="ft-video" data-thumb="'+thumb+'">'
						+'<param name="movie" value="http://s.wsj.net/media/swf/VideoPlayerMain.swf"></param>'
						+'<param name="allowFullScreen" value="true"></param>'
						+'<param name="allowscriptaccess" value="always"></param>'
						+'<param name="flashvars" value="videoGUID={'+v_id[1]+'}&playerid=1000&plyMediaEnabled=1&configURL=http://wsj.vo.llnwd.net/o28/players/&autoStart=false" base="http://s.wsj.net/media/swf/"name="flashPlayer"></param>'
						+'<embed src="http://s.wsj.net/media/swf/VideoPlayerMain.swf" bgcolor="#FFFFFF"flashVars="videoGUID={'+v_id[1]+'}&playerid=1000&plyMediaEnabled=1&configURL=http://wsj.vo.llnwd.net/o28/players/&autoStart=false" base="http://s.wsj.net/media/swf/" name="flashPlayer" width="512" height="363" seamlesstabbing="false" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>'
					+'</object>';
		$this.after(iframe);
		$this.hide().remove();
	});
	jQuery("iframe.youtube-player, iframe[src*='youtube.com/']").each(function(){
		var v_id = $(this).attr('src').match(/\/embed\/(.+?)[\/?]/);
		console.info($(this).attr('src'), v_id);
			v_id = v_id ? v_id[1] : '';
		$(this).width($(this).width()).height($(this).height()).addClass('ft-video-iframe').
			attr('data-thumb', 'http://i.ytimg.com/vi/'+v_id+'/0.jpg');
	})
	var youtube_selector = "embed[src*='s.ytimg.com/yt'], #watch-player.html5-player, #movie_player.html5-video-player, #movie_player-html5, embed#movie_player";
	if (navigator.userAgent.indexOf('MSIE') > -1 && window.location.href.indexOf('youtube.com') > -1) {
		youtube_selector += ", object#movie_player";
	}
	self.find(youtube_selector).each(function() {
		var $this = jQuery(this);
		if (jQuery("meta[property='og:url']").length) {
			var v_id = jQuery("meta[property='og:url']").attr('content').match(/\?v=(.+?)(?:&|$)/);
		} else {
			var v_id = [0, jQuery("[data-video-id]").attr('data-video-id')];
		}
		//console.info($this, v_id);
		var thumb = jQuery('link[itemprop=thumbnailUrl]').attr("href");
		var iframe = '<iframe width="'+$this.width()+'" height="'+$this.height()+'" class="youtube_player ft-video-iframe" src="http://www.youtube.com/embed/'+v_id[1]+'?autoplay=1" frameborder="0" allowfullscreen data-thumb=" ' + thumb + ' "></iframe>';
		$this.after(iframe);
		$this.hide().remove();
	});
	//http://www.bing.com/videos/browse
	self.find("object[data*='http://img.widgets.video.s-msn.com/v/']").each(function() {
		var $this = jQuery(this);
		var v_id = $this.find("param[name='flashvars']").attr('value').match(/widgetId=(.*?)_player/);
		if (!v_id && jQuery("a[href*='vid=']").attr('href')) v_id = jQuery("a[href*='vid=']").attr('href').match(/vid=(.*?)[&$]/);
		if (!v_id && jQuery("meta[property='og:video']").length) v_id = jQuery("meta[property='og:video']").attr('content').match(/v=(.*?)&/);
		if (v_id && v_id.length) {
			$this.find("param[name='flashvars']").attr('value')
			$this.after('<IFRAME width="'+$this.width()+'" height="'+$this.height()+'" class="ft-video-iframe" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" src="http://hub.video.msn.com/embed/'+v_id[1]+'/?vars=bGlua292ZXJyaWRlMj1odHRwJTNBJTJGJTJGbXNuLmZveHNwb3J0cy5jb20lMkZ2aWRlbyUzRnZpZGVvaWQlM0QlN0IwJTdEJmJyYW5kPWZveHNwb3J0cyZjb25maWdOYW1lPXN5bmRpY2F0aW9ucGxheWVyJnN5bmRpY2F0aW9uPXRhZyZta3Q9ZW4tdXMmbGlua2JhY2s9aHR0cCUzQSUyRiUyRnd3dy5iaW5nLmNvbSUyRnZpZGVvcyZjb25maWdDc2lkPU1TTlZpZGVvJmZyPXNoYXJlZW1iZWQtc3luZGljYXRpb24%3D"></IFRAME>');
			$this.hide().remove();
		}
	});

	//http://www.collegehumor.com/
	self.find("embed[src*='.collegehumor.cvcdn.com/']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[name='sailthru.image.full']").attr('content');
	  var v_id = window.location.href.match(/video\/(.*?)\//);
	  if (v_id && v_id.length) {
		  var iframe = '<iframe src="http://www.collegehumor.com/e/'+v_id[1]+'" class="ft-video-iframe" data-thumb="'+thumb+'" width="600" height="338" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>';
			$this.after(iframe);
			$this.hide().remove();
	  }
	});

	//http://www.mtv.com/
	self.find("object[data*='media.mtvnservices.com/']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//ESPN
	self.find("object[data*='www.kyte.tv/f/']").each(function() {
	  var $this = jQuery(this);
	  var thumb;
	  if (jQuery(".thumb img").length > 0) {
		thumb = jQuery(".thumb img").attr('src');
	  } else if ( jQuery(".top-story-image img").length > 0 ) {
		thumb = jQuery(".top-story-image img").attr('src');
	  }
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//zoopy.com
	self.find("object[data*='zoopycdn.com/player.swf']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//expotv.com
	self.find("embed[src*='expotv.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//videojug.com/
	self.find("object[data*='views/player/Player.swf']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//wildscreen.tv
	self.find("embed[src*='wildscreen.tv']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery(".profil_pic").find('img').attr('src');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//xtranormal.com
	self.find("#player").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("#moviepic").attr('value');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//wired.com
	self.find("#myPlayer").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery(".bc_selected").find('div').html();
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//ebaumsworld.com
	self.find("#flashContainer").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//sevenload
	self.find("#flashVideoPlayer, object[data*='.sevenload.']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.buzznet.com
	self.find("embed[src*='.buzznet.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.blogtv.com
	self.find("embed[src*='.blogtv.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//blip.tv
	self.find("#video_player_embed, embed[src*='blip.tv']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//veoh.com
	self.find("embed[src*='.veoh.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[name='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.openfilm.com
	self.find("object[name='video_player_content'], object[data*='.openfilm.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[name='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//fotki.com
	self.find("object[data*='.fotki.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[name='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//myspace.com
	self.find("embed[src*='.myspace.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//blinkx.com
	self.find("#player").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//mayomo.com
	self.find("#flowplayer").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//nbc.com
	self.find("object[data*='video.nbcuni.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//ted.com
	self.find("object[data*='ted.com'], embed[src*='video.ted.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//vh1.com
	self.find("embed[src*='vh1.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.worldstarhiphop.com
	self.find("embed[src*='.worldstarhiphop.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.hulu.com
	self.find("embed[src*='.hulu.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//rollingstone
	//http://www.polygon.com/features/2013/5/21/4350930/xbox-one-what-we-know
	self.find("object[data*='player.ooyala.com']").each(function() {
		if ($(this).parent().is('object')) {
			var $this = $(this).parent();
			var w = Math.max($(this).width(), $(this).parent().width());
			var h = Math.max($(this).height(), $(this).parent().height());
		} else {
			var $this = $(this);
			var w = $(this).width();
			var h = $(this).height();
		}
	
	  var thumb;
	  if ($("meta[itemprop='thumbnail']").length > 0) {
		thumb = $("meta[itemprop='thumbnail']").attr('content');
	  } else if ($("meta[property='og:image']").length > 0) {
		thumb = $("meta[property='og:image']").attr('content');
	  } else if ( $(".top-story-image img").length > 0 ) {
		thumb = $(".top-story-image img").attr('src');
	  }
	  
	  var vid = Util.VideoContext.getVideo($this.closest('[data-chorus-video-id]').attr('data-chorus-video-id')).provider_video_id;
	  var iframe = '<iframe src="http://player.ooyala.com/iframe.html#pbid=2ff6d6fff2b2457bb9ea2cfcf77dc25b&ec='+vid+'" data-thumb="'+thumb+'" class="ft-video-iframe" width="'+w+'" height="'+h+'"></iframe>';
	  $this.after(iframe);
	  $this.hide().remove();
	});

	//pitchfork.com
	self.find("object[data*='.ignimgs.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//nbcolympics.com
	self.find("object[data*='/code/Flash/VideoPlayer.swf']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//break.com
	self.find("object[data*='.break.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[name='embed_video_thumb_url']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//cbsnews.com
	self.find("embed[src*='cbsnews_player.swf']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//vbox7.com
	self.find("embed[src*='.vbox7.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  var v_id = thumb.match(/.*\/(.*)..jpg/);
	  var iframe = '<embed type="application/x-shockwave-flash" src="http://i48.vbox7.com/player/ext.swf" width="512" height="439" id="VBOXplayer" name="VBOXplayer" bgcolor="#000000" quality="high" wmode="opaque" allowscriptaccess="always" allowfullscreen="true" flashvars="vid='+v_id[1]+'&amp;autoplay=1&amp;karaokeMode=true&amp;karaokeEnabled=false&amp;nstat=false&amp;playerBufferTime=3&amp;voteEnabled=false&amp;relatedEnabled=false&amp;subsEnabled=false"></embed>';

	  $this.after(iframe);
	  $this.hide().remove();
	});

	//.ordienetworks.com
	self.find("object[data*='.ordienetworks.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.livestrong.com
	self.find("embed[src*='.dmdentertainment.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.nytimes.com
	self.find("embed[src*='.nytimes.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.photobucket.com
	self.find("embed[src*='.photobucket.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("link[rel='image_src']").attr('href');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.godtube.com
	self.find("object[data*='.salemwebnetwork.com']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	//.telly.com
	self.find("object[name*='twitvid']").each(function() {
	  var $this = jQuery(this);
	  var thumb = jQuery("meta[property='og:image']").attr('content');
	  $this.addClass('ft-video').attr('data-thumb', thumb);
	});

	if (location.href.indexOf('imdb.com') > -1) {
		jQuery("iframe[src*='/playlist/']").addClass('ft-video-iframe').attr('data-thumb', jQuery("meta[property='og:image']").attr('content'));
	}

	//aniboom
	self.find("embed[src*='aniboom.com/']").each(function() {
		var $this = jQuery(this);
		var v_id = jQuery("[rel='video_src']").attr('href').match(/aniboom.com\/e\/(.*)/);
		var thumb = jQuery("[rel='videothumbnail']").attr('href');
		var iframe = '<object id="aniboomPlayercs3_fp" class="ft-video" width="640" height="360" align="middle" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" data-thumb="'+thumb+'" style="left: 0px;">'
				  + '<param value="always" name="allowScriptAccess">'
				  + '<param value="true" name="allowFullScreen">'
				  + '<param value="http://www.aniboom.com/Media/Flash/aniboomPlayercs3.swf?movieID='+ v_id[1] + '&showRelated=false" name="movie">'
				  + '<param value="high" name="quality">'
				  + '<param value="noscale" name="scale">'
				  + '<param value="lt" name="salign">'
				  + '<param value="#000000" name="bgcolor">'
				  + '<param value="transparent" name="wmode">'
				  + '<embed width="640" height="360" data-thumb="'+thumb+'" align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" name="aniboomPlayercs3" bgcolor="#000000" salign="lt" scale="noscale" wmode="opaque" quality="high" src="http://www.aniboom.com/Media/Flash/aniboomPlayercs3.swf?movieID='+v_id[1]+'&showRelated=false" style="">'
			   + '</object>';
		$this.after(iframe);
		$this.hide().remove();
	});

	//schooltube
	self.find(".sv_flash_object, video[poster*='.schooltubecdn.com']").each(function() {
		var $this = jQuery(this);
		var thumb = jQuery(".sublime_video_content").find('img:first').attr('src');
		$this.addClass('ft-video').attr('data-thumb', thumb);
	});
	//justin tv
	self.find("object[data*='justin.tv/widgets/'], object[data*='jtvnw.net/widgets/']").each(function() {
		var $this = jQuery(this);
		var params = $this.find('param[name=flashvars]').val().split('&');
		var params_obj = {}, params_temp;
		for (var i=0;i<params.length;i++) {
			params_temp = params[i].split('=');
			params_obj[params_temp[0]] = params_temp[1];
		}
		var iframe = '<object type="application/x-shockwave-flash" height="'+$this.height()+'" width="'+$this.width()+'" id="jtv_player_flash" data="http://www.justin.tv/widgets/jtv_player.swf?channel='+params_obj.channel+'" bgcolor="#000000">'
					+'<param name="movie" value="http://www.justin.tv/widgets/jtv_player.swf" />'
					+'<param name="allowFullScreen" value="true" />'
					+'<param name="flashvars" value="auto_play=true&channel='+params_obj.channel+'&start_volume=50&watermark_position=top_right" /></object>';
		$this.after(iframe);
		$this.hide().remove();
	});
}


if (typeof define != 'undefined') {
	define(function() {

	});
}
