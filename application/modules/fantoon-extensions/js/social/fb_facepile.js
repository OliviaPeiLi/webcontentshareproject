define(['jquery'], function($) {
	//RR - async load / performance
	$('#fb_facepile').attr('src', $('#fb_facepile').attr('data-url'));

	/* RR - if user is not logged in this will be automaticaly hidden
	function fb_facepile() {
		if (typeof FB != 'undefined') {
			FB.getLoginStatus(function(response) {
				console.info('facepile', response);
				if (response.status != 'connected' && response.status != 'not_authorized') {
					$('#fb_facepile').hide();
				} else {
					$('#fb_facepile').attr('src', $('#fb_facepile').attr('data-url'));
				}
			});
		} else {
			window.setTimeout(function() { fb_facepile(); }, 100)
		}
	}
	fb_facepile();
	*/
});