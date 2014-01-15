/*
 * Custom popup for showing messages
 * @to-do - move the html to a view and load this js there, also move this file
 *          ot the place where the view will be added this is not a plugin.
 * @to-do - another idea move all this to ajaxForm
 */
(function(){

	var popup_notification = '#notification_bar';
	var notification_overlay = '#notification_overlay';

	var POPUP_TIMEOUT = 3000;
	var SHOWING_TIME = 100;
	
	$(document).on('popup_info_clear', function() {
		$(popup_notification).html('').hide();
	});
	
	$(document).on('popup_info', function(e, msg, className) {
		
		if (!$(popup_notification).length) {
			$('body').append('<div id="' + popup_notification.replace('#','') + '"></div>');
		}

		if (className && className.indexOf('append') > -1) {
			className = className.replace('append','');
			$(popup_notification).append('<p>'+msg+'</p>').attr('class', className);
		} else {
			$(popup_notification).html('<p>'+msg+'</p>').attr('class', className);
		}
		
		$(notification_overlay).show('fade');
		$(popup_notification).show('fade');
		
		setTimeout(function(){ 
			$(popup_notification).hide('slow');  
			$(notification_overlay).hide('fade');
		}, POPUP_TIMEOUT);
	
	});
	
})();
