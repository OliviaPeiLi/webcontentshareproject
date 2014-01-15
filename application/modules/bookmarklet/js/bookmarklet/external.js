/*
 * This code is injected in the remote site and loads the css and js needed for the bookmarklet
 * @see clipboard_ui.js
 */
var self = this;
var dragIco = "http://icongal.com/gallery/image/58685/drag_arrow_cursor.png";
var arrowIco = baseUrl+"images/bookmarklet/arrow.png";
var iframe, popup, fandrop_div;
var clipboard_ui;  

//facebook fix
window.__d = null; //facebook.com

//vimeo fix
if (location.href.indexOf('vimeo.com') > -1 && typeof window.removeEvent == 'function' ) {
	window.removeEvent('domready');
}

if (typeof JSON == 'undefined' || typeof JSON.stringify !== 'function') {
	JSON = {
	    escapable: /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
	    meta: {'\b': '\\b','\t': '\\t','\n': '\\n','\f': '\\f','\r': '\\r','"' : '\\"','\\': '\\\\'},
		quote: function(string) {
			JSON.escapable.lastIndex = 0;
	        return JSON.escapable.test(string) ? '"' + string.replace(JSON.escapable, function (a) {
	            var c = JSON.meta[a];
	            return typeof c === 'string'
	                ? c
	                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
	        }) + '"' : '"' + string + '"';
	    },
	    stringify: function(jsonData) {
			var strJsonData = jsonData instanceof Array ? '[' : '{';
			var itemCount = 0;
			for (var item in jsonData) {
				if (itemCount > 0) strJsonData += ',';
				temp = jsonData[item];
				if (typeof(temp) == 'object' || typeof(temp) == 'array') {
					s =  JSON.stringify(temp);
				} else if (typeof(temp) == 'string'){
					s = JSON.quote(temp);
				} else if (typeof(temp) == 'undefined'){
					s = '""';
				} else {
					s = '"' + String(temp) + '"';
				}
				if (parseInt(item) == item) {
					strJsonData += s;
				} else {
					strJsonData += '"'+item + '":' + s;
				}
				itemCount++;
			}
			strJsonData += jsonData instanceof Array ? ']' : '}';;
			return strJsonData;
		}
	}
}

function loadCss(url, _body) {
	if (_body.ownerDocument.createStyleSheet) {
		try {
			_body.ownerDocument.createStyleSheet(url);
		} catch (e) {
			var fileref = _body.ownerDocument.createElement("link");
				fileref.setAttribute("rel", "stylesheet");
				fileref.setAttribute("type", "text/css");
				fileref.setAttribute("href", url);
			if (_body.ownerDocument.getElementsByTagName("head").length) {
				console.info(_body.ownerDocument.getElementsByTagName("head")[0]);
				_body.ownerDocument.getElementsByTagName("head")[0].appendChild(fileref)
			} else {
				_body.appendChild(fileref)
			}
		}
	} else {
		var fileref = _body.ownerDocument.createElement("link");
		fileref.setAttribute("rel", "stylesheet");
		fileref.setAttribute("type", "text/css");
		fileref.setAttribute("href", url);
		if (_body.ownerDocument.getElementsByTagName("head").length) {
			_body.ownerDocument.getElementsByTagName("head")[0].appendChild(fileref)
		} else {
			_body.appendChild(fileref)
		}
	}	
}

var body = document.body;
if (document.body.tagName == 'FRAMESET') {
	var frames = document.body.getElementsByTagName('frame');
	if (frames.length == 1 && frames[0].src && frames[0].src.indexOf(location.host) == -1) {
		alert('This site uses iframe redirection. You will be redirected to the original URL. Please start the bookmarklet there.');
		window.location.href = frames[0].src
	}
}
/*
if (document.body.tagName.toUpperCase() == 'BODY') {
	body = document.body; 
} else {
	//http://www.johnwrightphoto.com/
	//try {
		body = document.body.getElementsByTagName('frame')[0].contentDocument.body;
	//} catch (e) {
	//	window.location.href = document.body.getElementsByTagName('frame')[0].src;
	//	return;
	//}
}
*/
for (var i=0;i<css.length;i++) {
	loadCss(css[i], body);
}

function load_script(a, b) {
	if (scripts[a].indexOf('?') > -1 ) {
		jQuery.ajaxSetup({ cache: true });
	}
	var script_name = scripts[a]+(scripts[a].indexOf('?') == -1 ? '?v='+Math.round(Math.random()*100) : '');
    try {
        jQuery.getScript(script_name, function () {
            a >= scripts.length - 1 ? b.call() : load_script(a + 1, b)
        })
    } catch (e) {
	    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
	    t.src = script_name;
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
	    	t.onload = function() {
	            a >= scripts.length - 1 ? b.call() : load_script(a + 1, b)
	    	}
    }
}
function init(body) {
	if (jQuery('#scraping_overlay').length) return;
	var $body = jQuery(body);
	if ($body.is('frameset')) {
		$body = $body.parent();
	}
	//Fix z-index
	$body.find('*').each(function() {
		if (window.getComputedStyle && window.getComputedStyle(this).zIndex > 2147483645 ) {
			jQuery(this).css('z-index', 2147483644);
		}
	});

	//http://www.cbsnews.com/8301-3460_162-57423281/terrorism-experts-on-bin-laden-one-year-after-death/?tag=clip_overlay;clipboard-popup-close
	//http://photos.presstelegram.com/2012/07/photos-medal-round-of-womens-58kg128-lbs-2012-olympic-weightlifting-on-monday-july-30/#4
	$body.append(general_layout);
    load_script(0, function () {
        communicator = communicator();
        self.clipboard_ui = $body.clipboard_ui({
        	'jQuery': jQuery,
        	baseUrl: baseUrl,
        	lang: php.lang,
        	loaderIco: loaderIco,
        	preview_popup_view: preview_popup_view,
        	design_ugc: design_ugc,
            on_select: function (a) {
            	//communicator._onclose_popup()
            },
            on_set_content: function(content) {
            	communicator.set_content(content);
            },
            on_add: function (_data) {
                //self.clipboard_ui.stop(true);
                communicator._onbefore_update();                
                _data.nodrag = true;
                communicator.drag_end(_data);
            }
        });
        init_iframe();
    })
}

if (location.host.indexOf("fandrop.com") == -1 
	&& location.host.indexOf("fantoon.com") == -1  
	&& location.host.indexOf('fantoon.local') == -1
	&& location.href.indexOf('http://ft/') == -1
    && location.href.indexOf('http://localhost/') == -1
	|| location.host.indexOf("blog.fantoon.com") != -1
) {
    init(body)
} else { 
	console.log('redropinfo'); 
	$('#open_redrop_info').trigger('click');
}

function init_iframe() {

    //bobef: #FD-1858
    //fix possible double slash which could break the iframe
    var baseUrlIframe = baseUrl;
    if ( baseUrlIframe.charAt( baseUrlIframe.length - 1 ) == '/' ) {
        baseUrlIframe = baseUrlIframe.substr(0, baseUrlIframe.length - 1);
    }
    //end of #FD-1858

	communicator.set_iframe(jQuery("#success_popup iframe").attr('src',baseUrlIframe + '/bookmarklet/success'));
    communicator.set_iframe(jQuery("#save_link iframe").attr('src',baseUrlIframe + '/bookmarklet/popup'));
    var has_images = $('#ft_image_mode ul li').length ? 'true' : 'false';
    var has_videos = $('#ft_video_mode ul li').length ? 'true' : 'false';
    communicator.set_iframe(jQuery("#fandrop_div #scraping_overlay_iframe").attr('src',baseUrlIframe + '/bookmarklet?has_images='+has_images+'&has_videos='+has_videos));
    iframe = jQuery("#fandrop_div #scraping_overlay_iframe").get(0);
    fandrop_div = jQuery("#fandrop_div").get(0);
    //bounceIframe();
    jQuery("#fandrop_div a.close, #fandrop_div a.done, #external_postbox a.done").click(function () {
    	communicator._onclose_popup();
        return false
    });
    
    jQuery('a.help').hover(function() {
    	jQuery('#fandrop_div .tooltip-5').show();
    }, function() {
    	jQuery('#fandrop_div .tooltip-5').hide();
    });
    jQuery('a.go_home').hover(function() {
    	jQuery('#fandrop_div .tooltip-6').show();
    }, function() {
    	jQuery('#fandrop_div .tooltip-6').hide();
    });
    jQuery('a.close').hover(function() {
    	jQuery('#fandrop_div .tooltip-7').show();
    }, function() {
    	jQuery('#fandrop_div .tooltip-7').hide();
    });

    jQuery('#redrop-popup .close').click(function() { communicator._onclose_redrop_popup(); return false; })
    jQuery('#scraping_overlay, #save_link .clipboard-popup-close').click(function() { communicator._onclose_save_link_popup(); return false; });
    jQuery('#scraping_overlay, #success_popup .clipboard-popup-close').click(function() { communicator._onclose_success_popup(); return false; });
    
    communicator._onload = function(data) {
    }
    communicator._onset_clip_mode = function(data) {
    	self.clipboard_ui.set_clip_mode(data.mode);
    	self.clipboard_ui.start(true);
    }
    communicator._onclose_save_link_popup = function() {
    	jQuery('#save_link').hide();
    	jQuery('#scraping_overlay').hide();
    	jQuery("#fandrop_div").css('zIndex','');
    	if (self.clipboard_ui.options.design_ugc) {
    		return communicator._onshow_image_mode();
    	}
    	self.clipboard_ui.start(true);
    }
    communicator._onclose_success_popup = function() {
    	jQuery('#success_popup').hide();
    	jQuery('#scraping_overlay').hide();
        jQuery("#fandrop_div").css('zIndex','');
    }
    communicator._onshow_image_mode = function() {
    	console.info("Show image mode");
    	self.clipboard_ui.stop(true).clip_overlay.hide();
    	$('body').css('overflow','hidden');
    	$('#ft_video_mode').hide();
    	$('#ft_image_mode').show();
    }
    communicator._onshow_video_mode = function() {
    	console.info("Show video mode");
    	self.clipboard_ui.stop(true).clip_overlay.hide();
    	$('body').css('overflow','hidden');
    	$('#ft_image_mode').hide();
    	$('#ft_video_mode').show();
    	if ($('#ft_video_mode li').length == 1) {
    		$('#ft_video_mode li a').trigger('mouseup');
    	}
    }
    communicator._onshow_as_popup = function(a) {
    	console.info('show as popup');
        jQuery("#save_link, #scraping_overlay").show();
        jQuery('#fd-iframe-overlay').hide();
        jQuery("#fandrop_div").attr('style', 'display: none !important');
        $('#ft_image_mode').hide();
        self.clipboard_ui.stop(true).clip_overlay.hide();
        
        //Make the html avaliable for our site
        jQuery('#save_link, #scraping_overlay').hide();
        
		//Site specific
        jQuery('html').attr('style', 'height: auto !important');
        if (location.href.indexOf('.zedo.com') > -1) {
            jQuery('link').each(function() {
            	jQuery(this).attr('href', baseUrl+'external/index.php?url='+jQuery(this).attr('href'));
            });
            jQuery('script').each(function() {
            	if (!jQuery(this).attr('src')) return;
            	jQuery(this).attr('src', baseUrl+'external/index.php?url='+jQuery(this).attr('src'));
            });
            jQuery('style').each(function() {
            	jQuery(this).html( jQuery(this).html().replace('url(','url('+baseUrl+'external/index.php?url=') );
            });
        }
        if (location.href.indexOf('.adobe.com') > -1) {
        	jQuery('html').attr('style', 'height: auto !important; background: none !important');
        }
        if (location.href.indexOf('mail.ru') > -1) {
        	jQuery('div.layout__footer').css('bottom', 'auto');
        }
		if (location.href.indexOf('paypopup.com') > -1) {
			jQuery("div[id*='emailserv_fl_']").css('position', 'static');
		}

        if (document.doctype) {
			var html = "<!DOCTYPE "
		         + document.doctype.name
		         + (document.doctype.publicId ? ' PUBLIC "' + document.doctype.publicId + '"' : '')
		         + (!document.doctype.publicId && document.doctype.systemId ? ' SYSTEM' : '') 
		         + (document.doctype.systemId ? ' "' + document.doctype.systemId + '"' : '')
		         + '>';
        } else {
        	var html = "";
        }
	    html += document.documentElement.outerHTML.replace(/<script id="web_scraper[^>]*>[^>]*>/gi,'');
        
        jQuery('#save_link, #scraping_overlay').show();
        jQuery("#fandrop_div").attr('style', 'z-index: 1000;');
        
		if (navigator.userAgent.indexOf('MSIE') > -1) {
			html = html.replace(/<object/gi,'<object type="application/x-shockwave-flash"')
						.replace(/classid=.*? /gi,'')
						.replace(/classid=".*?"/gi,'')
    	}
		
		html = html.replace(/(\r\n|\n|\r)/gm,"");
		
        jQuery("#save_link iframe")[0].contentWindow.postMessage(JSON.stringify({
        	fandrop_message: true,
        	action: 'show_as_popup',
        	url: window.location.href,
        	title: jQuery('title').html(),
        	html: html,
        	width: jQuery(document).width(),
        	height: jQuery(document).height()
        }), '*');
    }
    var login_refresh = false;
    communicator._onshow_as_login = function(data) {
    	console.info('show_as_login', data);
    	login_refresh = true;
    	jQuery(iframe).height(data.height).width(869);
    	jQuery(iframe).parent().css({
    		'margin-right': -jQuery(iframe).width()/2, 
    		'margin-top': -jQuery(iframe).height()/2, 
    	})
    	jQuery('#fandrop_div').css('display','block').addClass('login');
        jQuery('#scraping_overlay_iframe').css('display','inline');
        jQuery("#scraping_overlay, #fandrop_div, #fandrop_div #scraping_overlay_iframe").show();
        jQuery('#fandrop_div .ft-loader').hide();
    }
    communicator._onshow_as_bar = function(data) {
    	jQuery(iframe).height(data.height).width(370);
    	if (data.no_start) return;
    	console.info('show as bar', data);
    	jQuery("#fandrop_div").attr('style','').removeClass('login');
        jQuery("#fandrop_div, #fandrop_div #scraping_overlay_iframe, #fandrop_div > a, .fd-quick-clip-ico").show();
        jQuery('#scraping_overlay, #fandrop_div .ft-loader').hide();
        jQuery('#fandrop_div #scraping_overlay_iframe').css('display','block');
        if (data.user_avatar.indexOf('default') == -1) {
            jQuery('#fandrop_div .help .help_box').css('background','url('+data.user_avatar+') no-repeat center center');
        }
        
        //intro help texts
        if (jQuery("#fandrop_div .intro_step_1").hasClass('visible')) {
            window.setTimeout(function() {
            	jQuery("#fandrop_div .intro_step_1").removeClass('visible');
            	jQuery("#fandrop_div .intro_step_2").addClass('visible');
            	window.setTimeout(function() {
                	jQuery("#fandrop_div .intro_step_2").removeClass('visible');
            	}, 3000);
            }, 3000);
            //End intro help texts
        }
        
        $("embed[src*='.pdf']").each(function() {
			var $self = $(this);
			communicator.pdf2html($(this).attr('src'), function(html) {
				console.info('pdf converted ', $self);
				$self.before('<div width="'+$self.width()+'" height="'+$self.height()+'">'+html+'</div>');
				$self.remove();
				self.clipboard_ui.parse_absolute_elements();
			});
		});
        
        //Update cache
        var media = {'images':[], 'videos':[]};
        $('img[src]').each(function() {
        	if (!this.src) return;
        	if ($(this).width() < 30 && $(this).height() < 30) return;
        	var src = $.toAbsURL($(this).attr('src'), location);
        	for (var i=0;i < media['images'].length; i++) {
        		if (media['images'][i]['src'] == src) return;
        	}
        	media['images'].push({'src': src, 'width': $(this).width(), 'height': $(this).height()});
        });
        console.info(media);
        communicator.update_cache(window.location.href, media);
        
        self.clipboard_ui.start();
        //IE_Quirks_mode_fix(fandrop_div);
        if (!self.clipboard_ui.options.folders) {
            console.info(data);
            for (var i in data.options) {
            	self.clipboard_ui.options[i] = data.options[i];
            }
            console.info('set fodlers', self.clipboard_ui.options);        	
        }
    	jQuery('#fandrop_div a.help').attr('href', baseUrl+data.user_uri);
        //refresh popup after login
        if (login_refresh) {
        	jQuery('#save_link iframe').attr('src', jQuery('#save_link iframe').attr('src'));
        	jQuery('#success_popup iframe').attr('src', jQuery('#success_popup iframe').attr('src'));
        }
    }
    
    communicator._onerror = function(b) {
    	console.info('SHOW ERROR', b);
    	self.clipboard_ui.start();
        var error_msg = jQuery("#ft_error_msg");
        error_msg.show().find(/*"strong.message"*/"h3").html(b.message);
        window.setTimeout(function () {
        	error_msg.hide();
        }, 3000);
    }
    communicator._onclose_popup = function(data) {
    	jQuery('body').css('overflow','');
        jQuery("#fandrop_div, #fd-iframe-overlay, #scraping_overlay, #clip_overlay, #ft_image_mode, #ft_video_mode").hide();
    	jQuery("#fandrop_div").attr('style','display: none !important');
        jQuery('div.token-input-dropdown-fd_dropdown').hide();
        $('html, body').css('overflow','');
        
        self.clipboard_ui.stop(true);
        console.info('onclose');
        if (data && data.id) {
        	jQuery('#save_link').hide();
        	console.info('success', data);
        	//jQuery('#success_popup').show().find('iframe')[0].src += '/' + data.id;
        	jQuery('#success_popup').show().find('iframe')[0].contentWindow.postMessage(JSON.stringify({
            	fandrop_message: true,
            	action: 'show_success',
            	data: data
            }), '*');
        	window.hide_timeout = window.setTimeout(function() {
        		// USE THIS TO TURN OFF FADING
        		jQuery('#success_popup').hide('fade');
        	}, 10000);        	
        }
    }
    //@deprecated
    communicator._onedit = function(a) {
    	 //Edit popup
    }
    //@deprecated
    communicator._onupdate = function(a) {
    	 //transfer from edit popup to the bar
        communicator.update(a);
    }
    communicator._onafter_add = function(data) {
    	console.info('after add', data);
    	
    	var folders = self.clipboard_ui.options.folders;
    	var hasit = false;
    	for (var i=0;i< folders.length; i++) if (folders[i].id == data.folder_id) hasit = true;
    	if (!hasit) {
    		console.info('add folder: ', data.folder_id, data.folder_name);
    		self.clipboard_ui.options.folders.push({'id':data.folder_id, 'name':data.folder_name});
    	}
    	jQuery('body').css('overflow','').find('#scraping_overlay').hide();
    	communicator._onclose_popup(data);
    	
    	//if (data && (data.auto_close && data.id)) {
    	//	communicator._onclose_popup(data);
    	/*} else if (data && data.id) {
        	console.info('success', data);
        	jQuery('#success_popup').show().find('iframe')[0].contentWindow.postMessage(JSON.stringify({
            	fandrop_message: true,
            	action: 'show_success',
            	data: data
            }), '*');
        	window.hide_timeout = window.setTimeout(function() {
        		// USE THIS TO TURN OFF FADING
        		jQuery('#success_popup').hide('fade');
        	}, 3000);        	
        }*/
    }
    communicator._onbefore_update = function(a) {
    	window.onbeforeunload = function() {
			var leave_message = 'The link is not yet saved. Are you sure you want to navigate out of the page?';
			var e = typeof e == 'undefined' ? window.event : e;
			if (e) e.returnValue = leave_message;
			return leave_message;
		}
    }
    communicator._onafter_update = function(a) {
		window.onbeforeunload = null;
    }
}
