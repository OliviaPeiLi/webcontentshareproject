/**
 *  QUnit tests for landing page (homepage when user not logged in).
 */
QUnit.module("Landing page");

	/**
	 * @deprecated 1/21/2013 - http://dev.fantoon.com:8100/browse/FD-2976
	 */
	//QUnit.asyncTest("Click comment btn", 1, function() {
	//	$('#list_newsfeed .newsfeed_entry:first')
	//		.find('.newsfeed_comments_lnk').trigger('click');
	//
	//	// if page is not redirected, start() will never be executed
	//	// so that testing will be suspended forever
	//	window.onbeforeunload = function() {
	//		ok(true, "Page redirected");
	//		start();
	//		return false;
	//	}
	//});
	
	QUnit.asyncTest("Click redrop btn", 1, function() {		
		// if page is not redirected, start() will never be executed
		// so that testing will be suspended forever
		window.onbeforeunload = function() {
			window.clearTimeout(checkTimeout);
			ok(true, "Page redirected");
			start();
			return false;
		}
		
		$('#list_newsfeed .newsfeed_entry:first')
			.find("a[href=#collect_popup]").trigger('click');
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Redrop test - timedout");
		}, 10 * 1000);
	});
	
	QUnit.asyncTest("Click upvote btn", 2, function() {
		var button = $('#list_newsfeed .upbox > .up_button:visible');
		ok(button.length, "no buttons visible");
		//RR - No checks in the tests
		//if (button.length > 0) {
		window.onbeforeunload = function() {
			window.clearTimeout(checkTimeout);
			ok(true, "Page redirected");
			start();
			return false;
		}
		button.trigger('click');
		//} else {
		//	start();
		//}
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Upvote test - timedout");
		}, 10 * 1000);
	});