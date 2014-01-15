/**
 * Logic for handling all dropdown menus, such as notifications, edit, etc.
 * The button that invokes the menu eneds to have a class 'ft_dropdown'
 * @to-do switch to "on" events
 * @to-do write docs in the wiki
 */
define(["jquery"], function() {
	
	var opened_ft_dropdown = null;
	var dropdown_wrap = '.ft-dropdown, .ft-dropdown-hover';

	$(document).on('mouseenter click', dropdown_wrap, function(e) {
		//dont open on mouseenter if doesnt have the -hover class, however
		//-hover dropdowns will open also on click to be compatible with tablets
		console.log(e.type,e.target);
		if (e.type == 'mouseenter' && !$(this).hasClass('ft-dropdown-hover')) {
			return false;
		}
		if (e.type == 'click' && $(this).hasClass('ft-dropdown-hover')) {
			console.log('CLICK registered');
		} else {
		
			var $target = $('#'+$(this).attr('rel'));
			if ($target.hasClass("menu_active")) return false;
			
			if (opened_ft_dropdown) {
				opened_ft_dropdown.trigger('ft_dropdown_close');
			}
			$(this).trigger('ft_dropdown_open');
			return false;
		}
	})
	.on('ft_dropdown_open', dropdown_wrap, function() {
		var $target = $('#'+$(this).attr('rel'));
		var target_class = 'ft-dropdown-target';
		if ($(this).hasClass('ft-dropdown-hover') && !$(this).hasClass('keep_on_mouseleave')) {
			var target_class = 'ft-dropdown-hover-target';
		}
		$target.addClass(target_class).show(100);
		// $target.on('click', function(e) { e.stopPropagation(); })
		$target.addClass('menu_active');
		opened_ft_dropdown = $(this);
	})
	.on('ft_dropdown_close', dropdown_wrap, function() {
		var $target = $('#'+$(this).attr('rel'));

		$target.hide(100);
		$target.removeClass('menu_active');
		opened_ft_dropdown = null;
	});
	
	//for NORMAL MENUS (close on click out)
	$(document).on('click', function(e) {
		if (opened_ft_dropdown 
				&& !$(e.target).closest('.ft-dropdown-target').length
				&& !$(e.target).closest('.ui-autocomplete').length
		) {
			opened_ft_dropdown.trigger('ft_dropdown_close');
			opened_ft_dropdown = null;
		}
	});

	$(document).on('click', '.ft-dropdown-target .close_popup',  function(){
		if (opened_ft_dropdown) opened_ft_dropdown.trigger('ft_dropdown_close');
	});

	//for Hover MENUS (close on mouse leave)
	$(document).on('mouseleave','.ft-dropdown-hover', function(e) {
		if (opened_ft_dropdown && e.relatedTarget && !$(e.relatedTarget).closest('.ft-dropdown-hover-target').length ) {
			opened_ft_dropdown.trigger('ft_dropdown_close');
		} else {
		}
	});

	$(document).on('mouseleave','.ft-dropdown-hover-target', function(e) {
		if (opened_ft_dropdown && e.relatedTarget != opened_ft_dropdown[0]) {
			opened_ft_dropdown.trigger('ft_dropdown_close');
		}
	});

});
