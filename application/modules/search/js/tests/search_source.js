/**
 * Search page test (people)
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var url = window.location.pathname.split('/');
var query = url[url.length - 1];

testModule( 'Search Source', function () {
	var keyword = '.primary_title a';
	var newsfeed = '#list_newsfeed';
	var items = ' .newsfeed_entry';
	
	test("basic page state", function() {
		equal($(keyword).text(), query);
		ok($(newsfeed+items).length, "no results found");
	});
	
});