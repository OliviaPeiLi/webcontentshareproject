/**
 *  Handles the logic for the bookmarklet bar. Loaded inside an inframe injected in a remote site by external.js
 *  @package Bookmarklet
 *  @autor Radil
 *  @link /bookmarklet
 *  @uses bookmarklet/communicator - general communicator from the injeted js to the iframe. Using postMessage.
 *  @uses plugins/jsend - to commpress the html sent to the iframe for faster transfer.
 */
define(["bookmarklet/communicator",  "plugins/jsend"], function(communicator) {
	
	/* =========== Vars ============ */
	var self = this;
	self.communicator = communicator;
	self.html_content = null;
	self.edit_popup_dialog = $('#bar-edit-image');
	var videoIco = '/images/video-icon.png';
	var htmlIco = '/images/defaultHTML2.png';
	this.loader = htmlIco;
	
	/* =============== Private Functions ============= */
	
	_gaq.push(['_trackPageview', 'external-scraper-bar-mode-opened']);
	
	function setCookie(name,value,days) {
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime()+(days*24*60*60*1000));
	        var expires = "; expires="+date.toGMTString();
	    }
	    else var expires = "";
	    document.cookie = name+"="+value+expires+"; path=/";
	}

	function getCookie(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
	}

	function deleteCookie(name) {
	    setCookie(name,"",-1);
	}
	
	/**
	 * Populates local variable html_content. Function is used for async behavior of the bookmarklet
	 * @param html_content - the html to be compressed
	 */
	function set_content(html_content) {
		jQuery.jSEND(html_content, function(compressed) {
			console.info('Compressed');
			self.html_content = compressed;
		});
	}

	/**
	 * Sends the data to the backend and adds the drop
	 * @param interest - deprecated
	 * @param data (object:{
	 * 			folder:      - the selected folder_id from the preview popup dropdown
	 * 			interests:   - deprecated
	 * 			type:        - content, embed, photo
	 * 			link:        - original url from where the content is dropped
	 * 			title:       - deprecated
	 * 			description: - user typed text in the preview popup
	 * 			width:       - width of the selected content
	 * 			height:      - height of the selected content
	 * 		}) 
	 */
	function add_image(interest, data) {
		console.info('add image', data);
		var ul = interest.find('ul:first');
		var span = ul.parent().find('span:first').hide();
			
		var post_data = {
			'folder_id': data.folder,
			'interests': data.interests,
			'link_type': data.type,
			'link_url': data.link,
			'title': data.title,
			'description': data.description,
			'img_width': (data.width || 0),
			'img_height': Math.min(data.height||0, 2000)				
		}


		if (data.type == 'image') {
			post_data['activity[link][source_img]'] = data.url;
		} else if (data.type == 'screenshot' || data.type == 'html') {
			if (img = post_data['link_url'].match(/vzaar.com\/videos\/(.*)/)) {
				post_data['link_url'] = 'http://view.vzaar.com/'+img[1]+'/thumb';
			}
		} else if (data.type == 'embed') {
			post_data['activity[link][media]'] = encodeURIComponent(data.data);
			post_data['img'] = data.url;
		} else if (data.type == 'text') {
		}
		if (data.content) {
			set_content(data.content);
		}

		window.setTimeout(function() { span.show(); },200); // IE fix - breaks the image position when over the title span element


		$(document).ajaxError(function(e, xhr, settings, err) {
			console.info('ajax error!');
			communicator.after_add({error: 'Error 500 - please try again later.'});
			communicator.error('Error 500 - please try again later.');
		});

		$.post('/bookmarklet/add_image', post_data, function(responce_data) {
				responce_data['auto_close'] = $('#bar .fd-setting [name=auto_close]').is(':checked')
				communicator.after_add(responce_data);
				var folders = php.bar_options.folders;
		    	var hasit = false;
		    	for (var i=0;i< folders.length; i++) if (folders[i].id == responce_data.folder_id) hasit = true;
		    	if (!hasit) {
		    		php.bar_options.folders.push({'id':responce_data.folder_id, 'name':responce_data.folder_name});
		    	}

				if (!responce_data || !responce_data.id) {
					communicator.error(responce_data.error ? responce_data.error : 'Database error pleace try again later.');
					return;
				}

				post_data.id = responce_data.id;
				if (data.type == 'image' && !responce_data.thumb) {
					$.post('/bookmarklet/add_image_after/'+post_data.id, {}, function(data) {
						if (!data.status) {
							communicator.error('Error uploading image.');
							return;
						}
						communicator.after_update(data);
					},'json');
				} else if (data.type == 'html' || data.type == 'text') {
					function post_html() {
						if (!self.html_content) {
							window.setTimeout(function() { post_html(); }, 100);
							return;
						}
						$.post('/bookmarklet/add_html_after/'+post_data.id, {'content': self.html_content}, function(after_data) {
							if (!after_data.status) {
								communicator.error('Error uploading html.');
								return;
							}
							communicator.after_update(after_data);
						},'json');
					}
					window.setTimeout(function() { post_html(); }, 100);
				} else {
					communicator.after_update();
				}
		},'json');
		return true;
	}
	
	/* ===================== Events ====================== */
	/*
	$(window).unload(function() {
		$('li.screenshot').each(function () {
			$.ajax('/bookmarklet/delete/'+$(this).attr('link-id'), { async: false });
		});
	});
	*/
	$('[help]').hover(function() {
	 	$('#bar').addClass('help-mode-select-opened');
	 	communicator._onload(null, true);
		$('.ui-tooltip.'+$(this).attr('help')).show();
	}, function() {
	 	$('#bar').removeClass('help-mode-select-opened');
	 	communicator._onload(null, true);
		$('.ui-tooltip.'+$(this).attr('help')).hide();
	});
	
	communicator._onload = function(data, no_start) {
		//console.info('_onload');
		php.bar_options.last_selected_folder = getCookie('last_selected_folder')
		communicator.show_as_bar({
						no_start: no_start,
						user_uri: php.userUrl,
						user_avatar: php.userAvatar,
						height: $('#bar').outerHeight(true) + parseInt($('#bar').css('border-top-width')) + parseInt($('#bar').css('border-bottom-width')),
						options: php.bar_options,
					});
	}
	
	communicator._onupdate = function(data) {
		$("li[data-id='"+data.newsfeed_id+"'] .link_button a")
			.attr('href', php.baseUrl+'folder/'+data.uri_name+'/'+data.folder.name+'/'+data.folder.id);
	}
	
	communicator._onset_content = function(data) {
		console.info('set content');
		set_content(data.content);
	}
	
	communicator._ondrag_end = function(data) {
		_gaq.push(['_trackPageview', 'external-scraper-shared-mode-'+$("#clipAndScroll_container input[name='clip_mode']:checked").val()]);
		for (var folder_id in data.folder) {}
		setCookie('last_selected_folder', folder_id);
		$.get('/bookmarklet/watchdog', {}, function(auth) {
			if (auth == 'OK') {
				add_image( $('#bar_public_box'), data);
			} else {
				window.location.href = '/bookmarklet';
			}
		});
		$('#bar_public_box').removeClass('hover'); 
	}
	
	communicator._onstart = function() {
		$('#clipAndScroll_container').removeClass('paused');
		communicator._onload(); //refresh the iframe height for the external
	}

	communicator._onsearch = function(data) {
		$.get('/search/ajax_people', {'mentions': true, 'term': data.query}, function(search_result) {
			communicator.search_result(search_result);
		},'json');
	}
	
	communicator._onupdate_cache = function(data) {
		console.info(data);
		$.post('/bookmarklet/update_cache', data, function(response) {});
	}
	
	communicator.init();
		
	$(document).on('click', '#bar a.drop_page', function() {
		_gaq.push(['_trackPageview', 'external-scraper-quick-link-opened']);
		communicator.show_as_popup();
		
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.people.identify(user);
			mixpanel.track('BOOKMARKLET: Drop Page', {'user':user});
		}
		
	});
	
	$(document).on('click','#bar a.image_mode', function() {
		_gaq.push(['_trackPageview', 'external-scraper-image-mode-opened']);
		communicator.show_image_mode();
		
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.people.identify(user);
			mixpanel.track('BOOKMARKLET: Image mode', {'user':user});
		}
		
	});
	
	$(document).on('click', '#bar a.close', function() {
		communicator.close_popup();
		return false;
	});
	
	$(document).on('update', function() {
		$('li.sample').data({
			'activity[link][thread_id]': 1,
			'activity[link][link]': '',
			'activity[link][img]': '',
			'activity[link][title]': '',
			'activity[link][content]': '',
			'description': '',
			'activity[link][media]': '',
			'page_type': 'page',
			'thread_id': 1,
			'link_type': 'content'
		});
		communicator.set_iframe(self.edit_popup_dialog);
		communicator._onclose_popup = function() {
			self.edit_popup_dialog.hide();
		}
	}).trigger('update');
});