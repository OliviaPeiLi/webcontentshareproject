/*
 * Custom popup for showing messages
 * @to-do - move the html to a view and load this js there, also move this file
 *          ot the place where the view will be added this is not a plugin.
 * @to-do - another idea move all this to ajaxForm
 */
define(['common/utils', 'jquery'], function(u) {

	var POPUP_TIMEOUT = 3000;
	var SHOWING_TIME = 100;
	
	$(document).on('popup_info_clear', function() {
		$('#notification_bar').html('').hide();
	});
	
	$(document).on('popup_info', function(e, msg, className) {
		
		if (!$('#notification_bar').length) {
			$('body').append('<div id="notification_bar"></div>'); //<div id="notification_overlay"></div>
		}
		if (className && className.indexOf('append') > -1) {
			className = className.replace('append','');
			$('#notification_bar').append('<p>'+msg+'</p>').attr('class', className);
		} else {
			$('#notification_bar').html('<p>'+msg+'</p>').attr('class', className);
		}
		
		$('#notification_overlay').show('fade');
		$('#notification_bar').show('fade');
		setTimeout(function(){ 
			$('#notification_bar').hide('slow');  
			$('#notification_overlay').hide('fade');
		}, POPUP_TIMEOUT);

	});
	
});
