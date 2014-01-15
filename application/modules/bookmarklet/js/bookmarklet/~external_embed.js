var iframe, preview;
	
function pre_init() {
	//console.info('pre init');
    if (typeof jQuery == "undefined") {
    	var a = false;
        fileref = document.body.ownerDocument.createElement("script");
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", "http://www.fandrop.com/js/modules/plugins/jquery.min.js");
        fileref.setAttribute("charset", "UTF-8");
        fileref.onload = fileref.onreadstatechange = function () {
        	//console.info(a, this.readyState);
            if (!a && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
                a = true;
                fileref.onload = fileref.onreadystatechange = null;
                pre_init();
            }
        };
        document.body.appendChild(fileref);
    } else {
    	if (jQuery('#fandrop_embed_btn').length) {
    		init();
    	} else {
    		jQuery(document).ready(function() {
    			if (jQuery('#fandrop_embed_btn').length) {
	    			init();
    			} else {
    				alert('The fandrop button is not found! Make sure you have included it somewhere in the page');
    			}
    		});
    	}
    }
}
pre_init();

function init() {
	//console.info('init');
	var btn = jQuery('#fandrop_embed_btn');
	var theme = {
			'display': 'block',
			'position': 'relative',
			'width': 75, 'height': 25,
			'background': '#f0eded url("'+baseUrl+'/images/dropIt_site.png") no-repeat 0 0',
			'color': '#656565',
			'padding': '4px 8px',
			'border-radius': '3px',
			'box-sizing': 'border-box',
			'-moz-box-sizing': 'border-box',
			'-web-kit-box-sizing': 'border-box'
	};
	switch(btn.attr('data-theme')) {
		case 'theme 1': theme['width'] = 75; break;
	}
	btn.css(theme);
	if (btn.attr('data-count')) {
		btn.append('<span/>').css({
			'position': 'absolute', 'left': '100%', 'border': '1px solid #D0D0D0', 'padding': '0 5px'
		});
		jQuery.getScript(baseUrl+'get_embed_count.js?link='+(btn.attr('data-link') || window.location.href)+'&selector='+btn.attr('data-content'));
	}
			
	var loaded_scripts = 0;
	for (var i=0; i<scripts.length; i++) {
	    jQuery.getScript(scripts[i]+(scripts[i].indexOf('?') == -1 ? '?v='+Math.round(Math.random()*100) : ''  ), function () {
	    	loaded_scripts++;
	    	if (loaded_scripts >= scripts.length) post_init();
	    });
	}
}

function post_init() {
	//console.info('post_init');
	var btn = jQuery('#fandrop_embed_btn');
	var el = jQuery(btn.attr('data-content'));
    var comm = communicator();
    comm._onload = function() {}
	comm._onset_content = send_contents;
	comm._onclose = function() { iframe.hide(); }
	comm._onafter_add = function() {
		window.setTimeout(function() {
			iframe.hide();
		},10000);
	}
    iframe = jQuery('<iframe src="'+baseUrl+'bookmarklet/embed_popup" allowtransparency="true"></iframe>');
    iframe.css({
    	'display': 'none',
    	'position': 'absolute',
    	'z-index': 2147483647,
    	'left': '50%', 'top':'50%',
    	'width': 620, 'height': 337,
    	'margin-left': -310, 'margin-top': -100
    })
    preview = jQuery('<div style="'
    		+'filter: progid:DXImageTransform.Microsoft.Alpha(opacity = 30); '
    		+'position: absolute; '
    		+'background-color: #1346AC;'
    		+'background-color: rgba(19, 70, 172, 0.3);'
    		+'border-color: rgba(3, 54, 156, 0.3);'
    		+'border: 3px solid'
    		+'border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px;'
    		+'box-shadow: 0 5px 12px 6px rgba(80, 80, 80, 0.55);'
    		+'-moz-box-shadow: 0 5px 12px 6px rgba(80, 80, 80, 0.55);'
    		+'-webkit-box-shadow: 0 5px 12px 6px rgba(80, 80, 80, 0.55);'
    		+'z-index: 2147483644;'
    		+'display: none;"></div>');
    preview.css({
    	'top': el.offset().top, 'left': el.offset().left,
    	'width': el.width(), 'height': el.height()
    });
	jQuery('body').append(iframe, preview);
	btn.click(function() { iframe.show(); })
		.hover(function() {
			preview.show();
		}, function() {
			preview.hide();
		});
	comm.set_iframe(iframe);
}

function send_contents() {
	var btn = jQuery('#fandrop_embed_btn');
	var el = jQuery(btn.attr('data-content'));
	var type = get_type(el);
	var html = get_contents(el);
    if (navigator.userAgent.indexOf('MSIE') > -1) {
    	html = html.replace(/<object/gi,'<object type="application/x-shockwave-flash"')
					.replace(/classid=.*? /gi,'')
					.replace(/classid=".*?"/gi,'')
	}
    var content = {
    	'html': html,
    	'type': type,
    	'width': -el.width(),
    	'height': -el.height(),
    	'link': btn.attr('data-link') || window.location.href,
    	'title': btn.attr('data-title') || jQuery('title').html(),
    	'description': btn.attr('data-description') || jQuery('meta[name=description]').attr('content'),
    	'selector': btn.attr('data-content')
    }
    iframe.get(0).contentWindow.postMessage(JSON.stringify({
    	fandrop_message: true,
    	action: 'set_content',
    	content: content
    }), '*');
}

function get_contents(el) {
    var prefix = '';
    var postfix = '';
	var appendindex = 0;
	var pointers = {};
	
    jQuery(el).find('*').each(function (index) {
    	jQuery(this).attr('rel',appendindex);
    	pointers[appendindex] = jQuery(this);
	    appendindex++;
	});
	
	if (jQuery(el)[0] && jQuery(el)[0].tagName == 'BODY') {
		var newclone = jQuery(el).clone(true);
	} else {
		var newclone = jQuery(jQuery(el).outerHTML().replace(/<!--.*?-->/g, ''));
	}
			
    //Clean
    newclone.find('script').remove();
    
    //Validate
    if (newclone[0].tagName == 'TD') {
        var prefix = '<table><tr>';
        var postfix = '</tr></table>';
    } else if (newclone.get(0).tagName in {'TR':'','TBODY':'','THEAD':'','TFOOT':''}) {
    	var prefix = '<table>';
    	var postfix = '</table>';
    } else if (newclone.get(0).tagName == 'LI') {
    	var prefix = '<ul>';
    	var postfix = '</ul>';
    } else  if (newclone.get(0).tagName in {'DL':'','DT':''}) {
        var prefix = '<ol>';
        var postfix = '</ol>';
    }
    
    //css parsing via classes
    newclone.find('*').each(function (index) {
        var $this = jQuery(this);
        var donor = pointers[$this.attr('rel')];
        if (!donor)  {
        	return;
        }
        
        $this.getStyleObject(donor)
        	.removeAttr('class').removeAttr('id').removeAttr('rel')
        	.removeAttr('onclick').removeAttr('onmousedown').removeAttr('onmouseover');
        
        if (this.tagName == 'A')  {
        	$this.attr('href',jQuery.toAbsURL($this.attr('href')));
        	$this.attr('target','_blank');
        }
        if (this.tagName == 'IMG') $this.attr('src', this.src.replace(/^\s\s*/, '').replace(/\s\s*$/, ''));
        if (this.tagName == 'OBJECT' && $this.attr('data')) $this.attr('data', jQuery.toAbsURL($this.attr('data')));
        if (this.tagName == 'EMBED' && $this.attr('src')) $this.attr('src', jQuery.toAbsURL($this.attr('src')));
    });
    
    if (newclone.attr('href')) {
    	newclone.attr('href',jQuery.toAbsURL(newclone.attr('href')));
    	newclone.attr('target','_blank');
    }
    if (newclone.get(0).tagName == 'IMG') newclone.attr('src', newclone.attr('src').replace(/^\s\s*/, '').replace(/\s\s*$/, ''));
    if (newclone.get(0).tagName == 'OBJECT' && newclone.attr('data')) newclone.attr('data', jQuery.toAbsURL(newclone.attr('data')));
    if (newclone.get(0).tagName == 'EMBED' && newclone.attr('src')) newclone.attr('src', jQuery.toAbsURL(newclone.attr('src')));
    if (newclone.get(0).tagName == 'IFRAME' && newclone.attr('src').indexOf('.jpg') > -1) newclone = jQuery('<img src="'+newclone.attr('src')+'"/>');
    
    newclone.getStyleObject(el, true).removeAttr('class');
   
    newclone.css({'position': 'relative', 'overflow':'hidden', 'margin':0, 'margin-left':0,'margin-top':0,'margin-right':0,'margin-bottom':0});
    newclone.attr('data-style', newclone.attr('data-style').replace(/margin[^;]*;/g,''));
    newclone.attr('data-style', newclone.attr('data-style').replace(/display:none;/g,''));
    //end of parsing
    
	newclone.show();
    
    newclone.find('style').remove();
    
    return prefix + newclone.outerHTML() + postfix;
}

function get_type(el) {
	if (el.find('img').length == 1 && !el.text()) {
		return 'image';
	} else if (el.find("video, embed, iframe[src*='youtube.com'], iframe[src*='vimeo.com'], iframe[src*='dailymotion.com'], iframe[src*='d.yimg.com/'], iframe[src*='hub.video.msn.com/embed/'], object").length >= 1 && el.text().length < 500) {
		return 'embed';
	} else {
		return 'html';
	} 
}