/**
 * Autoscroll. Triggers an ajax request when a content is scrolled to bottom.
 * @uses common.ajaxList -  for json responces 
 * @uses jquery
 * @to-do - write documentation with examples in the wiki
 */
define(['common/ajaxList', 'jquery'], function(){
	
	function split_data(data, callback, ready_callback, i) {
		i = i || 0;
		if (i < data.length) {
			callback.call(this, data[i]);
			window.setTimeout(function() {
				split_data(data, callback, ready_callback, i+1);
			}, 50);

		} else {
			ready_callback.call(this);
		}
	}
	
	//@update RR - 8/2/2012 - Added max_scrolls var
	$(function() {
	$(document)
		.on('scroll_bottom','.fd-autoscroll', function() {

			var $this = $(this);
			var bot_elt = $this.find('.feed_bottom');			
			$this[0].scrolls++;
			console.info('{Autoscroll} - page '+$this[0].scrolls);
			
			if ($this.attr('data-url')) {

				$this.addClass('loading');
				//RR - the 'if' is temporary while we move all requests to json

				if ($this.attr('data-template')) { 
					$.get($this.attr('data-url'), {'page': $this[0].scrolls}, function(data) {

						var callback;
						if ($this[0].ajaxList_process instanceof Function) {
							callback = $this[0].ajaxList_process;
						}

						var template = $($this.attr('data-template'));

						if ( data.length == 0 ) {
							console.info('{Autoscroll} - no more items');
							bot_elt.hide();
						}	else {
							split_data(data, function(current_data) {
								bot_elt.before(template.tmpl(current_data, callback));
							}, function() {
								$this.removeClass('loading');
								$( document ).trigger( 'update' );
							});
						}

					}, 'json');

				} else { // else template = $(this).prevAll('script:first');
					$.get($this.attr('data-url'), {'page': $this[0].scrolls}, function(data) {
						bot_elt.before(data);
						if (!$(data).find(':not(script,link)').length) {
							console.info('{Autoscroll} - no more items');
							bot_elt.hide();
						}
						$this.trigger('scroll_bottom_success', data);
						$this.removeClass('loading');
					});
				}

			} else { 
				$this.trigger('scroll_bottom_success');
			}
		});
	
		$('.fd-autoscroll')
		.each(function() {

			var $this = $(this); //the list container
			if ($this.hasClass('intialized')) return;
			this.scrolls = 0;
			var container = $this.attr('data-container') || window; //the window element or id specified by data-container
			var max_scrolls = $this.attr('data-maxscrolls') || 999;
			var bot_elt = $this.find('.feed_bottom');

			if (!bot_elt.length) return;

			var bottom_padding = bot_elt.hasClass('collections_feed_bottom') ? 200 : 700;

			if ($('#activity_feed_bottom').length)	{
				bottom_padding = bot_elt.attr("id") == 'activity_feed_bottom' ? 40 : bottom_padding;	
			}

			if ($('#notification_feed_bottom').length)	{
				bottom_padding = bot_elt.attr("id") == 'notification_feed_bottom' ? 40 : bottom_padding;	
			}

			$this.addClass('intialized');
			
			bot_elt.click(function() {
				$this.trigger('scroll_bottom');
				return false;
			});

			function checkScroll() {
				if (max_scrolls > 0 && $this[0].scrolls >= max_scrolls) return;
				if ($this.hasClass('loading')) return;
				//console.info('Check scroll', $(this).height(), $(this).scrollTop(), bot_elt.height(), bot_elt.offset().top, -bottom_padding );
				if ( bot_elt.is(':visible') && $(this).height() + $(this).scrollTop() >= bot_elt.height() + bot_elt.offset().top - bottom_padding ) {
					$this.trigger('scroll_bottom');
				}

			}

			var $container = $( container );
			$container.scroll( checkScroll );

			//BP: activate this one when it is registered because one can scroll to the bottom before
			if ( $container.length > 0 ) {
				checkScroll.call( $container[0] );
			}

		});	
	})

    return this;
});
