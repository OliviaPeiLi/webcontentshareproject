/**
 * Code for the success popup. Appears after successfull drop
 * @link /bookmarklet/success
 * @see external.js
 * @uses bookmarklet/communicator - to receive drop data from the injected js
 * @uses jquery 
 */
define(["bookmarklet/communicator", "social/all", "jquery"], function(communicator) {
	
	/* ============================= Events ============================= */
	communicator._onload = function() {
	}
	communicator.init();
	
	/*
	 * Init the popup
	 */
	communicator._onshow_success= function(res) {
		var data = res.data;
		console.info('SUCCESS', data);
		
		$('div.bookmarklet-success').attr('data-newsfeed_id', data.id);
		$('p.folder-info a').attr('href', data.folder_url).html(data.folder_name);
		
		$('.share_twt_app').attr('data-url',data.folder_url).attr('data-text', data.title);
		$('.share_fb_app').removeClass('disabled_bg').attr('data-newsfeed_id', data.id);
		
		$('#newsfeed_url').attr('href', data.folder_url);
		
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.people.identify(user);
			mixpanel.track('Item Dropped', {'user':user});
			mixpanel.track('BOOKMARKLET: Dropping from Bookmarklet', {'user':user});

		}
	
	}
	
	/**
	 * Share on Twitter
	 */
	var newwindow;
	$(document).on('click', '#share_on_twitter_btn', function(){
		if (typeof newwindow == 'undefined') {
			newwindow=window.open($(this).attr('href'),'','height=400,width=500');
		}
		if (window.focus) {newwindow.focus()}
		return false;		
	});
	
});