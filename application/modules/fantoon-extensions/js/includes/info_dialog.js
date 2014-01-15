/**
 * Logic for the +Add popup and Walkthrough
 * @link /
 * @link /bookmarklet_walkthrough @deprecated
 */
define(['jquery', 'plugins/token-list'], function() {

	/* ======================== Private functions ================== */
	
	function browser_check() {
		console.log('TEEEST');
		$('.show_bookmark_bar_text, .show_animation_video').hide();
		if (navigator.userAgent.indexOf('Chrome') > -1) {
			//either chrome or safari
			if (navigator.userAgent.indexOf('Chrome/') >= 0) {
				//chrome
				$('#bookmarklet_bar_chrome, .bookmarklet_bar_chrome').show();
				$('#animation_video_chrome, .animation_video_chrome').show();
			} else {
				//safari
				$('#bookmarklet_bar_safari, .bookmarklet_bar_safari').show();
				$('#animation_video_safari, .animation_video_safari').show();
			}
		} else if (navigator.userAgent.indexOf('Firefox') > -1) {
			//firefox
			$('#bookmarklet_bar_ff, .bookmarklet_bar_ff').show();
			$('#animation_video_ff, .animation_video_ff').show();
		} else if (navigator.userAgent.indexOf('MSIE') > -1) {
			if (parseFloat(navigator.userAgent.match(/MSIE ([0-9.]+)/)[1]) <= 8.0) {
				//IE8 or below
				$('#bookmarklet_bar_ie8, .bookmarklet_bar_ie8').show();
				$('#animation_video_ie8, .animation_video_ie8').show();
			} else {
				//IE9+
				$('#bookmarklet_bar_ie, .bookmarklet_bar_ie').show();
				$('#animation_video_ie, .animation_video_ie').show();
			}
		} else if (navigator.userAgent.indexOf('Opera') > -1) {
			//Opera
			$('#bookmarklet_bar_opera, .bookmarklet_bar_opera').show();
			$('#animation_video_opera, .animation_video_opera').show();
		}
	}
	
	/* ======================== Events ============================== */

	/**
	 * Display appropriate bookmarklet instructions based on web browser
	 */
	var selector = '#get_bookmarklet_dialog';

	$(document).on('show', selector, function(){
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			//mixpanel.people.identify(user);
			mixpanel.track('+Add', {'user':user});
		}

		$('#animation_video').attr('src', $('#animation_video').attr('data-url'));
		$('#info_dialog_img_upload').removeClass('step2');
		$('#get_bookmarklet_dialog .postbox_img_preview img').attr('src','');
		browser_check();
		$.get('/info_dialog_opened');
	})
	.on('hidden', selector, function() {
		$(this).closest('#get_bookmarklet_dialog').find('#postbox_second_step').hide();
		$('.bottom').css('position','').css('top','').show();
		$(this).closest('#get_bookmarklet_dialog').find('.top, .middle, .bottom').show();
	});
	
	/* ============================== Direct code ========================== */
	
	browser_check();

	$('.bookmarklet-btn a').click(function(e){
		e.preventDefault();
	});
	
});