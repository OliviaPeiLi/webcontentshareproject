/*
 * Signup Page logic. Support for the FB signup button
 * @link /signup	
 * @uses jquery
 */
define(['social/all', 'jquery'], function() {
	
	$(document).on('click', '#facebook_login_button_old', function() {
		
		$(this).addClass('loading');
		fb_login(function(auth) {
	    	if(! auth.userID){
	    		$(this).removeClass('loading');
	    		return;
	    	}
    		window.parent.location.href = '/facebook_after?redirect_url='+php.redirect_url;

		},function(){
			$('#signup_social_wrapper a').removeClass("loading");
		});

		return false;
	});
	
	$(document).on('click','#provider_twitter_link', function() {
		twt_login(function() {
			window.parent.location.href = '/twitter_after?redirect_url='+php.redirect_url;
		});
		return false;
	});
	
});