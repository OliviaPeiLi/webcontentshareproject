/**
 * TOOLTIPS
 *   Sets custom title popup for the elements with "title" attribute
 * @to-do switch to 'live' events
 */
define(['jquery'], function () {
	
	$(document)
		.on('mouseover show_title','.custom-title, .custom-tooltip', function() {

			if (!$(this).data('title')) {
				$(this).data('title', $(this).attr('title')).attr('title','');
			}

			if ($(this).hasClass("js-disabled")) return;

			var titleText = $(this).data('title');
			if (!titleText) return;

			var title = $('#tab_label');
			if (! title.length) {
				title = $('<div id="tab_label" class="tab_label" style="display:none;z-index:9999999;"><span></span></div>');
				$('body').append(title);
			}
			
			title.html('<span></span>' + titleText);
			
			var css = {
					'top': $(this).offset().top - title.height() - 15,
					'left': $(this).offset().left - title.width()
			}
			
			if ($(this).attr('title-class')) {
				title.attr('class', $(this).attr('title-class'));
			} else {
				title.attr('class','tab_label');
			}

			if ($(this).attr('title-pos') == 'bottom') {
				css['top'] = $(this).offset().top + $(this).outerHeight();
				css['left'] = $(this).offset().left+ ($(this).outerWidth()-title.outerWidth())/2;
			}
			else if ($(this).attr('title-pos') == 'top') {
				css['top'] = $(this).offset().top - title.outerWidth() - 11;
				css['left'] = $(this).offset().left+ ($(this).outerWidth()-title.outerWidth())/2;
			}
			else if ($(this).attr('title-pos') == 'left') {
				css['top'] = $(this).offset().top;
				css['left'] = $(this).offset().left  - title.outerWidth() - 11;				
			}
			else if ($(this).attr('title-pos') == 'right') {
				css['top'] = $(this).offset().top;
				css['left'] = $(this).offset().left + $(this).outerWidth() + 11;
			}
			title.css(css).show();
		})
		.on('mouseout hide_title','.custom-title, .custom-tooltip',  function () {
	    	$('#tab_label').hide();
		})
		.on('mousedown','.custom-title, .custom-tooltip', function() {
	    	$('#tab_label').hide();
		});
	
});