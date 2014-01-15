/**
 *  External bookmarklet 'bookmark page popup' code. Holds the same functionality as internal share link popup
 *  so its defined as its child.
 */
define(['bookmarklet/communicator', "plugins/jsend", 'plugins/token-list', 'jquery'], function(communicator) {
	var self = this;
	communicator._onload = function() {
		communicator.set_content();
	}
	communicator.init();
	
	communicator._onset_content = function(data) {
		$('#external_postbox .pagetitle span').html(data.content.title);
		$('#external_postbox .pagedescr textarea').val(data.content.description);
		if (data.content.type == 'html') {
			jQuery.jSEND(data.content.html, function(compressed) {
				console.info('Compressed');
				self.html = compressed;
			});
			data.content.html = '';
		}
		self.data = data.content;
		console.info(self.data);
		$('#scraper_form submit').show();
	}
	
	$('#external_main .clipboard-popup-close').live('click', function() {
		communicator.close();
		return false;
	});
	
	console.info(php);
	$('#scraper_form').live('submit', function() {
		self.data.description = $(this).find('textarea').val();
		self.data.folder_id = $(this).find("input[name='folder_id[]']").val();
		
		window.onbeforeunload = function() {
			var leave_message = 'The link is not yet saved. Are you sure you want to navigate out of the page?';
			var e = typeof e == 'undefined' ? window.event : e;
			if (e) e.returnValue = leave_message;
			return leave_message;
		}
		$.post('/bookmarklet/add_image', self.data, function(responce_data) {
			window.onbeforeunload = null;
			if (!responce_data || !responce_data.id) {
				$('#external_postbox').html('<p class="error">'+(responce_data.error ? responce_data.error : 'Database error pleace try again later.')+'</p>');
				return;
			}
			
			$('#external_postbox').html('<h1>'+php.lang.bookmarklet_success_message+'<a href="'+responce_data.folder_url+'" target="_blank">'+responce_data.folder_name+'</a></h1>');
						
			communicator.after_add(responce_data);
			
			if (data.type == 'screenshot') {
				var top = 0;
				$.post('/bookmarklet/screenshot', {top: top, id: responce_data.id, url: post_data['activity[link][link]'] }, function (data) {
					if (!data || !data.result) {
						communicator.error('Error uploading image.', li.offset().left)
						return;
					}
					li.find('img').attr('src', data.result);
				},'json');
			} else if (data.type == 'image') {
				$.post('/bookmarklet/add_image_after/'+post_data.id, function(data) {
					if (!data.status || !data.thumb) {
						communicator.error('Error uploading image.', li.offset().left)
						return;
					}
					//li.find('img').attr('src', data.thumb);
					communicator.after_update(data);
				},'json');
			} else if (data.type == 'html' || data.type == 'text') {
				function post_html() {
					if (!self.html) {
						window.setTimeout(function() { post_html(); }, 100);
						return;
					}
					$.post('/bookmarklet/add_html_after/'+responce_data.id, {'content': self.html}, function(after_data) {
						if (!after_data.status) {
							$('#external_postbox').html('<p class="error">Error uploading html</p>');
							return;
						}
						communicator.after_update(after_data);
					},'json');
				}
				post_html();
			} else {
				if (data.type == 'embed' && !responce_data.thumb) {
					li.find('img').attr('src', htmlIco);
				}
			}
		},'json');
		return false;
	}) ;
	
	$.fn.initTokenInput();
	$('#scraper_form submit').hide();
});