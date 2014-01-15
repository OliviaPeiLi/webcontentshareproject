/**
 * Used for the bookmarklet login popup
 * @link /bookmarklet/login
 * @uses jquery
 * @uses bookmarklet/communicator - to send the data to the injected js
 */
define(['bookmarklet/communicator','social/all', 'jquery'], function(communicator) {
	
	/* ======================== Private Functions ======================= */
	//console.info('login.js');
	communicator._onload = function() {
		communicator.show_as_login({
			height: 400//$('.external_login').outerHeight(true)
		});
	}
	communicator.init();
	
	/* ====================== Direct code ==================== */
	//Mixpanel tracking
	if (typeof(mixpanel) !== 'undefined') {
		var user = php.userId ? php.userId : 0;
		mixpanel.people.identify(user);
		mixpanel.track('BOOKMARKLET: Login', {'user':user});
	}
	
	if (navigator.userAgent.indexOf('MSIE')) {
		$('#bookmarklet_login_form input').on('keyup', function(e) {
			if (e.keyCode == 13) {
				console.info('submit',$(this).closest('form').find(':submit').length);
				$(this).closest('form').find(':submit').click();
			}
		})
	}

	/* ===================== Events ========================== */
	
	/**
	 * Opens the twitter login popup
	 */
	$(document).on('click',"#provider_twitter_link", function() {

		twt_login(function() {
			window.location.href = '/bookmarklet/bar'; //refresh
		}, function(msg) {
			console.info('Could not login: ', msg);
			/*
			// FD-5032 - edited by Geno
			$('#bookmarklet_login_form .error').show().html(msg + " Redirecting ...");
			 setTimeout(function(){
			 	window.parent.location.href  = '/signup';
			 },2000);
			*/
		}, 'login');

        return false;
	});
	
	/**
	 * Opens facebook login
	 */
	$(document).on('click', '#fb_login_button,#facebook_login_button_header', function(){
		$(this).addClass('loading');
		fb_login(function(auth) {
			$.post("/fb_login", {'auth': auth}, function(response) {
		    	if( ! response.status){
		    		$('#bookmarklet_login_form .error').show().html('This Facebook account is not associated with any Fandrop account.')
		    		return;
		    	}
	    		window.location.href = '/bookmarklet/bar'; //refresh
		    }, 'json');
		});
		return false;
	});
	
	/**
	 * Closes the bookmarklet
	 */
	$(document).on('click', '#login a.clipboard-popup-close', function() {
		communicator.close_popup();
		return false;
	})
	
	
});