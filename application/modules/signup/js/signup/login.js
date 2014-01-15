/*
 * Js login for the login form
 * @link /signin
 * @uses jquery
 */
define(['social/all', 'jquery'], function() {

	var $self = this;
	
	/* ==================== Events ============================= */
	/**
	 * Fix for a bug requested by a user. No one of us could replicate this
	 * unless the login form stays open for more than 30mins (csrf expire time)
	 */
	$().on('click', '#submitLogin', function() {
		var $this = $(this);
		$.get('/get_csrf', function(resp) {
			if (resp.status) {
				$('input[name=ci_csrf_token]').val(resp.csrf.hash);
				$this.closest('form').submit();
			}
		},'json');
		return false;
	});

	$(document).on('click', '#fb_login_button,#facebook_login_button_header', function(){
		$(this).addClass('loading');
		fb_login(function(auth) {
			jQuery.post("/fb_login", {}, function(response) {
		    	if(! response.status){
		    		window.parent.location.href = '/signup?redirect_url='+php.redirect_url;
		    		return;
		    	}
	    		window.location.href = php.redirect_url;
		    }, 'json');
		},function(){
			// not logged enable login button
			$('#signup_social_wrapper a').removeClass("loading");
		});
		return false;
	});
	$(document).on('click', '#provider_twitter_link', function() {
		$(this).addClass('loading');
		twt_login(function() {
			window.location.href = php.redirect_url;
		}, function(msg) {
			if (msg == 'closed_window')	{
				$('#signup_social_wrapper a').removeClass("loading");
			} else	{
				window.parent.location.href = '/signup';
			}
			//Error messages goes here!
		}, 'login');
		return false;
	});

});