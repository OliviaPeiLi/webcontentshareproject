/**
 * For the request invite page
 */
define(['social/all', 'jquery'], function() {
	
	$(document).on('click', '#facebook_login_button_old', function() {
		fb_login(function(auth) {
			location.href = '/request_invite_fb?access_token='+auth.accessToken+(location.search.replace('?','&'));
		});
		return false;
	});
});