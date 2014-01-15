/*
 * General code for popups. Extension to bootstrap popup
 * @link http://twitter.github.com/bootstrap/javascript.html#modals
 * @uses jquery
 * @uses jquery-ui - for draggable effect
 * @uses plugins/bootstrap-modal
 * @uses plugins/bootstrap-transition
 * @to-do - write docs in the wiki
 */
define(['jquery', 'jquery-ui', 'plugins/bootstrap-modal', 'plugins/bootstrap-transition'], function() {

	var self = this;
	var loader = $('<div class="loader_loading"></div>');
		loader.appendTo($('body'));

	window.popup_cache = [];

	$(document).on('click',"[rel='popup']", function() {
		
		if ($(this).hasClass('disabled')) return false;
		self.target = $(this);
		dataform = target.attr('data-form');
		loader.fadeIn();
		console.info('{bootsrap popup} - trigger', $(this));

		if ($(this).attr('data-norefresh') && this._dialog) {

			this._dialog.dialog('open');
			loader.hide();

			return false;
		} else if ( dataform ) {

			if ( !$(dataform).find('.required').val() && !$(dataform).find('.required').text() ) {
				$( dataform +' .error').show();
				loader.hide();
				return false;
			} else {
				$( dataform +' .error').hide();
			}

			$.post($(this).attr('href'), $( dataform ).serialize(), popupHandler);

		} else if ($(this).attr('data-url')) {
			if ($(this).attr('data-url').substr(0,1) == '#') {
				popupHandler( $($(this).attr('data-url') ).show());
			} else if ($(this).attr('data-url').substr(0,4) == 'http') {
				popupHandler('<iframe src="'+$(this).attr('data-url')+'"></iframe>');
			} else {
				// popup handler
				$.get($(this).attr('data-url'),{}, popupHandler);
			}

		} else if ($(this).attr('href').substr(0,1) == '#') {
			if ($($(this).attr('href')).length <= 0) {
				//url = php.baseUrl + 'drop/' + (parseInt($(this).attr('data-newsfeed_id')) > 0 ? $(this).attr('data-newsfeed_id') : $(this).closest('.newsfeed_entry').attr('data-newsfeed_id'));
				//console.info('{bootstrap popup} - not found', $(this).attr('href'), 'redirecting');
				//console.log('URL: ' + url);
				document.location.href = php.redirectUrl;
			}else {
				popupHandler($($(this).attr('href')).show());
			}
		} else if ($(this).attr('href').substr(0,4) == 'http') {
			var w = $(this).attr('data-width') || 800;
			var h = $(this).attr('data-height') || 500;
			popupHandler('<iframe src="'+$(this).attr('href')+'" '+(w ? 'width="'+w+'"' : '')+' '+(h ? 'height="'+h+'"' : '')+'></iframe>');
		} else {
			$.get($(this).attr('href'),{}, popupHandler);
		}

		return false;
	});
	
	$('[rel=popup] a').on('click', function(e) { e.stopPropagation(); });
	$('[rel=popup] textarea').on('click', function(e) { e.stopPropagation(); });

	function popupHandler(data) {

		//$('.menu').hide();
		loader.hide();
		if (typeof data == 'string' && data.indexOf('{"status":"error"') === 0) {
			data = jQuery.parseJSON(data);
			if (data.error) $( dataform +' .error').html(data.error);
			$( dataform +' .error').show();
			return;
		}
		
		var div = null;
		var cached = false;

		if (typeof data == 'string') {
			if (target.attr('data-group')) {
				if (window.popup_cache[target.attr('data-group')]) {
					div = window.popup_cache[target.attr('data-group')];
					cached = true;
				} else {
					div = $('<div style="width:auto"/>');
					div.appendTo('body');
					window['popup_cache'][target.attr('data-group')] = div;
				}
			} else {
				div = $('<div style="width:auto"/>');
				div.appendTo('body');
			}
			div.addClass('modal').html(data);
		} else {
			div = data.addClass('modal');
		}
		
		div.find('.modal-header, .new_close').remove();
		var title = typeof target.attr('title') != 'undefined' ? target.attr('title') : target.attr('data-title');
		if (! target.attr('data-hidetitlebar') && typeof title != 'undefined') {
			if (title) {
				div.prepend('<div class="modal-header">' +
							'<button class="new_close" data-dismiss="modal"></button>' +
							'<h3>' + title + '</h3>' +
							'</div>');
			} else {
				div.prepend('<button class="new_close" data-dismiss="modal"></button>');
			}
		}
		if (cached && div.is(":visible")) {
			return;
		}
		div.hide();
		
		target.trigger('before_show', [div]);
		if (div.hasClass('fd-modal')) {
			var backdrop = "static";
		} else {
			var backdrop = true;
		}

		$('body').css({'overflow':'hidden','margin-right':17});
		div.addClass('modal').addClass('fade').show();
		if (!div.hasClass('no-auto-position')) {
			div.css({
				'margin-left':-div.outerWidth()/2 || -250,
				'margin-top': -Math.min( div.height()/2 || 280, $(window).height()/2-50 )   // Removed extra comma - such a comma is usually not accepted by IE. SylwiaFP. 
			})
		}
		
		div.modal({
			keyboard: true,
			show: true,
			backdrop: backdrop
		})
		.on('show', function() {
			console.info('SHOW');
		})
		.on('shown', function() {
			console.info('SHOWN');
			//$('#header').css('zIndex',1);
		})
		.on('hide', function() {
			console.info('HIDE', this);
			$('.token-input-dropdown-google').hide();
			$('.token-input-dropdown-fd_dropdown').hide();
			$('.loading_icon').remove();
			//$('#header').css('zIndex',100000);
		})
		.on('hidden', function() {
			console.info('HIDDEN');

			// clean up upload_img_filename when close
			if ($('#upload_img_filename')) {
				$('#upload_img_filename').val('');
			}
			$('body').css({'overflow':'','margin-right':''});
			//console.info('close opened dropdown');
			$('#hdr_notifications').trigger('ft_dropdown_close');
			//bobef: #FD-2118
			if ( !div.hasClass('in') ) {
				div.modal('removeBackdrop');
			}
			//end of #FD-2118
		});
		//BP: fix of #FD-3299
		//    too buggy, Radil recommended to turn it off and I don't like the dragging feature either
		/*if (div.find('.modal-header').length) {
			div.draggable({
				cursor: 'move',
				handle: '.modal-header'
			});
		}*/
		//BP: end of #FD-3299
	}
	
});
