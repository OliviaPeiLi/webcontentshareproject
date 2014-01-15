/*
 *  Communicator script. Uses the postMessage method for cross-domain communication
 *  @see bar.js
 *  @see external.js
 */
var communicator = function() {
	//console.info('communicator.js')
	var origin, ownerWindow, remoteUrl;
	var self = this;
	
	var JSON = {
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
		stringify_arr: function(jsonData) {
			var strJsonData = '[';
			for (var i=0;i<jsonData.length;i++) {
				if (i > 0) strJsonData += ',';
				temp = jsonData[i];
				if (typeof(temp) == 'object' || typeof(temp) == 'array') {
					s =  JSON.stringify(temp);
				} else if (typeof(temp) == 'string'){
					s = JSON.quote(temp);
				} else if (typeof(temp) == 'undefined'){
					s = '""';
				} else {
					s = '"' + String(temp) + '"';
				}
				strJsonData += s;
			}
			strJsonData += ']';
			return strJsonData;
		},
		stringify: function(jsonData) {
			if (jsonData instanceof Array) return JSON.stringify_arr(jsonData);
			var strJsonData = '{';
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
				
				strJsonData += '"'+item + '":' + s;
				itemCount++;
			}
			strJsonData += '}';
			return strJsonData;
		}
	}
	
	this.messageHandler = function(msg) {
		//console.info('MSG', msg);
		if (!msg) return;
		if (msg.data.indexOf('{') != 0) return;
		if (typeof self._onload != 'function') {
			//console.info('save message from:', msg, msg.data);
			window.tmp_message = msg;
			return;
		}
		
		//console.info('mesage from: ', msg.origin, msg.data);
		tmp_message = null;
		var data = {};		
		try {
			data = $.parseJSON(msg.data);
		} catch (e) {
			try {
				eval('data='+msg.data);
			} catch (e) {
				console.info(e);
				console.info(msg.data);
			}
		}

		if (!data.fandrop_message) return;
		
		if (data.action) {
			switch(data.action) {
				case 'load':
					self.remoteUrl = msg.data.url;
				case 'show_as_bar':	//secondary load functions
				case 'show_as_popup':	//secondary load functions
					self.origin = msg.origin != 'null' ? msg.origin : '*'; // youtube fix
					self.ownerWindow = msg.source;
				default:
					if (typeof self['_on'+data.action] == 'function') {
						try {
							self['_on'+data.action](data);						
						} catch (e) { console.error('_on'+data.action, e); }
					} else {
						//console.info('function: _on'+data.action+'() doesnt exist in: ');
						//for (i in self) if (typeof (self[i]) == 'function') console.info(i+': '+self[i]);
					}
			}
		}	
	}
	
	this.init = function() {	//IE Fix
		//console.info('communicator init');
		if (window.tmp_message) {
			//console.info('call tmp message', window.tmp_message.data);
			this.messageHandler(window.tmp_message); 
		}
	}
	
	// Events
	//this._onload = function() { //Abstract function
	//	console.info('Abstract');
	//}
	
	// Methods
	this.error = function(message, left) { self.post_message({ action:'error', message: message, left:left });  }	
	//this.close = function(data) { self.post_message(jQuery.extend(data, { action:'close' })); }	
	this.start = function() { self.post_message({ action:'start' });  }	
	this.set_clip_mode = function(mode) { self.post_message({ action:'set_clip_mode', mode: mode });  }	
	this.update = function(data) { self.post_message(jQuery.extend(data, { action:'update' }));  }	
	this.set_content = function(content) { self.post_message({ action:'set_content', content: content });  }
	this.after_add = function(data) { self.post_message(jQuery.extend(data, { action:'after_add' }));  }	
	this.before_update = function(data) { self.post_message(jQuery.extend(data, { action:'before_update' }));  }	
	this.after_update = function(data) { self.post_message(jQuery.extend(data, { action:'after_update' }));  }	
	this.drag_end = function(data) { self.post_message(jQuery.extend(data, { action:'drag_end' }));  }	
	this.drag_over = function() { self.post_message({ action:'drag_over' });  }	
	this.drag_out = function() { self.post_message({ action:'drag_out' });  }	
	this.close_popup = function(data) { self.post_message(jQuery.extend(data, { action:'close_popup' })); }	
	this.show_as_popup = function() { self.post_message({ action:'show_as_popup' }); }
	this.show_as_bar = function(data) { self.post_message(jQuery.extend(data, { action:'show_as_bar' })); }
	this.show_as_login = function(data) { self.post_message(jQuery.extend(data, { action:'show_as_login' })); }
	this.show_image_mode = function() { self.post_message({ action:'show_image_mode' }); }
	this.show_video_mode = function() { self.post_message({ action:'show_video_mode' }); }
	this.update_cache = function(link, data) { self.post_message({'link': link, 'data': data, 'action': 'update_cache'}); }
	this.edit = function(id, left) { self.post_message({ action:'edit',left:left,id:id }); }
	this.show_help = function() { self.post_message({ action:'show_help'});  }
	this.search_result = function(search_result) { self.post_message({ 'result': search_result, action:'search_result'}); }
	self._onsearch_result = function(data) { self.search_callback.call(this, data.result); }
	this.search = function(query, callback) {
		self.search_callback = callback;
		self.post_message({'query': query, 'action':'search'}); 
	}
	
	self.pdf2html_callbacks = {};
	this.pdf2html = function(src, callback) {
		self.pdf2html_callbacks[src] = callback;
		self.post_message({'action':'pdf2html', 'src':src}); 
	}
	self._onpdf2html = function(data) {
		$.get('/bookmarklet/pdf2html', {'src':data.src}, function(data) {
			data['action'] = 'pdf2html_callback';
			data['src'] = data.src;
			self.post_message(data);
		},'json');
	}
	self._onpdf2html_callback = function(data) {
		console.info(self.pdf2html_callbacks, data);
		self.pdf2html_callbacks[data.src].call(this, data.html);
	}
	
	this.set_iframe = function(iframe) {
		iframe.unbind('load').bind('load', function() {
			//console.info('message to (iframe load): ', this.contentWindow, JSON.stringify([this.src, window.location.href]));
			this.contentWindow.postMessage(JSON.stringify({"action":'load', "url": window.location.href, "fandrop_message": true}),'*');
		});		
	}
	this.post_message = function(data) {
		if (!self.ownerWindow) return;
		data.fandrop_message = true;
		self.ownerWindow.postMessage(JSON.stringify(data), self.origin);
	}
	
	if (window.addEventListener) {
		//console.info('addEventListener');
		window.addEventListener('message', messageHandler);
	} else if (window.attachEvent) {
		//console.info('attachEvent');
		window.attachEvent('onmessage', messageHandler);
	} else {
		//console.info('onmessage');
		window.onmessage = messageHandler;
	}
	
	
	return this;
}

if (typeof define != 'undefined') {
	if (window.location.href.indexOf('ft/') > -1 
			|| window.location.href.indexOf('localhost') > -1 
			|| window.location.href.indexOf('fantoon.local') > -1 
			|| window.location.href.indexOf('fandrop.com') > -1 
			|| window.location.href.indexOf('127.0.0.1') > -1 
			|| window.location.href.indexOf('fantoon.com') > -1 ) {
		define(communicator);	
	}
}