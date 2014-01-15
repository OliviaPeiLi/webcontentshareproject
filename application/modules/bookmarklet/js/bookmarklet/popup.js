/**
 *  External bookmark page popup code.
 *  @link - /bookmarklet/popup
 *  @uses - bookmarklet/communicator - to send the response data to the injected js and clipboard_ui
 *  @uses - plugins/mentions - for the user typed text
 *  @uses - plugins/jsend - to compress the html sent to the backend
 *  @uses - plugins/token-list - for the collections dropdown
 *  @uses - jquery-ui - 
 *  @uses - jquery - 
 */
define(['bookmarklet/communicator', "plugins/mentions", "plugins/jsend", 'common/fd-scroll', 'plugins/token-list', 'jquery-ui', 'jquery'], function(communicator) {
	
	/* =============== Variables ================== */
	var self = this;
	self.content = null;
	
	/* ================= Direct code ============= */
	$.fn.initTokenInput();
	
	/* ================ Private functions ================== */
	function post_html(newsfeed_id) {
		console.info('Posting html');
		if (!self.content) {
			window.setTimeout(function() { post_html(newsfeed_id); }, 500);
			return;
		}
		console.info('Posting html1');
		$.post('/bookmarklet/add_page_after/'+newsfeed_id, {content: self.content}, function(data) {
			console.info('add page after', data);
			communicator.after_update(data);
		},'json');
	}
	
	function setCookie(name,value,days) {
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime()+(days*24*60*60*1000));
	        var expires = "; expires="+date.toGMTString();
	    }
	    else var expires = "";
	    document.cookie = name+"="+value+expires+"; path=/";
	}
	
	function close_me(data) {
		_gaq.push(['_trackPageview', 'external-scraper-shared-mode-3']);
		console.info('Close popup ', data);
		communicator.close_popup(data);
	}
	
	$.fn.insertAtCaret = function(myValue) {
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
	}
	
	/* ==================================== Events ============================ */
	communicator._onload = function() {
	}
	
	communicator._onshow_as_popup = function(data) {
		if ($("[autofocus]").length) $("[autofocus]").focus();
		if (!$("#scraper_form [name='link_url']").val()) {
			$("#scraper_form [name='link_url']").val(data.url);
		}
		$("#scraper_form [name='img_width']").val(data.width);
		$("#scraper_form [name='img_height']").val(data.height);
		$("[rel='title']").html(data.title);
		$("input[name='title']").val(data.title);
		console.info('Compressing');
		jQuery.jSEND(data.html, function(compressed) {
			console.info('Compressed');
			self.content = compressed;
		});
		
		//RR - remove - http://dev.fantoon.com:8100/browse/FD-3248 
		/*
		$("select.tokenInput").tokenInput('option','addBoxValidate', function(e) {
			if (e.which >= 48 && e.which <= 59 && e.shiftKey || (this.value.length > 40 && e.which != 8) ) {

				//Used for error displaying on failed validation
				if (this.value.length > 40) {
					var tooltip_text = 'Please type shorter name';
				} else {
					var tooltip_text = 'Special Characters are not allowed';
				}
				var tooltip = jQuery('#tab_label');
				if (! tooltip.length) {
					tooltip = jQuery('<div id="tab_label" class="tab_label tab_label_err menu" style="display:none;z-index:9999999;"><span></span><strong></strong></div>');
					jQuery('document.body').append(tooltip);
				}
				tooltip.find('strong').text(tooltip_text);
				var css = {
						'position': 'absolute',
						'background': '#FFAAAA',
						'z-index': 999999999999,
						'border-radius': '3px',
						'padding': '1px 5px',
						'top': jQuery(e.target).offset().top - 32,
						'left': jQuery(e.target).offset().left - 10,
						'font-family': "Arial, 'Times New Roman', sans-serif"
				}
				jQuery(e.target).closest('div').after(tooltip);
				tooltip.css(css).show();
				console.info(tooltip);
				return false;
			} else {
				jQuery('#tab_label').hide();
			}
			return e.which==8 || e.which==37 || e.which==39 || String.fromCharCode(e.which).match(/[^A-Za-z0-9-_\s]/) === null;
		});*/
	}
	communicator.init();
	
	/**
     * Save data
     */     
	$(document).on('submit','#scraper_form', function() {
		var $form = $(this);
		$form.find('.error').hide();
		//Validate
		if (!$("[name='description']").val().replace(/^\s+|\s+$/g,"")) {
			// $form.find('.error.description').show().html('Description field can&#39;t be empty');
			$('#notification_bar').show().html('Description field can&#39;t be empty').delay(2000).fadeOut();
			return false;
		}
		if (!$(".form_left .tokenInput-hidden").length || !$(".form_left .tokenInput-hidden").val()) {
			// $form.find('.form_left .error').show().html('Select a collection');
			$('#notification_bar').show().html('Select a collection').delay(2000).fadeOut();
			return false;
		}
		/* RR - hashtag validation disabled by Alexi request
		if ( ! $("textarea.fd_mentions").val().match(/#[a-zA-Z]/)) {
			$form.find('.error').show().html('You need to use at least one hashtag');
			return false;
		}
		*/
		//End Validate
		$('#bookmark_status_popup').show();
		communicator.before_update();
		
		$.post($(this).attr('action'), $(this).serialize(), function(msg) {
			$('#bookmark_status_popup').hide('fade');
			if (!msg.status) {
				$form.find('.error').show().html(msg.error);
			} else {
				setCookie('last_selected_folder', msg.folder_id);
				close_me(msg);
		       	post_html(msg.id);
			}
	       	$('#postbox, #page_postbox_url').val('');
			communicator.after_add(msg);
		}, 'json');
        return false;
	});
	
	/**
	 * Submit on enter in textarea
	 */
	$(document).on('keydown blur',"textarea.fd_mentions", function(e) {
		if (e.keyCode == 13 && !e.shiftKey && !$(this).closest('form').hasClass('loading')) {
			$('#scraper_form').submit();
			return false;
		}
		$(this).parent().find('.maxLength').text($(this).attr('maxlength') - $(this).val().length);
		$(this).closest('form').find('.error').hide();
	});
	
	/**
	 * Add hashtags
	 */
	$(document).on('click', '.hashtag', function() {
		$('textarea.fd_mentions').insertAtCaret(' '+$(this).attr('href'));
		return false;
	});
	
	/**
	 * @deprecated
	 */
	$(document).on('click', '.clip', function() {
		communicator.show_as_bar();
	});
	
});