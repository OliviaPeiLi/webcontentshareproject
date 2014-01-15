/**
 * Logic for the twitter share btn
 */
define(['jquery'], function() {
	
	/* ================= Variables ================= */
	var self = this;
	var btn = ' .share_twt_app';
	var clicked_btn; //Writes the clicked btn element to add disabled class and other logic
	var auth;
	var _w;
	
	this.twt_login = function(callback, error, action) {

		action = action || 'connect';
		console.info('{twitter} - ', action);

		_w = window.open("/twitter/"+action, '', 'height=600,width=800'); //RR - window title is not supported by IE

		window.setTwitterID = function(id) { //	feedback function
			console.log('twitter callback', id);
			auth = {'userId': id};
			callback.call(this, id);
		}

		window.twitterError = function(msg) {
			if (error) error.call(this, msg);
		}

		var _inteval;

		function checkClose()	{
			if (_w.closed && !auth)	{
				clearInterval(_interval);	
				if (error) error.call(this, "closed_window");
			}
		}

		_interval = window.setInterval(checkClose,500);

	}
	
	/* =================== Events =================== */
	
	$(document).on('click', btn, function() {

		if ($(this).hasClass("inactive"))	{
			return false;
		}
		
		var location = $(this).attr('data-url');
			// fix bug with different returned data from backend
			// location = location.indexOf("/") === 0 ? location : "/" + location;
		
		if ( location.indexOf("http") == -1 )	{
			location = window.location.origin + location + "/";
		}

		var data_url = encodeURIComponent ($(this).attr('href'));

		var url = 'original_referer='+encodeURIComponent (window.location);
			//RR - text should not include location
			url += '&text='+encodeURIComponent ($(this).attr('data-text'));
			url += '&tw_p=tweetbutton';
			url += '&url='+encodeURIComponent (location);

		if ($(this).attr('data-hashtags')) {
			url += '&hashtags='+$(this).attr('data-hashtags');
		}
		if ($(this).attr('data-via')) {
			url += '&via='+$(this).attr('data-via');
		}

		clicked_btn = this;

		if (!php.userId) {
			self.twt_login(function() {}, function() {}, 'tweet?'+url);
		} else {
			window.open('https://twitter.com/intent/tweet?'+url, '', 'width=550,height=450'); //RR - window title is not supported by IE
		}

		return false;
	})
	.on('tweet', btn, function() {

		var data, container;

		// tweet in newsfeed or a folder
		if ( $(this).closest('[data-newsfeed_id]').length ) {
			// twitter for a drop
			container = $(this).closest('[data-newsfeed_id]');
			var newsfeed_id = container.attr('data-newsfeed_id'); 
			data = {'newsfeed_id': newsfeed_id};
		} else {
			// twitter for a folder
			container = $(this).closest('[data-folder_id]');
			var folder_id = container.attr('data-folder_id'); 
			var data = {'folder_id': folder_id};
		}

		if (php.userId) {
			data.user_id = php.userId;
		} else {
			data.social_user_id =  auth.userId;
		}
		if (php.referral) {
			data.referral = php.referral;
		}
		console.info('{Twitter} - tweet', data);
		$(this).addClass('inactive');
		$.post('/add_share/twitter', data, function(res) {
			console.info('{Twitter} - share res', res);

			if (!res.status) {
				console.info('share error', res);
				return;
			}

			container.find('.js-share_count').text(parseInt(container.find('.js-share_count').text()) + 1);
			$(document).trigger('share_success', ['twitter']);
		},'json');

	});
	
	$(window).bind('message', function(e) {
		if (!e.originalEvent || !e.originalEvent.data) return;
		
		if (e.originalEvent.origin.indexOf('twitter.com') == -1) return;
		//console.info('{twitter} - ', e.originalEvent.data);
		//ready events
		if (e.originalEvent.data.indexOf('{') == -1) return;
		var data = $.parseJSON(e.originalEvent.data);
		console.info('{Twitter} - data', data);
		//{"id":0,"method":"trigger","params":["tweet",null]}
		if (data.method && data.method == 'trigger' && data.params) {
			var action = data.params.shift();
			if (action == 'tweet') $(clicked_btn).trigger('tweet', data.params);
		}
	})
	
	return this;
});
