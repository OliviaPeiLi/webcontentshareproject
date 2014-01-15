/**
 *  QUnit tests for landing page (homepage when user not logged in).
 */
QUnit.module("Landing page");

if (php.landing_ugc) {
	var up_button = '.js_folder:first .upbox > .upvote:visible';
} else {
	var up_button = '#list_newsfeed .upbox > .up_button:visible';
}
	
if ( ! php.landing_ugc) {
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
}

	QUnit.asyncTest("Click upvote btn", 2, function() {
		
		ok($(up_button).length, "no buttons visible");
		
		window.onbeforeunload = function() {
			window.clearTimeout(checkTimeout);
			ok(true, "Page redirected");
			start();
			return false;
		}
		
		$(up_button).trigger('click');
		
		var checkTimeout = window.setTimeout(function() {
			ok(false, "Upvote test - timedout");
		}, 10 * 1000);
	});