/**
 *  The UI for the clipboard external and internal scraper. This code is injected in the remote site
 *  @see - clipboard_ui_commons.js - holds the functions used in the logic of  this file
 */

$.fn.clipboard_ui = function(options) {
	var $ = options['jQuery'];
	var quickClipIco = '<a href="javascript:;" class="fd-quick-clip-ico" style="display:none"></a>';
	var scroll_x, scroll_y, maxelements = 4000;
	var scrollpage = false;
	var self = this;
	var clip_overlay = null;
	var clip_overlay1 = null;
	var page_overlay = null;
	var draggable = null;
	var content_timer = null;
	var font_faces;
	this.paused = true;

	var bookmark_page_border = 7;
	var popup_content_padding = 10;

	options.draggable_parent = options.draggable_parent ? options.draggable_parent : self;
	this.options = options;
	this.popupCSS = {};

	function supportsRGBA() {
		var scriptElement = document.getElementsByTagName('script')[0];
		var prevColor = scriptElement.style.color;
		try {
			scriptElement.style.color = 'rgba(1,5,13,0.44)';
		} catch(e) { console.error(e); }
		var result = scriptElement.style.color != prevColor;
		scriptElement.style.color = prevColor;
		return result;
	}

	/*
	 * Starts the UI functionality
	 * @see - self.stop
	 */
	self.start = function() {
		self.paused = false;
		self.find('#fd-iframe-overlay').show();
		$("#fandrop_div").attr('style','').removeClass('login');
		self.css('overflow','').find('#scraping_overlay').hide();
		if (!supportsRGBA()) {
			console.info('Good luck with that dinosaur!');
			self.find('#clip_overlay').css('opacity','0.3'); //IE7 fix
		} else {
			$('.fd-remote-iframe.visible').show();//.css('opacity',1);
		}
		self.find('#clip_overlay').removeClass('clipboard-popup').removeClass('fixed').html('')	//.draggable('enable');
		$('html, body').css('overflow','');
		//Commented out because of http://www.weavetexfashion.com/baby-frocks.html
		//.css('margin-left':'').css('margin-top',''); //IE8 fix

		if (scrollpage == 2) {
			//self.paused = true;
			self.find('#clip_overlay, #fd-iframe-overlay, a.fd-quick-clip-ico').hide();
			if (self.clip_overlay1) self.clip_overlay1.hide();
		} else {
			if (self.pointer) self.pointer.show();
			try {
				self.find('a.fd-quick-clip-ico').show();
			} catch (e) { console.error(e); } //"NOT_SUPPORTED_ERR: DOM Exception 9"
		}
		
		window.ft_mouseMoveHandler(); //Select default element

	}

	/**
	 * Pauses the bookmarklet when the close button in the bar is clicked
	 * @see - self.start - to start the UI again.
	 */
	self.stop = function(hide_controls) {
		//console.info('stop', hide_controls);
		self.paused = true;
		if (self.pointer) self.pointer.hide();
		var $doc = $(document);
		self.find('a.fd-quick-clip-ico, div#fd-iframe-overlay').hide();
		self.clip_overlay.css('opacity','1'); //IE8 fix
		if (self.clip_overlay1) self.clip_overlay1.css('opacity','1'); //IE8 fix
		if (hide_controls) {
			//console.info(self.clip_overlay);
			/*if (navigator.userAgent.indexOf('MSIE') > -1) {	  //Yes IE just wants to be SLOW!
				window.setTimeout(function() {
					self.clip_overlay.hide();
					if (self.clip_overlay1) self.clip_overlay1.hide();
				},1);
			} else {*/
				self.clip_overlay.hide();
				if (self.clip_overlay1) self.clip_overlay1.hide();
			//}
		}
		return this;
	}

	/**
	 * Changes from text to html mode
	 * @deprecated
	 */
	self.set_clip_mode = function(mode) {
		//console.info('set mode: ', mode);
		mode = parseInt(mode);
		scrollpage = mode > 2 ? 0 : mode;
		if (mode == 2) {
			//self.stop(true);
			if (self.pointer) self.pointer.hide();
			self.find('#clip_overlay, #fd-iframe-overlay, a.fd-quick-clip-ico').hide();
			if (self.clip_overlay1) self.clip_overlay1.hide();
			self.page_overlay.hide();
			self.addIframeOverlays({'opacity':0.8});
			return false;
		} else {
			if (self.pointer) self.pointer.show();
			self.page_overlay.show();
			self.clip_overlay.unbind('mouseout');
			self.removeIframeOverlays({'opacity':0.8});
			self.start();
		}
		return true;
	}

	var paused1 = false;
	window.ft_mouseMoveHandler = function (e) {
		if (!e) { //Select the default element
			var els = $("body > *:not(script, link, #fd_pointer, #clip_overlay_warning, #clip_overlay, .clipboard-popup, #fandrop_div, #scraping_overlay, #fd-iframe-overlay, #fd-pointer)");
			if (els.length == 1) {
				e = {
						'clientX': els.offset().left + els.width() / 2,
						'clientY': els.offset().top + els.height() / 2,
						'target': els[0]
				}
			} else {
				e = {
						'clientX': $(window).width() / 2,
						'clientY': $(window).height() / 2,
						'target': window
				}
			}			
		}
		if (self.pointer) self.pointer.css('left', e.clientX + 25).css('top', e.clientY);
		if (self.paused) return;
		if (scrollpage == 2) return;
		var $target = $(e.target);

		if ($target.closest('a.fd-quick-clip-ico').length) {
			var el = $target.closest('a.fd-quick-clip-ico').data('image');
			if (el && self.clip_overlay.data('selected') != el) {
				currentstack = Array();
				self.clip_overlay.data('selected', make_selection(el));
			}
			return;
		}

		if (paused1) return;
		if ($(this).parents('#fandrop_div').length) return;
		var $this = $(this);
		paused1 = true;
		window.setTimeout(function() {paused1 = false}, 200);

		if (scroll_x > -1 && scroll_y > -1) {
			xdiff = e.pageX - scroll_x;
			ydiff = e.pageY - scroll_y;
			var distance = Math.pow((xdiff * xdiff + ydiff * ydiff), 0.5);
			if (distance < 100) {
				return;
			} else {
				scroll_x = -1;
				scroll_y = -1;
			}
		}

		self.clip_overlay.hide();
		self.page_overlay.hide();
		/*if (navigator.userAgent.indexOf('MSIE') > -1 && typeof document.body.style.opacity == 'undefined' && self.clip_overlay1) {
			console.info('IE8 fix 2');
			self.draggable.hide();
			window.setTimeout(function() {  //for IE8 internal only
				window.ft_mouseMoveHandler_after(e);
			},1);
		} else {*/
			window.ft_mouseMoveHandler_after(e);
		//}
	};
	window.ft_mouseMoveHandler_after = function (e) {
		var el = self.elementFromPoint(e.clientX, e.clientY);
		//TO-DO these 2 are temporary the code has to search for absolute positioned elements inside;
		if (!el) {
			el = $('body *:visible:first')[0];
		}
		
		if(window.location.href.indexOf('allegro.pl') > -1 && $(el).closest('#galContainer').length) {

		} else if (window.location.href.indexOf('picturepangea.com') > -1 && $(el).attr('id') == 'content') {
			$('body').css('background-color','');
			el = $('#ss-background').css('background-color','')[0];
		}else if (window.location.href.indexOf('tasmanadventurecruises.com.au') > -1) {
			if($(el).attr('id') == 'TemplateContent') {
				el = $(el).parent()[0];
				$(el).height(920);
			}
		} else if (window.location.href.indexOf('slideshare.net/') > -1 && $(el).closest('.playerWrapper').length) {
			el = $(el).closest('.playerWrapper')[0];
		} else if (window.location.href.indexOf('lexus.com/') > -1 && $(el).closest('#vcArea').length) {
			el = $(el).closest('#vcArea')[0];
		} else if (window.location.href.indexOf('maps.yahoo.com') > -1 && $(el).closest('#ymap').length) {
			el = $(el).closest('#ymap')[0];
		} else if (window.location.href.indexOf('.yahoo.com') > -1 && $(el).closest('div.yui-carousel-content').length) {
			el = $(el).closest('div.yui-carousel-content')[0];
		} else if (window.location.href.indexOf('maps.google.') > -1 && $(el).closest('#map').length) {
			el = $(el).closest('#map')[0];
		} else if (window.location.href.indexOf('.google.') > -1 && $(el).closest('#lga').length) {
			el = $(el).closest('#lga').width(540)[0];
		} else if (window.location.href.indexOf('tshirthell.com') > -1 && $(el).closest('#customize_shirt_image').length) {
			el = $(el).closest('#customize_shirt_image')[0];
		} else if (window.location.href.indexOf('.strfri.com') > -1 && $(el).attr('id') == 'content') {
			el = $(el).parent();
		} else if (window.location.href.indexOf('.vipboxsports.eu') > -1 && $(el).closest('h1.ui-accordion-header').length) {
			el = $(el).closest('h1.ui-accordion-header')[0];
		} else if (window.location.href.indexOf('.telegraph.co.uk') > -1 && $(el).closest('div.nextPrevLayer').length) {
			el = $(el).closest('div.nextPrevLayer')[0];
		} else if ($(el).is('iframe') && el.src.indexOf('google.com/logos') > -1) {
			//el = el;
		} else if (window.location.href.indexOf('http://uk.eonline.com/') > -1 && $(el).closest('.photo-nav-container').length) {
			el = $(el).closest('.photo-nav-container').next();
		} else if (window.location.href.indexOf('vichy.com/') > -1 && $(el).is('blockquote')) {
			el = el.parentNode;
		} else if (window.location.href.indexOf('ibnlive.in.com') > -1 && $(el).hasClass('slides1')) {
			el = el.parentNode;
		} else if (el.tagName.toLowerCase() == 'shape') {
			el = $(el).closest(':not(shape)')[0];
			console.info('position: ',el.style.listStylePosition);
			if ($(el).attr('style').indexOf('position: absolute') > -1) el = el.parentNode;
		} else if (el
					&& (
							(el.tagName.toLowerCase() == 'iframe' && el.className.indexOf('ft-video-iframe') == -1)
							|| el.tagName.toLowerCase() == 'frame'
							|| (el.tagName == 'OBJECT' && el.type == 'text/html')
						)
					&& window.location.href.indexOf('scientificamerican.com') == -1
		) {
			var iframe = el;
			try {
				var doc = iframe.contentDocument || iframe.contentWindow.document;
				if (doc) {
					el = doc.elementFromPoint(e.pageX - $(iframe).offset().left, e.pageY - $(iframe).offset().top) || iframe;
					$(el).data('parents', [{iframe: iframe, doc: doc}]);
				}
			} catch (e) {
				$(el).data('parents', []);
			}
		} else {
			if(el.tagName.toLowerCase() == 'a' && $.trim($(el).html()) == "") {
				el = el.parentNode;
			}
			while ($(el).isAbsolute()){
				console.info('getting absolute element');
				el = el.parentNode;
			}

			$(el).data('parents', []);
		}
		self.clip_overlay.show();
		self.page_overlay.show();
		self.draggable.show();
		if (e.clientY < 20 || e.clientX < 20 || (e.clientX < 50 && e.clientY < 50) || e.clientY > $(window).height()- 20 || e.clientX > $(window).width()-20) {
			self.find('#clip_overlay').data('selected', null);
			self.draggable.css({top: 0, left: 0, width: self.width()-bookmark_page_border*2, height: '100%'}).addClass('bookmark-page');
			if (self.pointer) self.pointer.html('Bookmark page').addClass('bookmark-page');
			if (self.draggable1) {
				self.draggable1.css({top: self.scrollTop(), left: 0, width: self.width()-bookmark_page_border*2, height: '100%'}).addClass('bookmark-page');
			}
		} else if (el && self.find('#clip_overlay').data('selected') != el) {
			if ($(el).find('*:visible').size() <= maxelements) {
				currentstack = Array();
				self.find('#clip_overlay').data('selected', make_selection(el));
				self.draggable.removeClass('bookmark-page');
				if (self.pointer) self.pointer.removeClass('bookmark-page');
				//http://dev.fantoon.com:8100/browse/FD-1272
				self.css('line-height',1.5);
				try {
					if (self.pointer) self.pointer.html(options.lang.point_and_click)
				} catch (e) {
					console.error(e); 
					if (self.pointer) self.pointer.html(options.lang.point_and_click.replace('&uarr;','up').replace('&darr;','down'));
				}
			}
		}
	}

	/**
	 * Adds supposed type to the selected content and other data which will be sent to bar iframe
	 * @see bar.js
	 */
	function add(el, data_el) {
		if (!data_el) data_el = el;
		if (data_el.find('img').length == 1 && !data_el.text() && data_el.find('div').length < 2) {
			el.data({
					url: data_el.find('img').toAbsURL().attr('src'),
					content: self.parent().find("meta[name='description']").attr('content'),
					type: 'image'
				});
		//removed text length because of http://www.theworkcontinues.org/page.asp?id=1789
		} else if (data_el.find("video, embed, iframe.ft-video-iframe, object").length >= 1 && !$.trim(data_el.text()).length) {
			data_el.find('embed, object').removeAttr('style');
			data_el.find("object").each(function() {
				var $object = $(this).removeAttr('id');
				$object
					.attr('width', $object.width())
					.attr('height', $object.height())
					.find('embed')
						.attr('style','')
						.attr('width', $object.width())
						.attr('height', $object.height());
				if (!$object.attr('data')) {
					if ($object.find('embed').attr('src')) {
						$object.attr('data', $object.find('embed').attr('src'));
					} else {
						$object.attr('data', $object.find("param[name='movie']").attr('value'))
					}
				}
			});
			data_el.find("embed").each(function() {
				var $embed = $(this);
				if (!$embed.closest('object').length) {
					$embed/*.attr('width', $embed.width())*/.attr('height',$embed.height());
				}
			});
			
			//bobef: #FD-1784
			//flash objects are copied without their parent, in the same
			//time they are forced to be transparent, so the background is lost
			//get the background of the parent and apply it to the flash object
			var tobject = data_el.find('video, embed, iframe[allowTransparency], object');
			console.info(tobject, tobject.parent());
			tobject.setBackground( tobject.parent(), function() {} );
			//end of #FD-1784

			var data = data_el.find('video, embed, iframe, object').parent().html();
			if (location.href.indexOf('http://www.ted.com/') > -1) {
				data = data.replace('<object','<object thumb="'+$("meta[property='og:image']").attr('content')+'"');
			}
			if (navigator.userAgent.indexOf('MSIE') > -1) {
				//http://www.liveleak.com/view?i=697_1340301975
				data = data.replace(/<object/gi,'<object type="application/x-shockwave-flash"')
							.replace(/classid=.*? /gi,'')
							.replace(/classid=".*?"/gi,'')
			}
			el.data({
					content: data_el.text(),
					data: data,
					type: 'embed'
				});
		} else {//if (el.find('*').length > 2 || el.find('iframe').length ) {
			var el1 = data_el.clone();

				if (navigator.userAgent.indexOf('MSIE') > -1) {
					el1.find('object').each(function() {
						if (!$(this).attr('type')) $(this).attr('type', 'application/x-shockwave-flash');
						$(this).removeAttr('classid');
					})
				}
			var html = el1.html();

			window.setTimeout(function() {
				options.on_set_content.call(this, html);
			}, 100);

			el.data({
				//content: html,
				type: 'html'
			});
		}
		el.data('title', self[0].ownerDocument.title);
		el.data('link', self[0].ownerDocument.location.href);
		el.data('width', data_el.width());
		el.data('height', data_el.height());
	}
	
	/*
	 * Adds ft-absolute class to absolute elements to find them later in the get_content function faster
	 * @see get_content()
	 */
	self.parse_absolute_elements = function() {
		//:visible is added bc of: http://glo.msn.com/relationships/what-your-daughter-in-law-wont-tell-you-7581.gallery
		self.find('*:not(.ft-absolute):visible').each(function() {
			if ($(this).closest('#fandrop_div, #success_popup, #save_link, #clip_overlay').length) return;
			if ($(this).css('position') == 'absolute') {
				//http://sea.battle.net/en/
				try { $(this).addClass('ft-absolute'); } catch (e) { this.className += ' ft-absolute' }
			} else if ($(this).css('position') == 'fixed') {
				$(this).addClass('ft-fixed');
			}
		});
	}
	
	self.init_image_mode = function() {
		var $container = $('#ft_image_mode');
		var $template = $('#ft_image_mode script');
		var $list = $('#ft_image_mode ul');
		var added_imgs = {};
		
		$list.html('');
	
		$('img').each(function() {
			if (this.width < 100 || this.height < 50 || typeof added_imgs[this.src] != 'undefined') return;
			
			added_imgs[this.src] = true;
			var $this = $(this);
			var new_item = $('<div>'+$template.html()+'</div>');
				new_item.find('img').attr('src', this.src).load(function() {
					if (this.width < 100 || this.height < 50) {
						$(this).closest('li').remove();
					};
				});
				new_item.find('a')
					.click(function() {
						return false;
					})
					.mouseup(function(e) {
						self.clip_overlay.data('selected', make_selection($this, false, true));
						self.paused = false;
						self.draggable.trigger(e);
					})
			$list.append(new_item.first().find('li'));
		});
		
		$container.click(function() {
			if (self.options.design_ugc) return false;
			if (self.clip_overlay.is(':visible')) {
				self.clip_overlay.hide();
			} else {
				$(this).hide();
				self.start();
			}
		});
	}
	
	self.init_video_mode = function() {
		var $container = $('#ft_video_mode');
		var $template = $('#ft_video_mode script');
		var $list = $('#ft_video_mode ul');
		
		$list.html('');
		console.info("INIT VIDEO MODE", $('object.ft-video, embed.ft-video, iframe.ft-video-iframe'));
		
		$('object.ft-video, embed.ft-video, iframe.ft-video-iframe').each(function() {
			var $this = $(this);
			console.info($this);
			var new_item = $('<div>'+$template.html()+'</div>');
				new_item.find('img').attr('src', $this.attr('data-thumb'));
				new_item.find('a')
					.click(function() {
						return false;
					})
					.mouseup(function(e) {
						self.clip_overlay.data('selected', make_selection($this, false, true));
						self.paused = false;
						self.draggable.trigger(e);
					})
			$list.append(new_item.first().find('li'));
		});
		
		$container.click(function() {
			if (self.options.design_ugc) return false;
			if (self.clip_overlay.is(':visible')) {
				self.clip_overlay.hide();
			} else {
				$(this).hide();
				self.start();
			}
		});
	}

	/**
	 * INIT
	 */
	function init() {
		//GEt absolute elements
		self.parse_absolute_elements();
		font_faces = self.getFontFaces();

		//Fix flash inside iframe
		self.find("iframe[src*='nbc.com/assets'], img[src*='getaddictedto.com/']").each(function() {
			this.src = options.baseUrl+'/external/index.php?url='+escape(this.src);
		});

		//Replaces videos with their embed code and adds thumbnail data
		self.parseVideos();

		//Fix videos which overflow the popup
		self.find('iframe:not(#scraping_overlay_iframe, #bookmark-page, #success-iframe)').each(function() {
			if (this.src.indexOf('http://www.youtube.com/embed/') > -1) {
				this.src += (this.src.indexOf('?') != -1 ? '&' : '?') + 'wmode=opaque';
			} else {
				$(this).addClass('fd-remote-iframe');
				if ($(this).is(':visible')) {
					$(this).addClass('visible');
				}
			}
		});
		
		//changed opaque to transparent http://www.disney.pl/superbia/index.jsp
		self.find('embed').each(function() {
			var self = $(this).attr('wmode', 'transparent').hide();
			if (!self.attr('base')) {
				self.attr('base', location.href.replace(location.hash ,''));
			} else if (self.attr('base').substr(0,1) == '/') {
				self.attr('base', location.protocol+'//'+location.host+self.attr('base'));
			}
			//http://www.longines.com/swf/3d-vision-player.swf?l_SequencePath=/documents/3d-watch/hydroconquest.swf
			self.attr('width', self.width()).attr('height', self.height());
			window.setTimeout(function() { self.show(); }, 100);
		});

		self.find('applet').each(function() {
			var self = $(this).attr('wmode', 'transparent').hide(),
			loc = window.location.href.replace(window.location.hash ,''),
			dir = loc.substring(0, loc.lastIndexOf('/'));
			self.find("param[name=wmode]").remove();
			var param = $(document.createElement('param')).attr('name','wmode').attr('value','transparent');
			self.append(param);
			if (!self.attr('codebase')) self.attr('codebase', dir);
			window.setTimeout(function() { self.show(); }, 100);
		});
		
		self.find('object').each(function() {
			if (this.name == 'cn_video_player') return; //cartoonnetwork.com fix
			var $this = $(this);
			console.info('object', $this.attr('id'));
			var left = $this.offset().left - $this.parent().offset().left;
			//console.info($this.parent(), $this.parent().width(), $this.parent().height());
			//Commeted out because of - http://www.jackfroot.com/2011/07/far-away-music-video-by-tyga-feat-chris-richardson/
			//$this.parent().width($this.parent().width());
			//if ($this.parent().height()) $this.parent().height($this.parent().height());

			var w = $this.width();
			var h = !isNaN(this.height) ? this.height : $this.height();
			$this.attr('width', w).attr('height', h);
			$this.find("param[name=wmode]").remove();
			var param = $(document.createElement('param')).attr('name','wmode').attr('value','transparent');
			$this.append(param);
			if (!$this.find("param[name=base]").length) {
				var param = $(document.createElement('param')).attr('name','base').attr('value',location.href.replace(location.hash ,''));
				$this.prepend(param);
			}
			var temp_data = $this.attr('data'); $this.attr('data',''); //need to refresh bc of FF
			window.setTimeout(function() {
				$this.attr('data', temp_data);
			}, 10);
		});
		
		if (navigator.userAgent.indexOf('MSIE') > -1) {   //I wish IE devs to burn in hell!!!!!!!!!!!!!1
			$('object').each(function() {
				var html = $(this).outerHTML().replace('"Window"','"transparent"');
				$(this).before(html);
				$(this).remove();
			});
		}
		//end video fix;
		
		//widgets
		if (document.location.href.indexOf('http://www.flickr.com/') > -1 && document.location.href.indexOf('/show') > -1) {
			var page_show_url = encodeURI(document.location.pathname);
			var tags = document.location.href.match(/\/tags\/(.*?)\//);
			var iframe = '<object width="900" height="640">'
							+'<param name="flashvars" value="offsite=true&lang=en-us&page_show_url='+page_show_url+'&page_show_back_url=&tags='+(tags ? tags[1] : '')+'&sort=interestingness-desc&jump_to=&start_index="></param>'
							+'<param name="movie" value="http://www.flickr.com/apps/slideshow/show.swf?v=109615"></param>'
							+'<param name="allowFullScreen" value="true"></param>'
							+'<embed type="application/x-shockwave-flash" width="900" height="640" src="http://www.flickr.com/apps/slideshow/show.swf?v=109615" allowFullScreen="true" '
									+'flashvars="offsite=true&lang=en-us&page_show_url='+page_show_url+'&page_show_back_url=&tags='+(tags ? tags[0] : '')+'&sort=interestingness-desc&jump_to=&start_index=">'
							+'</embed>';
						'</object>'
			$('#swfdiv').html(iframe);
		}

		if (location.href.indexOf('google.com/finance') > -1 && $('.gf-chart-linktochart-url').length) {
			var iframe_data =  $('.gf-chart-linktochart-url').val().match(/q=(.*?)&/gi)[0].replace('q=','').replace('&','').split(',');
			var chart_data = {'symbol': [] }
			for (var i=0; i<iframe_data.length; i++) {
				chart_data['symbol'].push(iframe_data[i].split(':')[1]);
			}
			chart_data['symbol'].join(',');
			var iframe_src = 'http://www-igprev-opensocial.googleusercontent.com/gadgets/ifr?exp_rpc_js=1&exp_track_js=1&url=http%3A%2F%2Fwww.google.com%2Fig%2Fmodules%2Ffinance_chart.xml&container=igprev&view=default&lang=bg&country=US&sanitize=0&v=696476b20b0cec90&parent=http://www.google.com&libs=core:core.io:core.iglegacy:auth-refresh&synd=igprev&view=default#rpctoken=-1180209110&ifpctok=-1180209110&up_displayExtendedHours=false&up_displaySplits=true&up_displayVolume=true&up_defaultZoomDays=3&up_finance_symbol='+chart_data['symbol']+'&up_stockSymbol='+chart_data['symbol']+'&up_displayDividends=true';
			var w = $('#chart_anchor').width();
			var h = $('#chart_anchor embed').height();
			$('#chart_anchor').html('<iframe width="'+w+'" height="'+h+'" frameborder=0 scrolling="no" src="'+iframe_src+'"></iframe>')
		} else if (document.location.href.indexOf('slideshare.net') > -1) {
			var source = $('.playerWrapper');
			var iframe = '<iframe src="'+$("meta[name='twitter:player']").attr('value')+'" width="'+source.width()+'" height="'+source.height()+'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px" allowfullscreen></iframe>';
			source.html(iframe);
		}
		//End widgets
		
		$('body').findSlideshows();
		
		//Site protections
		$(document).unbind('keyup keydown keypress'); //twitter fix
		if (location.href.indexOf('maps.yahoo.com') > -1) {
			$(document).keydown(function(e) { e.preventDefault(); return true; }); //maps.yahoo.com
		}
		$('body').css('position','inherit'); //http://www.ehow.com/how_5398261_make-own-beer-pong-tables.html
		$('body').css('height','auto'); //http://www.homes.com/
		$('body').css('min-height','auto'); //http://www.homes.com/
		$('body > table').css('height','auto'); //http://www.homes.com/
		$('#fmts-lb-cn-iframe').remove(); //http://www.huffingtonpost.com/2012/04/30/lindsay-lohan-washroom-attendent-100-dollars-white-house-dinner_n_1464762.html
		$('iframe.ywp-iframe-shim').remove(); //http://www.hipsterrunoff.com/altreport/2012/04/thom-yorke-ashamed-his-ponytail-hides-his-thommytail-cover-rolling-stone.html
		$('div.anti-download').remove(); //http://www.fotopedia.com/wiki/Antique_car#!/items/pixelterror-C91sevqa9zo
		$('div#photo-drag-proxy').remove(); //http://www.flickr.com/photos/josephferris76/6282592560/in/set-72157627450542765/
		$('#pictureGalleryGeneratedId_0 .nav').hide(); //http://www.bbc.co.uk/news/in-pictures-19076154
		$('#tags_container').remove(); //http://lookbook.nu/look/3757521-71612
		$('div.clearer').remove(); //http://www.27estore.com/grey-oak-interior-doors-4242
		if (window.location.href.indexOf('usmagazine.com/') > -1) $('#wrap .mousetrap').remove();
		if (window.location.href.indexOf('lexus.com/') > -1) $('#mainImageHolder2').remove();
		if (window.location.href.indexOf('archdaily.com/') > -1) $('.winners-overlay').remove();
		if (window.location.href.indexOf('facebook.com/') > -1) $('.coverBorder').hide();
		if (window.location.href.indexOf('sevenload.com/') > -1) $('.overlayLink, .hoverBG').hide();
		if (window.location.href.indexOf('target.com/') > -1) $('img.pimg').css('z-index','1');
		if (window.location.href.indexOf('animationlibrary.com/') > -1) {
			$('#searchBoxDiv, #links, #authen').css('z-index',100);
			$('table tr:first td').width(946);
		}
		if (window.location.href.indexOf('checkers.com/') > -1) {
			$('#footer_cont').css('position','static');
			$('#stickers').css('position','static');
		}
		
		//Ads
		$("[src*='fastclick.net/']").remove();
		if (window.location.href.indexOf('mapsofindia.com/') > -1) {
			$('#close_sld, .pop_up1_alt, #slider_main').remove();
		}
		if (window.location.href.indexOf('n4bb.com/') > -1) {
			$('.getsocial').remove();
		}
		$("[id*='meebo']").remove();
		//:after selector fix
		if (window.location.href.indexOf('indiatimes.com/') > -1) {
			$('div.container').css('width','100%');
			$('div.clr').css('clear','both');
			$('#leftNav .inner').css({'position':'absolute','top':'auto'});
		}
		
		//Other site specific
		if (window.location.href.indexOf('caribisles.com/') > -1) $('#resortPreview > div').css('overflow','hidden');
		if (window.location.href.indexOf('bbc.co.uk/') > -1) $('td > img[height=1], td > img[height=3]').css('display','block');
		//Proxies
		if (window.location.href.indexOf('1000goals.com/') > -1) {
			$('img').each(function() { this.src = options.baseUrl+'external/index.php?url='+this.src; });
		}
		if (window.location.href.indexOf('splashnology.com/') > -1) {
			$('p > img, img.scale-with-grid').each(function() { this.src = options.baseUrl+'external/index.php?url='+this.src; });
		}
		if (window.location.href.indexOf('fanpop.com/') > -1 || window.location.href.indexOf('.ourvanity.com/') > -1 || window.location.href.indexOf('.zedo.com') > -1 ) {
			$('img').each(function() { this.src = options.baseUrl+'external/index.php?url='+this.src; });
		}
		if (window.location.href.indexOf('myfunnyworld.net/') > -1) {
			$('#chart_anchor embed').each(function() { this.src = options.baseUrl+'external/index.php?url='+escape(this.src); });
		}
		if (window.location.href.indexOf('http://bgflash.com/') > -1) {
			$('#box embed').each(function() { this.src = options.baseUrl+'external/index.php?url='+escape(this.src); });
		}
		if (window.location.href.indexOf('imdb.com/') > -1) {
			$('a > img').each(function() { this.src = options.baseUrl+'external/index.php?url='+escape(this.src); });
		}
		if (window.location.href.indexOf('fashionmagazine.com/') > -1) {
			$('a > img').each(function() { this.src = options.baseUrl+'external/index.php?url='+escape(this.src); });
		}
		if (window.location.href.indexOf('cliffchiang.com/') > -1) {
			$('p > img').each(function() { this.src = options.baseUrl+'external/index.php?url='+escape(this.src); });
		}
		//end site protections
		self.append('<div id="fd-iframe-overlay">'
				+'<div class="fd-top"></div>'
				+'<div class="fd-left"></div>'
				+'<div class="fd-right"></div>'
				+'<div class="fd-bottom"></div>'
			+'</div>');
		self.append('<div id="clip_overlay"></div>'
					+'<div id="clip_overlay_warning" style="display:none">stop! you are too greedy, try select less stuff man</div>'
					);
		
		if (!self.options.design_ugc) {
			self.append('<div id="fd-pointer"></div>');//.html(options.lang.point_and_click);

			self.pointer = self.find('#fd-pointer');
			
			self.pointer.bind('mouseenter', function() {
				$(this).hide();
			}).bind('mouseleave', function() {
				 $(this).show();
			});
		}
		
		
		self.draggable = self.find('#clip_overlay');
		self.clip_overlay = self.find('#clip_overlay');
		self.page_overlay = self.find('#fd-iframe-overlay');

		if (self != options.draggable_parent) {
			console.info('ADD SECONDARY', self, options.draggable_parent, self.find('#clip_overlay'));
			self.clip_overlay1 = self.find('#clip_overlay').clone(true).addClass('clone');
			self.clip_overlay1.appendTo(options.draggable_parent);
			self.draggable = self.clip_overlay1;
			//TO-DO: This is a hack for internal scraper some other js is preventing these events to execute;
			$(document).unbind('mouseup keypress mousedown keydown');
		} else {
			//Fix other broken sites
			//Tumblr crashes in chrome tries to load all existant pages - so we are allowing 100 reuqests (an usual site doesnt need more).
			max_ajax_requests = 100;
			if (location.href.indexOf('psh-imonjusticesplaylist.tumblr.com/') > -1) max_ajax_requests = 1
			if (location.href.indexOf('facebook.com/') > -1) max_ajax_requests = 1
			var _open = XMLHttpRequest.prototype.open;
			window.XMLHttpRequest.prototype.open = function(type, url, async) {
				if (!max_ajax_requests--) return false;
				_open.call(this, type, url, async);
			}
			//End site specific fixes
		}

		self.page_overlay.bind('mousemove mouseover click', ft_mouseMoveHandler);
		if (self.pointer) {
			$(document).on('mouseenter','#fandrop_div', function() {self.pointer.hide();});
			$(document).on('mouseleave','#fandrop_div', function() { if (!self.paused && scrollpage != 2) self.pointer.show();});
		}
		
		self.draggable.mousemove(function(e) {
			if (typeof self.get_offset == 'function') { //internal scraper
				e.clientY += - self.get_offset().top ;//- (self.scrollTop() ? self.scrollTop() : self.parent().scrollTop());
			}
			ft_mouseMoveHandler(e);
		});

		//Add quick cilp icons to the media
		self.find("img, video, embed, object, iframe.ft-video-iframe").each(function() {
			var $this = $(this);
			if (this.tagName == 'IMG' && (this.src.indexOf('google.com/vt/') > -1 || this.src.indexOf('maps.gstatic.com') > -1)) return;
			if (this.tagName == 'IMG' && (this.src.indexOf('lbs.ovi.com') > -1) ) return;
			if ($this.closest('#fandrop_div').length) return;
			if ($this.width() < 32 || $this.height < 32 || !$this.offset().left || !$this.offset().top) return;
			var ico = $(quickClipIco)
						.data('image', $this)
						.bind('mousedown', function(e) {
							//console.info('mousedown', e, self.draggable);
							self.clip_overlay.data('selected', make_selection($this, false, true));
							self.draggable.trigger(e);
						})
						.bind('mouseup', function(e) {
							//console.info('mouseup', self.draggable);
							self.draggable.trigger(e);
						})
						.bind('mousemove', ft_mouseMoveHandler);
			self.append(ico);

			if (this.tagName != 'IMG') {
				ico.addClass('fd-quick-clip-ico-embed')
			}
			if ($this.width() < 120 || $this.height() < 120) {
				ico.css('left', $this.offset().left - 8)
					.css('top', $this.offset().top - 8)
					.find('img').css({width: 16, height: 16});
			} else {
				ico.css('left', $this.offset().left + 10)
					.css('top', $this.offset().top + 10)
					.find('img').css({width: 32, height: 32});
			}
		});
		//End quick clip icons
		
		if ($('#ft_image_mode').length) {
			self.init_image_mode();
		}
		if ($('#ft_video_mode').length) {
			self.init_video_mode();
		}

		/**
		 * After a content is selected by the user the hightlight is converted to a popup
		 * this function adds its styling the events are added in init_popup() called by
		 * mouseup event
		 * @see init_popup()
		 * @see self.draggable.bind('mouseup'
		 */
		function generate_popup($self, selected) {
			if (selected) {
				console.info('Set size', selected.outerWidth()+300, selected.outerHeight()+65);
				$self.width(selected.outerWidth()+300).height(selected.outerHeight()+65);
			} else {
				$self.width(650).height(300);
			}
			$self.addClass('clipboard-popup').css('overflow','');
			$('html, body').css('overflow','hidden');
			$('#ft_image_mode, #ft_video_mode').hide();

			var w = $self.width();
			var h = $self.height();
			console.info('w/h', w, h);

			var l = Math.round( ($(window).width()-w)/2 );
			//bobef: #FD-2848 the innerHeight thing here is because ff returns 0 for some kind of doctype
			var t = Math.round( ((window.innerHeight||$(window).height())-h)/2 );
			var scroll = (self.scrollTop() ? self.scrollTop() : self.parent().scrollTop());
			var t_start = $self.offset().top - scroll;
			$self.addClass('fixed');
			console.info('popup animate', t_start, t);
			$self
				.css('top', t_start)
				.hide()
				.show()
				.prepend('<div id="clipboard-popup-header">'+quickClipIco+'</div>')
				.append(options.preview_popup_view)
				.append('<a href="" id="clipboard-popup-close"></a>');
			
			try {
				$self.stop().animate({'top': t, 'left': l}, 500, function() {
					$self.css('left', l).css('top', t);
					console.info('popup animation end', t);
				});
			} catch (e) {
				console.error(e); 
				$self.css('left', l).css('top', t+'px');
				/*
				window.setTimeout(function() {
					$self.css('left', l).css('top', t+'px');
				},300);
				window.setTimeout(function() {
					$self.css('left', l).css('top', t+'px');
				},400);*/
			}
			try {
				$self.find('#clipboard-popup-header, #clipboard-popup-controls, #clipboard-popup-close')
				.css('opacity', 0).animate({'opacity':1}, 1000);
			} catch (e) {
				console.error(e);
			}

			$('textarea.fd_mentions').mentions({
				search: function(query, callback) {
					communicator.search(query, callback);
				}
			});
			var selected_folder = self.options.folders[0];
			for (var i=0;i<self.options.folders.length;i++) {
				if (self.options.folders[i].id == self.options.last_selected_folder) {
					selected_folder = self.options.folders[i];
				}
			}
			
			$self.find("input[name='folder_id']").tokenInput(self.options.folders, {
				tokenLimit: 1,
				allowInsert: true,
				showDropdownOnFocus: true,
				no_results_text: "Create a collection",
				prePopulate: [selected_folder],
				theme: 'fd_dropdown',
				placeholder: 'Click to Add',
				create_only : true,
				tokenFormatter: function(item) { return "<li><p>" + item[this.propertyToSearch] + "</p><span class='ico'></span></li>"; },

				/*//RR - removed - http://dev.fantoon.com:8100/browse/FD-3248
				addBoxValidate: function(e) {

					if (e.which >= 48 && e.which <= 59 && e.shiftKey || (this.value.length > 40 && e.which != 8) ) {

						//Used for error displaying on failed validation
						if (this.value.length > 40) {
							var tooltip_text = 'Please type shorter name';
						} else {
							var tooltip_text = 'Special Characters are not allowed';
						}
						
						var tooltip = $('#tab_label');
						if (! tooltip.length) {
							tooltip = $('<div id="tab_label" class="tab_label tab_label_err menu" style="display:none;z-index:9999999;"><span></span><strong></strong></div>');
							$('document.body').append(tooltip);
						}
						tooltip.find('strong').text(tooltip_text);
						var css = {
								'position': 'absolute',
								'background': '#FFAAAA',
								'z-index': 999999999999,
								'border-radius': '3px',
								'padding': '1px 5px',
								'top': $(e.target).offset().top - 32,
								'left': $(e.target).offset().left - 10,
								'font-family': "Arial, 'Times New Roman', sans-serif"
						}
						$(e.target).closest('div').after(tooltip);
						tooltip.css(css).show();
						console.info(tooltip);
						return false;

					} else {
						$('#tab_label').hide();
					}

					return e.which==8 || e.which==37 || e.which==39 || String.fromCharCode(e.which).match(/[^A-Za-z0-9-_\s]/) === null;
				}*/

			});
			
			var sample_hashtag = $self.find("a.hashtag.sample");
			for (var i=0;i<self.options.hashtags.length;i++) {
				var new_hashtag = sample_hashtag.clone().removeClass('sample').attr('href', self.options.hashtags[i])
					.text(self.options.hashtags[i]);
				sample_hashtag.after(new_hashtag);
			}
		}

		/**
		 * After a content is selected by the user the hightlight is converted to a popup
		 * this function adds its  events called by mouseup event
		 * @see generate_popup()
		 * @see self.draggable.bind('mouseup'
		 */
		function init_popup($self, selected) {
			var type = $self.data().type;
			if (type == 'embed' && !selected.hasClass('ft-video') && !selected.hasClass('ft-video-iframe')) {
				type = 'html';
			}
			$self.find('a.fd-quick-clip-ico').show().addClass('fd-quick-clip-ico-'+type);

			//validate css :after selector
			if (location.href.indexOf('disney.go.com') == -1) {
				$self.find('*:visible').each(function() {
					if ($(this).html() && $(this).height() == 0) {
						$(this).css('overflow','hidden');
						if ($(this).height() == 0) {
							$(this).css('overflow','');
						}
					}
				});
			}

			$self.find('textarea').off('keydown').keydown(function(e) {
				if (e.keyCode == 13 && ! $(this).closest('form').hasClass('loading')) {
					$(this).closest('form').submit();
					e.preventDefault();
					return false;
				}
				if (location.href.indexOf('maps.yahoo.com') > -1) {
					if (e.keyCode == 8) {
						this.value = this.value.substr(0, this.value.length-1);
					} else {
						var _char = String.fromCharCode(e.which);
						this.value += e.shiftKey ? _char : _char.toLowerCase();
					}
				}
				$(this).parent().find('span.maxLength').text($(this).attr('maxlength') - $(this).val().length);
				$(this).closest('form').find('span.error').hide();
				e.stopPropagation();
				return true;
			}).click(function() {
				//once textarea blurred it doesnt focus here:
				//https://picasaweb.google.com/lh/view?hl=en&sugexp=les;&cp=10&gs_id=12&xhr=t&q=google+plus&bav=on.2,or.r_gc.r_pw.r_qf.&biw=1440&bih=785&um=1&ie=UTF-8&sa=N&tab=wq#slideshow/5768565604933986034
				$(this).focus();
			})
			.focus();
			
			$self.find('a.hashtag').off('click').click(function() {
				$self.find('textarea.fd_mentions').insertAtCaret(' '+$(this).text());
				return false;
			});

			$('iframe.fd-remote-iframe').hide();//.css('opacity',0);
			$self.find('div#clipboard-popup-content iframe.visible').show();
			//Popup IU events
			self.find('div#scraping_overlay').show().click(function(e) {
				$('div.token-input-dropdown-fd_dropdown').hide();
				$('a#clipboard-popup-close').trigger(e);
			});
			$self.find('a#clipboard-popup-close').bind('click', function(e) {
				$('div.token-input-dropdown-fd_dropdown').hide();
				if ($('#ft_image_mode').is(':visible') || $('#ft_video_mode').is(':visible')) {
					self.clip_overlay.hide();
				} else if (self.options.design_ugc) { //backup - should never execute
					communicator._onshow_image_mode();
				} else {
					self.start();
				}
				
				return false;
			});
			$self.find("form#clipboard-popup-controls input[type='submit']").click(function() {
				$(this).closest('form').submit();
				return false;
			});
			$self.find('form#clipboard-popup-controls').unbind('submit').bind('submit', function(e) {

				//Validate

				// $(this).find('span.error').hide();
				//the specific textarea selector is added bc of: http://armorgames.com/play/14328/minigolf-pro?text=&folder_id=&folder_id%5B700%5D=aaaa
				if (!$(this).find("textarea.fd_mentions").val().replace(/^\s+|\s+$/g,"")) {
					// $(this).find('span.error').show().html('Description can&apos;t be empty');
					$('#notification_bar').show().html('Description can&apos;t be empty').delay(3000).fadeOut();
					return false;
				}

				/* RR - hashtag validation disabled by Alexi request
				if ( ! $(this).find("textarea.fd_mentions").val().match(/#[a-zA-Z]/)) {
					$(this).find('span.error').show().html('You need to use at least one hashtag');
					return false;
				}
				*/

				if ( ! $(this).find("input.tokenInput-hidden").length) {
					$('#notification_bar').show().html('Please select a collection').delay(3000).fadeOut();
					// $(this).find('span.error').show().html('Please select a collection');
					return false;
				}
				//End validate
				var content = $(this).parents('div.clipboard-popup').find('div#clipboard-popup-content');
				var folder_id = $(this).find("input.tokenInput-hidden").attr('name').match(/\[(.*?)\]/gi)[0].replace('[','').replace(']','');
				var folder_name = $(this).find("input.tokenInput-hidden").val();
				var has_folder = false;
				for (var i=0;i<self.options.folders.length;i++) if (self.options.folders[i].id == folder_id) has_folder = true;
				if (!has_folder) {
					self.options.folders.push({
						'id': folder_id,
						'name': folder_name
					});
				}

				self.options.last_selected_folder = folder_id;
				var data = content.data();
					data.folder = {};
					data.folder[folder_id] = folder_name;
					//data.interests = $(this).find("[name='interests']").val();
					data.description = $(this).find("textarea.fd_mentions").val(); //not using name bc of: http://armorgames.com/play/14328/minigolf-pro?text=&folder_id=&folder_id%5B700%5D=aaaa
				//$('a#clipboard-popup-close').trigger('click');
				options.on_add.call(content, data);
				//console.info('end submit');
				
				$self.find('#clipboard-popup-content').html("<h2>Processing....</h2>");
				$self.find('#clipboard-popup-controls, #post_button_container').remove();
				
				$('#clip_overlay').css({
						'height':60,'min-height':60,
						'width': 230,'min-width': 0,
						'left':$(window).width()/2-115,'top':$(window).height()/2-30
					});

				return false;
			});
		
		}

		/**
		 * Selects the highlighted content
		 */
		self.draggable.bind('mouseup', function(e) { //it was mousedown
			//console.info('mousedown', e);
			var $e = e;
			var $self = $(this);

			if ($self.hasClass('bookmark-page')) {
				communicator._onshow_as_popup();
				$self.removeClass('bookmark-page');
				return;
			}

			//console.info('mousedown1');
			if (options.on_select) options.on_select.call();
			e.offsetX = e.offsetX || e.pageX-$self.offset().left;
			e.offsetY = e.offsetY || e.pageY-$self.offset().top;
			this.position = e.pageX*e.pageY;
			//var cursorAt = { left: e.offsetX, top: e.offsetY };
			//self.draggable.draggable('option', 'cursorAt' ,cursorAt);

			//console.info('mousedown2');
			if (scrollpage == 2) {
				if ($(this).hasClass('clipboard-popup')) return;
				var text = '';
				self.find('.ft-text-sel').each(function() { text += $(this).html(); });

				//console.info('Text: '+text);
				$self.data('type','text').html('')
					.css('opacity','1')  //IE8 fix
					.append('<div id="clipboard-popup-content" class="fd-text-preview">'+text+'</div>')
					.find('#clipboard-popup-content')
						.data({ content: text, type: 'text'})
						.data('title', self[0].ownerDocument.title)
						.data('link', self[0].ownerDocument.location.href);

				generate_popup($self, $self.find('#clipboard-popup-content'));
				//console.info($(this));
				$(this).find('a.fd-quick-clip-ico').show()
					.removeClass('fd-quick-clip-ico-embed')
					.removeClass('fd-quick-clip-ico-image')
					.removeClass('fd-quick-clip-ico-html')
					.addClass('fd-quick-clip-ico-text');

				init_popup($self);
			} else {
				//console.info('mousedown2.5', e.which, self.paused);
				if (e.which == 3 || self.paused) return;
				self.stop();
				$self.html('<div id="clipboard-popup-content"></div>');
				var selected = $(self.find('#clip_overlay').data('selected'));
				console.log(selected[0]);
				generate_popup($self, selected);
				//console.info('get content1', self.find('#clip_overlay').data('selected'));
				get_content(selected[0], $self.find('#clipboard-popup-content'), function(data_el) {
					console.info('resize');
					$self.find('.fd-html-loader').remove();
					add($self.find('#clipboard-popup-content'), data_el);
					console.info('data', $self.find('#clipboard-popup-content').data());
					$self.data($self.find('#clipboard-popup-content').data()).find('#clipboard-popup-content').addIframeOverlays();
					var data_type = $self.data().type;
					
					$self.resizeToWindow({
						type: data_type,
						max_width: $(window).width() * 0.95 - 300,
						max_height: $(window).width() * 0.95,
					});

					if (data_type == 'image') {
						var img = new Image();
							img.src = $self.data().url;
							img.onerror = function() {
								$('.token-input-dropdown-fd_dropdown').hide();
								self.start();
							}
					}

					$self.find("iframe[src*='youtube.com']").each(function() {
						this.src =  this.src.replace('autoplay=1','');
					});

					if (location.href.indexOf('finance.yahoo.com/') > -1 && data_type == 'embed') data_type = 'html';

					$self.find('a.fd-quick-clip-ico').show()
						.removeClass('fd-quick-clip-ico-embed')
						.removeClass('fd-quick-clip-ico-image')
						.removeClass('fd-quick-clip-ico-text')
						.removeClass('fd-quick-clip-ico-html')
						.addClass('fd-quick-clip-ico-'+data_type);
					try {
						init_popup($self, selected);
					} catch (e) {console.error(e);}
					//Re-render content - http://dev.fantoon.com:8100/browse/FD-2309
					$self.find('iframe').contents().find('body').hide();
					window.setTimeout(function() { $self.find('iframe').contents().find('body').show(); }, 100);
				});
			}
			//self.css('overflow','hidden'); //to avoid scrolling when the popup is opened
		})
		.bind('contextmenu rightclick', function (e) {
			if ($(this).hasClass('clipboard-popup')) return true;
			e.preventDefault();
			return false;
		})
		/**
		 * Selects larger or smaller portion of html content on mouse scroll
		 */
		.bind('mousewheel', function (e, delta, deltaX, deltaY) {
			if (!scrollpage && !self.paused)
			{
				var el = self.find('#clip_overlay').data('selected');
				thisparent = $(el).parent();
				if (!thisparent.height()) thisparent = thisparent.parent();
				if (location.href.indexOf('ibnlive.in.com') > -1 && thisparent.hasClass('slides1')) thisparent = thisparent.parent(); 
				thisparent.data($(el).data());
				if (deltaY > 0 && thisparent.get(0).tagName != 'HTML') { //scrolling up
					if (thisparent.find('*:visible').size() < maxelements) {
						scroll_x = e.pageX;
						scroll_y = e.pageY;
						currentstack.push(el);
						self.clip_overlay.data('selected', make_selection(thisparent, true));
						self.draggable.removeClass('bookmark-page');
						if (self.pointer) self.pointer.html(options.lang.point_and_click).removeClass('bookmark-page');
					} else {
						/*
						//Drop page if scroll too much up
						self.draggable.css({top: self.scrollTop(), left: 0, width: self.width()-bookmark_page_border*2, height: '100%'}).addClass('bookmark-page');
						self.pointer.html('Bookmark page').addClass('bookmark-page');
						if (self.draggable1) {
							self.draggable1.css({top: self.scrollTop(), left: 0, width: self.width()-bookmark_page_border*2, height: '100%'}).addClass('bookmark-page');
						}*/
					}
				} else if (deltaY < 0 && currentstack.length) { //scroll down
					var el = currentstack.pop();
					self.clip_overlay.data('selected', make_selection(el, true));
					self.draggable.removeClass('bookmark-page');
					if (self.pointer) self.pointer.html(options.lang.point_and_click).removeClass('bookmark-page');
					if (currentstack.length) {
						scroll_x = e.pageX;
						scroll_y = e.pageY;
					} else {
						scroll_x = -1;
						scroll_y = -1;
					}
				}
				e.stopPropagation();
				e.preventDefault();
				return false;
			} else if (self != options.draggable_parent) {
				$(this).css({top:$(this).offset().top + deltaY * 30});
				self.scrollTop(self.scrollTop() - deltaY * 30)
				e.preventDefault();
			}
		});
		self.bind('mousedown', function(e) {
			if (!self.paused && scrollpage == 2 && ! self.clip_overlay.hasClass('clipboard-popup')
					&& ! $(e.target).closest('#fandrop_div').length
					&& e.target.id != 'clip_overlay'
				) {
				$('#fandrop_div .fd-bar-overlay').show();
			}
		});
		self.bind('mouseup', function(e) { //text selection
			if (self.paused) return;
			if (scrollpage != 2) return;
			$('#fandrop_div .fd-bar-overlay').hide();
			//$('#fandrop_div').show();
			if (e.target.id == 'clip_overlay') {
				return;
			}

			var div = $(document.createElement('DIV')).addClass('ft-text-sel');
				div.appendTo('body');
				div.html(self.getHtmlSelection(e));
				div.find('a').toAbsURL().attr('target','_blank');
				div.css({'max-width':'90%','display':'inline-block'});
			if (div.html()) {
				self.draggable.show().mousedown().mouseup();
			}
			div.remove();
		});
	} //End init();

	/**
	 * Moves the highlight to the element on which the mouse is over, or resizes the hightlight
	 * when the mouse is scrolled up/down
	 * @see .bind('mousewheel'
	 * @see window.ft_mouseMoveHandler
	 */
	function make_selection(el, scrolling, safe)
	{
		if (!el) return;

		if ($(el).closest('a.fd-quick-clip-ico').length) {
			var el = $(el).closest('a.fd-quick-clip-ico').data('image');
		}

		if (typeof scrolling == "undefined") scrolling = false;
		var offset = $(el).offset();

		var ntop = offset.top;
		var nleft = offset.left;
		//removed true because of http://dev.fantoon.com:8100/browse/FD-601
		//http://www.etsy.com/listing/106967271/minnie-mouse-dress-tutu-party-dress-in
		var nwidth = Math.max($(el).width(), $(el).outerWidth());
		var nheight = Math.max($(el).height(), $(el).outerHeight());
		var overlay_border = 3;

		if ($(el).data('parents')) {
			var parents = $(el).data('parents');
			for (var i=0;i<parents.length;i++) {
				ntop += $(parents[i].iframe).offset().top;
				nleft += $(parents[i].iframe).offset().left;
				ntop -= $(parents[i].doc).find('body').scrollTop();
			}
		}

		if (nwidth > $('body').width() - overlay_border) nwidth = $('body').width() - overlay_border;

		if ($(el).is('body')) {
			nwidth -= overlay_border*2;
			nheight -= overlay_border*2;
		} else {
			//compensate for border
			ntop -= overlay_border;
			nleft -= overlay_border;
		}

		if ($(el).get(0).tagName == 'A') {
			$(el).children('img').each(function () {
				var this_offset = $(this).offset();
				if (this_offset.top < ntop) {
					nheight += ntop - this_offset.top;
					ntop = this_offset.top;
				}
				if (this_offset.left < nleft) {
					nwidth += nwidth - this_offset.left;
					nleft = this_offset.left;
				}
			});
		}
		ntop = Math.round(ntop);
		nleft = Math.round(nleft);

		var css = {
				top: ntop,
				left: nleft,
				width: nwidth,
				height: nheight
			};

		if (safe) {
			self.clip_overlay.css(css).show();
		} else {
			try { self.clip_overlay.stop() } catch (e) {console.error(e); } //http://www.sfbike.org/?btwd
			try {                                        //http://www.weavetexfashion.com/baby-frocks.html
				self.clip_overlay.animate(css, 150).show();

				self.page_overlay.find('div.fd-top').animate({'height': ntop-$(window).scrollTop() },150);
				self.page_overlay.find('div.fd-bottom').animate({'top': ntop-$(window).scrollTop()+nheight },150);
				self.page_overlay.find('div.fd-left').animate({'top': ntop-$(window).scrollTop(), 'height': nheight, 'width':nleft },150);
				self.page_overlay.find('div.fd-right').animate({'top': ntop-$(window).scrollTop(), 'height': nheight, 'left':nleft+nwidth },150);
			} catch (e) {
				console.error(e); 
			}
		}

		return el;
	}
	
	/**
	 * copies the selected html content from the site to the preview iframe.
	 * @see self.draggable.bind('mouseup',
	 */
	function get_content(el, container, callback) {
		console.info('get content', el);
		container.html('<div class="fd-html-loader"><img src="'+options.loaderIco+'"/></div>');

		if (location.pathname.substr(-4,4) == '.swf') {
			container.html( $(el).clone().outerHTML() );
			callback.call(this);
			return;
		}
		var doctype_str = '';
		if (document.doctype) {
			var doctype_str = "<!DOCTYPE "
		         + document.doctype.name
		         + (document.doctype.publicId ? ' PUBLIC "' + document.doctype.publicId + '"' : '')
		         + (!document.doctype.publicId && document.doctype.systemId ? ' SYSTEM' : '') 
		         + (document.doctype.systemId ? ' "' + document.doctype.systemId + '"' : '')
		         + '>';
        } else {
        	var doctype_str = "";
        }
		var newclone = document.createElement('iframe');
		newclone.src = 'about:blank'; 
		console.info('Container', container);
		console.info('iframe size', $(el).outerWidth(), $(el).outerHeight());
		var body_style = 'margin:0; overflow-x: hidden;';
		if ($(el).outerWidth() < 300) {
			newclone.width = $(el).outerWidth()+8;
			newclone.style.marginLeft = ((container.width()-$(el).outerWidth())/2-8)+'px';
		} else {
			newclone.width = '100%';
		}
		if ($(el).outerHeight() < 300) {
			newclone.height = $(el).outerHeight()+8;
			newclone.style.marginTop = ((container.height()-$(el).outerHeight())/2-8)+'px';
			var body_style = 'margin:0; overflow: hidden;';
		} else {
			newclone.height = '100%';
		}

		container.append(newclone);
		newclone = newclone.contentDocument ? newclone.contentDocument : newclone.contentWindow.document;
		newclone.open();
		console.info('Write doctype', doctype_str);
		newclone.write(doctype_str+'<html><body style="'+body_style+'"></body></html>');
		newclone = $(newclone);
		
		var clipboardresult = '';
		var appendindex = 0;
		var pointers = {};
		if (location.href.indexOf('maps.google.') > -1 && el.id == 'tileContainer') {
			var link = $("#mapmaker-link").attr('href');
			var ll = link.match(new RegExp('ll=(.*?)&','gi'));
			var spn = link.match(new RegExp('spn=(.*?)&','gi'));
			var z = link.match(new RegExp('z=(.*?)(&|$)','gi'));
			container.html('<iframe width="'+($(el).width()-24)+'" height="'+$(el).height()+'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'
					+"http://maps.google.com/?"+ll+spn+(z[0].replace('&','')+'&')+"output=embed"
					+'"></iframe>');
			callback.call(this);
			return;
		}
		else if (location.href.indexOf('maps.yahoo.com') > -1 && $(el).closest('#ymap').length) {
			container.html($(el).closest('#ymap').outerHTML())
			callback.call(this);
			return ;
		} else if ($(el).hasClass('ft-video-iframe')) {
			newclone.find('body').append($(el).clone());
			newclone[0].close();
			callback.call(newclone, newclone.find('body'));
			return;
		} else if ($(el).is('object') || $(el).is('embed')) {
			$(el).toAbsURL($(el));
			newclone.find('body').copy_style($('body'))
				.css({'height':'auto','width':'auto'})
				.append(
					$('<div/>').width(el.width).height(el.height).css('overflow','hidden')
						.append($(el).clone())
				);
			newclone[0].close();
			window.setTimeout(function() { //Firefox - the flash - http://www.ustream.tv/MTV
				callback.call(newclone, newclone.find('body'));
			},1000);
			return ;
		}
		if (el.tagName == 'HTML') {
			el = $(el).find('body').height($(el).height()).get(0);
		}
		//site specific fixes
		if (location.href.indexOf('animetoplist.org') > -1 ) $(el).find('div#main').width(961);
		if (location.href.indexOf('videojug.com/') > -1 ) {
			$(el).find('#explore_channels').width(481);
			if ($(el).is('#home_page')) $(el).width(963);
		}
		if (location.href.indexOf('http://googleplus-covers.com/') > -1 ) $(el).find("div#container").width(1116);
		if (location.href.indexOf('http://futures.tradingcharts.com/') > -1 ) $(el).find("td select").parent().css('-webkit-text-fill-color', 'black');
		if (location.href.indexOf('http://www.iplay.com/') > -1 ) $(el).find("ul.navigation").css('background', 'url(http://assets.blaze.com/channel/iplay_en/css/v27034_9/headerBackgroundTile.png) bottom');
		if (location.href.indexOf('http://kidzdom.com/') > -1 ) $(el).find("#tong").remove();
		if (location.href.indexOf('http://uplay.ubi.com') > -1 ) {
			$(el).find(".tab-pane").width(0);
			window.setTimeout(function() {
				$(el).find(".tab-pane").width('100%');
			},500);
		}

		$(el).attr('rel',appendindex);
		pointers[appendindex] = $(el);
		appendindex++;

		$(el).find('*').each(function (index) {
			if ($(this).is('link')) return;
			pointers[appendindex] = $(this).attr('rel',appendindex);
			appendindex++;
		});
		var prefixStyleStr = '';

		
		if ($(el)[0] && $(el)[0].tagName.toLowerCase() == 'body') {
			var outerhtml = $(el).clone();
				//Clean
				outerhtml.find('div#clip_overlay, div#clip_overlay_warning').remove();
				outerhtml.find('div#scraping_overlay, div#fandrop_div, #scraping_overlay_popup').remove();
				outerhtml.find('noscript, script, style, link').remove();
			newclone.find('body').append('<div/>')
			newclone.find('body > div').attr('rel','0').html(outerhtml.html().replace(/<!--.*?-->/g, '')).copy_style($(el));
		} else if ($(el)[0] && $(el)[0].tagName.toLowerCase() == 'frameset') {
			var rows = $(el).attr('rows') ? $(el).attr('rows').split(',') : [];
			var cols = $(el).attr('cols') ? $(el).attr('cols').split(',') : [];
			console.info('frameset sizes', rows, cols);
			var outerhtml = $(el).find('frame').each(function(i) {
				var iframe = $('<iframe></iframe>').attr('src', $(this).attr('src')).toAbsURL($(this));
				iframe.width($(this).width()).height($(this).height());
				/*if (rows[i]) {
					rows[i] = rows[i] == '*' ? '100%' : rows[i];
					iframe.height(rows[i]);
				}
				if (cols[i]) {
					cols[i] = cols[i] == '*' ? '100%' : cols[i];
					iframe.width(cols[i]);
				} else {
					iframe.width('100%');
				}*/
				
				iframe.copy_style($(this));
				newclone.find('body').append(iframe);
			})
		} else {
			var outerhtml = $(el).clone();
				//Clean
				outerhtml.find('div#clip_overlay, div#clip_overlay_warning').remove();
				outerhtml.find('div#scraping_overlay, div#fandrop_div, #scraping_overlay_popup').remove();
				outerhtml.find('noscript, script, style, link').remove();
			//Get absolute elements
			var abs_elements_to_add = [];
			var base_x=0, base_y=0;
			var parents = $(el).data('parents');
			if (parents) for (var i=0;i<parents.length; i++) {
				base_x += $(parents[i].iframe).offset().left;
				base_y += $(parents[i].iframe).offset().top;
			}
			//RR - removed , *.ft-fixed - more bugs are added for adding these elements instead of removing them
			//http://www.reverbnation.com/smhspill - player at the bottom
			if (!$('#ft_image_mode').is(':visible')) {
				$('*.ft-absolute').each(function() {
					var $this = $(this);
					if ($this.closest($(el)).length) return;
					if ($(el).closest($this).length) return;
					if ($this.isInside($(el), base_x, base_y) ) {
						var closest = $this.closestCss('overflow','hidden');
						if (closest.isInside($(el), base_x, base_y)) {
							abs_elements_to_add.push($this);
						}
					}
				});
			}
			
			//Clean duplicates
			for (var i=0;i<abs_elements_to_add.length;i++) {
				if (abs_elements_to_add[i]) abs_elements_to_add[i].find('*.ft-absolute, *.ft-fixed').each(function() {
					for (var j=0;j<abs_elements_to_add.length;j++) {
						if (abs_elements_to_add[j] && this == abs_elements_to_add[j][0]) {
							abs_elements_to_add.splice(j, 1);
							return;
						}
					}
				});
			}

			//Validate
			//prefix style here bc of (header): http://www.animationlibrary.com/animation/24491/Robot_dances/
			var prefix = ''; var postfix = '';
			if (el.tagName == 'TD') {
				var donor_el = $(el).closest('table');
				donor_el.attr('rel',appendindex); pointers[appendindex] = donor_el; appendindex++;
				var attrs = '';
				for (var attr in {'cellpadding':'','cellspacing':'','border':''}) {
					if (donor_el.attr(attr)) attrs += ' '+attr+'="'+donor_el.attr(attr)+'"';
				}
				prefix = '<table'+attrs+' rel="'+(appendindex-1)+'"><tr>'; postfix = '</tr></table>';
			} else if (el.tagName in {'TR':'','TBODY':'','THEAD':'','TFOOT':''}) {
				var donor_el = $(el).closest('table');
				donor_el.attr('rel',appendindex); pointers[appendindex] = donor_el; appendindex++;
				var attrs = '';
				for (var attr in {'cellpadding':'','cellspacing':'','border':''}) {
					if (donor_el.attr(attr)) attrs += ' '+attr+'="'+donor_el.attr(attr)+'"';
				}
				prefix = '<table'+attrs+' rel="'+(appendindex-1)+'">'; postfix = '</table>';
			} else if (el.tagName == 'LI') {
				var donor_el = $(el).closest('ul');
				donor_el.attr('rel',appendindex); pointers[appendindex] = donor_el; appendindex++;
				prefix = '<ul rel="'+(appendindex-1)+'">'; postfix = '</ul>';
			} else  if (el.tagName in {'DL':'','DT':''}) {
				var donor_el = $(el).closest('ol');
				donor_el.attr('rel',appendindex); pointers[appendindex] = donor_el; appendindex++;
				prefix = '<ol rel="'+(appendindex-1)+'">'; postfix = '</ol>';
			}
			console.info('prefix', prefix);
			newclone.find('body').html('<style>'+font_faces+'</style>'+prefix+outerhtml.outerHTML().replace(/<!--.*?-->/g, '')+postfix);
			
			//Add script generated iframes
			if ($(el).is('iframe:not([src])')) {
				var target = newclone.find('[rel='+$(el).attr('rel')+']');
				console.info("copy iframe contents", el, target);
				var this_doc = el.contentDocument ? el.contentDocument : el.contentWindow.document;
				//var target_doc = target[0].contentDocument ? target[0].contentDocument : target[0].contentWindow.document;
				console.info(this_doc.body.innerHTML);
				target.after('<div>'+this_doc.body.innerHTML+'</div>');
				target.remove();
			}
			$(el).find('iframe:not([src])').each(function() {
				var target = newclone.find('[rel='+$(el).attr('rel')+']');
				console.info("copy iframe contents", el, target);
			});
			
			//Add absolute elements
			for (var i=0;i<abs_elements_to_add.length;i++) {
				console.info('add absolute: ', abs_elements_to_add[i][0]);
				$this = abs_elements_to_add[i];
				pointers[appendindex] = $this.attr('rel',appendindex);
				appendindex++;
				$this.find('*').each(function (index) {
					if ($(this).is('link')) return;
					pointers[appendindex] = $(this).attr('rel',appendindex);
					appendindex++;
				});

				var z_index_el = $(el);
				while(z_index_el.css('z-index') == 'auto' && !z_index_el.is('body')) {
					z_index_el = z_index_el.parent();
				}
				var zindex = z_index_el.css('z-index');

				if(zindex != "auto") {
					zindex = parseInt(zindex);
					
					var thisindex_el = $this;
					while(thisindex_el.css('z-index') == 'auto' && !thisindex_el.is('body')) {
						thisindex_el = thisindex_el.parent();
					}
					var thisindex = thisindex_el.css('z-index');
					console.info(thisindex_el, thisindex);
					
					if(thisindex != "auto") zindex += parseInt(thisindex);
					
					if (zindex > 2147483640) {
						zindex = 2147483640;
						z_index_el.css('z-index', zindex);
					}
					
					$this.css({'z-index': parseInt(zindex)});
				}
				
				//http://disney.go.com/finding-nemo/home/
				var ele = $this.clone();
				ele.find('noscript, script, style').remove();
				
				ele.attr('data-left', $this.offset().left - $(el).offset().left);
				ele.attr('data-top', $this.offset().top - $(el).offset().top);
				
				if ($this.isPrev($(el))) {
					newclone.find('body [rel=0]').after(ele);
				} else if (!$(el).is('img') || $this.css('position')) {
					newclone.find('body [rel=0]').before(ele);
				}
			}
		}

		newclone.find('canvas').each(function() {
			if (!$(this).attr('rel') || !pointers[$(this).attr('rel')]) return;
			var donor = pointers[$(this).attr('rel')][0];
			if (!donor) return;
			try {
				var img = document.createElement('img');
					img.src = donor.toDataURL("image/png");
				$(this).before(img);
				$(img).copy_style($(this));
			} catch (e) {
				console.info('Could not copy canvas', donor);
			}
			$(this).remove();
		});
		newclone.find('.fd-remote-iframe.visible').show();
		newclone.find('a').attr('target','_blank');

		var container = newclone.find('body [rel=0]').parent(); 
		var els = container.find('*');
		window.setTimeout(function() { //in FF the default attributes are added after some time so we need to wait
			copy_styles(el, els, pointers, 0, function() {
				container.find('[rel=0]').css({
					'left':0,'top':0,'bottom':0,'right':0
				})
				if (container.is('body')) {
					container.find('[rel=0]').css({
						'margin': 0, 'float':'none','overflow':'hidden', 'opacity': '1',
						'left':0,'top':0,'bottom':0,'right':0, 'width': $(el).width(), 'height': $(el).height() 
					});
					if (!container.find('[rel=0]').is('img')) {
						container.find('[rel=0]').css({'position': 'relative'});
					}
				} else {
					container.css({
						'position': 'relative', 'margin': 0, 'float':'none','overflow':'hidden',
						'left':0,'top':0,'bottom':0,'right':0, 'width': $(el).width(), 'height': $(el).height() 
					});
				}
				if (location.href.indexOf('.mantri.in') == -1) { //this site has dynamic background
					var proxy = null;
					if (location.href.indexOf('cssplay.co.uk') > -1) proxy = options.baseUrl+'/external/index.php?url=';
					newclone.find('[rel=0]').setBackground($(el), function() {
						newclone[0].close();
						callback.call(newclone, newclone.find('body'));			
					}, proxy);
				} else {
					newclone[0].close();
					callback.call(newclone, newclone.find('body'));			
				}
			})
		},1);

		return ;
	}

	/**
	 * Copies the styles of a set of elements to the new set
	 * @see get_content()
	 */
	function copy_styles(el, els, pointers, start, callback) {
		var limit = Math.min(els.length, start+20);

		for (var i=start; i < limit; i++) {
			var $this = $(els[i]);
			var donor = pointers[$this.attr('rel')];
			if (!donor) {
				console.info('donor not found: '+$this.attr('rel'));
				continue;
			}
			$this.toAbsURL(donor);
			if ($this.closest('svg').length) continue;
			$this
				.removeAttr('class').removeAttr('id')//.removeAttr('rel')
				.removeAttr('onclick').removeAttr('onmousedown').removeAttr('onmouseover')
				.copy_style(donor)
			if ($this.attr('data-left') && $this.attr('data-top')) {
                //http://dev.fantoon.com:8100/browse/FD-1190
				try{ $this.css({
						'left': $this.attr('data-left')+'px', 'top': $this.attr('data-top')+'px',
						'margin-left':0,'margin-top':0
					});
				} catch (e) { console.error(e); }
			} else if ($this.css('position') == 'absolute' && $this.closest('[data-top]').length) {
				try{ $this.css({
						'top': $this.css('top') - $this.closest('[data-top]').attr('data-top'),
						'left': $this.css('left') - $this.closest('[data-left]').attr('data-left'), 
						'margin-left':0,'margin-top':0
					});
				} catch (e) { console.error(e); }
			} else if ($this.css('position') == 'fixed') {
				$this.css({
					'top': $this.offset().top - $(el).offset().top,
					'left': $this.offset().left - $(el).offset().left
				})
			}
			
		}
		if (i < els.length) {
			window.setTimeout(function() {
				copy_styles(el, els, pointers, i, callback);
			},1);
		} else {
			callback.call(this);
		}
	}

	init();
	return this;
}

if (typeof define != 'undefined') {
	define(["bookmarklet/clipboard_ui_commons"], function() {

	});
}
