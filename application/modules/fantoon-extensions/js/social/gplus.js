/**
 * Logic for google plus button
 */
define(['jquery'], function() {
	
	var btn = '.share_gplus_app';
	
	window.plusOneCallback = function(data) {
		console.info('plus one callback', data);
	}
	
	$(document).on('click', btn, function() {

		if ($(this).hasClass("inactive")) return false;
		var container = $(this).closest('[data-newsfeed_id]');
		var newsfeed_id = container.attr('data-newsfeed_id');
		
		if ($(this).attr('data-url')) {
			var url = $(this).attr('data-url');
		} else {
			var url = php.baseUrl+'/drop/'+container.attr('data-url');
		}
		console.info('{gplus} - share', url); 
		if (window.showModalDialog) {
			window.showModalDialog("https://plus.google.com/share?url="+url, '', 'height=300,width=600'); //RR - window title is not supported by IE
		} else {
			window.open("https://plus.google.com/share?url="+url, '', 'height=300,width=600'); //RR - window title is not supported by IE
		}
		//They dont have callback yet: https://code.google.com/p/google-plus-platform/issues/detail?id=232
			console.info('{gplus} - callback', this);
			$(this).addClass('inactive');
			var data = {'newsfeed_id': newsfeed_id};
			if (!php.userId) {
				var ip = php.ip.split('.');
				data.social_user_id = parseInt(ip[0])+(255*parseInt(ip[1]))+(255*255*parseInt(ip[2]))+(255*255*255*parseInt(ip[3]));
			}
			if (php.referral) {
				data.referral = php.referral;
			}
			$.post('/add_share/gplus', data, function(res) {
				if (!res.status) {
					console.info('share error', res);
					return;
				}
				container.find('.js-share_count').text(parseInt(container.find('.js-share_count').text()) + 1);
				$(document).trigger('share_success', ['gplus']);
			},'json');
		return false;
	});
})