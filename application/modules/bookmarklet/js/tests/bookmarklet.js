/**
 * Bookmarklet test - QUnit utils fails for the bookmarklet probably because of third party code.
 * So we are using plain qunit (ugly) tests here.
 */

	var main_frame = '#scraping_overlay_iframe';
	
	QUnit.asyncTest("bookmarklet iframe added", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ($(main_frame).length) {
				window.clearInterval(checkInterval);
				ok(true);
				QUnit.start();
			}
		},100);
	});
		
	QUnit.asyncTest("bookmarklet login loaded", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ($(main_frame).contents().find('#login_email').length) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				
				ok(true);
				QUnit.start();
			}
		},100);
		var checkTimeout = window.setTimeout(function() {
			ok(false);
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("bookmarklet login success", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ($(main_frame).contents().find('#bar').length) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				
				ok(true);
				QUnit.start();
			}
		},100);
		$(main_frame).contents().find('#login_email').val('test.user1@example.com');
		$(main_frame).contents().find('#login_password').val('lFDvlksDF');
		$(main_frame).contents().find('#login_submit').click();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false);
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Drop page popup show", 3, function() {
		var checkInterval = window.setInterval(function() {
			if ($('#bookmark-page').contents().find('#scraper_form').length) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				
				ok($('#bookmark-page').contents().find('#scraper_form').is(':visible'));
				QUnit.start();
			}
		},100);
		ok($('#bookmark-page').length, "Drop page iframe not found");
		ok($(main_frame).contents().find('#bar a.drop_page').length, "Drop page button not found");
		$(main_frame).contents().find('#bar a.drop_page').click();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false);
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Drop page popup form validation show error", 3, function() {
		var error = $('#bookmark-page').contents().find('.error');
		var checkInterval = window.setInterval(function() {
			if (error[0].style && error[0].style.display != 'none') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true, "error message not shown");
				QUnit.start();
			}
		},100);
				
		window.setTimeout(function() {
			ok($('#bookmark-page').contents().find('form').length, "form not found");
			ok($('#bookmark-page').contents().find('.error').length, "error element not found");
			$('#bookmark-page').contents().find('#scraper_form input[type=submit]').click();
		}, 1000);
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Drop page popup form validation hide error", 2, function() {
		var checkInterval = window.setInterval(function() {
			if ($('#bookmark-page').contents().find('.error')[0].style.display == 'none') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true);
				QUnit.start();
			}
		},100);
		
		ok($('#bookmark-page').contents().find('textarea.fd_mentions').length, "Textarea not found");
		$('#bookmark-page').contents().find('textarea.fd_mentions').val('Qunit test drop #test').trigger('blur');
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Drop page submit", 2, function() {
		var success;
		var checkInterval = window.setInterval(function() {
			success = $('#success-iframe').contents().find('.folder-info a');
			console.info(success.attr('href'));
			if (success.attr('href') != '') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		ok($('#bookmark-page').contents().find('.form_right select option').length, "Folders not added");
		$('#bookmark-page').contents().find('.form_right select option').removeAttr('selected');
		$('#bookmark-page').contents().find('.form_right select option').each(function() {
			if ($(this).val() == 'Bookmarklet folder') $(this).attr('selected','selected');
		});
		$('#bookmark-page').contents().find('#scraper_form input[type=submit]').click();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});

	QUnit.asyncTest("Restart the bookmarklet", 1, function() {
		
		var checkInterval = window.setInterval(function() {
			if ($('#clip_overlay').is(':visible')) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		fandrop_bookmarklet.communicator.start();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});

	QUnit.asyncTest("Open html preview popup", 1, function() {
		var checkInterval = window.setInterval(function() {
			if ($('#clip_overlay').hasClass('clipboard-popup')) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		console.info('Selected content: ', $('#clip_overlay').width()+'x'+$('#clip_overlay').height())
		$('#clip_overlay').trigger('mousedown').trigger('mouseup');
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	/*
	 * @to-do - the error text is null in testing server - works ok in local
	 */
	QUnit.asyncTest("preview popup validation", 1, function() {
		var checkInterval = window.setInterval(function() {
			var error = $('form#clipboard-popup-controls').find('.error'); 
			console.info(error.length, error[0].innerHTML, error.text());
			if (error[0].style.display != 'none') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		console.info('Submit', $('form#clipboard-popup-controls input[type=submit]')[0]);
		$('form#clipboard-popup-controls input[type=submit]').click(); 
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("preview popup hide validation", 1, function() {
		var checkInterval = window.setInterval(function() {
			var error = $('form#clipboard-popup-controls').find('.error');
			if (error[0].style.display == 'none') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		$('form#clipboard-popup-controls textarea.fd_mentions').val('Qunit test drop #test_hash').keydown();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Folder dropdown - open", 1, function() {
		var checkInterval = window.setInterval(function() {
			var dropdown = $('.token-input-dropdown-fd_dropdown:visible'); 
			if (dropdown.length && dropdown.find('li').length) {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		$('form#clipboard-popup-controls .token-input-list-fd_dropdown').click();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Folder dropdown - select", 2, function() {
		var checkInterval = window.setInterval(function() {
			if ($('form#clipboard-popup-controls input[type=hidden]').val() == 'Bookmarklet folder') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		var selected_folder = null;
		$('.token-input-dropdown-fd_dropdown:visible li').each(function() {
			if ($(this).text() == 'Bookmarklet folder') selected_folder = $(this);
		});
		ok(selected_folder.length, "Bookmarklet folder not found");
		selected_folder.trigger('mousedown');
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
	
	QUnit.asyncTest("Html content submit", 1, function() {
		var checkInterval = window.setInterval(function() {
			var success = $('#success-iframe').contents().find('.folder-info a');
			if (success.attr('href') != '') {
				window.clearInterval(checkInterval);
				window.clearTimeout(checkTimeout);
				ok(true)
				QUnit.start();
			}
		},100);
		
		$('#success-iframe').contents().find('.folder-info a').attr('href',''); //reset
		$('form#clipboard-popup-controls input[type=submit]').click();
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Timed out");
			QUnit.start();
		},10*1000);
	});
