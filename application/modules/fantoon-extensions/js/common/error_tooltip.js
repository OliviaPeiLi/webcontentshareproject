/**
 * TOOLTIPS
 *   Sets custom title popup for the elements with "title" attribute
 * @to-do - very similar to common/custom title - needs to be replaced with it
 */
define(['jquery'], function () {
	$.fn.error_tooltip = function() {
		var self = this;
		this.get_tooltip = function() {
			var tooltip = $('#tab_label');
			//title.remove();
			if (! tooltip.length) {
				tooltip = $('<div id="tab_label" class="tab_label menu" style="display:none;z-index:9999999;"><span></span></div>');
				$('body').append(tooltip);
			}
			return tooltip;
		}
		
		this.on('activate_tooltip', function (event,tooltip_text) {
			var tooltip = self.get_tooltip();
			tooltip.html('<span></span>'+tooltip_text);
			var css = {
					'top': $(this).offset().top - tooltip.height(),
					'left': $(this).offset().left - tooltip.width() - 35
			}
			console.log(css);
			if ($(this).attr('tooltip-class')) {
				tooltip.attr('class', $(this).attr('tooltip-class')+' menu');
			} else {
				tooltip.attr('class','tab_label menu');
			}
			if ($(this).attr('tooltip-pos') == 'left') {
				console.log('LEFT');
				css['top'] = $(this).offset().top;
				css['left'] = $(this).offset().left-tooltip.outerWidth()-10;
			}

			tooltip.css(css).show();
			console.log(tooltip);
		})
		.on('deactivate_tooltip',  function () {
	    	$('#tab_label').hide();
		})
		.on('mousedown', function() {
	    	$('#tab_label').hide();
		});
		

		$(document).on('click', function(e){
			if ($(e.target).is('#tab_label') || $(e.target).closest('#tab_label').length>0) {
				
			} else {
				$('#tab_label').hide();
			}
		})

	};
	/*
	$(function() {
		$('.custom-title').custom_title();
		$('.custom-tooltip').custom_title();
	})
	*/
});