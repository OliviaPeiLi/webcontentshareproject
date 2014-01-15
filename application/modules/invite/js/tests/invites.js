/**
 *  Test invite page e.g. http://fandrop.com/invite
 */
QUnit.module("Invites page");
	var google_test_data = {
		'form': '#gaia_loginform',
		'data': {
			'#Email': 'fantoon.test@gmail.com',
			'#Passwd': 'fantoon1234',
		}
	}
	var yahoo_test_data = {
			'form': '#login_form',
			'data': {
				'#username': 'fantoon.test@yahoo.com',
				'#passwd': 'fantoon1234',
			}
	}
	var facebook_test_data = {
			'form': '#login_form',
			'data': {
				'#email': 'judy_jackson_82@yahoo.com',
				'#pass': 'lHvWUIAJnJKNoKM',
			}
		}

	QUnit.asyncTest("Test Gmail invite", 3, function() {
		$('a[rel=gmailInviter]').click();
		$('#link_google').click();
		
		window.popup_initialize = function(url) {
			if (url.indexOf('/ServiceLogin?') > -1) { //Login form
				QUnit.ok(true, "Login form shown");
				return google_test_data;
			}
			if (url.indexOf('/oauth2/auth?') > -1) { //Grant access to fandrop api
				QUnit.ok(true, "Grant access button click");
				return {'button':'#submit_approve_access'};
			}
			return false;
		};
		
		window.popup_close = function () {
			QUnit.ok(true, "Google grant access finished");
			window.clearTimeout(checkTimeout);
			start();
		}
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Gmail timed out');
			start();
		}, 10*1000);
	});
	
	QUnit.asyncTest("Google contacts loaded", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ( ! $('#google-connector-button .invitesList .friend').length) return;
			ok(true, "Google contacts loaded");
			window.clearTimeout(checkTimeout);
			window.clearInterval(checkInterval);
			start();
		},100);
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Google contacts loading timed out');
			start();
		}, 2000);
	});
	
	QUnit.asyncTest("Follow All button", 3, function() {
		$('.request_follow_all').on('success', function() {
			window.clearTimeout(checkTimeout);
			ok($(this).is(':hidden'), "Follow all btn shuld hide");
			ok($('.request_follow:visible').length == 0, "All follow buttons should hide");
			ok($('.request_unfollow:hidden').length == 0, "All unfollow buttons should show");
			start();
		})
		.click();
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Follow all timed out');
			start();
		}, 10*1000);
	});
	
	QUnit.asyncTest("Unfollow user button", 2, function() {
		$('.request_unfollow:first').on('success', function() {
			window.clearTimeout(checkTimeout);
			ok($(this).is(':hidden'), "Unfollow button should be hidden");
			ok($(this).parent().find('.request_follow').is(':visible'), "Follow button should be visible");
			start();
		})
		.click();
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Follow all timed out');
			start();
		}, 2000);
	});

	QUnit.asyncTest("Follow user button", 2, function() {
		$('.request_follow:first').on('success', function() {
			window.clearTimeout(checkTimeout);
			ok($(this).is(':hidden'), "Unfollow button should be hidden");
			ok($(this).parent().find('.request_unfollow').is(':visible'), "Follow button should be visible");
			start();
		})
		.click();
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Follow all timed out');
			start();
		}, 2000);
	});
	
	QUnit.asyncTest("Test invite popup", 3, function() {
		var $invite_btn = $('#google-connector-button .invitesList .friend .invite_btn:first');
		$("#invite_popup [name='message']").val('This should reset');
		
		$('#invite_popup').on('shown', function() {
			ok($('#invite_popup').is(':visible'), "Ivite popup should open");
			equal($("#invite_popup [name='email[]']").val(), $invite_btn.closest('.friend').find('.email').text(), "Friend email differs");
			equal($("#invite_popup [name='message']").val(), '', "Message should reset");
			
			window.clearTimeout(checkTimeout);
			start();
		});
		$invite_btn.click();
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Invite popup timed out');
			start();
		}, 2000);		
	});
	
	QUnit.asyncTest("Test Yahoo invite", 3, function() {
		$('a[rel=yahooInviter]').click();
		$('#link_yahoo').click();
		
		window.popup_initialize = function(url) {
			if (url.indexOf('/login?') > -1) { //Login form
				console.info('Login page shown')
				QUnit.ok(true, "Login form shown");
				return yahoo_test_data;
			}
			if (url.indexOf('/request_auth?') > -1) { //Grant access to fandrop api
				console.info('Auth page shown');
				QUnit.ok(true, "Grant access button click");
				return {'button':'#agree'};
			}
			return false;
		};
		
		window.popup_close = function () {
			console.info('Grant access finished');
			QUnit.ok(true, "Yahoo grant access finished");
			window.clearTimeout(checkTimeout);
			start();
		}
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Yahoo popup timed out');
			start();
		}, 10*1000);
	});
	
	QUnit.asyncTest("Yahoo contacts loaded", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ( ! $('#yahoo-connector-button .invitesList .friend').length) return;
			ok(true, "Yahoo contacts loaded");
			window.clearTimeout(checkTimeout);
			window.clearInterval(checkInterval);
			start();
		},100);
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Yahoo contacts loading timed out');
			start();
		}, 2000);
	});

	// test invite only for valid email
	QUnit.asyncTest("Test invite only for valid email", 1, function() {
		// set email address to make it valid
		$('.inviteField_email:first').val('test@yahoo.com');

		$('#submit_invites').closest('form').bind('success', function(e, data) {
			equal( $(this).find('.success').length, 1, "Only 1 valid email should be sent" );
		}).find('submit').submit();

		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Test invite only for valid email: time out');
			start();
		}, 2000);
	});
	
	/*
	QUnit.asyncTest("Facebook test invite", 2, function() {
		$('a[rel=facebookInviter]').click();
		$('#link_facebook').click();
		
		window.popup_initialize = function(url) {
			if (url.indexOf('/login.php?') > -1) { //Login form
				QUnit.ok(true, "Login form shown");
				return facebook_test_data;
			}
			return false;
		};
		
		window.popup_close = function () {
			QUnit.ok(true, "Facebook grant access finished");
			window.clearTimeout(checkTimeout);
			start();
		}
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Facebook login timed out');
			start();
		}, 10*1000);
	});
	
	QUnit.asyncTest("Facebook contacts loaded", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ( ! $('#facebook-connector-button .invitesList .friend').length) return;
			ok(true, "Facebook contacts loaded");
			window.clearTimeout(checkTimeout);
			window.clearInterval(checkInterval);
			start();
		},100);
		var checkTimeout = window.setTimeout(function() {
			ok(false, 'Facebook contacts loading timed out');
			start();
		}, 10*1000);
	});

	QUnit.asyncTest("Facebook invite", 4, function() {
		$btn = $('.fb_invite_all_btn');
		ok ($btn.length, "Invite all button not found");
		var checkInterval = window.setInterval(function() {
			if (!$btn.hasClass('loading')) {
				window.clearTimeout(checkTimeout);
				window.clearInterval(checkInterval);
				ok(true, "Loader hidden");
				var $send_invite_iframe;
				$('#fb-root .fb_dialog_iframe iframe').each(function() {
					if (
						$(this).offset().top <= $btn.offset().top
						&& $(this).offset().left <= $btn.offset().left
						&& $(this).offset().top+$(this).height() >= $btn.offset().top+$btn.height()
						&& $(this).offset().left+$(this).width() >= $btn.offset().left+$btn.width()
					) {
						$send_invite_iframe = $(this);
					}
				});
				ok($send_invite_iframe&&$send_invite_iframe.length, "Send iframe not found");
				ok($send_invite_iframe.contents().find('#ok_clicked').length, "Send button not found");
				start();
			}
		},1000);
		
		var checkTimeout = window.setTimeout(function() {
			console.info($('#fb_xdm_frame_http').focus().contents().find('body').length);
			ok(false, 'Facebook send invite timed out');
			start();
		}, 10*1000);
	});
	*/

		
