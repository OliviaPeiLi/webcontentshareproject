/* *********************************************************
 * JS Logic for system notifications
 *
 * ******************************************************* */

define(['jquery'],function(){
	
	var selector = '#notification_close';

	$(document)
		.on('preAjax', selector, function() {
			$('#systemNotification').hide('fade');
		})
		.on('success', selector, function(e, data) {
			if (!data.status) {
				return;
			}
			if (data.content) {
				$('#systemNotification').html(data.content).show('fade');
			}
		});
	

});
