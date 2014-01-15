/**
 * This plugin creates a custom scroll to overflow: hidden elements
 * @uses jquery
 * @uses plugins/jquery.mousewheel - for the mousewheel event
 */
define(['jquery', 'plugins/jquery.mousewheel'], function() {

	var scroll_helper_class = 'fd-scroll-helper';
	var scroll_helper = '<span class="'+scroll_helper_class+'" unselectable="on"></span>';
	var scroll_speed = 30;
	//for the dragging
	//@see $('.fd-scroll-helper').on('mousedown'
	var dragging_element;
	
	/**
	 * Adds the scroll helper element to the container
	 */
	function init_scroll($container) {
		$container.append(scroll_helper);
	}
	
	/**
	 * Sets the height of the helper element like the system scroll
	 */
	window.set_height = function ($container) {
		var precent = $container.height() / $container[0].scrollHeight;
		$container.find('.'+scroll_helper_class).height(Math.round($container.height() * precent));
	}
	
	/**
	 * Scrolls the $container with specified offset
	 * @return (boolean) - if the scroll was successfull
	 */
	function scrollTo($container, deltaY) {
		var scroll_top = $container.scrollTop();

		$container.scrollTop(scroll_top + deltaY);

		var precent = $container.scrollTop() / ($container[0].scrollHeight - $container.height());
		if (precent >= 1) return true;
		
		var _top = $container.scrollTop()+ precent*( $container.height() - $('.' + scroll_helper_class ).height() );

		$container.find('.'+scroll_helper_class).css('top', _top);
		$container.trigger('scroll');

		return scroll_top != $container.scrollTop();
	}

	/**
	 * The scroll container
	 */
	$(document)
		.on('mouseenter','.fd-scroll', function() {

			if ( $(this)[0].scrollHeight <= $(this).outerHeight()) return; //the same behavior as overflow: auto
			// if ( $(this)[0].scrollHeight + 5 <= $(this).outerHeight()) return; //the same behavior as overflow: auto
			//create the scroll if doesnt exists

			if (!$(this).find('.'+scroll_helper_class).length) {
				init_scroll($(this));
			}

			scrollTo( $(this), 1 );

			//defines the height on show because the content may be changed after init_scroll
			set_height($(this));
			
			$(this).find('.'+scroll_helper_class).show();
		})
		.on('mouseleave','.fd-scroll', function() {
			if (!dragging_element)	{
				$(this).find('.'+scroll_helper_class).hide();
			}
		})
		.on('mousewheel','.fd-scroll', function(e, delta, deltaX, deltaY) {

			if (!$(this).find('.'+scroll_helper_class).length) return;
			
			if (scrollTo($(this), -deltaY*scroll_speed)) {
				e.preventDefault();
			}
		});
	
	/*
	 * Drag the scroll helper element
	 */
	$(document)
		.on('mousedown','.fd-scroll-helper', function(e) {
			this.drag_start = e.pageY;
			dragging_element = this;
			e.stopPropagation();
		})
		.on('click', '.fd-scroll-helper', function(e) {
			e.stopPropagation();			
		});
	
	$(document)
		.on('mousemove', function(e) {
			if (!dragging_element) return;

			var $container = $(dragging_element).closest('.fd-scroll');

			var mult = $container[0].scrollHeight / $container.height();

			scrollTo( $container, ( e.pageY - dragging_element.drag_start ) * mult );
			set_height($container);
			dragging_element.drag_start =  e.pageY;
		})
		.on('mouseup', function(e) {
			if (!dragging_element) return;
			setTimeout(function(){
				dragging_element = null;	
			},150)
		})
		/**
		 * Prevent text selection while dragging
		 */
		.on('selectstart', function() {
			if (!dragging_element) return;
			return false;
		});
	
		/**
	     * "Scroll to top" Button
	     */
	    $(window).scroll(function(){
			if($(this).scrollTop()>=100)
				$('#ScrollToTop').fadeIn();
			else
				$('#ScrollToTop').fadeOut();
		});

	    /**
	     * Scroll to top button
	     */
		$("#ScrollToTop").click(function(){
			$("html, body").animate({scrollTop:"0px"},400);
			return false
		});		

});