/**
 * Logic for the pinterest button
 */
define(["jquery"], function() {

	var pinterest_opened_window = false;
	
	$(document).on('click', '.pin-it-button', function(){

		var $this = this;

		if ($(this).hasClass("inactive")) return false;

		var container = $(this).closest('[data-newsfeed_id]');
		var newsfeed_id = container.attr('data-newsfeed_id');
		console.info('{pinterest} - ', newsfeed_id);
		
		if (php.userUrl) _kmq.push(['identify', ''+php.userUrl+'']);
		_kmq.push(['record', 'pinned a drop on pinterest', {'newsfeed_id':''+newsfeed_id+''}]);
		
		var href = "http://pinterest.com/pin/create/button/?"
						+"url="+$(this).attr('data-url')
						+"&amp;media="+$(this).attr('data-media')
						+"&amp;description="+$(this).attr('data-description')+$(this).attr('data-url');
		
		if (window.showModalDialog) {
			window.showModalDialog(href, '', 'height=600,width=800'); //RR - window title is not supported by IE
		} else {
			window.open(href, '', 'height=600,width=800'); //RR - window title is not supported by IE
			pinterest_opened_window = true;
		}
		
		//Pinit doesnt have any callback to track success event so we just suppose the user pinned the item
		var data = {'newsfeed_id': newsfeed_id};
		
		if (!php.userId && php.ip) {
			var ip = php.ip.split('.');
			data.social_user_id = parseInt(ip[0])+(255*parseInt(ip[1]))+(255*255*parseInt(ip[2]))+(255*255*255*parseInt(ip[3]));
		}
		if (php.referral) {
			data.referral = php.referral;
		}
		
		$.post('/add_share/pinterest', data, function(res) {

			if (!res.status) {
				console.info('share error', res);
				return;
			}

			$($this).addClass("inactive");

			container.find('.js-share_count').text(parseInt(container.find('.js-share_count').text()) + 1);
			$(document).trigger('share_success', ['pinterest']);
		
		},'json');

		return false;
	});

});
