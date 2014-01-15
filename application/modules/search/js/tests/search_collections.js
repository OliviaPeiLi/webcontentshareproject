/**
 * Search page test (folders)
 */

QUnit.Utils._export( this );
var qunit_module = queryParams().qunit_module;

var query = queryParams().q;

testModule( 'Search Folders', function () {
	var keyword = '.primary_title';
	var newsfeed = '#folders';
	var items = ' .js-folder';
	
	test("basic page state", function() {
		equal($(keyword).text(), query);
		ok($(newsfeed+items).length, "no results found");
	});
	
});